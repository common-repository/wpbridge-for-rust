<?php
global $wpdb;
/**
 * Plugin
 */
define( 'WPBRIDGE_PLUGIN_VERSION', $this->plugin_version);
define( 'WPBRIDGE_PLUGIN_NAME', 'WPBridge for Rust');
define( 'WPBRIDGE_TEXT_DOMAIN', 'wpbridge-for-rust');
/**
 * PATHS
 */
define( 'WPBRIDGE_VENDOR_PATH', WPBRIDGE_PATH . 'vendor' . DIRECTORY_SEPARATOR );
define( 'WPBRIDGE_INCLUDES_PATH', WPBRIDGE_PATH . 'inc' . DIRECTORY_SEPARATOR );
define( 'WPBRIDGE_SETTINGS_PATH', WPBRIDGE_INCLUDES_PATH . 'settings' . DIRECTORY_SEPARATOR );
define( 'WPBRIDGE_PAGES_PATH', WPBRIDGE_SETTINGS_PATH . 'pages' . DIRECTORY_SEPARATOR );
define( 'WPBRIDGE_ADMIN_PATH', WPBRIDGE_PATH . 'admin' . DIRECTORY_SEPARATOR );

/**
 * URLS
 */
define( 'WPBRIDGE_ADMIN_IMG_URL', WPBRIDGE_URL . '/admin/img' . '/' );
define( 'WPBRIDGE_MODALS_URL', WPBRIDGE_URL . 'inc/settings/pages/modals/');

/**
 * APIEndpoints
 */
// define( 'WPBRIDGE_ENDPOINT_SERVICES', 'http://127.0.0.1:3000/' ); //DEBUG
define( 'WPBRIDGE_ENDPOINT_SERVICES', 'https://danlevi.tech/' );
define( 'WPBRIDGE_ENDPOINT_PAYMENT', WPBRIDGE_ENDPOINT_SERVICES . 'payment/wpbridge-pro/' );
/**
 * DATABASE TABLES
 */
define( 'WPBRIDGE_SETTINGS_TABLE', $wpdb->prefix . 'wpbridge_settings' );
define( 'WPBRIDGE_PLAYER_TABLE', $wpdb->prefix . 'wpbridge_players' );
define( 'WPBRIDGE_PLAYER_STATS_TABLE', $wpdb->prefix . 'wpbridge_player_stats' );
define( 'WPBRIDGE_PLAYER_LOOT_TABLE', $wpdb->prefix . 'wpbridge_player_loots' );

/**
 * STATS
 */
define( 'WPBRIDGE_SERVER_STATS', [
    'ip',
    'port',
    'level',
    'identity',
    'seed',
    'worldsize',
    'maxplayers',
    'hostname',
    'description',
    'updated'
]);
define( 'WPBRIDGE_PLAYER_STATS', [
    'joins',            
    'leaves',               
    'deaths',               
    'suicides',             
    'kills',                
    'headshots',            
    'wounded',              
    'recoveries',           
    'crafteditems',         
    'repaireditems',        
    'explosivesthrown',     
    'voicebytes',           
    'hammerhits',           
    'reloads',              
    'shots',                
    'collectiblespickedup', 
    'growablesgathered',    
    'chats',                
    'npckills',             
    'meleeattacks',         
    'mapmarkers',           
    'respawns',             
    'rocketslaunched',      
    'antihackviolations',   
    'npcspeaks',            
    'researcheditems',
    'killedbynpc',
    'lootcontainer',
    'lootbradheli',
    'loothackable',
    'lootcontainerunderwater'   
]);
