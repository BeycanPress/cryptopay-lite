<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Helpers;

trait Template
{
    /**
     * @param string $html
     * @param array<mixed> $allowedHtml
     * @return void
     */
    public static function ksesEcho(string $html, array $allowedHtml = []): void
    {
        $allowedHtml = array_merge_recursive(self::parseAllowedHtml($html), $allowedHtml);
        echo wp_kses($html, array_merge_recursive(wp_kses_allowed_html('post'), $allowedHtml));
    }

    /**
     * @param string $viewName
     * @param array<mixed> $args
     * @return string
     */
    public static function view(string $viewName, array $args = []): string
    {
        ob_start();
        extract($args);

        // for use in templates
        $getImageUrl = fn($name) => self::getImageUrl($name);
        $view = fn($viewName, $args = []) => self::view($viewName, $args);
        $viewEcho = fn($viewName, $args = []) => self::viewEcho($viewName, $args);
        $ksesEcho = fn($html, $allowedHtml = []) => self::ksesEcho($html, $allowedHtml);

        include self::getProp('pluginDir') . 'views/' . $viewName . '.php';
        return ob_get_clean();
    }

    /**
     * @param string $viewName
     * @param array<mixed> $args
     * @param array<mixed> $allowedHtml
     * @return void
     */
    public static function viewEcho(string $viewName, array $args = [], array $allowedHtml = []): void
    {
        self::ksesEcho(self::view($viewName, $args), $allowedHtml);
    }

    /**
     * @param string $templateName
     * @param array<mixed> $args
     * @return string
     */
    public static function getTemplate(string $templateName, array $args = []): string
    {
        extract($args);
        ob_start();
        include self::getProp('phDir') . 'templates/' . $templateName . '.php';
        return ob_get_clean();
    }

    /**
     * @param string $templateName
     * @param array<mixed> $args
     * @param array<mixed> $allowedHtml
     * @return void
     */
    public static function echoTemplate(string $templateName, array $args = [], array $allowedHtml = []): void
    {
        self::ksesEcho(self::getTemplate($templateName, $args), $allowedHtml);
    }

        /**
     * @param string $templateName
     * @param string $templateFile
     * @param array<mixed> $params
     * @return void
     */
    public static function registerPageTemplate(string $templateName, string $templateFile, array $params = []): void
    {
        add_filter('theme_page_templates', function ($templates) use ($templateName, $templateFile) {
            $templateFile = self::getProp('pluginKey') . $templateFile;
            return array_merge($templates, [$templateFile => esc_html($templateName)]);
        });

        add_filter('template_include', function ($template) use ($params) {
            global $post;

            if ($post && is_page()) {
                $pageTemplate = get_page_template_slug($post->ID);

                if (strpos($pageTemplate, elf::getProp('pluginKey')) !== false) {
                    exit(self::viewEcho(
                        'page-templates/' . str_replace(self::getProp('pluginKey'), '', $pageTemplate),
                        $params
                    ));
                }
            }

            return $template;
        }, 99);
    }

    /**
     * @param string $jsonString
     * @param bool $array
     * @return object|array<mixed>
     */
    public static function parseJson(string $jsonString, bool $array = false): object|array
    {
        return json_decode(html_entity_decode(stripslashes($jsonString)), $array);
    }

    /**
     *
     * @param string $content
     * @return string
     */
    public static function catchShortcode(string $content): string
    {
        global $shortcode_tags;
        $tagnames = array_keys($shortcode_tags);
        $tagregexp = join('|', array_map('preg_quote', $tagnames));

        // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcodes()
        $pattern = '(.?)\[(' . $tagregexp . ')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';

        return preg_replace_callback('/' . $pattern . '/s', 'do_shortcode_tag', $content);
    }

    /**
     * @param string $html
     * @return array<mixed>
     */
    public static function parseAllowedHtml(string $html): array
    {
        try {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();

            $result = [
                'style' => true,
                'script' => true
            ];

            foreach ($dom->getElementsByTagName('*') as $element) {
                $tagName = $element->tagName;

                if (isset($result[$tagName])) {
                    continue;
                }

                $attributes = [];


                foreach ($element->attributes as $attribute) {
                    $attributeName = $attribute->nodeName;
                    $attributes[$attributeName] = true;
                }

                if (!empty($attributes)) {
                    $result[$tagName] = $attributes;
                } else {
                    $result[$tagName] = true;
                }
            }

            if (isset($result['input'])) {
                $result['input']['class'] = true;
            }

            if (isset($result['option'])) {
                $result['option']['selected'] = true;
            }

            return $result;
        } catch (\Throwable $th) {
            self::debug($th->getMessage(), 'ERROR');
            return [];
        }
    }

        /**
     * @param string $imageName
     * @return string
     */
    public static function getImageUrl(string $imageName): string
    {
        return self::getProp('pluginUrl') . 'assets/images/' . $imageName;
    }

    /**
     * @param string $notice notice to be given
     * @param string $type error, success more
     * @param bool $dismissible in-dismissible button show and hide
     * @return void
     */
    public static function notice(string $notice, string $type = 'success', bool $dismissible = false): void
    {
        echo wp_kses_post(self::getTemplate('notice', [
            'type' => $type,
            'notice' => $notice,
            'dismissible' => $dismissible
        ]));
    }

    /**
     * @param string $notice notice to be given
     * @param string $type error, success more
     * @param bool $dismissible in-dismissible button show and hide
     * @return void
     */
    public static function adminNotice(string $notice, string $type = 'success', bool $dismissible = false): void
    {
        add_action('admin_notices', function () use ($notice, $type, $dismissible): void {
            self::notice($notice, $type, $dismissible);
        });
    }
}
