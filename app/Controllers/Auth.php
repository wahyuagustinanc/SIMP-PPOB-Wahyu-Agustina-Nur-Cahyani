<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function index()
    {
        return view('regist');
    }

    public function register()
    {
        helper(['form', 'url']);

        $request = service('request');

        $rules = [
            'email'            => 'required|valid_email',
            'first_name'       => 'required',
            'last_name'        => 'required',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->with('error', $errors)->withInput();
        }

        $data = [
            'email'      => $request->getPost('email'),
            'first_name' => $request->getPost('first_name'),
            'last_name'  => $request->getPost('last_name'),
            'password'   => $request->getPost('password')
        ];

        $client = \Config\Services::curlrequest([
            'http_errors' => false,
            'verify'      => false
        ]);

        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/registration', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody(), true);

            if ($statusCode == 200 && isset($result['status']) && $result['status'] == 0) {
                return redirect()->to('/')->with('success', $result['message'] ?? 'Registrasi berhasil, silahkan login');
            } else {
                return redirect()->to('/')->with('error', $result['message'] ?? 'Registrasi gagal')->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->to('/')->with('error', 'Terjadi kesalahan server: ' . $e->getMessage())->withInput();
        }
    }

    public function register2()
    {
        helper(['form']);

        $rules = [
            'email'            => 'required|valid_email',
            'first_name'       => 'required',
            'last_name'        => 'required',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/')->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $request = service('request');

        $data = [
            "email"      => $request->getPost('email'),
            "first_name" => $request->getPost('first_name'),
            "last_name"  => $request->getPost('last_name'),
            "password"   => $request->getPost('password')
        ];

        $client = \Config\Services::curlrequest();
        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/registration', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                // 'body' => json_encode($data)
                'json' => $data
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['status']) && $result['status'] == 0) {
                return redirect()->to('/')->with('success', $result['message'] ?? 'Registrasi berhasil silahkan login');
            } else if (isset($result['status']) && $result['status'] == 102) {
                return redirect()->to('/')->with('success', 'Paramter email tidak sesuai format');
            } else {
                return redirect()->to('/')->with('error', $result['message'] ?? 'Registrasi gagal');
            }
        } catch (\Exception $e) {
            return redirect()->to('/')->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    public function login()
    {
        return view('login');
    }

    public function doLogin()
    {
        helper(['form']);

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/login')->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $request = service('request');

        $data = [
            "email"    => trim($request->getPost('email')),
            "password" => $request->getPost('password')
        ];

        $client = \Config\Services::curlrequest(['http_errors' => false]);

        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/login', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['status']) && $result['status'] == 0) {
                session()->set('jwt_token', $result['data']['token']);
                session()->set('user_email', $data['email']);
                return redirect()->to('/homepage')->with('success', 'Login berhasil');
            } else {
                $message = $result['message'] ?? 'Login gagal';

                if (strpos($message, 'password') !== false) {
                    return redirect()->back()->withInput()->with('passwordError', $message);
                } else {
                    return redirect()->back()->withInput()->with('error', $message);
                }
            }
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }
}