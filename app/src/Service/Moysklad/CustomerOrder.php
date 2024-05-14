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
            $state = $requestedOrder['state']['id'];
//            var_dump($requestedOrder['agent']);die;
            $agent = [
                'name' => $requestedOrder['agent']['name'],
                'href' => $requestedOrder['agent']['meta']['uuidHref'],
            ];
            $organization = [
                'name' => $requestedOrder['organization']['name'],
                'href' => $requestedOrder['organization']['meta']['uuidHref'],
            ];
            $currency = [
                'name' => $requestedOrder['rate']['currency']['name'],
                'href' => $requestedOrder['rate']['currency']['meta']['uuidHref'],
            ];

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
            ->execAsArray();
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
        $result = Curl::factory(self::CUSTOMER_ORDERS_URL . '?expand=state,agent,organization,rate.currency&order=created,desc&limit=100')
            ->setOptions([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Accept-Encoding: gzip',
                    'Authorization: Bearer ' . $this->authentication->getToken(),
                ]
            ])
            ->execAsArray();

        return $result['rows'];
    }

    /**
     * @return array
     */
    private function statesRequest(): array
    {
        $result = Curl::factory(self::CUSTOMER_ORDERS_URL . '/metadata')
            ->setOptions([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Accept-Encoding: gzip',
                    'Authorization: Bearer ' . $this->authentication->getToken(),
                ]
            ])
            ->execAsArray();;

        return $result['states'];
    }
}
