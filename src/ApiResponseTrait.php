<?php

declare(strict_types=1);

namespace InfCompany\ApiResponseLaravel;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use InfCompany\ApiResponseBase\Meta;
use InfCompany\ApiResponseBase\StatusCode;

trait ApiResponseTrait
{
    protected ?ApiResponse $apiResponseService = null;

    protected function getApiResponse(): ApiResponse
    {
        if ($this->apiResponseService === null) {
            $this->apiResponseService = app(ApiResponse::class);
        }

        return $this->apiResponseService;
    }

    public function success(mixed $data = null, string $message = 'Successful', ?Meta $meta = null): JsonResponse
    {
        return $this->getApiResponse()->success($data, $message, $meta);
    }

    public function created(mixed $data = null, string $message = 'Created', ?Meta $meta = null): JsonResponse
    {
        return $this->getApiResponse()->created($data, $message, $meta);
    }

    public function updated(mixed $data = null, string $message = 'Updated', ?Meta $meta = null): JsonResponse
    {
        return $this->getApiResponse()->updated($data, $message, $meta);
    }

    public function accepted(mixed $data = null, string $message = 'Accepted', ?Meta $meta = null): JsonResponse
    {
        return $this->getApiResponse()->accepted($data, $message, $meta);
    }

    public function noContent(): JsonResponse
    {
        return $this->getApiResponse()->noContent();
    }

    public function fail(
        int|StatusCode $code = StatusCode::BAD_REQUEST,
        string $message = 'Bad Request',
        mixed $data = null,
        ?Meta $meta = null,
    ): void {
        $this->getApiResponse()->fail($code, $message, $data, $meta);
    }

    public function error(
        int|StatusCode $code = StatusCode::INTERNAL_SERVER_ERROR,
        string $message = 'Internal Server Error',
        mixed $data = null,
        ?Meta $meta = null,
    ): void {
        $this->getApiResponse()->error($code, $message, $data, $meta);
    }

    public function badRequest(string $message = 'Bad Request', mixed $data = null): void
    {
        $this->getApiResponse()->badRequest($message, $data);
    }

    public function unauthorized(string $message = 'Unauthorized'): void
    {
        $this->getApiResponse()->unauthorized($message);
    }

    public function forbidden(string $message = 'Forbidden'): void
    {
        $this->getApiResponse()->forbidden($message);
    }

    public function notFound(string $message = 'Not Found'): void
    {
        $this->getApiResponse()->notFound($message);
    }

    public function unprocessable(string $message = 'Unprocessable Entity', mixed $data = null): void
    {
        $this->getApiResponse()->unprocessable($message, $data);
    }

    public function paginator(
        LengthAwarePaginator $paginator,
        string $resourceClass,
        ?Meta $meta = null,
    ): JsonResponse {
        return $this->getApiResponse()->paginator($paginator, $resourceClass, $meta);
    }
}
