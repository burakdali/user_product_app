<?php

namespace App\Http\Controllers\API;

use Whoops\Run;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;
use App\Http\Resources\ProductResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ProductCollection;


class UserController extends Controller
{

    public function log_in(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'data' => [
                'token' => $user->createToken($user->first_name())->plainTextToken,
                'name' => $user->first_name(),
            ],
            'message' => 'user logged in!.',
        ]);
    }
    public function RegisterWithPhoneNumber(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|numeric|unique:users,phone_number',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);
        return response()->json($user, 200);
    }
    public function RegisterWithEmail(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json($user, 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new UserCollection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => ['required', 'min:4'],
            'last_name' => ['required', 'min:4'],
            'email' => ['sometimes', 'email'],
            'phone_number' => ['sometimes', 'numeric'],
            'password' => ['required', Rules\Password::defaults()],
        ]);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'is_admin' => $request->is_admin,
            'password' => Hash::make($request->password),
        ]);
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'first_name' => ['sometimes', 'min:4', Rule::unique('users')->ignore($user->first_name(), 'first_name')],
            'last_name' => ['sometimes', 'min:4', Rule::unique('users')->ignore($user->last_name(), 'last_name')],
            'email' => ['sometimes', 'email'],
            'phone_number' => ['sometimes'],
            'password' => ['sometimes', 'min:8', Rule::unique('users')->ignore($user->password(), 'password')],
        ]);
        $user->update([
            'first_name' => $request->first_name ?? $user->first_name,
            'last_name' => $request->last_name ?? $user->last_name,
            'email' => $request->email ?? $user->email,
            'phone_number' => $request->phone_number ?? $user->phone_number,
            'is_admin' => $request->is_admin ?? $user->is_admin,
            'password' => Hash::make($request->password),
        ]);
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
    public function getUserProducts($id)
    {
        $user = User::find($id);

        if (!$user) {
            return "this user is not exists";
        }
        $products = $user->products()->get();
        return response()->json($products, 200);
    }
    public function getAuthenticatedUserProducts()
    {
        $user = Auth::user();
        $products = $user->products()->get();
        return response()->json($products, 200);
    }
}
