<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

abstract class BaseAPI
{
    /**
     * @var string rest|ajax
     */
    protected string $type = 'rest';

    /**
     * @var array<string>
     */
    private array $nameSpaces;

    /**
     * @var array<mixed>
     */
    private array $middlewares = [];

    /**
     * @param array<string,array<mixed>> $routeList
     * @return void
     */
    public function addRoutes(array $routeList): void
    {
        if (empty($routeList)) {
            return;
        }

        if ('rest' == $this->type) {
            $this->nameSpaces = array_keys($routeList);
            add_action('rest_api_init', function () use ($routeList): void {
                foreach ($routeList as $nameSpace => $routes) {
                    foreach ($routes as $route => $config) {
                        $callback = is_array($config) ? $config['callback'] : $config;
                        $methods = isset($config['methods']) ? $config['methods'] : ['POST', 'GET'];
                        if (isset($config['middleware'])) {
                            $this->addMiddleware($nameSpace . '/' . $route, $config['middleware']);
                        }
                        register_rest_route($nameSpace, $route, [
                            'callback' => [$this, $callback],
                            'methods' => $methods,
                            'permission_callback' => '__return_true'
                        ]);
                    }
                }
            });
        } elseif ('ajax' == $this->type) {
            foreach ($routeList as $nameSpace => $routes) {
                foreach ($routes as $route => $config) {
                    $callback = is_array($config) ? $config['callback'] : $config;
                    $nameSpace = str_replace(['\\', '/', '-'], '_', $nameSpace);
                    Helpers::ajaxAction($this, $nameSpace . '_' . $callback);
                }
            }
        } else {
            throw new \Exception('Invalid API type');
        }

        add_filter('rest_pre_dispatch', [$this, 'middlewareFilter'], 10, 3);

        Helpers::addAPI($this);
    }

    /**
     * @param string $nameSpace
     * @param array<mixed>|\Closure $caller
     * @return void
     */
    protected function addMiddleware(string $nameSpace, array|\Closure $caller): void
    {
        $this->middlewares[$nameSpace] = $caller;
    }

    /**
     * @param mixed $result
     * @param \WP_REST_Server $server
     * @param \WP_REST_Request $request
     * @return mixed
     */
    public function middlewareFilter(mixed $result, \WP_REST_Server $server, \WP_REST_Request $request): mixed
    {
        if (empty($this->middlewares)) {
            return $result;
        }

        $path = implode('/', Helpers::getRoutePaths($request->get_route()));

        foreach ($this->middlewares as $endpoint => $caller) {
            if (false !== strpos($path, $endpoint)) {
                if (is_array($caller)) {
                    $result = call_user_func_array($caller, [$result, $server, $request]);
                } else {
                    $result = $caller($result, $server, $request);
                }
            }
        }

        return $result;
    }

    /**
     * @param string $nameSpace
     * @return string
     */
    public function getUrl(?string $nameSpace = null): string
    {
        if ('rest' == $this->type) {
            $nameSpace = isset($this->nameSpaces[$nameSpace])
            ? $this->nameSpaces[$nameSpace]
            : array_values($this->nameSpaces)[0];

            return home_url('?rest_route=/' . $nameSpace);
        } else {
            return admin_url('admin-ajax.php');
        }
    }
}
