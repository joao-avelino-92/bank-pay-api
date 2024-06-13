<?php

    namespace App\Services\Mock;

    use App\Exceptions\AuthMockException;
    use App\Exceptions\TransferDeniedException;
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\GuzzleException;

    class transferNotificationMock {
        public function verifyMockNotification() {

            $client = new Client();

            try {
                //não foi passado o body para fazer a requisição completa no ddesafio????
                $response = $client->request('POST', 'https://util.devi.tools/api/v1/notify', [
                    'form_params' => [
                        'foo' => 'bar',
                        'baz' => ['oi', 'lá!']
                    ]
                ]);
                $json = json_decode($response->getBody()->getContents());
                return collect($json);
            } catch (GuzzleException $e) {
                return response()->json(['Error' => 'Mock Notification Service is not working. Try again later.'], 403);
            }
        }
    }
