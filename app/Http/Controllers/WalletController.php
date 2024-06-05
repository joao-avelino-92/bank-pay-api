<?php /** @noinspection PhpUndefinedFieldInspection */

    namespace App\Http\Controllers;

    use App\Models\Wallet;
    use App\Repositories\WalletRepository;
    use Illuminate\Http\Request;

    class WalletController extends controller {

        public function __construct(WalletRepository $walletRepository)
        {
            $this->walletRepository = $walletRepository;
        }

        public function getWallets(): \Illuminate\Http\JsonResponse {
            $wallets = Wallet::all();
            return response()->json($wallets);
        }

        public function getWalletById($id) {
            $wallet = Wallet::findOrFail($id);
            return response()->json($wallet);
        }

        public function postWallet(Request $request) {
            $this->validateRequest($request);

            return $this->walletRepository->createWallet($request);

        }

        public function validateRequest(Request $request) {
            $this->validate($request, [
                'user_id' => 'required',
            ]);
        }

    }
