<?php

    $features = json_decode($subscription->data->product->key_features);
?>
<div class="wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap">
    <h1 class="wp-heading-inline <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-settings-heading"><?php echo WPBRIDGE_PLUGIN_NAME . ' - ' . __('Power Up!', WPBRIDGE_TEXT_DOMAIN); ?></h1>
    <br>
    <br>
    <h3><?php echo __('Your plugin was upgraded to',WPBRIDGE_TEXT_DOMAIN); ?> <?php echo esc_html($subscription->data->product->name_pretty); ?>!</h3>
    <h3>Features unlocked:</h3>
    <ul>
    <?php
    foreach ($features as $feature) {
    ?>
    <li><img src="<?php echo site_url('/wp-content/plugins/wpbridge-for-rust/admin/img/check.svg'); ?>"><?php echo esc_html($feature); ?></li>
    <?php
    }
    ?>
    </ul>
    <h4><?php echo __('A file download was just initiated and the license should be in your clipboard, but if it isn\'t, please grab it below!',WPBRIDGE_TEXT_DOMAIN); ?></h4>
    <textarea class="wpbridge-for-rust-textarea wpbridge-for-rust-textarea-subscription" data-license="<?php echo $subscription->data->product->name_pretty; ?>" readonly rows="3" cols="79">Key="<?php echo esc_html($subscription->data->subscription->subscription_key); ?>"
    Domain="<?php echo esc_html($subscription->data->subscription->site_url); ?>"</textarea>
    <p><?php echo __('Please save your subscription details somewhere safe, it is proof that the associated site owns the license.',WPBRIDGE_TEXT_DOMAIN); ?></p>
    <a class="wpbridge-for-rust-btn active" style="margin-left:0" href="<?php echo admin_url('admin.php?page=wpbridge-settings-page'); ?>"><?php echo __('Back to',WPBRIDGE_TEXT_DOMAIN); ?> <?php echo WPBRIDGE_PLUGIN_NAME; ?></a>
</div>