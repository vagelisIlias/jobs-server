<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::all());
    }
}
