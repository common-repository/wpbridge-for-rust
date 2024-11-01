<?php

class WPB_F_R_WPBRIDGE_WEBSERVICES
{
    private static $_instance = null;

    function WPB_F_R_Fetch_IP() {
        $ip = $this->file_get_contents_curl(WPBRIDGE_ENDPOINT_SERVICES . 'iplookup');
       return $ip;
    }

    function WPB_F_R_Fetch_Subscription_Exists($subscriptionDetails) {
        return trim($this->file_get_contents_curl(WPBRIDGE_ENDPOINT_PAYMENT . 'subscription/exists',$subscriptionDetails));
    }

    function file_get_contents_curl( $url, $POST = null ) {
        
            
            $ch = curl_init( $url );
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            if($POST) {
                curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($POST) );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            }
            $data = curl_exec( $ch );
            $err = curl_error( $ch );
            curl_close( $ch );
            if($err) return $err;
            return $data;
        
    }

    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_WEBSERVICES();
        }
        return self::$_instance;
    }
}
WPB_F_R_WPBRIDGE_WEBSERVICES::WPB_F_R_instance();
?>