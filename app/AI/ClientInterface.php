<?php

namespace App\AI;

use OpenAI\Responses\Assistants\AssistantResponse;
use OpenAI\Responses\Threads\Messages\ThreadMessageListResponse;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;

interface ClientInterface {
	public function retrieveAssistant(string $assistantId);

	public function createAssistant(array $config);

	public function uploadFile(string $file, AssistantResponse $assistant);

	public function createThread(array $parameters = []);

	public function createMessage(string $message, string $threadId);

	public function messages(string $threadId);

	public function run(string $threadId, AssistantResponse $assistant): ThreadMessageListResponse;

	public function runStatus(ThreadRunResponse $run): bool;
}