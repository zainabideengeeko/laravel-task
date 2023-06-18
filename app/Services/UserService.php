<?php

namespace App\Services;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(UserRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : null,
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateUser($userId, UserRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $user = User::findOrFail($userId);
            $user->update([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : null,
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
