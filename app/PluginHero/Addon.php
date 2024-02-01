<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

class Addon
{
    /**
     * @var string
     */
    private string $key;

    /**
     * @var string
     */
    private string $parentKey;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $file;

    /**
     * @var string
     */
    private string $slug;

    /**
     * @var string
     */
    private string $version;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $viewDir;

    /**
     * @var object
     */
    private object $data;

    /**
     * @param string $key
     * @param string $file
     */
    public function __construct(string $key, string $file)
    {
        $this->data = Helpers::getPluginData($file);

        $this->key = $key;
        $this->file = $file;

        $this->name = $this->data->Name;
        $this->version = $this->data->Version;

        $this->path = plugin_dir_path($file);
        $this->url = plugin_dir_url($file);
        $this->slug = plugin_basename($file);

        $this->viewDir = $this->path . 'views/';

        $this->parentKey = Plugin::getKey();
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getParentKey(): string
    {
        return $this->parentKey;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getViewDir(): string
    {
        return $this->viewDir;
    }

    /**
     * @return object
     */
    public function getData(): object
    {
        return $this->data;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getImageUrl(string $path): string
    {
        return $this->url . 'assets/images/' . $path;
    }

    /**
     * @param string $viewName
     * @param array<mixed> $args
     * @return string
     */
    public function view(string $viewName, array $args = []): string
    {
        extract($args);
        ob_start();
        include $this->viewDir . $viewName . '.php';
        return ob_get_clean();
    }

    /**
     * @param string $viewName
     * @param array<mixed> $args
     * @param array<mixed> $allowedHtml
     * @return void
     */
    public function viewEcho(string $viewName, array $args = [], array $allowedHtml = []): void
    {
        Helpers::ksesEcho($this->view($viewName, $args), $allowedHtml);
    }

    /**
     * @param string $path
     * @param array<string> $deps
     * @return string
     */
    public function registerScript(string $path, array $deps = []): string
    {
        [$key, $url] = Helpers::createAssetParams($path, $this->url, 'js');

        $key = $this->key . '-' . $key;

        wp_register_script(
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
     * @param array<string> $deps
     * @return string
     */
    public function addScript(string $path, array $deps = []): string
    {
        [$key, $url] = Helpers::createAssetParams($path, $this->url, 'js');

        $key = $this->key . '-' . $key;

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
     * @param array<string> $deps
     * @return string
     */
    public function registerStyle(string $path, array $deps = []): string
    {
        [$key, $url] = Helpers::createAssetParams($path, $this->url, 'css');

        $key = $this->key . '-' . $key;

        wp_register_style(
            $key,
            $url,
            $deps,
            $this->version
        );

        return $key;
    }

    /**
     * @param string $path
     * @param array<string> $deps
     * @return string
     */
    public function addStyle(string $path, array $deps = []): string
    {
        [$key, $url] = Helpers::createAssetParams($path, $this->url, 'css');

        $key = $this->key . '-' . $key;

        wp_enqueue_style(
            $key,
            $url,
            $deps,
            $this->version
        );

        return $key;
    }
}
