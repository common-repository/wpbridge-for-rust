<div class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-tab-content" style="padding:10px;">
    <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-statistics-tabs">
        <ul>
            <li><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-statistics-player-tab"><?php echo __('Players', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
            
            <li class="danger-tab"><a href="#<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-statistics-wipe-tab"><?php echo __('Wipe', WPBRIDGE_TEXT_DOMAIN); ?></a></li>
        </ul>
        <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-statistics-player-tab">
            <?php require_once WPBRIDGE_PAGES_PATH . 'statistics' . DIRECTORY_SEPARATOR . 'players_page.php'; ?>
        </div>
        <div id="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-statistics-wipe-tab">
            <?php require_once WPBRIDGE_PAGES_PATH . 'statistics' . DIRECTORY_SEPARATOR . 'wipe_page.php'; ?>
        </div>
    </div>
</div>