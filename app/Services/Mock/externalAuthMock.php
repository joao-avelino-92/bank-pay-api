<?php

    namespace App\Services\Mock;

    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\GuzzleException;

    class externalAuthMock {
        public function verifyMockAuth() {

            $client = new Client();

            try {
                $response = $client->request('GET', 'https://util.devi.tools/api/v2/authorize');
                $json = collect(json_decode($response->getBody()->getContents()));
                return ['status' => $json->get('status')];
            } catch (\Exception $exception) {
                return ['status' => 'Error'];
            }
        }
    }
