<?php

namespace App\Component\Http\Response;

/**
 * Class JsonResponse
 */
class JsonResponse extends Response
{
    protected array $headers = [
        'Content-Type' => 'application/json; charset=utf-8'
    ];

    /**
     * @return string
     */
    public function getContent(): string
    {
        $content = (array)$this->content;

        return json_encode($content, JSON_UNESCAPED_UNICODE);
    }
}
