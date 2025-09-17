<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Trait\Api\V1\ApiResponseTrait\ApiResponseTrait;
use App\Http\Resources\Api\V1\UserResource\UserResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Api\V1\UserRequest\UpdateUserRequest;

class UserController extends Controller
{
    use AuthorizesRequests;
    use ApiResponseTrait;
    
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        return UserResource::collection(User::paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // TODO fix the request to update the fields
        // TODO Add a notification and let user the status updated
        $this->authorize('update', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        // TODO send an email the account deleted
        return response()->noContent();
    }
}
