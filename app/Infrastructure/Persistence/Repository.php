<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use Ramsey\Uuid\Uuid;

class Repository
{
    protected $documentClass;

    public function __construct($documentClass)
    {
        $this->documentClass = $documentClass;
    }

    public function newQuery()
    {
        return (new $this->documentClass)->newQuery();
    }

    public function getDocumentClass()
    {
        return $this->documentClass;
    }

    public function generateUuid()
    {
        return (string)Uuid::uuid5(Uuid::uuid4(), $this->getDocumentClass() . '@'. config('app.url'));
    }

    public function getVisible($userUuid = null)
    {
        return $this->newQuery()
            ->withTrashed()
            ->visible($userUuid)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function (AbstractDocument $model) {
                return $model
                    ->transform(['include_all'])
                    ->toArray();
            });
    }

    public function getUsingPermissions($permissions)
    {
        return $this->newQuery()
            ->withTrashed()
            ->where(function ($query) use ($permissions) {
                return $query->fitsPermissions($permissions);
            })
            ->get()
            ->map(function (AbstractDocument $model) {
                return $model
                    ->transform(['include_all'])
                    ->toArray();
            });
    }

    /**
     * @param $uuid
     * @return AbstractDocument|null
     */
    public function find($uuid)
    {
        if (is_array($uuid)) {
            $uuids = $uuid;
            $ret = [];

            foreach($uuids as $uuid) {
                $ret[$uuid] = $this->find($uuid);
            }

            return $ret;
        }
        else {
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->getDocumentClass()))) {
                $document = $this->newQuery()->withTrashed()->find($uuid);
            } else {
                $document = $this->newQuery()->find($uuid);
            }
        }

        return $document;
    }

    public function findActive($uuid)
    {
        return $this->newQuery()->where('is_disabled', false)->find($uuid);
    }

    public function create(array $data, $protectedData = [], $save = true)
    {
        $document = new $this->documentClass;

        $document->fill($data);
        $document->uuid = $this->generateUuid();

        foreach ($protectedData as $attribute => $value) {
            $document->{$attribute} = $value;
        }

        if ($save) {
            $document->save();
        }

        $document->touchOwners();

        return $document;
    }

    public function update(array $data, $protectedData = [], $save = true)
    {
        $document = $this->find($data['uuid']);

        $document->fill($data);

        foreach ($protectedData as $attribute => $value) {
            $document->{$attribute} = $value;
        }

        if ($save) {
            $document->save();
        }

        return $document;
    }

    public function delete($uuid)
    {
        $document = $this->find($uuid);

        if ($document) {
            $document->delete();
        }

        return $document;
    }

    public function restore($uuid)
    {
        $document = $this->newQuery()->withTrashed()->get()->find($uuid);

        $document->restore();

        return $document;
    }

    public function archive($uuid)
    {
        $document = $this->find($uuid);

        $document->archived_at = $document->freshTimestamp();
        $document->save();

        return $document;
    }

    public function unarchive($uuid)
    {
        $document = $this->find($uuid);

        $document->archived_at = null;
        $document->save();

        return $document;
    }

    /**
     * TODO: rewrite batch actions from the ground up.
     */
    public function deleteBatch($uuids)
    {
        $documents = $this->newQuery()->withTrashed()->whereIn('uuid', $uuids)->get();

        foreach ($documents as $document) {
            $document->delete();
        }

        $documents = $this->newQuery()->withTrashed()->whereIn('uuid', $uuids)->get();

        return $documents;
    }

    public function restoreBatch($uuids)
    {
        $this->newQuery()->withTrashed()->whereIn('uuid', $uuids)->get()->each(function ($document) {
            $document->restore();
        });

        return $this->newQuery()->withTrashed()->whereIn('uuid', $uuids)->get();
    }

    public function archiveBatch($uuids)
    {
        $documents = $this->newQuery()->whereIn('uuid', $uuids)->get();

        foreach ($documents as $document) {
            $document->archived_at = (new $this->documentClass)->freshTimestamp();
            $document->save();
        }

        return $documents;
    }

    public function unarchiveBatch($uuids)
    {
        $documents = $this->newQuery()->whereIn('uuid', $uuids)->get();

        foreach ($documents as $document) {
            $document->archived_at = null;
            $document->save();
        }

        return $documents;
    }
}