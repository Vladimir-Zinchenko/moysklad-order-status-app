<?php

namespace App\Util;

use RuntimeException;

class Curl
{
    protected $ch;

    protected string $url;

    protected array $options = [
        CURLOPT_ENCODING => '',
    ];

    public const MIME_X_WWW_FORM  = 'application/x-www-form-urlencoded';

    public const MIME_FORM_DATA  = 'multipart/form-data';

    public const MIME_JSON = 'application/json';

    /**
     * @param string $url
     *
     * @return Curl
     */
    public static function factory(string $url): Curl
    {
        return new self($url);
    }

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->ch = curl_init();
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options): Curl
    {
        $this->options = $this->options + $options;

        return $this;
    }

    /**
     * @param string $key
     * @param        $value
     *
     * @return $this
     */
    public function setOption(string $key, $value): Curl
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function unsetOption(string $key): Curl
    {
        unset($this->options[$key]);

        return $this;
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        $this->setOption(CURLOPT_URL, $this->url);

        $key = md5(implode('', array_keys($this->options)) . implode('', $this->options));

        if (Cache::getInstance()->has($key)) {
            return Cache::getInstance()->get($key, '');
        }

        curl_setopt_array($this->ch, $this->options);

        $output = curl_exec($this->ch);
//        var_dump($output);die;

        if ($output === false) {
            throw new RuntimeException('Error when requesting to the server');
        }

        curl_close($this->ch);

        Cache::getInstance()->set($key, $output);

        return $output;
    }

    /**
     * @return array
     */
    public function execAsJson(): array
    {
        return json_decode($this->exec(), true);
    }
}
