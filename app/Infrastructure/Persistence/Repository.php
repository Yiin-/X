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
        return (string)Uuid::uuid5(Uuid::uuid4(), config('app.url'));
    }

    public function getVisible($userUuid = null)
    {
        return $this->newQuery()
            ->withTrashed()
            ->visible($userUuid)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function (AbstractDocument $model) {
                return $model->transform();
            });
    }

    /**
     * @param $uuid
     * @return AbstractDocument|null
     */
    public function find($uuid)
    {
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->getDocumentClass()))) {
            $document = $this->newQuery()->withTrashed()->find($uuid);
        } else {
            $document = $this->newQuery()->find($uuid);
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
            if ($document->deleted_at) {
                $document->forceDelete();
            } else {
                $document->delete();
            }
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
        $this->newQuery()->whereIn('uuid', $uuids)->delete();

        $documents = $this->newQuery()->withTrashed()->whereIn('uuid', $uuids)->get();

        foreach ($documents as $document) {
            if ($document->deleted_at) {
                $document->forceDelete();
            } else {
                $document->delete();
            }
        }

        $documents = $this->newQuery()->withTrashed()->whereIn('uuid', $uuids)->get();

        return $documents;
    }

    public function restoreBatch($uuids)
    {
        $this->newQuery()->whereIn('uuid', $uuids)->restore();

        return $this->newQuery()->withTrashed()->whereIn('uuid', $uuids)->get();
    }

    public function archiveBatch($uuids)
    {
        $this->newQuery()->whereIn('uuid', $uuids)->update([
            'archived_at' => (new $this->documentClass)->freshTimestamp()
        ]);

        return $this->newQuery()->whereIn('uuid', $uuids)->get();
    }

    public function unarchiveBatch($uuids)
    {
        $this->newQuery()->whereIn('uuid', $uuids)->update([
            'archived_at' => null
        ]);

        return $this->newQuery()->whereIn('uuid', $uuids)->get();
    }
}