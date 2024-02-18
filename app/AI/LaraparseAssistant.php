<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Assistants\AssistantResponse;

class LaraparseAssistant {
	protected AssistantResponse $assistant;

	public function __construct(string $assistantId) {
		$this->assistant = OpenAI::assistants()->retrieve($assistantId);
	}

	public static function create(array $config = []) {
		$assistant = OpenAI::assistants()->create(array_merge_recursive([
			'model' => 'gpt-4-1106-preview',
			'name' => 'Laraparse Tutor',
			'instructions' => 'You are a helpful programming teacher',
			'tools' => [
				['type' => 'retrieval']
			]
		], $config));

		return new static($assistant->id);
	}

	public function educate(string $file): static {
		$file = OpenAI::files()->upload([
			'purpose' => 'assistants',
			'file' => fopen($file, 'rb')
		]);

		OpenAI::assistants()->files()->create($this->assistant->id, ['file_id' => $file->id]);

		return $this;
	}

	public function createThread() {
		
	}

	public function run() {
		
	}
}