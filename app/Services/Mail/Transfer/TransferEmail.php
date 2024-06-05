<?php

    namespace App\Services\Mail\Transfer;

    use App\Models\User;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Mail\Message;
    class TransferEmail {
        public function sendTransferEmail($payeerUserData, $payeeUserData, $value): void {
            $transferDataArray =[
                'payeerEmail' => $payeerUserData->email,
                'payeerName' =>  $payeerUserData->name,
                'payeeEmail' =>  $payeeUserData->email,
                'payeeName' =>  $payeeUserData->name,
                'value' => $value
            ];

            try {
                //send to Payeer
                Mail::send('email.transfer.payer', ['transferDataArray' => $transferDataArray], function (Message $message) use ($transferDataArray) {
                    $message->to($transferDataArray['payeerEmail'], $transferDataArray['payeerName'])
                            ->from('joaoavnt@gmail.com', 'digital-bank-api')
                            ->subject('Hi, you send a transfer on digital-bank-api!');
                });

                //send to Payee
                Mail::send('email.transfer.payee', ['transferDataArray' => $transferDataArray], function (Message $message) use ($transferDataArray) {
                    $message->to($transferDataArray['payeeEmail'], $transferDataArray['payeeName'])
                            ->from('joaoavnt@gmail.com', 'digital-bank-api')
                            ->subject('Hi, you receive a transfer on digital-bank-api!');
                });
            } catch (\Exception $exception) {
                throw new \Exception($exception);
            }
        }
    }
