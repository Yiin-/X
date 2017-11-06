<?php

namespace App\Domain\Model\Documents\Shared;

use BadMethodCallException;

class AbstractDocumentRepository
{
    public function createRaw($data, $protectedData = [])
    {
        return $this->repository->create($data, $protectedData);
    }

    /**
     * Create a document
     * @param  array  $data           fillable data
     * @param  array  $protectedData  protected data
     * @return AbstractDocument       document instance
     */
    public function create($data, $protectedData = [])
    {
        $this->fillData($data, $protectedData, true);

        if (method_exists($this, 'creating')) {
            $this->creating($data, $protectedData);
        }

        $document = $this->repository->create($data, $protectedData, false);

        if (method_exists($this, 'saving')) {
            $this->saving($document, $data, $protectedData);
        }

        $document->save();

        if (method_exists($this, 'saved')) {
            $this->saved($document, $data, $protectedData);
        }
        if (method_exists($this, 'created')) {
            $this->created($document, $data, $protectedData);
        }

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
        $this->fillData($data, $protectedData);

        if (method_exists($this, 'updating')) {
            $this->updating($data, $protectedData);
        }

        $document = $this->repository->update($data, $protectedData, false);

        if (method_exists($this, 'saving')) {
            $this->saving($document, $data, $protectedData);
        }

        $document->save();

        if (method_exists($this, 'saved')) {
            $this->saved($document, $data, $protectedData);
        }
        if (method_exists($this, 'updated')) {
            $this->updated($document, $data, $protectedData);
        }

        return $document;
    }

    public function fillData(&$data, &$protectedData, $creating = false)
    {
        if ($creating) {
            if (method_exists($this, 'fillDefaultData')) {
                $this->fillDefaultData($data, $protectedData);
            }
        }

        if (method_exists($this, 'fillUserData')) {
            $this->fillUserData($protectedData);
        }

        if (method_exists($this, 'fillMissingData')) {
            $this->fillMissingData($data, $protectedData);
        }

        if (method_exists($this, 'adjustData')) {
            $this->adjustData($data, $protectedData);
        }
    }

    public function __call($method, $parameters)
    {
        try {
            return $this->repository->$method(...$parameters);
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(
                sprintf('Call to undefined method %s::%s()', get_class($this), $method)
            );
        }
    }
}