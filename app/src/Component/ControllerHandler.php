<?php

namespace App\Component;

use App\Component\Http\Request;
use App\Component\Http\Response\JsonResponse;
use App\Component\Http\Response\Response;
use App\Exception\HttpException;
use App\Util\Config;
use Exception;

/**
 * Class ControllerHandler
 */
class ControllerHandler
{
    private Container $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Response|null
     *
     * @throws
     */
    public function handle(): ?Response
    {
        try {
            /** @var Request $request */
            $request = $this->container->get(Request::class);
            $currentPath = trim($request->getPath(), '/');
            $pathConfig = $this->findConfigInRoutes($currentPath);
            $controllerMethod = null;

            if ($pathConfig) {
                $controllerClass = $pathConfig[0];
                $controllerMethod = $pathConfig[1] ?? null;
            } else {
                $controllerClass = sprintf('App\Controller\%sController', ucfirst($currentPath));
            }

            $response = $this->execute($controllerClass, $controllerMethod);
        } catch (Exception $exception) {
            $response = new JsonResponse(['error' =>$exception->getMessage()], 500);

            if ($exception instanceof HttpException) {
                $response->setStatusCode($exception->getErrorCode());
            }
        }

        return $response;
    }

    /**
     * @param string|null $controllerClass
     * @param string|null $controllerMethod
     *
     * @return Response|null
     *
     * @throws
     */
    private function execute(?string $controllerClass, ?string $controllerMethod): ?Response
    {
        if (!class_exists($controllerClass)) {
            throw new HttpException(404, 'Not found');
        }

        $controller = $this->container->get($controllerClass);

        if ($controllerMethod) {
            $result = $controller->$controllerMethod();
        } else {
            $result = $controller();
        }

        return $result;
    }

    /**
     * @param string $path
     * @return array|null
     */
    private function findConfigInRoutes(string $path): ?array
    {
        $config = null;

        foreach (Config::get('routes', []) as $route => $routeConfig) {
            if (trim($route, '/') === $path) {
                $config = (array)$routeConfig;
                break;
            }
        }

        return $config;
    }
}
