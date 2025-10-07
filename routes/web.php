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

Route::get('/profile/password', function () {
    return view('profile-password');
})->name('profile.password');

Route::put('/profile/update', function () {
    $validated = request()->validate([
        'name' => ['required','string','max:255'],
        'email' => ['required','email','max:255'],
    ]);
    return response()->json(['message' => 'ok', 'data' => $validated]);
})->middleware('jwt');

Route::put('/profile/password', function () {
    $validated = request()->validate([
        'current_password' => ['required','string'],
        'new_password' => ['required','string','min:8','confirmed'],
    ]);
    $payload = request()->attributes->get('jwt');
    if (!$payload || !isset($payload['sub'])) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    $user = \App\Models\User::find($payload['sub']);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password)) {
        return response()->json(['message' => 'Current password is incorrect'], 422);
    }
    $user->password = \Illuminate\Support\Facades\Hash::make($validated['new_password']);
    $user->save();
    return response()->json(['message' => 'ok']);
})->middleware('jwt');

Route::get('/profile-edit', function () {
    return view('profile-edit');
})->name('profile-edit');

Route::get('/logout', function () {
    return view('logout');
})->name('logout');
