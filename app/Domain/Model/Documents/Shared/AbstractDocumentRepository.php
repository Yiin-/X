<?php

namespace App\Domain\Model\Documents\Shared;

use BadMethodCallException;

class AbstractDocumentRepository
{
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