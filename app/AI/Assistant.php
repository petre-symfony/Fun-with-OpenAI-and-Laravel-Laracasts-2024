<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;

class Assistant {
	protected array $messages = [];

	public function messages() {
		return $this->messages;
	}

	public function send(string $message, bool $speech = false): ?string {
		$this->messages[] = [
			'role' => 'user',
			'content' => $message
		];

		$response =  OpenAI::chat()->create([
				"model" => "gpt-3.5-turbo",
				"messages" => $this->messages
			])->choices[0]->message->content;

		if ($response) {
			$this->messages[] = [
				'role' => 'assistant',
				'content' => $response
			];
		}

		return $speech ? $this->speech($response) : $response;
	}

	public function speech(string $message): string {
		return OpenAI::audio()->speech([
			'model' => 'tts-1',
			'input' => $message,
			'voice' => 'alloy'
		]);
	}

	public function systemMessage(string $message): static {
		$this->messages[] = [
			'role' => 'system',
			'content' => $message
		];

		return $this;
	}

	public function reply(string $message): ?string {
		return $this->send($message);
	}

	public function visualize(string $description, array $options = []): string {
		$options = array_merge([
			'prompt' => $description,
			'model' => 'dall-e-3'
		], $options);

		return OpenAI::images()->create($options)->data[0]->url;
	}
}