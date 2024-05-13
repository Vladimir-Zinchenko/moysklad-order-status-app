<?php

namespace App;

use App\Component\Container;
use App\Component\ControllerHandler;
use App\Component\Http\Request;
use App\Component\Http\Response\JsonResponse;
use App\Component\Http\Response\Response;
use App\Component\ResponseHandler;
use App\Util\Config;
use Exception;

/**
 * Class Application
 */
class Application
{
    private array $config;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function run(): void
    {
        session_start();

        Config::init($this->config);

        $containerMap = [];
        $container = new Container($containerMap);
        $handler = new ControllerHandler($container);
        $response = $handler->handle();
        /** @var Request $request */
        $request = $container->get(Request::class);

        if (is_null($response)) {
            $response = $request->isAjax() ? new JsonResponse([]) : new Response('');
        }

        (new ResponseHandler($response))->handle();

        exit();
    }
}
