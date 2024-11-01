<?php
    global $wpdb;
    $serverSettings = $wpdb->get_row("SELECT * FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id=1;");
    $validMapSettings = ((int)$serverSettings->seed !== 0) && ((int)$serverSettings->worldsize !== 0);
    $proPrefix = class_exists("WPB_F_R_WPBRIDGE_SUBSCRIPTION") && WPB_F_R_WPBRIDGE_SUBSCRIPTION::WPB_F_R_instance()->WPB_F_R_Get() ? " - Pro" : "";
?>
<div class="wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap">
    <h1 class="wp-heading-inline <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-settings-heading"><?php echo WPBRIDGE_PLUGIN_NAME . ' ' . $proPrefix; ?></h1>
    <br>
    <br>
    
        <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-settings-tabs">
            <ul>
                <li><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-status-tab"><?php echo __('Status', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
                <li><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-setup-tab"><?php echo __('Setup', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
                <?php if ( (get_option('wpbridge_rustmapapi_field','') !== "") && $validMapSettings ) { ?>
                    <li><a id="rustmaps-tab-btn" href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-rustmaps-tab"><?php echo __('RustMaps', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
                <?php } ?>
                <?php
                if($serverSettings->updated !== "1970-01-01 00:00:00") {
                ?>
                    <li><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-statistics-tab"><?php echo __('Statistics', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
                <?php
                }
                ?>
                <li><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-help-tab"><?php echo __('Help', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
                <?php if ( (get_option('wpbridge_subscription_key','') == "") ) { ?>
                    <!-- <li class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-pro-tab"><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-pro-tab"><?php echo __('Go pro', WPBRIDGE_TEXT_DOMAIN); ?></a></li> -->
                <?php } else { ?>
                    <li class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-pro-tab"><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-pro-tab"><?php echo __('Pro', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
                <?php } ?>
            </ul>
            <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-status-tab">
                <?php require_once WPBRIDGE_PAGES_PATH . 'status_page.php';  ?>
            </div>
            <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-setup-tab">
                <?php require_once WPBRIDGE_PAGES_PATH . 'setup_page.php';  ?>
            </div>
            <?php if ( (get_option('wpbridge_rustmapapi_field','') !== "") && $validMapSettings ) { ?>
                <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-rustmaps-tab">
                    <?php require_once WPBRIDGE_PAGES_PATH . 'rustmaps_page.php';  ?>
                </div>
            <?php } ?>
            <?php
            if($serverSettings->updated !== "1970-01-01 00:00:00") {
            ?>
            <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-statistics-tab">
                <?php require_once WPBRIDGE_PAGES_PATH . 'statistics_page.php';  ?>
            </div>
            <?php
            }
            ?>
            <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-help-tab">
                <?php require_once WPBRIDGE_PAGES_PATH . 'help_page.php';  ?>
            </div>
            <?php if ( (get_option('wpbridge_subscription_key','') == "") ) { ?>
                <!-- <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-pro-tab"> -->
                    <!-- <?php require_once WPBRIDGE_PAGES_PATH . 'pro_page.php';  ?> -->
                <!-- </div> -->
            <?php } else { ?>
                <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-pro-tab">
                    <?php require_once WPBRIDGE_PAGES_PATH . 'pro_subscribed_page.php';  ?>
                </div>
            <?php } ?>
        </div>
        
    
</div>


<div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-modal" class="wpbridge-modal">

  <!-- Modal content -->
  <div class="wpbridge-modal-content">
    <span class="wpbridge-modal-close">&times;</span>
    <div class="wpbridge-modal-body"></div>
  </div>

</div>