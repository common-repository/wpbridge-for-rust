<?php
    $rustMapFilePath = WP_CONTENT_DIR . '/uploads/RustMap_' . $serverSettings->seed . '_' . $serverSettings->worldsize . '.png';
?>
<div class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-tab-content" style="padding:10px;">
    <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-rustmaps-tabs">
        <ul>
            <li><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-rustmaps-status-tab"><?php echo __('Details', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
            <li><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-rustmaps-generate-tab"><?php echo __('Generate', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
        </ul>
        <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-rustmaps-status-tab">
            <?php require_once WPBRIDGE_PAGES_PATH . 'rustmaps/status_page.php';  ?>
        </div>
        <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-rustmaps-generate-tab">
            <?php require_once WPBRIDGE_PAGES_PATH . 'rustmaps/generate_page.php';  ?>
        </div>
    </div>
</div>