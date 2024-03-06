<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;

class Client {
	public function retrieveAssistant(string $assistantId) {
		return OpenAI::assistants()->retrieve($assistantId);
	}

	public function createAssistant() {
		
	}

	public function uploadFile() {
		
	}

	public function createThread() {
		
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