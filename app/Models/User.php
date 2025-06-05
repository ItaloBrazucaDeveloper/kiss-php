<?php

namespace App\Models;

class Client {
	public function __construct(
		public ?string $cpf,
		public ?string $name,
		public ?string $email,
		public ?string $phone,
		public ?string $status,
		public ?Address $address,
		public ?string $bithdate
	) { }
}