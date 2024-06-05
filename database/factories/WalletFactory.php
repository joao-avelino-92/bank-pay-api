<?php

	namespace Database\Factories;

	use App\Models\Wallet;
    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Support\Facades\Hash;

    class WalletFactory extends Factory {

        /**
         * The name of the factory's corresponding model.
         *
         * @var string
         */
        protected $model = Wallet::class;

        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition()
        {
            return [
                'id' => $this->faker->uuid,
                'user_id' => '03cfc5eb-4646-33fd-9770-d62e2e601235',
                'balance' => rand(1111, 9999),
            ];
        }

	}
