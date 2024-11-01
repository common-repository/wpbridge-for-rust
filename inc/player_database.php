<?php

class WPB_F_R_WPBRIDGE_Player_Database
{
    #region Variables
    private static $_instance = null;
    private $_wpdb;
    #endregion

    #region Player

    function WPB_F_R_GetPlayers($live = false)
    {
        $sql = "SELECT * FROM `" . WPBRIDGE_PLAYER_TABLE . "`" . ( $live ? " WHERE `updated` >= CURRENT_TIMESTAMP - INTERVAL 5 MINUTE;" : ";" );
        return $this->_wpdb->get_results($sql);
    }

    function WPB_F_R_GetPlayerStats()
    {
        $sql = "SELECT * FROM `" . WPBRIDGE_PLAYER_STATS_TABLE . "`;";
        return $this->_wpdb->get_results($sql);
    }

    function WPB_F_R_InsertPlayer($player)
    {
        $sql = "INSERT INTO `" . esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) . "` (`steamid`, `displayname`) VALUES ('" . esc_sql($player["SteamID"]) . "', '" . esc_sql($player["DisplayName"]) . "');";
        $this->_wpdb->query($sql);
        $sql = "INSERT INTO `" . esc_sql(WPBRIDGE_PLAYER_TABLE) . "` (`steamid`, `displayname`, `position`) VALUES ('" . esc_sql($player["SteamID"]) . "', '" . esc_sql($player["DisplayName"]) . "', '" . esc_sql(json_encode($player["Position"])) . "');";
        $this->_wpdb->query($sql);
        if(isset($player["LootedItems"]) && count($player["LootedItems"]) > 0) $this->WPB_F_R_StorePlayerLoot($player["SteamID"], $player["LootedItems"]);
    }

    function WPB_F_R_UpdatePlayer($player)
    {
        $this->WPB_F_R_UpdatePlayerTable($player);
        $this->WPB_F_R_UpdatePlayerStatsTable($player);
        $this->WPB_F_R_UpdatePlayerLootTable($player);
    }

    public function WPB_F_R_StorePlayerData($players)
    {
        foreach ($players as $player) {
            $sql = "SELECT `steamid` FROM `". esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) ."` WHERE `steamid` = '%d';";
            if($this->_wpdb->query($this->_wpdb->prepare($sql,$player["SteamID"])))
            {
                $this->WPB_F_R_UpdatePlayer($player);
            } else {
                
                $this->WPB_F_R_InsertPlayer($player);
            }
        }
        return true;
    }

    function WPB_F_R_UpdatePlayerTable($player)
    {
        $sql = "UPDATE `" . esc_sql(WPBRIDGE_PLAYER_TABLE) . "` SET `displayname` = '" . esc_sql($player["DisplayName"]) . "', `position` = '" . esc_sql(json_encode($player["Position"]))  . "' WHERE `steamid` = " . esc_sql($player["SteamID"]) . ";";
        $this->_wpdb->query($sql);
    }

    function WPB_F_R_UpdatePlayerStatsTable($player)
    {
        $sql = "SELECT * FROM `". esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) ."` WHERE `steamid` = '%d';";
        $existingPlayer = $this->_wpdb->get_row($this->_wpdb->prepare($sql, $player["SteamID"] ));
        
        $sql = "UPDATE `" . esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) . "` SET ";
        foreach (array_keys($player['Stats']) as $requestValue) {
            $columnName = strtolower($requestValue);
            $sql .= "`$columnName` = '" . ((int)$existingPlayer->{$columnName} + (int)($player['Stats'][$requestValue])) . "',";
        }
        $sql = chop($sql,',') . " WHERE `steamid` = '" . esc_sql($player["SteamID"]) . "';";
        $this->_wpdb->query($sql);
    }

    function WPB_F_R_UpdatePlayerLootTable($player)
    {
        if(!isset($player["LootedItems"]) || count($player["LootedItems"]) < 1) return;
        $this->WPB_F_R_StorePlayerLoot($player["SteamID"], $player["LootedItems"]);
    }

    function WPB_F_R_StorePlayerLoot($steamId, $items)
    {
        if(!is_array($items) || $items == null) return;
        if($this->_wpdb->get_var("SHOW TABLES LIKE '".esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE)."'") == WPBRIDGE_PLAYER_LOOT_TABLE);
        {
            $existing_columns = $this->_wpdb->get_col("DESC `".esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE)."`",0);
            foreach ($items as $item) {
                if(!in_array($item["Name"],$existing_columns))
                {
                    $this->_wpdb->query("ALTER TABLE `".esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE)."` ADD `".esc_sql(strtolower(trim($item["Name"])))."` INT(11) NOT NULL;");
                }
            }
            
            $sql = "SELECT `steamid` FROM `". esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) ."` WHERE `steamid` = '%d';";
            $queryResultPlayerExistsInStatsTable = $this->_wpdb->query($this->_wpdb->prepare($sql,$steamId));
            if($queryResultPlayerExistsInStatsTable)
            {
                $sql = "SELECT * FROM `". esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE) ."` WHERE `steamid` = '%d';";
                $queryResultPlayerExistsInLootTable = $this->_wpdb->get_row($this->_wpdb->prepare($sql,$steamId));
                
                if($queryResultPlayerExistsInLootTable)
                {
                    $sql = "UPDATE `". esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE) ."` SET ";
                    foreach ($items as $item) {
                        $sql .= "`" . esc_sql($item["Name"]) . "` = '" . ((int)$queryResultPlayerExistsInLootTable->{$item["Name"]} + (int)($item["Amount"])) . "',";
                    }
                    $sql = chop($sql,',') . " WHERE `steamid` = '" . esc_sql($steamId) . "';";
                    $this->_wpdb->query($sql);
                } else {
                    $sqlStart = "INSERT INTO `".WPBRIDGE_PLAYER_LOOT_TABLE."`(";
                    $sqlStart .= "`steamid`,";
                    $sqlEnd = "'" . esc_sql($steamId) . "',";
                    foreach ($items as $item) {
                        $columnName = strtolower(trim($item["Name"]));
                        $sqlStart .= "`" . esc_sql($columnName) . "`,";
                        $sqlEnd .= "'" . esc_sql($item["Amount"]) . "',";
                    }
                    $sqlStart = chop($sqlStart,',') . ") VALUES (";
                    $sqlEnd = chop($sqlEnd,',') . ");";
                    $sql = $sqlStart . $sqlEnd;
                    $this->_wpdb->query($sql);
                }
            }
        }
    }

    #endregion

    #region Wipe

    function WPB_F_R_WipeStatistics()
    {
        $sqlTruncatePlayerTable = "TRUNCATE TABLE `" . WPBRIDGE_PLAYER_TABLE . "`;";
        $sqlTruncateStatsTable = "TRUNCATE TABLE `" . WPBRIDGE_PLAYER_STATS_TABLE . "`;";
        $sqlTruncateLootTable = "TRUNCATE TABLE `" . WPBRIDGE_PLAYER_LOOT_TABLE . "`;";

        if($this->_wpdb->query($sqlTruncatePlayerTable) && $this->_wpdb->query($sqlTruncateStatsTable) && $this->_wpdb->query($sqlTruncateLootTable)) {
            return true;
        }
        return false;
    }

    #endregion

    #region Server

    function WPB_F_R_GetServerSettings()
    {
        return $this->_wpdb->get_row("SELECT * FROM `" . WPBRIDGE_SETTINGS_TABLE . "`;");
    }

    function WPB_F_R_StoreServerData($serverData)
    {
        if(!isset($serverData["Ip"]) && !isset($serverData['IP'])) return false;
        if(!isset($serverData["Port"])) return false;
        if(!isset($serverData["Level"])) return false;
        if(!isset($serverData["Identity"])) return false;
        if(!isset($serverData["Seed"])) return false;
        if(!isset($serverData["WorldSize"])) return false;
        if(!isset($serverData["MaxPlayers"])) return false;
        if(!isset($serverData["HostName"])) return false;
        if(!isset($serverData["Description"])) return false;
        if(!isset($serverData["PlayerCount"])) return false;
        if(isset($serverData['IP'])) {
            $serverIp = str_replace("\n", "", $serverData['IP']);            
        } else {
            $serverIp = str_replace("\n", "", $serverData["Ip"]);
        }
        $description = str_replace("\\n","<br>",$serverData["Description"]);

        $sql = "UPDATE `".WPBRIDGE_SETTINGS_TABLE."` SET 
                `ip`                = '%s',
                `port`              = '%d',
                `level`             = '%s',
                `identity`          = '%s',
                `seed`              = '%d',
                `worldsize`         = '%d',
                `maxplayers`        = '%d',
                `hostname`          = '%s',
                `description`       = '%s',
                `numactiveplayers`  = '%d',
                `updated` = NOW();";
        $this->_wpdb->query(
            $this->_wpdb->prepare(
                $sql,
                esc_sql($serverIp),
                esc_sql($serverData["Port"]),
                esc_sql($serverData["Level"]),
                esc_sql($serverData["Identity"]),
                esc_sql($serverData["Seed"]),
                esc_sql($serverData["WorldSize"]),
                esc_sql($serverData["MaxPlayers"]),
                esc_sql($serverData["HostName"]),
                esc_sql($description),
                esc_sql($serverData["PlayerCount"]),
            )
        );
        return true;
    }

    #endregion

    #region construct and instance

    public function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
    }

    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_Player_Database();
        }
        return self::$_instance;
    }

    #endregion
}
WPB_F_R_WPBRIDGE_Player_Database::WPB_F_R_instance();