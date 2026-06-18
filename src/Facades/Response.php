<?php

declare(strict_types=1);

namespace InfCompany\ApiResponseLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use InfCompany\ApiResponseLaravel\ApiResponse;

/**
 * @method static \Illuminate\Http\JsonResponse success(mixed $data = null, string $message = 'Successful', ?\InfCompany\ApiResponseBase\Meta $meta = null)
 * @method static \Illuminate\Http\JsonResponse created(mixed $data = null, string $message = 'Created', ?\InfCompany\ApiResponseBase\Meta $meta = null)
 * @method static \Illuminate\Http\JsonResponse updated(mixed $data = null, string $message = 'Updated', ?\InfCompany\ApiResponseBase\Meta $meta = null)
 * @method static \Illuminate\Http\JsonResponse accepted(mixed $data = null, string $message = 'Accepted', ?\InfCompany\ApiResponseBase\Meta $meta = null)
 * @method static \Illuminate\Http\JsonResponse noContent()
 * @method static void fail(int|\InfCompany\ApiResponseBase\StatusCode $code, string $message, mixed $data = null, ?\InfCompany\ApiResponseBase\Meta $meta = null)
 * @method static void error(int|\InfCompany\ApiResponseBase\StatusCode $code, string $message, mixed $data = null, ?\InfCompany\ApiResponseBase\Meta $meta = null)
 * @method static void badRequest(string $message = 'Bad Request', mixed $data = null)
 * @method static void unauthorized(string $message = 'Unauthorized')
 * @method static void forbidden(string $message = 'Forbidden')
 * @method static void notFound(string $message = 'Not Found')
 * @method static void unprocessable(string $message = 'Unprocessable Entity', mixed $data = null)
 * @method static \Illuminate\Http\JsonResponse paginator(\Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator, string $resourceClass, ?\InfCompany\ApiResponseBase\Meta $meta = null)
 */
class Response extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ApiResponse::class;
    }
}
