<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;

class LaraparseAssistant {
	public static function create() {
		
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