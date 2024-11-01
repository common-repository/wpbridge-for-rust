<?php
    require_once WPBRIDGE_INCLUDES_PATH . 'player_database.php';
?>
<div class="wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap">
    <h1 class="wp-heading-inline <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-settings-heading"><?php echo WPBRIDGE_PLUGIN_NAME . ' - ' . __('Wipe', WPBRIDGE_TEXT_DOMAIN); ?></h1>
    <br>
    <br>
    <?php
    if(WPB_F_R_WPBRIDGE_Player_Database::WPB_F_R_instance()->WPB_F_R_WipeStatistics()) {
    ?>
    <h3><?php echo __('Player statistics successfully wiped', WPBRIDGE_TEXT_DOMAIN); ?></h3>
    <a class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-btn active" style="margin-left:0" href="<?php echo admin_url('admin.php?page=wpbridge-settings-page'); ?>"><?php echo __('Back to settings',WPBRIDGE_TEXT_DOMAIN); ?></a>
    <?php
    } else {

    }
    ?>
</div>