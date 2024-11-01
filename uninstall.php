<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) return;
class WPB_F_R_WPBRIDGE_UNINSTALL
{
    private static $_instance = null;

    public function __construct()
    {
        $this->WPB_F_R_DropDatabaseTables();
        $this->WPB_F_R_DeleteOptions();
        $this->WPB_F_R_DeleteUpdateNoticeOptions();
    }

    function WPB_F_R_DropDatabaseTables()
    {
        global $wpdb;
        $sql = "DROP TABLE IF EXISTS `".$wpdb->prefix . 'wpbridge_settings'."`;";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS `".$wpdb->prefix . 'wpbridge_players'."`;";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS `".$wpdb->prefix . 'wpbridge_player_stats'."`;";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS `".$wpdb->prefix . 'wpbridge_player_loots'."`;";
        $wpdb->query($sql);
    }

    function WPB_F_R_DeleteOptions()
    {
        delete_option('wpbridge_secret_field');
        delete_option('WPBRIDGE_PLUGIN_VERSION');
        delete_option('wpbridge_rustmapapigeneratedfilename');
        delete_option('wpbridge_rustmapapidata');
        delete_option('wpbridge_rustmapapi_field');
        delete_option('wpbridge_rustmaplastgen');
    }

    function WPB_F_R_DeleteUpdateNoticeOptions()
    {
        delete_option('wpbridge_update_notice_shown_1.2.0');
        delete_option('wpbridge_update_notice_shown_1.2.12');
    }

    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_UNINSTALL();
        }
        return self::$_instance;
    }
}

WPB_F_R_WPBRIDGE_UNINSTALL::WPB_F_R_instance();