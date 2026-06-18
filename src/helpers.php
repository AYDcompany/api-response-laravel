<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Ayd\ApiResponseBase\Meta;
use Ayd\ApiResponseBase\Response;
use Ayd\ApiResponseBase\StatusCode;

if (!function_exists("respond")) {
    function respond(
        bool $success = true,
        mixed $data = null,
        string $message = "Successful",
        int|StatusCode $code = StatusCode::OK,
        ?Meta $meta = null,
    ): JsonResponse {
        $httpStatus = $code instanceof StatusCode ? $code->value : $code;

        if ($meta && !$meta->isEmpty()) {
            $payload["meta"] = $meta->toArray();
        }

        $payload = Response::build($success, $code, $data, $message, $meta);

        return new JsonResponse(
            $payload,
            $httpStatus,
            [],
            JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES |
                JSON_THROW_ON_ERROR,
        );
    }
}

if (!function_exists("success")) {
    function success(
        mixed $data = null,
        string $message = "Successful",
        ?Meta $meta = null,
    ): JsonResponse {
        return respond(true, $data, $message, StatusCode::OK, $meta);
    }
}

if (!function_exists("created")) {
    function created(
        mixed $data = null,
        string $message = "Created",
        ?Meta $meta = null,
    ): JsonResponse {
        return respond(true, $data, $message, StatusCode::CREATED, $meta);
    }
}

if (!function_exists("updated")) {
    function updated(
        mixed $data = null,
        string $message = "Updated",
        ?Meta $meta = null,
    ): JsonResponse {
        return respond(true, $data, $message, StatusCode::OK, $meta);
    }
}

if (!function_exists("accepted")) {
    function accepted(
        mixed $data = null,
        string $message = "Accepted",
        ?Meta $meta = null,
    ): JsonResponse {
        return respond(true, $data, $message, StatusCode::ACCEPTED, $meta);
    }
}

if (!function_exists("no_content")) {
    function no_content(): JsonResponse
    {
        return respond(true, null, "No Content", StatusCode::NO_CONTENT);
    }
}

if (!function_exists("message")) {
    function message(
        string $message,
        int|StatusCode $code = StatusCode::OK,
    ): JsonResponse {
        return respond(true, [], $message, $code);
    }
}

if (!function_exists("bad_request")) {
    function bad_request(
        string $message = "Bad Request",
        mixed $data = null,
    ): JsonResponse {
        return respond(false, $data, $message, StatusCode::BAD_REQUEST);
    }
}

if (!function_exists("unauthorized")) {
    function unauthorized(string $message = "Unauthorized"): JsonResponse
    {
        return respond(false, null, $message, StatusCode::UNAUTHORIZED);
    }
}

if (!function_exists("forbidden")) {
    function forbidden(string $message = "Forbidden"): JsonResponse
    {
        return respond(false, null, $message, StatusCode::FORBIDDEN);
    }
}

if (!function_exists("not_found")) {
    function not_found(string $message = "Not Found"): JsonResponse
    {
        return respond(false, null, $message, StatusCode::NOT_FOUND);
    }
}

if (!function_exists("unprocessable")) {
    function unprocessable(
        string $message = "Unprocessable Entity",
        mixed $data = null,
    ): JsonResponse {
        return respond(
            false,
            $data,
            $message,
            StatusCode::UNPROCESSABLE_ENTITY,
        );
    }
}

if (!function_exists("error")) {
    function error(
        string $message = "Internal Server Error",
        int|StatusCode $code = StatusCode::INTERNAL_SERVER_ERROR,
    ): JsonResponse {
        return respond(false, null, $message, $code);
    }
}
