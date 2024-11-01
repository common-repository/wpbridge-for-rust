<div class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-tab-content" style="padding:10px;">
    <form action="options.php" method="post">
        <?php settings_fields('wpbridge-settings-page'); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php echo __('Secret', WPBRIDGE_TEXT_DOMAIN); ?></th>
                    <td>
                        <input type="text" id="wpbridge_secret_field" class="regular-text" name="wpbridge_secret_field" value="<?php echo get_option('wpbridge_secret_field',''); ?>" placeholder="Please type or generate your unique secret">
                        <a href="#" id="wpbridge_secret_generate_button" <?php echo 'class="'.WPBRIDGE_TEXT_DOMAIN.'-btn ' . WPBRIDGE_TEXT_DOMAIN . '-tab-btn active"'; ?>>Generate<?php echo get_option('wpbridge_secret_field','') !== "" ? " " . __('new',WPBRIDGE_TEXT_DOMAIN) : ""; ?></a>
                        <br>
                        <p><?php echo __('Generate secret and paste it into',WPBRIDGE_TEXT_DOMAIN); ?> <code>{<?php echo str_replace(' ','_',__('YOUR SERVER FOLDER',WPBRIDGE_TEXT_DOMAIN)); ?>}\oxide\config\<span class="wpbridge-underline">WPBridge.json</span></code></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Your RustMap API Key',WPBRIDGE_TEXT_DOMAIN); ?></th>
                    <td>
                        <input type="text" id="wpbridge_rustmapapi_field" class="regular-text" name="wpbridge_rustmapapi_field" value="<?php echo get_option('wpbridge_rustmapapi_field',''); ?>" placeholder="Paste your RustMap API Key">
                        <br>
                        <p>Get your API Key on <a href="https://rustmaps.com/" target="_blank">rustmaps.com</a></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="submit" name="submit" id="submit" <?php echo 'class="'.WPBRIDGE_TEXT_DOMAIN.'-btn active wpbridge-settings-submit-btn"'; ?> value="Save Changes"> Remember to save changes
    </form>
</div>