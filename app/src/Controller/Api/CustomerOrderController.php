<?php

namespace App\Controller\Api;

use App\Component\Http\Request;
use App\Component\Http\Response\JsonResponse;
use App\Exception\HttpException;
use App\Service\Moysklad\Authentication;
use App\Service\Moysklad\CustomerOrder;

class CustomerOrderController
{
    private CustomerOrder $customerOrder;

    private Authentication $authentication;

    private Request  $request;

    /**
     * @param CustomerOrder $customerOrder
     * @param Authentication $authentication
     * @param Request $request
     */
    public function __construct(CustomerOrder $customerOrder, Authentication $authentication, Request  $request)
    {
        $this->customerOrder = $customerOrder;
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
                $response = $this->loadOrders();
                break;
            case Request::METHOD_POST:
                $response = $this->updateOrder();
                break;
            default:
                throw new HttpException(404, 'Not Found');
        }

        return $response;
    }

    /**
     * @return JsonResponse
     */
    public function loadOrders(): JsonResponse
    {
        $this->authentication->authenticatedOrFail();

        $orders = $this->customerOrder->getList();

        return new JsonResponse($orders);
    }

    /**
     * @return JsonResponse
     */
    public function updateOrder(): JsonResponse
    {
        $this->authentication->authenticatedOrFail();

        $data = $this->request->getAjaxData();
        $id = $data['id'];
        unset($data['id']);

        $result = $this->customerOrder->update($id, $data);
        var_dump($this->authentication->getToken(), $result); die;

        return new JsonResponse('OK');
    }
}
