<?php
    declare(strict_types=1);

    namespace App\Http\Controllers;


    use App\Repositories\Auth\AuthRepository;
    use Illuminate\Auth\AuthenticationException;
    use \Illuminate\Http\Request;
    use PHPUnit\Framework\InvalidDataProviderException;

    class AuthController extends Controller {

        public function __construct(AuthRepository $authRepository) {
            $this->authRepository = $authRepository;
        }

        /**
         * @throws \Exception
         */
        public function login(Request $req, $provider) {
            $this->validateRequest($req);
            $data = $req->only(['email', 'password']);
            try {
                return $this->authRepository->userAuthentication($data, $provider);
            } catch (InvalidDataProviderException $e) {
                return response()->json(['Errors' => ['main' => $e->getMessage()]], 422);
            } catch (AuthenticationException $e) {
                return response()->json(['Errors' => ['main' => $e->getMessage()]], 401);
            }
        }

        public function validateRequest(Request $request) {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);
        }

    }
