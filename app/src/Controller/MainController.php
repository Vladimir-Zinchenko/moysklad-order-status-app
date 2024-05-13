<?php

namespace App\Controller;

use App\Component\Http\Response\JsonResponse;
use App\Component\Http\Response\Response;
use App\Component\TemplateRenderer;
use App\Service\Moysklad\Authentication;
use App\Service\Moysklad\CustomerOrder;

/**
 * Class MainController
 */
class MainController
{
    private Authentication $authentication;

    private CustomerOrder $customerOrder;

    /**
     * @param Authentication $authentication
     * @param CustomerOrder  $customerOrder
     */
    public function __construct(Authentication $authentication, CustomerOrder $customerOrder)
    {
        $this->authentication = $authentication;
        $this->customerOrder = $customerOrder;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        if ($this->authentication->isAuthenticated()) {
            $location = '/orders';
        } else {
            $location = '/login';
        }

        return $this->redirect($location);
    }

    /**
     * @return Response
     */
    public function login(): Response
    {
        if ($this->authentication->isAuthenticated()) {
            return $this->redirect('/orders');
        }

        $content = TemplateRenderer::factory('main/login')->render();

        return new Response($content);
    }

    /**
     * @return Response
     */
    public function orders(): Response
    {
        if (!$this->authentication->isAuthenticated()) {
            return $this->redirect('/login');
        }

        $content = TemplateRenderer::factory('main/orders', [
            'login' => $this->authentication->getLogin(),
            'orderStatesList' => $this->customerOrder->getStates()
        ])->render();

        return new Response($content);
    }

    /**
     * @param string $location
     *
     * @return Response
     */
    protected function redirect(string $location): Response
    {
        return (new Response())
            ->setStatusCode(302)
            ->setHeaders(['Location' => $location]);
    }
}
