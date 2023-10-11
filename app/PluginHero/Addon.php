<?php 

namespace BeycanPress\CryptoPayLite\PluginHero;

class Addon
{   
    use Helpers;
    
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $parentKey;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $viewDir;
    
    /**
     * @param string $key
     * @param string $file
     */
    public function __construct(string $key, string $file)
    {
		if (!function_exists('get_plugin_data')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
        
        $data = (object) get_plugin_data($file);

        $this->key = $key;
        $this->file = $file;
        
        $this->name = $data->Name;
        $this->version = $data->Version;

        $this->path = plugin_dir_path($file);
        $this->url = plugin_dir_url($file);

        $this->viewDir = $this->path . 'views/';

        $this->parentKey = Plugin::getKey();
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFile() : string
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getVersion() : string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    public function getViewDir() : string
    {
        return $this->viewDir;
    }

    /**
     * @param string $viewName
     * @param array $args
     * @return string
     */
    public function view(string $viewName, array $args = []) : string
    {
        extract($args);
        ob_start();
        include $this->viewDir . $viewName . '.php';
        return ob_get_clean();
    }

    /**
     * @param string $viewName
     * @param array $args
     * @return void
     */
    public function viewEcho(string $viewName, array $args = []) : void
    {
        print($this->view($viewName, $args));
    }

    /**
     * @param string $path
     * @param array $deps
     * @return string
     */
    public function addScript(string $path, array $deps = []) : string
    {
        $f = substr($path, 0, 1);
        $key = explode('/', $path);
        $key = $this->parentKey . '-'. $this->key . '-' . str_replace('.js', '', end($key));
        $middlePath = $f === '/' ? 'assets/' : 'assets/js/';
        $url = $this->getUrl() . $middlePath . $path;
        wp_enqueue_script(
            $key,
            $url,
            $deps,
            $this->version,
            true
        );
        
        return $key;
    }

    /**
     * @param string $path
     * @param array $deps
     * @return string
     */
    public function addStyle(string $path, array $deps = []) : string
    {
        $key = explode('/', $path);
        $f = substr($path, 0, 1);
        $key = $this->parentKey . '-'. $this->key . '-' . str_replace('.css', '', end($key));
        $middlePath = $f === '/' ? 'assets/' : 'assets/css/';
        $url = $this->getUrl() . $middlePath . $path;
        wp_enqueue_style(
            $key,
            $url,
            $deps,
            $this->version
        );
        
        return $key;
    }
}