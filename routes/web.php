<?php

use App\AI\Assistant;
use App\AI\LaraparseAssistant;
use App\Rules\SpamFree;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;
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
Route::get('/assistant', function (){
	$assistant = new LaraparseAssistant(config('openai.assistant.id'));

	$messages = $assistant->createThread()
		->write('hello')
		->write('How to I grab the first paragraph using Laraparse?')
		->send();

	dd($messages);
});

Route::get('/detect_spam', function (){
	return view('create-reply');
});

Route::post('/replies', function() {
	request()->validate([
		'body' => [
			'required',
			'string',
			new SpamFree()
		]
	]);

	return 'Redirect wherever is needed. Post was valid';
});

Route::get('/image', function (){
	return view('image', [
		'messages' => session('messages', [])
	]);
});

Route::post('/image', function () {
	$attributes = request()->validate([
		'description' => ['required', 'string', 'min:3']
	]);

	$assistant = new Assistant(session('messages', []));

	$assistant->visualize($attributes['description']);

	session(['messages' => $assistant->messages()]);

	return redirect('/image');
});

Route::get('/', function () {

	return view('roast');
});

Route::post('/roast', function () {
	$attributes = request()->validate(['topic' => [
		'required', 'string', 'min:2', 'max:50'
	]]);

	$mp3 = (new Assistant())->send(
		message: "Please roast {$attributes['topic']} in a sarcastic ton",
		speech: true
	);

	file_put_contents(public_path($file = "/roasts/" . md5($mp3) . '.mp3'), $mp3);

	return redirect('/')->with([
		'file' => $file,
		'flash' => 'Boom , roasted'
	]);
});

Route::get('/', function () {
	$chat = new Assistant();

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