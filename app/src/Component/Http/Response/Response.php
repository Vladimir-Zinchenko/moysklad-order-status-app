<?php

namespace App\Component\Http\Response;

/**
 * Class Response
 */
class Response
{
    protected array $headers = [];
    protected $content;
    protected int $statusCode;

    /**
     * @param mixed $content
     * @param array $headers
     * @param int   $statusCode
     */
    public function __construct($content = null, int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->headers = array_merge($this->headers, $headers);
        $this->statusCode = $statusCode;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return Response
     */
    public function setHeader(string $key, string $value): Response
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return Response
     */
    public function setHeaders(array $headers): Response
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getHeader(string $key): string
    {
        return $this->headers[$key];
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     *
     * @return Response
     */
    public function setContent($content): Response
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return Response
     */
    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
