<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

abstract class BaseAPI
{
    /**
     * @var string<rest|ajax>
     */
    protected string $type = 'rest';

    /**
     * @var array<string>
     */
    private array $nameSpaces;

    /**
     * @param array<string,array<mixed>> $routeList
     * @return void
     */
    public function addRoutes(array $routeList): void
    {
        if (empty($routeList)) {
            return;
        }

        if ($this->type == 'rest') {
            $this->nameSpaces = array_keys($routeList);
            add_action('rest_api_init', function () use ($routeList): void {
                foreach ($routeList as $nameSpace => $routes) {
                    foreach ($routes as $route => $config) {
                        $callback = is_array($config) ? $config['callback'] : $config;
                        $methods = isset($config['methods']) ? $config['methods'] : ['POST', 'GET'];
                        register_rest_route($nameSpace, $route, [
                            'callback' => [$this, $callback],
                            'methods' => $methods,
                            'permission_callback' => '__return_true'
                        ]);
                    }
                }
            });
        } elseif ($this->type == 'ajax') {
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

        Helpers::addAPI($this);
    }

    /**
     * @param string $nameSpace
     * @return string
     */
    public function getUrl(?string $nameSpace = null): string
    {
        if ($this->type == 'rest') {
            $nameSpace = isset($this->nameSpaces[$nameSpace])
            ? $this->nameSpaces[$nameSpace]
            : array_values($this->nameSpaces)[0];

            return home_url('?rest_route=/' . $nameSpace);
        } else {
            return admin_url('admin-ajax.php');
        }
    }
}
