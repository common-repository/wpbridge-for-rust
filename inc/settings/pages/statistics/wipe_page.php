<?php
$wipeAllURL = admin_url('admin.php?page=wpbridge-wipe-all');
?>
<div class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-tab-content" style="padding:10px;">
    <h3><?php echo __('Wiping player statistics does not affect the Rust server.',WPBRIDGE_TEXT_DOMAIN); ?></h3>
    <p><?php echo __('It is adviced to have your Rust server turned off, or having WPBridge plugin unloaded when wiping statistics.',WPBRIDGE_TEXT_DOMAIN); ?> <?php echo __('If server is running and syncing, statistics will be repopulated instantly.',WPBRIDGE_TEXT_DOMAIN); ?></p>
    <a class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-btn active" style="margin-left:0;" href="<?php echo $wipeAllURL; ?>"><?php echo __('Wipe player statistics',WPBRIDGE_TEXT_DOMAIN); ?></a>
</div>