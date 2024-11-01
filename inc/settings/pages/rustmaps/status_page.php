<?php
$serverSettings = $wpdb->get_row("SELECT * FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id=1;");
require_once WPBRIDGE_INCLUDES_PATH . 'rustmaps.php';
$rustMapData = WPB_F_R_WPBRIDGE_RUSTMAPS_API::WPB_F_R_RustMaps_instance();

$rustMapLastGen = get_option('wpbridge_rustmaplastgen','');
$rustMapLastGenTime = null;
if($rustMapLastGen == '') {
    $rustMapLastGen = __('NEVER',WPBRIDGE_TEXT_DOMAIN);
} else {
    $rustMapLastGenTime = date("m/d/Y @ H:i:s a",strtotime($rustMapLastGen));
    $rustMapLastGen = strtoupper(date("m/d/Y",strtotime($rustMapLastGen)));
}


?>
<div class="wrap">
    <?php
    if($rustMapLastGen == '' || !$rustMapData->hasData) {
    ?>
    <div class="rustmaps-columns-wrapper">
        <div class="rustmaps-info-column">
            <img width="150" src="<?php echo WPBRIDGE_ADMIN_IMG_URL . 'rustmapapi_not_generated.jpg' ?>">
            <h5>Click the Generate tab to generate your first map.</h5>
        </div>
    </div>
    <?php
    } else {
    ?>
    <div class="rustmaps-columns-wrapper">
        <div class="rustmaps-info-column">
            
            <h4><?php echo __('General',WPBRIDGE_TEXT_DOMAIN) ?></h4>
            <h5><?php echo __('Last gen',WPBRIDGE_TEXT_DOMAIN) ?> <span <?php echo $rustMapLastGenTime ? "title=\"" . $rustMapLastGenTime . "\"" : ""; ?> class="float-right"><?php echo $rustMapLastGen; ?></span> </h5>
            <br>
            <?php
            if($rustMapLastGen !== __('NEVER',WPBRIDGE_TEXT_DOMAIN)) {
            ?>
            <h5><?php echo __('Seed',WPBRIDGE_TEXT_DOMAIN) ?> <span class="float-right"><?php echo $rustMapData->map['seed']; ?></span> </h5>
            <h5><?php echo __('Size',WPBRIDGE_TEXT_DOMAIN) ?> <span class="float-right"><?php echo $rustMapData->map['size']; ?></span> </h5>
            <h4><?php echo __('Biomes',WPBRIDGE_TEXT_DOMAIN) ?></h4>
            <h5><?php echo __('Snow Biome',WPBRIDGE_TEXT_DOMAIN) ?> <span class="float-right"><?php echo $rustMapData->biomes_percentage['snow']; ?>%</span> </h5>
            <h5><?php echo __('Desert Biome',WPBRIDGE_TEXT_DOMAIN) ?> <span class="float-right"><?php echo $rustMapData->biomes_percentage['desert']; ?>%</span> </h5>
            <h5><?php echo __('Forest Biome',WPBRIDGE_TEXT_DOMAIN) ?> <span class="float-right"><?php echo $rustMapData->biomes_percentage['forest']; ?>%</span> </h5>
            <h5><?php echo __('Tundra Biome',WPBRIDGE_TEXT_DOMAIN) ?> <span class="float-right"><?php echo $rustMapData->biomes_percentage['tundra']; ?>%</span> </h5>
            <h4><?php echo __('Monuments',WPBRIDGE_TEXT_DOMAIN) ?></h4>
            <?php
            foreach ($rustMapData->monumentData as $monumentName => $monumentCount) {
                ?>
            <h5><span class="h5-sm"><?php echo $monumentName; ?></span> <span class="float-right"><?php echo $monumentCount; ?></span> </h5>
            <?php
            }
            ?>
            <?php
            } 
            ?>

        </div>
        <?php
        if($rustMapLastGen !== '' && $rustMapData->hasData) {
        ?>
        <div class="rustmaps-map-column">
            <img src="<?php echo WPB_F_R_WPBRIDGE_RUSTMAPS_API::WPB_F_R_RustMaps_instance()->WPB_F_R_Image_URL(); ?>">
        </div>
        <?php
        }
        ?>
    </div>
    <?php
    }
    ?>
</div>