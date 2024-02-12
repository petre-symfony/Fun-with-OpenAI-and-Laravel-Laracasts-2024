<?php

namespace App\Console\Commands;

use App\AI\Chat;
use Illuminate\Console\Command;
use function Laravel\Prompts\{text, info, spin};

class ChatCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'chat';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Start a chat with OpenAI';

	/**
	 * Execute the console command.
	 */
	public function handle() {
		$question = text('What is your question for AI?');

		$chat = new Chat();

		$response = spin(fn() => $chat->send($question), 'Sending request');

		info($response);

		while ($question = text('Do you want to respond?')){

			$response = $chat->send($question);

			info($response);
		}

		info('Conversation over');
	}
}
