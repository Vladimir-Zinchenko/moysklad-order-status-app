<?php

namespace App\Component;

use App\Component\Http\Response\Response;

/**
 * Class ResponseHandler
 */
class ResponseHandler
{
    private Response $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response) {
        $this->response = $response;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        http_response_code($this->response->getStatusCode());
        $this->sendHeaders();

        echo $this->response->getContent();
    }

    /**
     * @return void
     */
    private function sendHeaders(): void
    {
        foreach ($this->response->getHeaders() as $headerKey => $headerValue) {
            header(sprintf('%s: %s', $headerKey, $headerValue));
        }
    }
}
