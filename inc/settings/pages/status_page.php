<?php
    global $wpdb;
    $serverSettings = $wpdb->get_row("SELECT * FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id=1;");
    if($serverSettings->updated == "1970-01-01 00:00:00") {
        $updated = __('NEVER',WPBRIDGE_TEXT_DOMAIN) . '<a class="' . WPBRIDGE_TEXT_DOMAIN . '-tab-btn '.WPBRIDGE_TEXT_DOMAIN.'-btn ' . WPBRIDGE_TEXT_DOMAIN . '-tab-btn active" href="#" data-parent="'.WPBRIDGE_TEXT_DOMAIN.'-setup-tab">' . __('Connect now','WPBRIDGE_TEXT_DOMAIN') . '</a>';
    } else {
        $updated = strtoupper(date("m/d/Y @ h:i:s a",strtotime($serverSettings->updated)));
    }
?>
<div class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-tab-content" style="padding:10px;">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><?php echo __('Last Server sync', WPBRIDGE_TEXT_DOMAIN); ?></th>
                <td>
                    <?php echo $updated; ?>
                </td>
            </tr>
            <?php
            if($serverSettings->updated !== "1970-01-01 00:00:00"){ ?>
            <tr>
                <th scope="row"><?php echo __('Hostname',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->hostname); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Identity',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->identity); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Description',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->description); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('IP',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->ip); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Port',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->port); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Level',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->level); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Seed',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->seed); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('World size',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->worldsize); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Max players',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->maxplayers); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Active players',WPBRIDGE_TEXT_DOMAIN); ?>:</th>
                <td><?php echo esc_html($serverSettings->numactiveplayers); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>