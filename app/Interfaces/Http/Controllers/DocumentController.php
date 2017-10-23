<?php

namespace App\Interfaces\Http\Controllers;

use App\Infrastructure\Persistence\Repository;
use Illuminate\Http\Request;
use App\Interfaces\Http\Requests\Documents\TabularDataRequest;

abstract class DocumentController extends AbstractController
{
    const VALIDATION_RULES_CREATE = 'create';
    const VALIDATION_RULES_UPDATE = 'update';
    const VALIDATION_RULES_PATCH = 'patch';

    /**
     * @var Repository
     */
    protected $repository;

    abstract public function getResourceName();
    abstract public function getValidationRules($action);
    abstract public function getValidationAttributes();

    public function dummy()
    {
        $document = factory($this->repository->getDocumentClass())->make();
        return $document->transform();
    }

    /**
     * Get list of all visible documents.
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $this->authorize('view', $this->repository->getDocumentClass());

        return response()->json(
            $this->repository->newQuery()
                // ->withTrashed()
                ->visible()
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($model) {
                    return $model->transform();
                })
            );
    }

    /**
     * Get list of documents in table format
     * @param TabularDataRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('view', $this->repository->getDocumentClass());

        return $this->all();
    }

    /**
     * Get entity by uuid
     * @param $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($uuid)
    {
        $document = $this->repository->find($uuid);

        $this->authorize('see', $document);

        return response()->json($document->transform());
    }

    /**
     * Create new entity
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->repository->getDocumentClass());

        $this->validate($request,
            $this->getValidationRules(static::VALIDATION_RULES_CREATE), [], $this->getValidationAttributes()
        );

        $data = $request->get($this->getResourceName(), []);

        return response()->json($this->repository->create($data)->transform(), 201);
    }

    /**
     * Update existing entity
     * @param  string  $uuid
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($uuid, Request $request)
    {
        $this->authorize('update', $this->repository->find($uuid));

        $this->validate($request,
            $this->getValidationRules(
                $request->isMethod('patch')
                ? static::VALIDATION_RULES_PATCH
                : static::VALIDATION_RULES_UPDATE
            ), [], $this->getValidationAttributes()
        );

        $data = $request->get($this->getResourceName(), []);

        $data['uuid'] = $uuid;

        return response()->json($this->repository->update($data)->transform());
    }

    /**
     * Delete entity
     * @param  string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($uuid)
    {
        // $this->authorize('delete', $this->repository->find($uuid));

        return response()->json($this->repository->delete($uuid)->transform());
    }

    /**
     * Restore entity
     * @param $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($uuid)
    {
        // $this->authorize('delete', $this->repository->find($uuid));

        return response()->json($this->repository->restore($uuid)->transform());
    }

    /**
     * Archive entity
     * @param  string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function archive($uuid)
    {
        // $this->authorize('archive', $this->repository->find($uuid));

        return response()->json($this->repository->archive($uuid)->transform());
    }

    /**
     * UnArchive entity
     * @param  string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function unarchive($uuid)
    {
        // $this->authorize('archive', $this->repository->find($uuid));

        return response()->json($this->repository->unarchive($uuid)->transform());
    }

    /**
     * Batch actions
     */
    public function deleteBatch(Request $request)
    {
        return response()->json(
            $this->repository->deleteBatch($request->get('keys', []))->map(function ($document) {
                return $document->transform();
            })
        );
    }

    public function restoreBatch(Request $request)
    {
        return response()->json(
            $this->repository->restoreBatch($request->get('keys', []))->map(function ($document) {
                return $document->transform();
            })
        );
    }

    public function archiveBatch(Request $request)
    {
        return response()->json(
            $this->repository->archiveBatch($request->get('keys', []))->map(function ($document) {
                return $document->transform();
            })
        );
    }

    public function unarchiveBatch(Request $request)
    {
        return response()->json(
            $this->repository->unarchiveBatch($request->get('keys', []))->map(function ($document) {
                return $document->transform();
            })
        );
    }

    /**
     * Table stuff
     */
    public function getTableQuery()
    {
        return $this->repository->newQuery();
    }

    public function getTable($request)
    {
        /* table state */
        $page = $request->get(static::TABLE_PAGE);
        $amount = $request->get(static::TABLE_AMOUNT);
        $orderBy = $request->get(static::TABLE_ORDER_BY, 'created_at');
        $orderDirection = $request->get(static::TABLE_ORDER_DIRECTION, 'desc');

         /* filters */
        $filter = $request->get(static::TABLE_FILTER);
        $searchBy = $request->get(static::TABLE_SEARCH_BY);

        /* QueryBuilder object of entity model */
        $query = $this->getTableQuery();

        /* we should only show results belonging to logged in user */
        // $query->filter($filter);
        // $query->searchBy($searchBy);

        /* update table state with fresh values */
        $tableState['total'] = $query->count();
        $tableState['pages'] = $amount ? max(ceil($tableState['total'] / $amount), 1) : 1;

        $rows = $this->getEntities($query, $page, $amount, $orderBy, $orderDirection);

        return [
            'rows' => $rows,
            'pages' => $tableState['pages'],
            'total' => $tableState['total']
        ];
    }

    protected function getEntities($query, $page, $documents_per_page, $orderBy, $orderDirection)
    {
        if ($documents_per_page) {
            $query
                ->skip($documents_per_page * $page)
                ->limit($documents_per_page);
        }
        return $query
            ->visible()
            ->orderBy($orderBy, $orderDirection)
            ->get()
            ->map(function ($model) {
                return $model->transform();
            });
    }

    protected function unauthorized()
    {
        return response('Unauthorized.', 401);
    }
}
