<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/register', function () {
    return view('register');    
})->name('register');

Route::post('/register', function () {
    $validated = request()->validate([
        'name' => ['required','string','max:255'],
        'email' => ['required','email','max:255','unique:users,email'],
        'password' => ['required','string','min:8'],
    ]);
    /** @var \App\Repositories\UserRepositoryInterface $repo */
    $repo = app(\App\Repositories\UserRepositoryInterface::class);
    $user = $repo->create(new \App\Schemas\UserSchema(
        $validated['name'],
        $validated['email'],
        $validated['password']
    ));
    return response()->json(['id' => $user->id, 'message' => 'registered']);
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    $validated = request()->validate([
        'email' => ['required','email'],
        'password' => ['required','string'],
    ]);
    $user = \App\Models\User::where('email', $validated['email'])->first();
    if (!$user || !\Illuminate\Support\Facades\Hash::check($validated['password'], $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 422);
    }
    $payload = [
        'sub' => $user->id,
        'email' => $user->email,
        'iat' => time(),
        'exp' => time() + 60*60*8, // 8h
    ];
    $token = \App\Support\Jwt::encode($payload);
    return response()->json(['token' => $token, 'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]]);
});

Route::get('/profile', function () {
    return view('profile-edit');
})->name('profile.edit');

Route::put('/profile/update', function () {
    // TODO: update profile later
    return back();
});

Route::put('/profile/password', function () {
    // TODO: change password later
    return back();
});

Route::get('/profile-edit', function () {
    return view('profile-edit');
})->name('profile-edit');
