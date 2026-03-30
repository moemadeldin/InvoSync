<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Actions\Auth\CreateUserAction;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Utils\APIResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class ExternalProvisionController
{
    use APIResponder;

    public function __invoke(StoreUserRequest $request, CreateUserAction $action): JsonResponse
    {
        /** @var array{name: string, email: string, password: string} $data */
        $data = $request->validated();
        $user = $action->execute($data);

        return $this->success($user, 'Teacher Registered Successfully.', Response::HTTP_CREATED);
    }
}
