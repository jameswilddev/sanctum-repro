<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', function (Request $request) {
    $user = User::create([
        'name' => 'Test',
        'email' => 'test@test.com',
        'password' => Hash::make('test'),
    ]);

    return [
        'token' => $user->createToken('test')->plainTextToken,
    ];
});

Route::middleware('auth:sanctum')->group(function(Router $router) {
    $router->post('/logout', function (Request $request) {
        auth()->user()->currentAccessToken()->delete();
    });

    $router->get('/user', function (Request $request) {
        return $request->user();
    });
});
