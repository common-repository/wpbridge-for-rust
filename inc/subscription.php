<?php


class WPB_F_R_WPBRIDGE_SUBSCRIPTION
{
    private static $_instance = null;

    public function WPB_F_R_Check($license_key,$site_url) {
        if($site_url !== get_site_url()) return false;
        return true;
    }

    public function WPB_F_R_Get($subscription_key = null)
    {
        if ( !function_exists('wp_get_current_user') ) {
            include(ABSPATH . "wp-includes/pluggable.php"); 
        }
        $currentWpUser = wp_get_current_user();

        if(!$subscription_key) {
            $subscription_key = get_option('wpbridge_subscription_key','');
        }
        
        $subscriptionDetails = [
            "subscription_key" => $subscription_key,
            "ip" => WPB_F_R_WPBRIDGE_WEBSERVICES::WPB_F_R_instance()->WPB_F_R_Fetch_IP(),
            "email" => $currentWpUser->user_email,
            "site_url" => get_site_url()
        ];

        $subscription = json_decode( WPB_F_R_WPBRIDGE_WEBSERVICES::WPB_F_R_instance()->WPB_F_R_Fetch_Subscription_Exists($subscriptionDetails) );

        if($subscription && isset($subscription->subscription) && $subscription->subscription !== "none" && isset($subscription->data)) {
            return $subscription;
        }
        return false;
    }
    
    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_SUBSCRIPTION();
        }
        return self::$_instance;
    }

}
WPB_F_R_WPBRIDGE_SUBSCRIPTION::WPB_F_R_instance();