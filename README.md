# API Response Laravel

Laravel integration for the `inf-company/api-response-base` package. Provides a service class, trait, facade, and global helper functions for building consistent JSON API responses.

## Installation

```bash
composer require inf-company/api-response-laravel
```

The service provider is auto-discovered. To register manually:

```php
// config/app.php
'providers' => [
    InfCompany\ApiResponseLaravel\Providers\ResponseServiceProvider::class,
],
```

## Usage

### 1. Dependency Injection

Inject `ApiResponse` directly into your controller or service:

```php
use InfCompany\ApiResponseLaravel\ApiResponse;

class UserController
{
    public function index(ApiResponse $response)
    {
        return $response->success($users);
    }

    public function store(ApiResponse $response)
    {
        // validation...

        return $response->created($user, 'User created');
    }

    public function show(ApiResponse $response, $id)
    {
        $user = User::find($id);

        if (!$user) {
            $response->notFound('User not found'); // throws HttpResponseException
        }

        return $response->success($user);
    }
}
```

### 2. Trait (for Controllers)

Use `ApiResponseTrait` to call response methods directly on the controller:

```php
use InfCompany\ApiResponseLaravel\ApiResponseTrait;

class UserController
{
    use ApiResponseTrait;

    public function index()
    {
        return $this->success(User::all());
    }

    public function store()
    {
        // validation...

        return $this->created($user, 'User created');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return $this->noContent();
    }
}
```

### 3. Facade

```php
use InfCompany\ApiResponseLaravel\Facades\Response;

Route::get('/health', fn () => Response::ok(['status' => 'healthy']));
```

To use the facade, add it to `config/app.php`:

```php
'aliases' => [
    'ResponseApi' => InfCompany\ApiResponseLaravel\Facades\Response::class,
],
```

### 4. Global Helper Functions

Helper functions are auto-loaded and return `JsonResponse` directly. Error helpers return the response (they do **not** throw exceptions):

```php
return success($data);
return created($user, 'User created');
return updated($user, 'User updated');
return accepted($data);
return no_content();
return message('Processing', 202);
return bad_request('Invalid input', $errors);
return unauthorized();
return forbidden();
return not_found('User not found');
return unprocessable('Validation failed', $errors);
return error('Something went wrong');
return respond($data, 'Custom', 200);
```

## Error Handling

Error methods on the service class and trait **throw `HttpResponseException`** rather than returning a value. This means you can call them without `return`:

```php
public function show(ApiResponse $response, $id)
{
    $user = User::find($id);

    if (!$user) {
        $response->notFound('User not found');
        // execution stops here — no return needed
    }

    return $response->success($user);
}
```

Available error methods: `fail()`, `error()`, `badRequest()`, `unauthorized()`, `forbidden()`, `notFound()`, `unprocessable()`.

## Paginated Responses

Transform paginated results with a Laravel API Resource class:

```php
public function index(ApiResponse $response)
{
    $paginator = User::paginate(20);

    return $response->paginator($paginator, UserResource::class);
}
```

This automatically:
- Transforms items via `UserResource::collection($paginator->items())->resolve(request())`
- Extracts pagination metadata (`total`, `per_page`, `current_page`) into `meta.pagination`
- Includes `request_id` and any resolved abilities in `meta`

## Abilities Resolver

Bind your resolver in a service provider to auto-inject abilities into every response:

```php
use InfCompany\ApiResponseBase\Contracts\AbilitiesResolver;

$this->app->bind(AbilitiesResolver::class, MyAbilitiesResolver::class);
```

The `ResponseServiceProvider` checks if `AbilitiesResolver` is bound and passes it to `ApiResponse` automatically.

## API Reference

### Success Methods

| Method | HTTP Status | Return Type |
|---|---|---|
| `success($data, $message, $meta)` | 200 | `JsonResponse` |
| `created($data, $message, $meta)` | 201 | `JsonResponse` |
| `updated($data, $message, $meta)` | 200 | `JsonResponse` |
| `accepted($data, $message, $meta)` | 202 | `JsonResponse` |
| `noContent()` | 204 | `JsonResponse` |
| `paginator($paginator, $resourceClass, $meta)` | 200 | `JsonResponse` |

### Error Methods

| Method | HTTP Status | Return Type |
|---|---|---|
| `fail($code, $message, $data, $meta)` | configurable | `void` (throws) |
| `error($code, $message, $data, $meta)` | configurable | `void` (throws) |
| `badRequest($message, $data)` | 400 | `void` (throws) |
| `unauthorized($message)` | 401 | `void` (throws) |
| `forbidden($message)` | 403 | `void` (throws) |
| `notFound($message)` | 404 | `void` (throws) |
| `unprocessable($message, $data)` | 422 | `void` (throws) |

## Requirements

- PHP ^8.1
- `inf-company/api-response-base`
- `illuminate/http` ^10.0 | ^11.0 | ^12.0
- `illuminate/support` ^10.0 | ^11.0 | ^12.0

## License

MIT
