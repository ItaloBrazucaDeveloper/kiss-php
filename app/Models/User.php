<?php

namespace App\Models;

class Client {
	public function __construct(
		public readonly ?string $cpf,
		public readonly ?string $name,
		public readonly ?string $email,
		public readonly ?string $phone,
		public readonly ?string $status,
		public readonly ?Address $address,
		public readonly ?string $bithdate
	) { }
}