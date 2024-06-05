<?php /** @noinspection PhpUndefinedFieldInspection */

    namespace App\Repositories;

	use App\Exceptions\OnlyLoggedCreateWalletException;
    use App\Models\Wallet;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Auth;

    class WalletRepository {
        public function createWallet($request){
            $wallet = new Wallet();

//            only the logged user can create your own wallet
            $loggedUser = Auth::user()->id;

            if($loggedUser == $request->user_id){
                $wallet->id = Str::uuid();
                $wallet->user_id = Auth::user()->id;
                $wallet->balance = $request->balance;
                $wallet->save();

            } else {
                throw new OnlyLoggedCreateWalletException();
            }

            return response()->json(['main' => 'Now You Have a Wallet!', $wallet], 200);
        }

	}
