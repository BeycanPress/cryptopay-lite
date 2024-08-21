<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

abstract class Page
{
    /**
     * Current page url
     * @var string
     */
    public string $url;

    /**
     * Current page url slug
     * @var string
     */
    public string $slug;

    /**
     * Current page url name
     * @var string
     */
    public string $name;

    /**
     * Class construct
     * @param array<mixed> $properties
     * @return void
     */
    public function __construct(array $properties)
    {
        $properties = (object) $properties;

        $slug = Helpers::getProp('pluginKey') . '-' . sanitize_title($properties->pageName);
        $this->slug = isset($properties->slug) ? $properties->slug : $slug;
        $this->url = admin_url('admin.php?page=' . $this->slug);

        add_action('admin_menu', function () use ($properties): void {
            $menuName = isset($properties->menuName) ? $properties->menuName : $properties->pageName;
            $this->name = $menuName;

            if (isset($properties->parent)) {
                $properties->parent = isset($properties->hidden) ? null : $properties->parent;
                add_submenu_page(
                    $properties->parent,
                    $properties->pageName,
                    $menuName,
                    'manage_options',
                    $this->slug,
                    [$this, 'page']
                );
            } else {
                add_menu_page(
                    $properties->pageName,
                    $menuName,
                    'manage_options',
                    $this->slug,
                    [$this, 'page'],
                    isset($properties->icon) ? $properties->icon : null
                );
                if (isset($properties->subMenu)) {
                    $subMenuName = isset($properties->subMenuName)
                    ? $properties->subMenuName
                    : $properties->subMenuPageName;

                    add_submenu_page(
                        $this->slug,
                        $properties->subMenuPageName,
                        $subMenuName,
                        'manage_options',
                        $this->slug,
                        [$this, 'page']
                    );
                }
                if (isset($properties->hidden)) {
                    add_action('admin_head', function (): void {
                        echo '<style>
                        #adminmenu #toplevel_page_' . esc_html($this->slug) . ' { 
                            display: none;
                        }
                        </style>';
                    });
                }
            }
        }, $properties->priority ?? 10);

        Helpers::addPage($this, $this->slug);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
