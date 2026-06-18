<?php

declare(strict_types=1);

namespace InfCompany\ApiResponseLaravel;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Resources\Json\JsonResource;
use InfCompany\ApiResponseBase\Concerns\BuildsApiResponse;
use InfCompany\ApiResponseBase\Contracts\AbilitiesResolver;
use InfCompany\ApiResponseBase\Meta;
use InfCompany\ApiResponseBase\Response;
use InfCompany\ApiResponseBase\StatusCode;

class ApiResponse
{
    use BuildsApiResponse;

    public function __construct(?AbilitiesResolver $abilitiesResolver = null)
    {
        if ($abilitiesResolver !== null) {
            $this->setAbilitiesResolver($abilitiesResolver);
        }
    }

    public function success(mixed $data = null, string $message = 'Successful', ?Meta $meta = null): JsonResponse
    {
        return $this->respond(StatusCode::OK, $data, $message, $meta);
    }

    public function created(mixed $data = null, string $message = 'Created', ?Meta $meta = null): JsonResponse
    {
        return $this->respond(StatusCode::CREATED, $data, $message, $meta);
    }

    public function updated(mixed $data = null, string $message = 'Updated', ?Meta $meta = null): JsonResponse
    {
        return $this->respond(StatusCode::OK, $data, $message, $meta);
    }

    public function accepted(mixed $data = null, string $message = 'Accepted', ?Meta $meta = null): JsonResponse
    {
        return $this->respond(StatusCode::ACCEPTED, $data, $message, $meta);
    }

    public function noContent(): JsonResponse
    {
        return $this->respond(StatusCode::NO_CONTENT, null, 'No Content');
    }

    public function fail(
        int|StatusCode $code = StatusCode::BAD_REQUEST,
        string $message = 'Bad Request',
        mixed $data = null,
        ?Meta $meta = null,
    ): void {
        throw new HttpResponseException($this->respond($code, $data, $message, $meta));
    }

    public function error(
        int|StatusCode $code = StatusCode::INTERNAL_SERVER_ERROR,
        string $message = 'Internal Server Error',
        mixed $data = null,
        ?Meta $meta = null,
    ): void {
        throw new HttpResponseException($this->respond($code, $data, $message, $meta));
    }

    public function badRequest(string $message = 'Bad Request', mixed $data = null): void
    {
        throw new HttpResponseException($this->respond(StatusCode::BAD_REQUEST, $data, $message));
    }

    public function unauthorized(string $message = 'Unauthorized'): void
    {
        throw new HttpResponseException($this->respond(StatusCode::UNAUTHORIZED, null, $message));
    }

    public function forbidden(string $message = 'Forbidden'): void
    {
        throw new HttpResponseException($this->respond(StatusCode::FORBIDDEN, null, $message));
    }

    public function notFound(string $message = 'Not Found'): void
    {
        throw new HttpResponseException($this->respond(StatusCode::NOT_FOUND, null, $message));
    }

    public function unprocessable(string $message = 'Unprocessable Entity', mixed $data = null): void
    {
        throw new HttpResponseException($this->respond(StatusCode::UNPROCESSABLE_ENTITY, $data, $message));
    }

    public function paginator(
        LengthAwarePaginator $paginator,
        string $resourceClass,
        ?Meta $meta = null,
    ): JsonResponse {
        $paginationMeta = Meta::fromPaginator($paginator);
        $items = $resourceClass::collection($paginator->items())->resolve(request());
        $mergedMeta = $paginationMeta->merge($this->buildMeta($meta));

        return $this->respond(StatusCode::OK, $items, 'Successful', $mergedMeta);
    }

    protected function respond(int|StatusCode $code, mixed $data, string $message, ?Meta $meta = null): JsonResponse
    {
        $httpStatus = ($code instanceof StatusCode) ? $code->value : $code;
        $payload = Response::build($code, $data, $message, $this->buildMeta($meta));

        return response()->json($payload, $httpStatus);
    }
}
