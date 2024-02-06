<?php

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
	$response = Http::post('https://api.openai.com/v1/chat/completions', [
		"model" => "gpt-3.5-turbo",
		"messages" => [
			[
				"role" => "system",
				"content" => "You are a poetic assistant, skilled in explaining complex concepts with creative flair"
			],
			[
				"role" => "user",
				"content" => "Compose a poem that explains the concept of trcursion in programming"
			]
		]
	])->json();

	dd($response);
});
