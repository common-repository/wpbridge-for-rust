<?php
    require_once WPBRIDGE_INCLUDES_PATH . 'player_database.php';
    $players = WPB_F_R_WPBRIDGE_Player_Database::WPB_F_R_instance()->WPB_F_R_GetPlayers();
?>
<div class="<?php echo WPBRIDGE_TEXT_DOMAIN; ?>-wrap <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-tab-content" style="padding:10px;">
<?php
    if(is_array($players) && count($players) > 0) {
        ?>
    <h3><?php echo __('Players',WPBRIDGE_TEXT_DOMAIN); ?> (<?php echo count($players); ?>)</h3>
    <table class="form-table players-table">
        <thead>
            <tr>
                <th class="th-num"><?php echo __('Num',WPBRIDGE_TEXT_DOMAIN); ?></th>
                <th><?php echo __('Display name(SteamId)', WPBRIDGE_TEXT_DOMAIN); ?></th>
                <th><?php echo __('Position', WPBRIDGE_TEXT_DOMAIN); ?></th>
                <th><?php echo __('Last updated', WPBRIDGE_TEXT_DOMAIN); ?></th>
                <th><?php echo __('Actions',WPBRIDGE_TEXT_DOMAIN); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($players); $i++) {
                $player = $players[$i];
                $position = json_decode($player->position);
                ?>
            <tr>
                <td><strong><?php echo $i+1; ?></strong></td>
                <td><?php echo esc_html($player->displayname); ?>(<a target="_blank" href="https://steamcommunity.com/profiles/<?php echo esc_html($player->steamid); ?>"><?php echo esc_html($player->steamid); ?></a>)</td>
                <td><strong>X</strong>: <?php echo esc_html($position->x); ?>, <strong>Y</strong>: <?php echo esc_html($position->y); ?>, <strong>Z</strong>: <?php echo esc_html($position->z); ?></td>
                <td><?php echo strtoupper(date("m/d/Y @ h:i:s a",strtotime($player->updated))); ?></td>
                <td><a href="#" class="copyData <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-btn <?php echo WPBRIDGE_TEXT_DOMAIN; ?>-btn-sm active" data-copy="teleport &quot;<?php echo $player->displayname; ?>&quot;">Copy teleport position</a></td>
            </tr>
            <?php
            } 
            ?>
        </tbody>
    </table>
    <?php
    } else {
    ?>
    <h3><?php echo __('No player statistics',WPBRIDGE_TEXT_DOMAIN); ?></h3>
    <?php
    }
    ?>
</div>