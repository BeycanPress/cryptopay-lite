<?php 

namespace BeycanPress\CryptoPayLite\PluginHero;

abstract class Page
{
    use Helpers;

    /**
     * Current page url
     * @var string
     */
    public $url;

    /**
     * Current page url slug
     * @var string
     */
    public $slug;

    /**
     * Current page url name
     * @var string
     */
    public $name;

    /**
     * Class construct
     * @param array $properties
     * @return void
     */
    public function __construct(array $properties)
    {
        $properties = (object) $properties;

        $this->slug = isset($properties->slug) ? $properties->slug : $this->pluginKey . '-' . sanitize_title($properties->pageName);
        $this->url = admin_url('admin.php?page=' . $this->slug);

        add_action('admin_menu', function() use ($properties) {
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
                    $subMenuName = isset($properties->subMenuName) ? $properties->subMenuName : $properties->subMenuPageName;
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
                    add_action('admin_head', function() {
                        echo '<style>
                        #adminmenu #toplevel_page_'.$this->slug.' { 
                            display: none;
                        }
                        </style>';
                    });
                }
            }
        }, $properties->priority ?? 10);

        $this->addPage($this);
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
}