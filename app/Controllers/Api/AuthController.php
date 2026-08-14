<?php

namespace App\Controllers\Api;

use App\Libraries\JWTService;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseApiController
{
    public function login(): ResponseInterface
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return $this->error('Validation Error', 422, $this->validator->getErrors());
        }

        $credentials = [
            'email'    => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
        ];

        // Cek kredensial via Shield user provider (tanpa menyentuh session)
        $users  = auth()->getProvider();
        $user   = $users->findByCredentials(['email' => $credentials['email']]);

        if ($user === null) {
            return $this->error('Kredensial tidak valid', 401);
        }

        // Validasi password menggunakan Shield Passwords library
        $passwordHandler = service('passwords');
        if (! $passwordHandler->verify($credentials['password'], $user->password_hash)) {
            return $this->error('Kredensial tidak valid', 401);
        }

        if (! $user->active) {
            return $this->error('Akun belum aktif', 403);
        }

        // Bersihkan session Shield yang mungkin tersisa dari request sebelumnya
        // agar tidak terjadi konflik session state
        session()->remove('logged_in');
        session()->remove('id');
        session()->remove('user');

        // Check if SSO is enabled and this acts as SSO Server (has private key)
        $ssoConfig = config('SSOConfig');
        if ($ssoConfig && $ssoConfig->enabled && !empty($ssoConfig->privateKey)) {
            $token = (new JWTService())->sign([
                'sub'      => (string) $user->id,
                'user_id'  => $user->id,
                'email'    => $user->email,
                'roles'    => $user->getGroups(),
            ]);
            return $this->success(['token' => $token], 'Login berhasil');
        }

        // Fallback: Generate Shield Access Token
        $token = $user->generateAccessToken('api-login');

        return $this->success([
            'token'    => $token->raw_token,
            'email'    => $user->email,
            'username' => $user->username,
        ], 'Login berhasil');
    }
}
