<?php

namespace App\Controllers;

class Akun extends BaseController
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

        $profileRes = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
            'headers' => $headers
        ]);

        $profile = json_decode($profileRes->getBody(), true);

        $data = [
            'profile' => $profile['data'] ?? []
        ];

        return view('Akun/index', $data);
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

        $res = $client->post(
            'https://take-home-test-api.nutech-integrasi.com/profile/image',
            [
                'headers'   => $headers,
                'multipart' => [
                    [
                        'name'     => 'profile_image',
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