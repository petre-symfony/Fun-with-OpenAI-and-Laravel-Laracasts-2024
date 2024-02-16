<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use OpenAI\Laravel\Facades\OpenAI;

class SpamFree implements ValidationRule {
	/**
	 * Run the validation rule.
	 *
	 * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void {
		$response = OpenAI::chat()->create([
			'model' => 'gpt-3.5-turbo-1106',
			'messages' => [
				['role' => 'system', 'content' => 'You are a forum moderator who always responds using json'],
				[
					'role' => 'user',
					'content' => <<<EOT
								Please inspect the following text and determine if it is spam.
								
								{$value}
								
								Expected response example:
								{"is_spam": true|false}
							EOT
				],
			],
			'response_format' => ['type' => 'json_object']
		])->choices[0]->message->content;

		$response = json_decode($response);

		if ($response->is_spam) {
			$fail('Spam was detected');
		};
	}
}
