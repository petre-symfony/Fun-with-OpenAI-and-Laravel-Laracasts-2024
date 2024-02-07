<?php

use App\AI\Chat;
use Illuminate\Support\Facades\Http;
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
	/**
	$messages = [
		[
			"role" => "system",
			"content" => "You are a poetic assistant, skilled in explaining complex concepts with creative flair"
		]
	];
	*/
	$chat = new Chat();

	$poem = $chat
		->send(
			"Compose a poem that explains the concept of trcursion in programming",
			"system message goes here"
		);

	return view('welcome', [
		'poem' => $poem
	]);
});