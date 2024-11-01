<?php
/**
 * Plugin Name: WPBridge for Rust
 * Plugin URI: https://wpbridge.danlevi.no
 * Description: Integrates Wordpress sites with Rust servers to show player statistics and server information.
 * Version: 1.2.12
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Dan-Levi Tømta
 * Author URI: https://www.danlevi.no
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpbridge-for-rust
 * Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=T7FNEG2D2ELC8
*/

if( !defined('ABSPATH') ) : exit(); endif;

class WPB_F_R_WPBRIDGE
{
    public $plugin_version = '1.2.12';
    private static $_instance = null;

    public function __construct()
    {
        $this->WPB_F_R_DefineConstants();
        $this->WPB_F_R_CheckVersion();
        $this->WPB_F_R_Init_WebServices();
        $this->WPB_F_R_InitInstall();
        $this->WPB_F_R_InitUnInstall();
        $this->WPB_F_R_InitSubscription();
        $this->WPB_F_R_InitSettings();
        $this->WPB_F_R_InitRestApi();
        $this->WPB_F_R_InitShortCodes();
        $this->WPB_F_R_InitPublic();
        
        $updateNoticeShown = get_option('wpbridge_update_notice_shown_1.2.12',0);
        if($updateNoticeShown == 0) {
            add_action( 'admin_notices', [$this,'WPB_F_R_plugin_update_message']);
        }
        
    }

    function WPB_F_R_plugin_update_message()
    {
    //     $noticeHeading = __('You MUST upgrade the Rust plugin in this update. If not the statistics will stop working.',WPBRIDGE_TEXT_DOMAIN);
    //     $noticeBody = __('Because both plugins have had an CORE rewrite it is crucial that you update the WordPress bridge plugin this time.',WPBRIDGE_TEXT_DOMAIN);
    //     $subHeading = __('You can fetch the latest version for WordPress Bridge here',WPBRIDGE_TEXT_DOMAIN) . ": <a target=\"_blank\" href=\"https://umod.org/plugins/wordpress-bridge\">WordPress Bridge " . __('on uMod.org',WPBRIDGE_TEXT_DOMAIN) . "</a>";
    //     $noticeBody2 = __('I understand this could be frustrating but it is better this way. Now the plugin is much more prepared for future updates.',WPBRIDGE_TEXT_DOMAIN);
    //     printf('<div class="notice notice-error">
    //         <h1>' . __('Attention dear Rust admin') . '</h1>
    //         <h2>' . $noticeHeading . '</h2>
    //         <p>' . $noticeBody . '</p>
    //         <p>' . $noticeBody2 . '</p>
    //         <h3>' . $subHeading . '</h3>
    //         <h1>' . __('Sorry for any inconvenience!',WPBRIDGE_TEXT_DOMAIN) . '</h1>
    //         <br>
    //         <br>
    //     </div>');
        update_option('wpbridge_rustmapapidata',null);
        update_option('wpbridge_update_notice_shown_1.2.12',1);
    }

    function WPB_F_R_Init_WebServices() {
        require_once WPBRIDGE_INCLUDES_PATH . 'webservices.php';
    }

    function WPB_F_R_InitSubscription()
    {
        $subscription_key = get_option('wpbridge_subscription_key','');
        if($subscription_key !== '') {
            require_once WPBRIDGE_INCLUDES_PATH . 'subscription.php';
            if(WPB_F_R_WPBRIDGE_SUBSCRIPTION::WPB_F_R_instance()->WPB_F_R_Get()) {
                require_once WPBRIDGE_INCLUDES_PATH . 'pro_features.php';
            }
        }
    }

    /**
     * Init public
     */
    public function WPB_F_R_InitPublic()
    {
        require_once WPBRIDGE_PATH . 'public/public.php';
    }

    /**
     * Init Shortcodes
     */
    public function WPB_F_R_InitShortCodes()
    {
        require_once WPBRIDGE_PATH . 'inc/shortcodes.php';
    }

    /**
     * Check version
     */
    public function WPB_F_R_CheckVersion()
    {
        $local_plugin_version = get_option('WPBRIDGE_PLUGIN_VERSION','0.0.0');
        if(version_compare(WPBRIDGE_PLUGIN_VERSION,$local_plugin_version,'>='))
        {
            
            require_once WPBRIDGE_PATH . 'update.php';
        }
    }

    /**
     * Settings
     */
    public function WPB_F_R_InitSettings()
    {
        require_once WPBRIDGE_PATH . 'inc/settings.php';
    }

    /**
     * Rest API
     */
    public function WPB_F_R_InitRestApi()
    {
        require_once WPBRIDGE_PATH . 'inc/rest_api.php';
    }

    /**
     * Install
     */
    public function WPB_F_R_InitInstall()
    {
        if(is_admin()) {
            require_once WPBRIDGE_PATH . '/install.php';
        }
    }

    /**
     * Uninstall
     */
    public function WPB_F_R_InitUnInstall()
    {
        if(is_admin()) {
            require_once WPBRIDGE_PATH . '/uninstall.php';
        }
    }

    /**
     * Constants
     */
    public function WPB_F_R_DefineConstants()
    {
        define( 'WPBRIDGE_PATH', trailingslashit( plugin_dir_path(__FILE__) ) );
        define( 'WPBRIDGE_URL', trailingslashit( plugins_url('/', __FILE__) ) );
        require_once WPBRIDGE_PATH . 'inc/constants.php';
    }

    /**
     * Singleton
     */
    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE();
        }
        return self::$_instance;
    }
}

WPB_F_R_WPBRIDGE::WPB_F_R_instance();