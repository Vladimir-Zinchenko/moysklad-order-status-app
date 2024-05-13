<?php

namespace App\Service\Moysklad;

use App\Exception\HttpException;
use App\Util\Curl;
use App\Util\Moysklad\MoyskladHelper;
use RuntimeException;

class Authentication
{
    protected array $authData = [];

    protected const AUTH_URL = 'https://api.moysklad.ru/api/remap/1.2/security/token';

    public function __construct()
    {
        $this->init();
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @return bool
     */
    public function authenticate(string $login, string $password): bool
    {
        if ($this->isAuthenticated()) {
            return true;
        }

        return $this->authenticateRequest($login, $password);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->authData = [];
        $_SESSION['auth_data'] = [];
    }

    public function authenticatedOrFail(): void
    {
        if (!$this->isAuthenticated()) {
            throw new HttpException(403, 'Unauthorized');
        }
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->authData['token'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->authData['login'] ?? null;
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return !empty($this->authData);
    }

    /**
     * @return void
     */
    protected function init(): void
    {
        if (isset($_SESSION['auth_data'])) {
            $this->authData = $_SESSION['auth_data'];
        }
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @return bool
     */
    protected function authenticateRequest(string $login, string $password): bool
    {
        $credentials = base64_encode($login . ':' . $password);
        $result = Curl::factory(self::AUTH_URL)
            ->setOptions([
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Accept-Encoding: gzip',
                    'Authorization: Basic ' . $credentials,
                ]
            ])
            ->execAsJson();

        if (isset($result['access_token'])) {
            $this->authData = [
                'token' => $result['access_token'],
                'login' => $login,
                'password' => $password,
            ];
            $_SESSION['auth_data'] = $this->authData;
            return true;
        }

        if (isset($result['errors'])
            && in_array(MoyskladHelper::ERR_AUTH, MoyskladHelper::codesFromErrors($result['errors'])))
        {
            $this->authData = [];
            return false;
        }

        throw new RuntimeException('Error in request processing');
    }
}
