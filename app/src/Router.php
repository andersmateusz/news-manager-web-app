<?php

declare(strict_types=1);

namespace Andersma\NewsManager;

use Andersma\NewsManager\Http\HttpException;
use Andersma\NewsManager\Http\HttpMethod;
use Andersma\NewsManager\Http\Response\ResponseInterface;
use BadMethodCallException;
use LogicException;
use RuntimeException;
use function array_column;
use function array_filter;
use function array_key_first;
use function array_map;
use function header;
use function http_response_code;
use function implode;
use function parse_url;
use function sprintf;
use function strtoupper;
use const PHP_URL_PATH;

/**
 * @method static void get(string $path, callable $handler)
 * @method static void post(string $path, callable $handler)
 * @method static void patch(string $path, callable $handler)
 * @method static void delete(string $path, callable $handler)
 * @method static void put(string $path, callable $handler)
 */
final class Router
{
    /** @var array<int, array{path:string, httpMethod: HttpMethod, handler: callable}> */
    private static array $routes = [];

    public static function __callStatic(string $name, array $arguments): void
    {
        $httpMethod = HttpMethod::tryFromName(strtoupper($name));

        if (!$httpMethod) {
            throw new BadMethodCallException(sprintf('Method "%s" does not exist', $name));
        }

        self::registerRoute($httpMethod, ...$arguments);
    }

    private static function registerRoute(HttpMethod $method, string $path, callable $handler): void
    {
        if (!empty(array_filter(self::$routes, static fn (array $route) => $path === $route['path'] && $method === $route['httpMethod']))) {
            throw new LogicException(sprintf('Route "%s" already registered', $path));
        }

        self::$routes[] = [
            'path' => $path,
            'httpMethod' => $method,
            'handler' => $handler,
        ];
    }

    public function handle(): never
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = HttpMethod::fromGlobal();

        $pathMatch = array_filter(self::$routes, static fn (array $route) => $path === $route['path']);
        $methodMatch = array_filter($pathMatch, static fn (array $route) => $method === $route['httpMethod']);

        try {
            if (empty($pathMatch)) {
                throw new HttpException('Not found', 404);
            }

            if (empty($methodMatch)) {
                $allowedMethods = array_map(static fn (HttpMethod $method) => $method->name, array_column(self::$routes, 'httpMethod'));
                throw new HttpException(sprintf('Method "%s" not allowed', $method->name), 405, ['Allow: ' . implode(', ', $allowedMethods)]);
            }

            $response = self::$routes[array_key_first($methodMatch)]['handler']();

            if (!$response instanceof ResponseInterface) {
                throw new RuntimeException(sprintf('Handler must return an instance of "%s"', ResponseInterface::class));
            }

            $response->send();

        } catch (HttpException $e) {
            http_response_code($e->getCode());
            foreach ($e->getHeaders() as $header) {
                header($header);
            }
            echo $e->getMessage();
            exit;
        }
    }
}