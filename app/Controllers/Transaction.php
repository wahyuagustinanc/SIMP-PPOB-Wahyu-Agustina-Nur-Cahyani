<?php

namespace App\Controllers;

class Transaction extends BaseController
{
    public function index()
    {
        $client = \Config\Services::curlrequest([
            'http_errors' => false,
            'verify'      => false
        ]);
        $token = session()->get('jwt_token');

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ];


        $historyRes = $client->get('https://take-home-test-api.nutech-integrasi.com/transaction/history', [
            'headers' => $headers
        ]);
        $profileRes = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
            'headers' => $headers
        ]);
        $balanceRes = $client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
            'headers' => $headers
        ]);

        $profile = json_decode($profileRes->getBody(), true);
        $balance = json_decode($balanceRes->getBody(), true);
        $history = json_decode($historyRes->getBody(), true);

        $data = [
            'history'  => $history['data']['records'] ?? [],
            'profile'  => $profile['data'] ?? [],
            'balance'  => $balance['data'] ?? [],
            'limit'    => $history['data']['limit'] ?? 0,
            'offset'    => $history['data']['offset'] ?? 0,
        ];

        return view('Transaction/index', $data);
    }

    public function loadMoreHistory()
    {
        $limit  = $this->request->getGet('limit') ?? 5;
        $offset = $this->request->getGet('offset') ?? 0;

        $client = \Config\Services::curlrequest([
            'http_errors' => false,
            'verify'      => false
        ]);

        $token = session()->get('jwt_token');

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ];

        $historyRes = $client->get("https://take-home-test-api.nutech-integrasi.com/history?limit={$limit}&offset={$offset}", [
            'headers' => $headers
        ]);

        $history = json_decode($historyRes->getBody(), true);

        return $this->response->setJSON($history['data']['records'] ?? []);
    }

    public function pembayaran($serviceCode)
    {
        $client = \Config\Services::curlrequest([
            'http_errors' => false,
            'verify'      => false
        ]);

        $token = session()->get('jwt_token');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ];

        $servicesRes = $client->get('https://take-home-test-api.nutech-integrasi.com/services', [
            'headers' => $headers
        ]);
        $profileRes = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
            'headers' => $headers
        ]);
        $balanceRes = $client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
            'headers' => $headers
        ]);

        $profile = json_decode($profileRes->getBody(), true);
        $balance = json_decode($balanceRes->getBody(), true);
        $services = json_decode($servicesRes->getBody(), true);

        $selected = null;
        foreach ($services['data'] ?? [] as $srv) {
            if ($srv['service_code'] === $serviceCode) {
                $selected = $srv;
                break;
            }
        }

        if (!$selected) {
            return redirect()->to('/')->with('error', 'Layanan tidak ditemukan');
        }

        return view('Transaction/pembayaran', [
            'service' => $selected,
            'profile'  => $profile['data'] ?? [],
            'balance'  => $balance['data'] ?? []
        ]);
    }

    public function prosesBayar()
    {
        $serviceCode = $this->request->getPost('service_code');
        $amount      = $this->request->getPost('amount');

        $token = session()->get('jwt_token');

        $client = \Config\Services::curlrequest([
            'http_errors' => false,
            'verify'      => false
        ]);

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json'
        ];

        $payload = [
            'service_code' => $serviceCode,
            'amount'       => (int)$amount
        ];

        $res = $client->post('https://take-home-test-api.nutech-integrasi.com/transaction', [
            'headers' => $headers,
            'json'    => $payload
        ]);

        $result = json_decode($res->getBody(), true);

        return $this->response->setJSON($result);
    }
}