<?php

use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/hello', function () {
    return "Hello world";
});

Route::get('users', function(){
    $users = User::all();


    if (count($users) > 0) {
        foreach($users as $user){
        $user->api_token = Str::random(80);
        $user->save();
        }
        return response()->json([
            'status' => 1,
            'message' => 'All users',
            'Number of user' => count($users),
            'data' => $users
        ], 200);
    }
    return response()->json([
        'status' => 0,
        'message' => 'No User Available'
    ]);

});




