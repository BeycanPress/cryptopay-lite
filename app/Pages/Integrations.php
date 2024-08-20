<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Pages;

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Page;
use BeycanPress\CryptoPayLite\PluginHero\Http\Client;

/**
 * Integrations page
 */
class Integrations extends Page
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var int
     */
    private int $count = 0;

    /**
     * @var string
     */
    private string $categories = '?categories=88,167,306,87';

    /**
     * @var string
     */
    private string $apiUrl = 'https://services.beycanpress.com/wp-json/general-data/';

    /**
     * Class construct
     * @return void
     */
    public function __construct()
    {
        try {
            $notification = '';
            $this->client = new Client();
            $this->client->setBaseUrl($this->apiUrl);
            $this->controlNotification();
            $this->count = absint(get_option('cryptopay_lite_new_product_notification_new_count', 0));
            if ($this->count > 0 && !(isset($_GET['page']) && 'cryptopay_home' == $_GET['page'])) {
                $notification =  sprintf(' <span class="awaiting-mod">%d</span>', $this->count);
                add_action('admin_bar_menu', function (): void {
                    if (current_user_can('manage_options')) {
                        global $wp_admin_bar; // phpcs:ignore
                        $wp_admin_bar->add_menu([ // phpcs:ignore
                            'id'    => 'cryptopay-new-product-notification',
                            'title' => Helpers::view('components/notification', [
                                'count' => $this->count
                            ]),
                            'href'  => $this->getUrl(),
                        ]);
                    }
                }, 500);
            }
        } catch (\Throwable $th) {
            Helpers::debug($th->getMessage(), 'ERROR', $th);
        }

        parent::__construct([
            'priority' => 1,
            'slug' => 'cryptopay_lite_integrations',
            'parent' => Helpers::getPage('HomePage')->getSlug(),
            'pageName' => esc_html__('Integrations', 'cryptopay') . $notification,
        ]);

        add_action('admin_head', function (): void {
            Helpers::viewEcho('css/admin-bar-css');
        });
    }

    /**
     * @return void
     */
    public function controlNotification(): void
    {
        $oldCount = get_option('cryptopay_lite_new_product_notification_count') ?? 0;
        if (date('Y-m-d') != get_option('cryptopay_lite_new_product_notification_date')) {
            $res = $this->client->get('notification-by-category' . $this->categories);
            $newCount = isset($res->success) && $res->success ? $res->data->count : 0;
            update_option('cryptopay_lite_new_product_notification_date', date('Y-m-d'));
            update_option('cryptopay_lite_new_product_notification_count', $newCount);
            if (($newCount - $oldCount)) {
                update_option('cryptopay_lite_new_product_notification_new_count', ($newCount - $oldCount));
            }
        }
    }

    /**
     * @return void
     */
    public function page(): void
    {
        $plugins = [];
        try {
            update_option('cryptopay_lite_new_product_notification_new_count', 0);
            $oldProducts = json_decode(get_option('cryptopay_lite_products_json', '{}'));
            if ($this->count || empty((array) $oldProducts)) {
                $res = $this->client->get('get-plugins-by-category' . $this->categories);
                $pluginsPure = isset($res->success) && $res->success ? $res->data->plugins : [];

                foreach ($pluginsPure as $key => $pluginPure) {
                    $firstCategory = array_values((array)$pluginPure->categories)[0];

                    if (!isset($plugins[$firstCategory->slug])) {
                        $plugins[$firstCategory->slug] = [];
                    }

                    $plugins[$firstCategory->slug][] = $pluginPure;
                }

                if (!empty($plugins)) {
                    update_option('cryptopay_lite_products_json', json_encode($plugins));
                }

                if ($oldProducts) {
                    foreach ($plugins as $category => &$pluginList) {
                        $pluginList = array_map(function ($product) use ($oldProducts, $category) {
                            if (isset($oldProducts->$category)) {
                                if (false === array_search($product->id, array_column($oldProducts->$category, 'id'))) {
                                    $product->new = true;
                                }
                            } else {
                                $product->new = true;
                            }
                            return $product;
                        }, $pluginList);
                    }
                }
            } else {
                $plugins = $oldProducts;
            }
        } catch (\Throwable $th) {
            Helpers::debug($th->getMessage(), 'ERROR', $th);
        }

        Helpers::addStyle('admin.min.css');
        Helpers::viewEcho('pages/integrations/index', compact('plugins'));
    }
}
