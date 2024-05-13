<?php

namespace App\Component\Http;

/**
 * Class Request
 *
 * Implementation outside of PSR
 */
class Request
{
    private string $path;
    private array $query;

    public const METHOD_GET = 'GET';

    public const METHOD_POST = 'POST';

    public function __construct() {
        $this->init();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $key
     *
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getQuery(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * @return array
     */
    public function getAllQuery(): array
    {
        return $this->query;
    }

    /**
     * @return bool
     */
    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * @return array
     */
    public function getAjaxData(): array
    {
        $rawData = file_get_contents("php://input");
        return json_decode($rawData, true);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return void
     */
    private function init(): void
    {
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        parse_str($query, $result);
        $this->query = $result;
    }
}
