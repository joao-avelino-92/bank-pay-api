<?php /** @noinspection PhpUndefinedFieldInspection */

    namespace App\Repositories;

    use App\Enums\UserAccountTypeEnum;
    use App\Exceptions\AuthMockException;
    use App\Exceptions\InsufficientBalanceOnWalletException;
    use App\Exceptions\TransferDeniedException;
    use App\Models\User;
    use App\Models\Transfer;
    use App\Models\Wallet;
    use App\Services\Mail\Transfer\TransferEmail;
    use App\Services\Mock\externalAuthMock;
    use App\Services\Mock\transferNotificationMock;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\DB;

    class TransferRepository {

        public function __construct(externalAuthMock $authMock, transferNotificationMock $notificationMock, TransferEmail $sendTransferEmail) {
            $this->authMock = $authMock;
            $this->notificationMock = $notificationMock;
            $this->sendTransferEmail = $sendTransferEmail;
        }

        public function createTransfer(array $data) {
            $payerWallet = Auth::user()->wallet;
            $payeeWallet = Wallet::findOrFail($data['payee_wallet_id']);

            if (!$this->isLoggedAndNotTrader($payerWallet)) { // verifica se usuario logado é do tipo User e se o payeer_id da requisição é igual ao logado.
                throw new TransferDeniedException();
            }

            if (!$this->checkUserWalletBalance($payerWallet->balance, $data['value'])) { // verifica saldo da carteira
                throw new InsufficientBalanceOnWalletException();
            }

            if (!$this->isAuthorizedByMock()) { //http request para serviço https://util.devi.tools/api/v2/authorize
                throw new AuthMockException();
            }

          $transfer = $this->persistTransfer($data, $payerWallet, $payeeWallet);
            return response()->json(['Transfer' => $transfer]);
        }

        private function persistTransfer(array $data, $payerWallet, $payeeWallet) {
            try {
                return DB::transaction(function () use ($data, $payerWallet, $payeeWallet) {
                    $transfer = Transfer::create([
                        'id' => Str::uuid(),
                        'payer_wallet_id' => $data['payer_wallet_id'],
                        'payee_wallet_id' => $data['payee_wallet_id'],
                        'value' => $data['value']
                    ]);

                    $payerWallet->debitWallet($data['value']);
                    $payeeWallet->depositWallet($data['value']);

                    //envio de notificação precisa ser disparado dentro da DB transaction, caso caia no catch não será feito.
                    //http request para serviço https://util.devi.tools/api/v1/notify
                    // isServiceAbleToSendNotifications implementar de acordo com o retorno da requisição
                    $mockNotificationResponse = $this->isServiceAbleToSendNotifications();

                    if (!$mockNotificationResponse) {
                        throw new AuthMockException();
                    } else {
                        try {
                            $this->sendTransferEmail->sendTransferEmail($payerWallet->user, $payeeWallet->user, $data['value']);
                        } catch (\Exception $exception) {
                            response()->json(["Error" => $exception]);
                        }
                    }
                    return $transfer;
                });
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }
        public function isLoggedAndNotTrader($payerWallet): bool {
            $userModel = Auth::user();
            if ($userModel->user_type === UserAccountTypeEnum::USER && $userModel->id === $payerWallet->user_id) { //por questao de segurança inclui essa verificação caso o front end envie um id pagador diferente do que esta logado
                return true;
            } else {
                return false;
            }
        }
        private function checkUserWalletBalance($walletBalance, $payloadValue): bool {
            return $walletBalance >= $payloadValue;
        }
        private function isAuthorizedByMock(): bool {
            $authService = $this->authMock->verifyMockAuth();
            if ($authService['status'] == 'success') {
                return true;
            };
            return false;
        }
        private function isServiceAbleToSendNotifications(): bool {
            $notififierService = $this->notificationMock->verifyMockNotification();
//            if (is_object($notififierService) && $notififierService->get('status') == 'success'){
            return true;
//            } else {
//                return false;
//            }
        }

    }
