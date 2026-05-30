<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->safe()->only(['name', 'email', 'password']) + [
            'role' => 'admin',
        ]);

        return $this->success('Registration successful', ['user' => $user], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->validated('email'))->first();

        if (! $user || ! $this->passwordMatches($user, $request->validated('password'))) {
            return $this->error('Invalid credentials', 401);
        }

        return $this->success('Login successful', ['user' => $user]);
    }

    private function passwordMatches(User $user, string $password): bool
    {
        $info = password_get_info($user->password);
        $matches = $info['algo'] === 0
            ? hash_equals($user->password, $password)
            : Hash::check($password, $user->password);

        if ($matches && $info['algo'] === 0) {
            $user->update(['password' => Hash::make($password)]);
        }

        return $matches;
    }
}
