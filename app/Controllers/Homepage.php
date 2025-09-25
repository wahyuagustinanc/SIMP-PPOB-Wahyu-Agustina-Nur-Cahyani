<?php

namespace App\Controllers;

class Homepage extends BaseController
{
    public function index()
    {
        $client = \Config\Services::curlrequest([
            'http_errors' => false,
            'verify'      => false
        ]);

        try {
            $token = session()->get('jwt_token');

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ];

            $profileRes = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => $headers
            ]);
            $balanceRes = $client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
                'headers' => $headers
            ]);
            $serviceRes = $client->get('https://take-home-test-api.nutech-integrasi.com/services', [
                'headers' => $headers
            ]);
            $bannerRes = $client->get('https://take-home-test-api.nutech-integrasi.com/banner', [
                'headers' => $headers
            ]);

            $profile = json_decode($profileRes->getBody(), true);
            $balance = json_decode($balanceRes->getBody(), true);
            $services = json_decode($serviceRes->getBody(), true);
            $banners = json_decode($bannerRes->getBody(), true);

            $data = [
                'profile'  => $profile['data'] ?? [],
                'balance'  => $balance['data'] ?? [],
                'services' => $services['data'] ?? [],
                'banners'  => $banners['data'] ?? []
            ];

            return view('Homepage/index', $data);
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function topup()
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

        $profileRes = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
            'headers' => $headers
        ]);
        $balanceRes = $client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
            'headers' => $headers
        ]);

        $profile = json_decode($profileRes->getBody(), true);
        $balance = json_decode($balanceRes->getBody(), true);

        $data = [
            'profile'  => $profile['data'] ?? [],
            'balance'  => $balance['data'] ?? []
        ];

        return view('Homepage/topup', $data);
    }

    public function doTopup()
    {
        $nominal = $this->request->getPost('nominal');

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
            'nominal'       => (int)$nominal
        ];

        $res = $client->post('https://take-home-test-api.nutech-integrasi.com/topup', [
            'headers' => $headers,
            'json'    => $payload
        ]);

        $result = json_decode($res->getBody(), true);

        return $this->response->setJSON($result);
    }


    public function transaction()
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

        return view('Homepage/transaction', $data);
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

        return view('Homepage/pembayaran', [
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

    public function lihatAkun()
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

        $profileRes = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
            'headers' => $headers
        ]);

        $profile = json_decode($profileRes->getBody(), true);

        $data = [
            'profile' => $profile['data'] ?? []
        ];

        return view('Homepage/akun', $data);
    }

    public function updateAkun()
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

        $body = json_encode([
            "first_name" => $this->request->getPost('first_name'),
            "last_name"  => $this->request->getPost('last_name')
        ]);

        $res = $client->put(
            'https://take-home-test-api.nutech-integrasi.com/profile/update',
            ['headers' => $headers, 'body' => $body]
        );

        $result = json_decode($res->getBody(), true);

        return $this->response->setJSON($result);
    }

    public function updateAkunImage()
    {
        $file = $this->request->getFile('profile_image');
        if (!$file->isValid()) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'File tidak valid'
            ]);
        }

        $client = \Config\Services::curlrequest([
            'http_errors' => false,
            'verify'      => false
        ]);

        $token = session()->get('jwt_token');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
        ];

        // kirim file ke API eksternal
        $res = $client->post(
            'https://take-home-test-api.nutech-integrasi.com/profile/image',
            [
                'headers'   => $headers,
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($file->getTempName(), 'r'),
                        'filename' => $file->getName(),
                    ]
                ]
            ]
        );

        $result = json_decode($res->getBody(), true);

        return $this->response->setJSON($result);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}