<?php

    namespace App\Http\Controllers;

    use App\Exceptions\AuthMockException;
    use App\Exceptions\InsufficientBalanceOnWalletException;
    use App\Exceptions\TransferDeniedException;
    use App\Repositories\TransferRepository;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;


    class TransferController extends Controller {
        public function __construct(TransferRepository $transferRepository)
        {
            $this->transferRepository = $transferRepository;
        }

        public function postTransfer(Request $request) {
            $this->validateRequest($request);

            $data = $request->only(['payer_wallet_id', 'payee_wallet_id', 'value']);
            $data['id']= Str::uuid();

            try {
                $result = $this->transferRepository->createTransfer($data);
                return response()->json($result);
            } catch (TransferDeniedException $exception) {
                throw new TransferDeniedException();
            } catch (InsufficientBalanceOnWalletException $exception){
                throw new InsufficientBalanceOnWalletException();
            } catch (AuthMockException $exception) {
                throw new AuthMockException();
            }
            catch (\Exception $exception) {
                return $exception->getMessage();
            }
        }

        public function validateRequest(Request $request) {
            $this->validate($request, [
                'payer_wallet_id' => 'required',
                'payee_wallet_id' => 'required',
                'value' => 'required|numeric'
            ]);
        }
    }
