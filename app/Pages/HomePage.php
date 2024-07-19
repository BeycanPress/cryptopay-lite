<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Pages;

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Page;

/**
 * Home page
 */
class HomePage extends Page
{
    /**
     * Class construct
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'pageName' => esc_html__('CryptoPay Lite', 'cryptopay'),
            'subMenuPageName' => esc_html__('Buy premium', 'cryptopay'),
            'slug' => 'cryptopay_lite_home',
            'icon' => Helpers::getImageUrl('menu.png'),
            'subMenu' => true,
            'priority' => 1,
        ]);

        add_action('admin_head', function (): void {
            echo '<style>
            
            .toplevel_page_cryptopay_lite_home .wp-menu-image img {
                width: 18px;
            }
            </style>';
        });
    }

    /**
     * @return void
     */
    public function page(): void
    {
        Helpers::viewEcho('pages/home-page/index');
    }
}
