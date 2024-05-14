<?php

namespace App\Service\Moysklad;

use App\Util\Curl;

class CustomerOrder
{
    private const CUSTOMER_ORDERS_URL = 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder';

    private Authentication $authentication;

    /**
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        $orders = [];

        foreach ($this->ordersRequest() as $requestedOrder) {
            $state = $requestedOrder['state']['meta']['href'];
            $state = explode('/', $state);
            $state = end($state);
            $agent = $this->getInfoByUrl($requestedOrder['agent']['meta']['href']);
            $organization = $this->getInfoByUrl($requestedOrder['organization']['meta']['href']);
            $currency = $this->getInfoByUrl($requestedOrder['rate']['currency']['meta']['href']);

            $order = [
                'id' => $requestedOrder['id'],
                'name' => $requestedOrder['name'],
                'href' => $requestedOrder['meta']['uuidHref'],
                'created' => $requestedOrder['created'],
                'agent' => $agent,
                'organization' => $organization,
                'currency' => $currency,
                'sum' => $requestedOrder['sum'],
                'state' => $state,
                'updated' => $requestedOrder['updated'],
            ];

            $orders[] = $order;
        }

        return $orders;
    }

    public function update(string $id, array $data): array
    {
        $body = [];
        $stateId = $data['stateId'];
        var_dump($id, $stateId);
        $body['state'] = [
            'meta' => [
                'href' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/' . $stateId,
                'type' => 'state',
                'mediaType' => 'application/json',
            ]
        ];

        return Curl::factory(self::CUSTOMER_ORDERS_URL . '/' . $id)
            ->setOptions([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => [
                    'Accept-Encoding: gzip',
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->authentication->getToken(),
                ],
                CURLOPT_POSTFIELDS => json_encode($body),
            ])
            ->execAsJson();
    }

    /**
     * @return array
     */
    public function getStates(): array
    {
        $states = [];

        foreach ($this->statesRequest() as $state) {
            $color = dechex($state['color']);
            if (strlen($color) !== 6) {
                $color = str_repeat('0', 6 - strlen($color)) . $color;
            }

            $states[$state['id']] = [
                'id' => $state['id'],
                'name' => $state['name'],
                'color' => '#' . $color,
            ];
        }

        return $states;
    }

    /**
     * @return array
     */
    private function ordersRequest(): array
    {
        $result = Curl::factory(self::CUSTOMER_ORDERS_URL . '?order=created,desc')
            ->setOptions([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Accept-Encoding: gzip',
                    'Authorization: Bearer ' . $this->authentication->getToken(),
                ]
            ])
            ->execAsJson();

        return $result['rows'];
    }

    private function getInfoByUrl(string $url): array
    {
        $result = $this->request($url);

        return [
            'name' => $result['name'],
            'href' => $result['meta']['uuidHref'],
        ];
    }

    /**
     * @return array
     */
    private function statesRequest(): array
    {
        $result = $this->request(self::CUSTOMER_ORDERS_URL . '/metadata');

        return $result['states'];
    }

    /**
     * @param string $url
     *
     * @return array
     */
    private function request(string $url): array
    {
        return Curl::factory($url)
            ->setOptions([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Accept-Encoding: gzip',
                    'Authorization: Bearer ' . $this->authentication->getToken(),
                ]
            ])
            ->execAsJson();
    }
}
