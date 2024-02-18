<?php

use App\AI\Assistant;
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
	$assistant = new \App\AI\LaraparseAssistant();

	$assistant->educate(storage_path('docs/parsing.md'));
	
	$assistant = OpenAI::assistants()->create([
		'model' => 'gpt-4-1106-preview',
		'name' => 'Laraparse Tutor',
		'instructions' => 'You are a helpful programming teacher',
		'tools' => [
			['type' => 'retrieval']
		],
		'file_ids' => [ $file->id ]
	]);

	$run = OpenAI::threads()->createAndRun([
		'assistant_id' => $assistant->id,
		'thread' => [
			'messages' => [
				['role' => 'user', 'content' => 'How do I grab the first paragraph?']
			]
		]
	]);

	do {
		sleep(1);

		$run = OpenAI::threads()->runs()->retrieve(
			threadId: $run->threadId,
			runId: $run->id
		);
	} while ($run->status !== 'completed');

	$messages = OpenAI::threads()->messages()->list($run->threadId);

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