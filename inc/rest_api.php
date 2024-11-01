<?php

class WPB_F_R_WPBRIDGE_REST_API
{
    private static $_instance = null;
    private $db_instance = null;

    public function __construct()
    {
        $this->WPB_F_R_InitRoutes();
        require_once WPBRIDGE_PATH . 'inc/player_database.php';
        $this->db_instance = WPB_F_R_WPBRIDGE_Player_Database::WPB_F_R_instance();
    }

    function WPB_F_R_InitRoutes()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'wpbridge', '/secret', array(
                'methods' => 'POST',
                'callback' => [$this,"WPB_F_R_Secret_Callback"],
                'permission_callback' => '__return_true'
            ));
            register_rest_route( 'wpbridge', '/player-stats', array(
                'methods' => 'POST',
                'callback' => [$this,"WPB_F_R_Player_Stats_POST_Callback"],
                'permission_callback' => '__return_true'
            ));
         });
    }
    
    function WPB_F_R_Player_Stats_POST_Callback($req)
    {
        if(!isset($req["Secret"])) return $this->WPB_F_R_ReturnError(401,"Secret not set");
        if($req["Secret"] != get_option('wpbridge_secret_field')) return $this->WPB_F_R_ReturnError(401,"Secret mismatch");

        if(isset($req["PlayerData"]))
        {
            $playerDataSuccessMessage = $this->db_instance->WPB_F_R_StorePlayerData($req["PlayerData"]);
            if($playerDataSuccessMessage) {
                if(isset($req["ServerData"])) {
                    $serverDataSuccessMessage = $this->db_instance->WPB_F_R_StoreServerData($req["ServerData"]);
                    if($serverDataSuccessMessage) {
                        return $this->WPB_F_R_ReturnSuccess(200, "Player and Server Data stored.");
                    }
                }
            }
        }
    }

    function WPB_F_R_Secret_Callback($req)
    {
        if(!isset($req["Secret"])) return $this->WPB_F_R_ReturnError(401,"Secret not set");
        if($req["Secret"] != get_option('wpbridge_secret_field')) return $this->WPB_F_R_ReturnError(401,"Secret mismatch");
        return $this->WPB_F_R_ReturnSuccess(200, "Ready");
    }

    function WPB_F_R_ReturnSuccess($code,$message)
    {
        return new WPB_F_R_WP_Rest_Success_Message(
            "success",
            $message,
            array( 'status' => $code)
        );
    }

    function WPB_F_R_ReturnError($code,$message)
    {
        return new WP_Error(
            'error',
            $message,
            array( 'status' => $code)
        );
    }


    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_REST_API();
        }
        return self::$_instance;
    }
}
#region Helper Classes
class WPB_F_R_WP_Rest_Success_Message
{
   public $code,$message,$data;
   public function __construct($_code, $_message,$_data)
   {
      $this->code = $_code;
      $this->message = $_message;
      $this->data = $_data;
   }
}
#endregion
WPB_F_R_WPBRIDGE_REST_API::WPB_F_R_instance();