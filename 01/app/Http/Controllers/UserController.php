<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $users = [
            (object)[
                'id' => 1,
                'username' => 'username_1',
            ],
            (object)[
                'id' => 2,
                'username' => 'username_2',
            ],
        ];

        return UserResource::collection($users);
    }
}
