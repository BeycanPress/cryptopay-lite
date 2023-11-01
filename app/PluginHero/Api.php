<?php 

namespace BeycanPress\CryptoPayLite\PluginHero;

abstract class Api
{
    use Helpers;
    
    /**
     * @var string
     */
    protected $type = 'rest';
    
    /**
     * @var array
     */
    private $nameSpaces;
    
    /**
     * @param array $routeList
     * @return void
     */
    public function addRoutes(array $routeList) : void
    {
        if (empty($routeList)) return;
        
        if ($this->type == 'rest') {
            $this->nameSpaces = array_keys($routeList);
            add_action('rest_api_init', function () use ($routeList) {
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
        } else {
            foreach ($routeList as $nameSpace => $routes) {
                foreach ($routes as $route => $config) {
                    $callback = is_array($config) ? $config['callback'] : $config;
                    $methods = isset($config['methods']) ? $config['methods'] : ['POST', 'GET'];
                    $this->ajaxAction($nameSpace . '_' . $callback);
                }
            }
        }
        
        $this->addApi($this);
    }

    /**
     * @param string $nameSpace
     * @return string
     */
    public function getUrl(?string $nameSpace = null) : string
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