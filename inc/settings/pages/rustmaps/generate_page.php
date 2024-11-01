<?php
$serverSettings = $wpdb->get_row("SELECT * FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id=1;");
?>
<div class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-tab-content" style="padding:10px;">
    <h3><?php echo __('The new map will be based on the current server settings',WPBRIDGE_TEXT_DOMAIN); ?>: <?php echo __('Seed',WPBRIDGE_TEXT_DOMAIN); ?>: <?php echo $serverSettings->seed; ?>, <?php echo __('Size',WPBRIDGE_TEXT_DOMAIN); ?>: <?php echo $serverSettings->worldsize; ?>.</h3>
    <a id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-generate-rust-map-btn" href="#" class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-btn active" style="margin:0;"><?php echo __('Generate now',WPBRIDGE_TEXT_DOMAIN); ?></a>
</div>