<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;

class LaraparseAssistant {
	public static function create(array $config = []) {
		return OpenAI::assistants()->create(array_merge_recursive([
			'model' => 'gpt-4-1106-preview',
			'name' => 'Laraparse Tutor',
			'instructions' => 'You are a helpful programming teacher',
			'tools' => [
				['type' => 'retrieval']
			]
		], $config));
	}

	public function educate(string $file) {
		return OpenAI::files()->upload([
			'purpose' => 'assistants',
			'file' => fopen($file, 'rb')
		]);
	}

	public function createThread() {
		
	}

	public function run() {
		
	}
}