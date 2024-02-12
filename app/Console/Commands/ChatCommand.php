<?php

namespace App\Console\Commands;

use App\AI\Chat;
use Illuminate\Console\Command;

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
		$question = $this->ask('What is your question for AI?');

		$chat = new Chat();

		$response = $chat->send($question);

		$this->info($response);

		while ($this->ask('Do you want to respond?')){
			$question = $this->ask('What is your reply?');

			$response = $chat->send($question);

			$this->info($response);
		}
	}
}
