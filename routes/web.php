<?php

use App\AI\Assistant;
use OpenAI\Laravel\Facades\OpenAI;
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
Route::get('/image', function (){
	return view('image', [
		'url' => session('url')
	]);
});

Route::post('/image', function () {
	$attributes = request()->validate([
		'description' => ['required', 'string', 'min:3']
	]);

	$url = OpenAI::images()->create([
		'prompt' => $attributes['description'],
		'model' => 'dall-e-3'
	])->data[0]->url;

	return redirect('/image')
		->with([
			'url' => $url
		]);
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