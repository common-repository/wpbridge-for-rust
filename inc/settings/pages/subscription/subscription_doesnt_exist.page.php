<div class="wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap">
    <h1 class="wp-heading-inline <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-settings-heading"><?php echo WPBRIDGE_PLUGIN_NAME; ?></h1>
    <h3><?php echo __('Ops..! Detected issues with the license.',WPBRIDGE_TEXT_DOMAIN); ?></h3>
    <table>
        <tbody>
            <tr>
                <th>Old site_url: </th>
                <td><?php echo $siteUrlFromFile; ?></td>
            </tr>
            <tr>
                <th>New site_url: </th>
                <td><?php echo get_site_url(); ?></td>
            </tr>
        </tbody>
    </table>
    <h3><?php echo __('This license seems to be non-existing.',WPBRIDGE_TEXT_DOMAIN); ?> <?php echo __('If you believe this is not the case please',WPBRIDGE_TEXT_DOMAIN); ?> <a href="mailto:dan-levi@outlook.com?subject=Issues%20with%20my%20license%20key%20for%20site%20<?php echo htmlentities(get_site_url()); ?>"><?php echo __('contact me',WPBRIDGE_TEXT_DOMAIN); ?></a> <?php echo __('and provide the license file and details above.',WPBRIDGE_TEXT_DOMAIN); ?></h3>
    <a href="<?php echo admin_url('admin.php?page=wpbridge-settings-page'); ?>" class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-btn active" style="margin:0;"><?php echo __('Go back',WPBRIDGE_TEXT_DOMAIN); ?></a>
</div>