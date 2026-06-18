<?php

declare(strict_types=1);

namespace InfCompany\ApiResponseLaravel\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use InfCompany\ApiResponseBase\Contracts\AbilitiesResolver;

class ResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ApiResponse::class, function (Application $app) {
            $resolver = $app->bound(AbilitiesResolver::class)
                ? $app->make(AbilitiesResolver::class)
                : null;

            return new ApiResponse($resolver);
        });

        $this->app->alias(ApiResponse::class, "response-api");
    }
}
