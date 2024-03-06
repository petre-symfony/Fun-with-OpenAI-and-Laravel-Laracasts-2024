<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Assistants\AssistantResponse;

class Client {
	public function retrieveAssistant(string $assistantId) {
		return OpenAI::assistants()->retrieve($assistantId);
	}

	public function createAssistant(array $config) {
		return OpenAI::assistants()->create($config);
	}

	public function uploadFile(string $file, AssistantResponse $assistant) {
		$file = OpenAI::files()->upload([
			'purpose' => 'assistants',
			'file' => fopen($file, 'rb')
		]);

		return OpenAI::assistants()
			->files()
			->create($assistant->id, ['file_id' => $file->id]);
	}

	public function createThread(array $parameters = []) {
		return OpenAI::threads()->create($parameters);
	}

	public function createMessage() {
		
	}

	public function messages() {
		
	}

	public function run() {
		
	}

	public function runStatus() {
		
	}
}