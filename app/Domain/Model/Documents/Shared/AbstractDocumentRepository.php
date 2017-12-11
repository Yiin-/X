<?php

namespace App\Domain\Model\Documents\Shared;

use BadMethodCallException;

class AbstractDocumentRepository
{
    protected $repository;
    protected $validator;

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;
    }

    public function getValidator()
    {
        return $this->validator;
    }

    public function createRaw($data, $protectedData = [])
    {
        return $this->getRepository()->create($data, $protectedData);
    }

    /**
     * Create a document
     * @param  array  $data           fillable data
     * @param  array  $protectedData  protected data
     * @return AbstractDocument       document instance
     */
    public function create($data, $protectedData = [])
    {
        \Log::debug(get_class($this) . ': create');
        if ($this->getValidator()) {
            $this->getValidator()->create();
        }

        $this->fillData($data, $protectedData, true);

        $this->creating($data, $protectedData);

        $document = $this->getRepository()->create($data, $protectedData, false);

        $this->saving($document, $data, $protectedData);

        $this->dispatchEvent('creating', $document);
        $this->dispatchEvent('saving', $document);

        $document->save();

        $this->saved($document, $data, $protectedData);
        $this->created($document, $data, $protectedData);

        $this->dispatchEvent('saved', $document);
        $this->dispatchEvent('created', $document);

        return $document;
    }

    /**
     * Update a document
     * @param  array $data           fillable data
     * @param  array $protectedData  protected data`
     * @return AbstractDocument      document instance
     */
    public function update($data, $protectedData = [])
    {
        \Log::debug(get_class($this) . ': update ' . $data['uuid']);

        if ($this->getValidator()) {
            $this->getValidator()->update();
        }

        $activity = $this->checkIfWeAreRestoringDocument($data, $protectedData);

        $this->fillData($data, $protectedData);
        $this->updating($data, $protectedData);

        $document = $this->getRepository()->update($data, $protectedData, false);

        if (isset($activity) && $activity) {
            $document->restoredFromActivity = $activity->id;
        }

        $this->saving($document, $data, $protectedData);

        $this->dispatchEvent('updating', $document);
        $this->dispatchEvent('saving', $document);

        $document->save();

        $this->saved($document, $data, $protectedData);
        $this->updated($document, $data, $protectedData);

        $this->dispatchEvent('saved', $document);
        $this->dispatchEvent('updated', $document);

        return $document;
    }

    /**
     * Dispatch custom model event
     *
     * We do that, so activity logger which saves document state
     * can save document together with his relationships, e.g.
     * when creating client, we need to dispatch `created` event
     * after we create both client and his contacts. That allows
     * us to restore client state w/ his contacts list from
     * previous state, otherwise client state will be saved before
     * his contacts are created, therefore after restoration his
     * contacts information will be lost, because it wasn't
     * available when client state was saved.
     */
    public function dispatchEvent($event, $document)
    {
        if ($eventClass = $document->getDocumentEvent($event)) {
            event(new $eventClass($document));
        }
    }

    /**
     * Returns activity if we're restoring document to his previous state.
     * FALSE otherwise.
     */
    public function checkIfWeAreRestoringDocument(&$data, &$protectedData)
    {
        if (isset($data['restoredFromActivity']) && $data['restoredFromActivity']) {
            $activity = \App\Domain\Model\System\ActivityLog\Activity::find($data['restoredFromActivity']);
            unset($data['restoredFromActivity']);

            if (!$activity) {
                return false;
            }

            // restore dates
            $backupData = json_decode(json_decode($activity->json_backup)->documentTransformed, true);
            $documentClass = $this->getRepository()->getDocumentClass();
            $dates = (new $documentClass)->getDates();

            foreach (array_diff($dates, ['updated_at']) as $date) {
                $protectedData[$date] = $backupData[$date]
                    ? \Carbon\Carbon::parse($backupData[$date]['date'])->toDateTimeString()
                    : null;
            }
            return $activity;
        }
        return false;
    }

    public function fillData(&$data, &$protectedData, $creating = false)
    {
        if ($creating) {
            $this->fillDefaultData($data, $protectedData);
        }

        $this->fillUserData($data, $protectedData);
        $this->fillMissingData($data, $protectedData);
        $this->adjustData($data, $protectedData);
    }

    public function __call($method, $parameters)
    {
        if (in_array($method, [
            'creating',
            'created',
            'updating',
            'updated',
            'saving',
            'saved',
            'fillDefaultData',
            'fillUserData',
            'fillMissingData',
            'adjustData'
        ])) {
            return;
        }
        try {
            return $this->getRepository()->$method(...$parameters);
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(
                sprintf('Call to undefined method %s::%s()', get_class($this), $method)
            );
        }
    }
}