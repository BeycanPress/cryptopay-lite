<?php

namespace BeycanPress\CryptoPayLite;

use \BeycanPress\Http\Client;

class Loader extends PluginHero\Plugin
{
    public function __construct($pluginFile)
    {
        parent::__construct([
            'pluginFile' => $pluginFile,
            'textDomain' => 'cryptopay_lite',
            'pluginKey' => 'cryptopay_lite',
            'settingKey' => 'cryptopay_lite_settings',
            'pluginVersion' => '1.2.2',
        ]);
        
        $this->feedback();

        add_filter('plugin_row_meta', function(array $links, string $file) {
            if ($file === plugin_basename($this->pluginFile)) {
                $links[] = '<b><a href="https://bit.ly/cplitebuynow" target="_blank">' . __('Buy premium', 'cryptopay_lite') . '</a></b>';
            }
            return $links;
        }, 10, 2);

        add_action('plugins_loaded', function() {
            $this->apiUrl = (new Api())->getUrl();
            new WooCommerce\Register();
        });
    }

    public function adminProcess() : void
    {
        new Pages\HomePage();
        add_action('init', function(){
            new Settings();
        }, 9);
    }

    public static function activation() : void
    {
        (new Models\OrderTransaction())->createTable();
    }

    public function feedback() {
        global $pagenow;
        
        add_action('wp_ajax_bpFeedbackApi' , [$this, 'bpFeedbackApi']);
        add_action('wp_ajax_nopriv_bpFeedbackApi' , [$this, 'bpFeedbackApi']);

        add_action('admin_enqueue_scripts', function() {
            global $pagenow;
            if ($pagenow === 'plugins.php') {
                wp_enqueue_script('cp-deactivation-message', plugins_url('assets/js/message.js', $this->pluginFile), ['jquery']);
            }
        });

        add_action('admin_footer', function() {
            global $pagenow;
            if ($pagenow === 'plugins.php') {
                $this->viewEcho('feedback');
            }
        });
    }

    public function bpFeedbackApi() 
    {
        $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : '';
        try {

            try {
                try {
                    $userEmail = wp_get_current_user()->user_email;
                } catch (\Throwable $th) {
                    global $wpdb;
                    $userEmail = ($wpdb->get_row("SELECT * FROM {$wpdb->users} WHERE ID = 1"))->user_email;
                }
            } catch (\Throwable $th) {
                $userEmail = '';
            }
    
            $data = [
                'reason' => $reason,
                'plugin' => 'CryptoPay Lite',
                'version' => $this->pluginVersion,
                'site' => get_site_url(),
                'email' => $userEmail ? $userEmail : get_option('admin_email'),
                'name' => get_option('blogname'),
            ];
            
            $body = '
                <h3>Plugin: ' . $data['plugin'] . '</h3>
                <h3>Version: ' . $data['version'] . '</h3>
                <h3>Site name: ' . $data['name'] . '</h3>
                <h3>Site domain: ' . $data['site'] . '</h3>
                <h3>Email: ' . $data['email'] . '</h3>
                <h3>Reason: ' . $data['reason'] . '</h3>
            ';
    
            $headers = [
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . $data['name'] . ' <' . $data['userEmail'] . '>',
            ];
    
			wp_send_json_success(wp_mail('beycanpress@gmail.com', 'CryptoPay Lite Feedback', $body, $headers));
		} catch (\Exception $e) {
			wp_send_json_success($e->getMessage());
		} 

        wp_die();
    }

    public static function uninstall() : void
    {
        $settings = get_option(self::$instance->settingKey);
        if (isset($settings['dds']) && $settings['dds']) {
            delete_option(self::$instance->settingKey);
            delete_option('woocommerce_cryptopay_lite_settings');
            (new Models\OrderTransaction())->drop();
        }
    }
}
