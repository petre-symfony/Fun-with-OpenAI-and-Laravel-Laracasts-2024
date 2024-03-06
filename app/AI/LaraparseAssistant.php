<?php

namespace App\AI;

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
	protected Client $client;
	/**
	 * Create a new LaraparseAssistant Instance
	 */
	public function __construct(string $assistantId, Client $client = null) {
	  $client ??= new Client();
		$this->client = $client;

		$this->assistant = $this->client->retrieveAssistant($assistantId);
	}

	/**
	 * Create a new OpenAI assistant
	 */
	public static function create(array $config = []) {
		$defaultConfig = [
			'model' => 'gpt-4-1106-preview',
			'name' => 'Laraparse Tutor',
			'instructions' => 'You are a helpful programming teacher',
			'tools' => [
				['type' => 'retrieval']
			]
		];

		$assistant = (new Client())->createAssistant(array_merge_recursive($defaultConfig, $config));

		return new static($assistant->id);
	}

	/**
	 * Provide reading material to the assistant
	 */
	public function educate(string $file): static {
		$this->client->uploadFile($file, $this->assistant);

		return $this;
	}

	/**
	 * Create a new thread
	 */
	public function createThread(array $parameters = []): static {
		$thread = $this->client->createThread($parameters);

		$this->threadId = $thread->id;

		return $this;
	}

	/**
	 * Fetch all messaged for the current thread
	 */
	public function messages():ThreadMessageListResponse {
		return $this->client->messages($this->threadId);
	}

	/**
	 * Write a new message
	 */
	public function write(string $message): static {
		$this->client->createMessage($message, $this->threadId);
		
		return $this;
	}

	/**
	 * Send all the messages to the assistant and await a respond
	 */
	public function send() {
		return $this->client->run($this->threadId, $this->assistant);
	}

}