<?php

class WPB_F_R_WPBRIDGE_RUSTMAPS_API
{
    private static $_instance = null;
    private $_data;
    private $_biomes_count = [];

    public $map = [];

    public $hasData = false;

    public $biomes_percentage = [];
    public $monumentData = [];

    public function __construct()
    {
        $this->_data = get_option('wpbridge_rustmapapidata', null);
        
        if($this->_data !== null && $this->_data !== "") {
            if(is_array($this->_data)) {
                $this->_data = (object) $this->_data;
            }
            $this->hasData = true;
            $this->map = [
                "seed" => $this->_data->seed,
                "size" => $this->_data->size
            ];
            
            $this->WPB_F_R_RustMaps_count_biomes();
            $this->WPB_F_R_RustMaps_calculate_biomes_percentage();
            $this->WPB_F_R_RustMaps_count_monuments();
        }
    }

    public function WPB_F_R_Image_URL() {
        return $this->_data->imageIconUrl;
    }

    public function WPB_F_R_RustMaps_count_monuments()
    {
        if(isset($this->_data->monuments)) {
            foreach ($this->_data->monuments as $monument) {
                
                if(!isset($this->monumentData[$monument['monument']])) {
                    $this->monumentData[str_replace("_"," ",$monument['monument'])] = 1;
                } else {
                    $this->monumentData[str_replace("_"," ",$monument['monument'])]++;
                }
            }
        }
    }

    public function WPB_F_R_RustMaps_calculate_biomes_percentage()
    {
        
        $biomesPercentage = [
            "snow" => number_format($this->_biomes_count['snow'] / $this->_biomes_count['total'] * 100,2),
            "desert" => number_format($this->_biomes_count['desert'] / $this->_biomes_count['total'] * 100,2),
            "forest" => number_format($this->_biomes_count['forest'] / $this->_biomes_count['total'] * 100,2),
            "tundra" => number_format($this->_biomes_count['tundra'] / $this->_biomes_count['total'] * 100,2),
        ];
        
        $this->biomes_percentage = $biomesPercentage;
    }

    function WPB_F_R_RustMaps_count_biomes()
    {
        $biomesCount = [
            "total" => 0,
            "snow" => 0,
            "desert" => 0,
            "forest" => 0,
            "tundra" => 0
        ];
        if(isset($this->_data->monuments)) {
            foreach ($this->_data->monuments as $monument) {
                if(isset($monument['biome'])) {
                    $biomesCount['total']++;
                    $biomesCount[strtolower($monument['biome'])]++;
                }
            }
        }
        $this->_biomes_count = $biomesCount;
    }

    static function WPB_F_R_RustMaps_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_RUSTMAPS_API();
        }
        return self::$_instance;
    }
}