<?php

	namespace App\Repositories\Auth;


    use App\Enums\UserAccountTypeEnum;
    use App\Models\User;
    use Illuminate\Auth\AuthenticationException;
    use Illuminate\Config\Repository;
    use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
    use Illuminate\Support\Facades\Hash;
    use PHPUnit\Framework\InvalidDataProviderException;

    class AuthRepository extends Repository {

        /**
         * @throws \Exception
         */
        public function userAuthentication(array $data, $provider): \Illuminate\Http\JsonResponse {
            $providers = UserAccountTypeEnum::map();

            if (!in_array($provider, $providers)) {
                throw new InvalidDataProviderException('Provider not allowed.');
            }

            $reqProvider = $this->getProvider($provider);
            $providerDataModel = $this->findModelByEmail($reqProvider, $data['email']);

            if (!$providerDataModel || !Hash::check($data['password'], $providerDataModel->password)) {
                throw new AuthenticationException('Not Authorized. Wrong email or password.');
            }

            $accountToken = $providerDataModel->createToken($reqProvider);

            $arr = [
                'access_token' => $accountToken->accessToken,
                'expires_at' => $accountToken->token->expires_at,
                'provider' => $provider
            ];

            return response()->json($arr);

        }

        private function getProvider(string $provider): AuthenticatableContract {
            if ($provider == "user") {
                return new User();
            } else {
                throw new InvalidDataProviderException('Provider not allowed.');
            }
        }

        private function findModelByEmail($reqProvider, string $email){
                return $reqProvider->where('email', '=', $email)->first();
        }
	}
