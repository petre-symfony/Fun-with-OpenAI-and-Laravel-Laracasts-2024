<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Assistants\AssistantResponse;
use OpenAI\Responses\Threads\Messages\ThreadMessageListResponse;

class LaraparseAssistant {
	/**
	 * The OpenAI AssistantResponse instance
	 */
	protected AssistantResponse $assistant;
	/**
	 * The id of the current thread
	 */
	protected string $threadId;

	/**
	 * Create a new LaraparseAssistant Instance
	 */
	public function __construct(string $assistantId) {
		$this->assistant = OpenAI::assistants()->retrieve($assistantId);
	}

	/**
	 * Create a new OpenAI assistant
	 */
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

	/**
	 * Provide reading material to the assistant
	 */
	public function educate(string $file): static {
		$file = OpenAI::files()->upload([
			'purpose' => 'assistants',
			'file' => fopen($file, 'rb')
		]);

		OpenAI::assistants()->files()->create($this->assistant->id, ['file_id' => $file->id]);

		return $this;
	}

	/**
	 * Create a new thread
	 */
	public function createThread(array $parameters = []): static {
		$thread = OpenAI::threads()->create($parameters);

		$this->threadId = $thread->id;

		return $this;
	}

	/**
	 * Fetch all messaged for the current thread
	 */
	public function messages():ThreadMessageListResponse {
		return OpenAI::threads()->messages()->list($this->threadId);
	}

	/**
	 * Write a new message
	 */
	public function write(string $message): static {
		OpenAI::threads()->messages()->create($this->threadId, [
			'role' => 'user',
			'content' => $message
		]);
		return $this;
	}

	/**
	 * Send all the messages to the assistant and await a respond
	 */
	public function send() {
		$run = OpenAI::threads()->runs()->create($this->threadId, [
			'assistant_id' => $this->assistant->id
		]);

		do {
			sleep(1);

			$run = OpenAI::threads()->runs()->retrieve(
				threadId: $this->threadId,
				runId: $run->id
			);
		} while ($run->status !== 'completed');

		return $this->messages();
	}
}