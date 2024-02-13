<?php

use App\AI\Chat;
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
	return view('roast');
});

Route::post('/roast', function () {
	$attributes = request()->validate(['topic' => [
		'required', 'string', 'min:2', 'max:50'
	]]);

	$prompt = "Please roast {$attributes['topic']} in a sarcastic ton";
	
	$chat = new Chat();

	$response = $chat->send($prompt);

	dd($response);
});

Route::get('/', function () {
	$chat = new Chat();

	$poem = $chat
		->systemMessage(
			'You are a poetic assistant, skilled in explaining complex concepts with creative flair'
		)
		->send(
			'Compose a poem that explains the concept of trcursion in programming'
		);

	$sillyPoem = $chat->reply('Cool, can you make it much, much sillier');

	return view('roast', [
		'poem' => $sillyPoem
	]);
});