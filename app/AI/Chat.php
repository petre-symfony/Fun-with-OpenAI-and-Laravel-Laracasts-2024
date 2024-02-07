<?php

namespace App\AI;

class Chat {
	protected array $messages = [];

	public function messages() {
		return $this->messages;
	}
}
//$chat->messages()