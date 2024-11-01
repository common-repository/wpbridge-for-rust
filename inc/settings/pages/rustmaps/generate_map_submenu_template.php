<?php
    $rustMapApiKey = get_option('wpbridge_rustmapapi_field','');
?>
<div class="wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap">
    <h1 class="wp-heading-inline <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-settings-heading"><?php echo WPBRIDGE_PLUGIN_NAME . ' - ' . __('Map generation', WPBRIDGE_TEXT_DOMAIN); ?></h1>
    <br>
    <br>
    <?php
    if($rustMapApiKey == '') {
    ?>
        <h2><?php echo __('It seems like the RustMaps API key is not set. Map generation aborted.', WPBRIDGE_TEXT_DOMAIN); ?></h2>
    <?php
    } else {
        global $wpdb;
        $settings = $wpdb->get_row("SELECT `seed`,`worldsize` FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id = 1");
        if($settings->seed == 0 || $settings->worldsize == 0) {
        ?>
            <h2><?php echo __('It seems like the map seed or world size is 0. Map generation aborted.', WPBRIDGE_TEXT_DOMAIN); ?></h2>
        <?php
        } else {
            require_once WPBRIDGE_INCLUDES_PATH . 'rustmaps.php';
            $rustMapsApi = WPB_F_R_WPBRIDGE_RUSTMAPS_API::WPB_F_R_RustMaps_instance();
            $mapData = $rustMapsApi->WPB_F_R_RustMaps_fetch_map($settings->seed,$settings->worldsize);

            if(!$mapData) {
            ?>
            <h2><?php echo __('Map generation failed to complete.', WPBRIDGE_TEXT_DOMAIN); ?></h2>
            <p><?php echo __('Ensure that your map api key is correct and that the Rust server have synced recently.',WPBRIDGE_TEXT_DOMAIN); ?></p>
            <p><?php echo __('Rustmaps.com could still be generating the map. Please try again in a few minutes.',WPBRIDGE_TEXT_DOMAIN); ?></p>
            <p><?php echo __('If the map generation continues to fail',WPBRIDGE_TEXT_DOMAIN); ?>, <a target="_blank" href="https://wordpress.org/support/plugin/wpbridge-for-rust/"><?php echo __('please reach out for support.',WPBRIDGE_TEXT_DOMAIN); ?></a></p>
            <?php
            } else {
            ?>
            <h3><?php echo __('Map generation successfully completed.', WPBRIDGE_TEXT_DOMAIN); ?></h3>
            <p><?php echo $mapData['details'] ?></p>
            <img height="300" src="<?php echo $mapData['relativeFilePath']; ?>">
            <br>
            <a style="margin:10px 0 0 0;" class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-btn active" href="<?php echo admin_url() . 'upload.php?item=' . $mapData['uploadId']; ?>"><?php echo __('View map in Media',WPBRIDGE_TEXT_DOMAIN); ?></a>
            <a style="margin:10px 0 0 10px;" href="<?php echo admin_url() . 'admin.php?page=wpbridge-settings-page'; ?>"><?php echo __('Back to settings',WPBRIDGE_TEXT_DOMAIN); ?></a>
            <?php
            }
        ?>
            
        <?php
        }
    ?>
    <?php
    }
    ?>
</div>