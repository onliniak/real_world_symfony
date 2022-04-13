<?php

namespace App\Repository\Exceptions;

class IsNullException extends \Exception implements \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
{

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        // TODO: Implement getStatusCode() method.
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        // TODO: Implement getHeaders() method.
    }
}