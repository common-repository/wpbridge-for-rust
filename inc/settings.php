<?php


class WPB_F_R_WPBRIDGE_SETTINGS
{
    private static $_instance = null;
    private $db_instance = null;

    public function __construct()
    {
        require_once WPBRIDGE_PATH . 'inc/player_database.php';
        $this->db_instance = WPB_F_R_WPBRIDGE_Player_Database::WPB_F_R_instance();
        
        $this->WPB_F_R_Init_Menu();
        $this->WPB_F_R_Init_Admin_JS();
        $this->WPB_F_R_Init_Sub_Menus();
        $this->WPB_F_R_Init_Admin_Ajax();
    }

    function WPB_F_R_Init_Admin_Ajax() {
        add_action( 'wp_ajax_wpbridge_rustmapapidata', [$this,'WPB_F_R_Admin_Ajax_UpdateOption_rustmapapidata'] );
    }

    function WPB_F_R_Admin_Ajax_UpdateOption_rustmapapidata() {

        if(!isset($_POST['rustmapapidata']) && !isset($_POST['wpbridge_rustmapapigeneratedfilename'])) {
            die(
                json_encode(
                    array(
                        'success' => false,
                        'message' => 'No Map data in $_POST'
                    )
                )
            );
        }

        $rustmapapidata = ($_POST['rustmapapidata']);
        $wpbridge_rustmapapigeneratedfilename = $_POST['wpbridge_rustmapapigeneratedfilename'];
        update_option('wpbridge_rustmapapigeneratedfilename',$wpbridge_rustmapapigeneratedfilename);
        update_option('wpbridge_rustmapapidata',$rustmapapidata);
        update_option('wpbridge_rustmaplastgen',date('Y-m-d H:i:s'));
        die(
            json_encode(
                array(
                    'success' => true,
                    'message' => 'Map data saved'
                )
            )
        );
    }
    

    function WPB_F_R_Init_Sub_Menus()
    {
        add_action('admin_menu', [$this,'WPB_F_R_Init_Wipe_All_Sub_Menu']);
        add_action('admin_menu', [$this,'WPB_F_R_Init_Pro_Success_Sub_menu']);
        add_action('admin_menu', [$this,'WPB_F_R_Init_Pro_Lost_Subscription_Sub_menu']);


    }

    function WPB_F_R_Init_Pro_Lost_Subscription_Sub_menu()
    {
        add_submenu_page(
            'wpbridge-settings-page',
            '',
            __('','wpbridge-for-rust'),
            'manage_options',
            'wpbridge-pro-lost-license',
            [$this,'WPB_F_R_Init_Pro_Lost_Subscription_Sub_Menu_Template_Callback']
        );
    }

    function WPB_F_R_Init_Pro_Lost_Subscription_Sub_Menu_Template_Callback()
    {
        if(!isset($_GET['license_key']) || !isset($_GET['site_url'])) {
            //HANDLE NO SUBSCRIPTION KEY
            exit;
        }

        $licenseKeyFromFile = $_GET['license_key'];
        $siteUrlFromFile = $_GET['site_url'];

        require_once WPBRIDGE_INCLUDES_PATH . 'subscription.php';

        $subscriptionCheck = WPB_F_R_WPBRIDGE_SUBSCRIPTION::WPB_F_R_instance()->WPB_F_R_Check($licenseKeyFromFile, $siteUrlFromFile);
        if(!$subscriptionCheck) {
            require_once WPBRIDGE_PAGES_PATH . 'subscription' . DIRECTORY_SEPARATOR . 'lost_subscription_wrong_site_url.page.php';
            return;
        }

        $subscription = WPB_F_R_WPBRIDGE_SUBSCRIPTION::WPB_F_R_instance()->WPB_F_R_Get($licenseKeyFromFile);

        if(!$subscription) {
            require_once WPBRIDGE_PAGES_PATH . 'subscription' . DIRECTORY_SEPARATOR . 'subscription_doesnt_exist.page.php';
            return;
        }
        
        update_option('wpbridge_subscription_key',$subscription->data->subscription->subscription_key);
        require_once WPBRIDGE_PAGES_PATH . 'pro' . DIRECTORY_SEPARATOR . 'pro_success_submenu_template.php';
    }

    function WPB_F_R_Init_Pro_Success_Sub_menu()
    {
        add_submenu_page(
            'wpbridge-settings-page',
            '',
            __('Pro Success','wpbridge-for-rust'),
            'manage_options',
            'wpbridge-pro-success',
            [$this,'WPB_F_R_Init_Pro_Success_Sub_Menu_Template_Callback']
        );
    }

    function WPB_F_R_Init_Pro_Success_Sub_Menu_Template_Callback()
    {

        if(!isset($_GET['wpbridge_pro_subscription_key'])) {
            // HANDLE NO SUBSCRIPTION KEY
            exit;
        }
        if ( !function_exists('wp_get_current_user') ) {
            include(ABSPATH . "wp-includes/pluggable.php"); 
        }
        $currentWpUser = wp_get_current_user();

        $subscriptionDetails = [
            "subscription_key" => rawurldecode($_GET['wpbridge_pro_subscription_key']),
            "ip" => WPB_F_R_WPBRIDGE_WEBSERVICES::WPB_F_R_instance()->WPB_F_R_Fetch_IP(),
            "email" => $currentWpUser->user_email,
            "site_url" => get_site_url()
        ];
        
        $subscription = json_decode( WPB_F_R_WPBRIDGE_WEBSERVICES::WPB_F_R_instance()->WPB_F_R_Fetch_Subscription_Exists($subscriptionDetails) );
        
        if($subscription && isset($subscription->subscription) && $subscription->subscription !== "none" && isset($subscription->data)) {
            update_option('wpbridge_subscription_key',$subscription->data->subscription->subscription_key);
            require_once WPBRIDGE_PAGES_PATH . 'pro' . DIRECTORY_SEPARATOR . 'pro_success_submenu_template.php';
        }
    }



    function WPB_F_R_Init_Wipe_All_Sub_Menu()
    {
        add_submenu_page(
            'wpbridge-settings-page',
            '',
            __('Wipe statistics','wpbridge-for-rust'),
            'manage_options',
            'wpbridge-wipe-all',
            [$this,'WPB_F_R_Init_Wipe_All_Sub_Menu_Template_Callback']
        );
    }

    function WPB_F_R_Init_Wipe_All_Sub_Menu_Template_Callback()
    {
        require_once WPBRIDGE_PAGES_PATH . 'statistics' . DIRECTORY_SEPARATOR . 'wipe_submenu_template.php';
    }

    function WPB_F_R_Init_Menu()
    {
        add_action('admin_menu', [$this,"WPB_F_R_SetupSettingsMenu"]);
        $this->WPB_F_R_SetupSecretSection();
        $this->WPB_F_R_SetupRustMapAPISection();
    }

    function WPB_F_R_SetupSettingsMenu()
    {

        add_menu_page(
            __('WPBridge for Rust', 'wpbridge-for-rust'),
            __('WPBridge for Rust', 'wpbridge-for-rust'),
            'manage_options',
            'wpbridge-settings-page',
            [$this,'WPB_F_R_wpbridge_settings_template_callback'],
            'dashicons-admin-links',
            null
        );
    }

    function WPB_F_R_SetupSecretSection()
    {
        add_settings_section(
            'wpbridge_settings_secret_section',
            '',
            '',
            'wpbridge-settings-page'
        );
        register_setting(
            'wpbridge-settings-page',
            'wpbridge_secret_field',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );
        add_settings_field(
            'wpbridge_secret_field',
            __('Your Secret', 'wpbridge-for-rust'),
            [$this,'WPB_F_R_SecretSettingsFieldCallback'],
            'wpbridge-settings-page',
            'wpbridge_settings_secret_section'
        );
    }

    function WPB_F_R_SetupRustMapAPISection()
    {
        add_settings_section(
            'wpbridge_settings_rustmapapi_section',
            '',
            '',
            'wpbridge-settings-page-rustmap'
        );
        register_setting(
            'wpbridge-settings-page',
            'wpbridge_rustmapapi_field',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );
        add_settings_field(
            'wpbridge_rustmapapi_field',
            __('Your RustMap API Key', 'wpbridge-for-rust'),
            [$this,'WPB_F_R_RustMapAPISettingsFieldCallback'],
            'wpbridge-settings-page-rustmap',
            'wpbridge_settings_rustmapapi_section'
        );
    }

    function WPB_F_R_wpbridge_settings_template_callback()
    {
        require_once WPBRIDGE_PAGES_PATH . 'main_page.php';
    }
    
    function WPB_F_R_Init_Admin_JS()
    {
        $serverSettings = $this->db_instance->WPB_F_R_GetServerSettings();
        if($serverSettings && $serverSettings->worldsize !== 0)
        {
            wp_register_script( 'wpbridge-server-settings', '' );
            wp_enqueue_script( 'wpbridge-server-settings' );
            wp_add_inline_script( 'wpbridge-server-settings', 'const SERVERSETTINGS = ' . json_encode($serverSettings) . ';' );
        }
        if ( !function_exists('wp_get_current_user') ) {
            include(ABSPATH . "wp-includes/pluggable.php"); 
        }
        $user = wp_get_current_user();
        if($user) {
            $currentUserData = null;
            $ip = WPB_F_R_WPBRIDGE_WEBSERVICES::WPB_F_R_instance()->WPB_F_R_Fetch_IP();
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                $currentUserData = [
                    "ip" => $ip,
                    "site_url" => get_site_url(),
                    "admin_url" => admin_url(),
                    "modal_url" => WPBRIDGE_MODALS_URL,
                    "user_nicename" => $user->user_nicename,
                    "user_email" => $user->user_email,
                    "rustmaps" => [
                        "api_key" => get_option('wpbridge_rustmapapi_field','')
                    ]
                    // "debug" => $user->data
                ];
                
            }

            if($currentUserData) {
                wp_register_script( 'wpbridge-user-data', '' );
                wp_enqueue_script( 'wpbridge-user-data' );
                wp_add_inline_script( 'wpbridge-user-data', 'const USERDATA = ' . json_encode($currentUserData) . ';' );
            }

            wp_register_script( 'wpbridge-pro-data', '' );
            wp_enqueue_script( 'wpbridge-pro-data' );
            wp_add_inline_script( 'wpbridge-pro-data', 'const WPBRIDGEPRODATA = ' . json_encode([
                "endpoints" => [
                    "services" => WPBRIDGE_ENDPOINT_SERVICES,
                    "payment" => WPBRIDGE_ENDPOINT_PAYMENT
                ] 
            ]) . ';' );

        }
        
        add_action('admin_enqueue_scripts', [$this,"WPB_F_R_InitAdminJavaScript"]);
    }

    function WPB_F_R_InitAdminJavaScript()
    {
        wp_enqueue_style('jquery-ui', WPBRIDGE_URL . 'admin/css/jquery-ui.min.css');
        wp_enqueue_style('wpbridge-admin-css', WPBRIDGE_URL . 'admin/css/admin.css');
        wp_enqueue_script(
            'wpbridge-admin-script',
            WPBRIDGE_URL . 'admin/js/settings.js',
            array('jquery', 'jquery-ui-core','jquery-ui-tabs'),
            rand(),
            true
        );
    }


    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_SETTINGS();
        }
        return self::$_instance;
    }
}

WPB_F_R_WPBRIDGE_SETTINGS::WPB_F_R_instance();