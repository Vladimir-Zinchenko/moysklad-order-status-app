<?php

namespace App\Controller\Api;

use App\Component\Http\Request;
use App\Component\Http\Response\JsonResponse;
use App\Exception\HttpException;
use App\Service\Moysklad\Authentication;

class AuthController
{
    private Authentication $authentication;

    private Request  $request;

    /**
     * @param Authentication $authentication
     * @param Request        $request
     */
    public function __construct(Authentication $authentication, Request  $request)
    {
        $this->authentication = $authentication;
        $this->request = $request;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        switch ($this->request->getMethod()) {
            case Request::METHOD_GET:
                $response = $this->info();
                break;
            case Request::METHOD_POST:
                $response = $this->login();
                break;
            default:
                throw new HttpException(404, 'Not Found');
        }

        return $response;
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authentication->logout();

        return new JsonResponse(['OK']);
    }

    /**
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        $this->authentication->authenticatedOrFail();

        return new JsonResponse(['login' => $this->authentication->getLogin()]);
    }

    /**
     * @return JsonResponse
     */
    private function login(): JsonResponse
    {
        $data = $this->request->getAjaxData();
        $login = $data['login'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($login) || empty($password)) {
            throw new HttpException(400, 'Bad Request');
        }

        if ($this->authentication->authenticate($login, $password)) {
            return new JsonResponse(['OK']);
        }

        return new JsonResponse(['Invalid login or password'], 403);
    }
}
