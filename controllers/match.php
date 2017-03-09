<?php

Class Controller_match Extends Controller_Base {

    function index() {

        $dota = Dota::me();

        if (isset($this->args[0])) {
            $match_id = (int) $this->args[0];
        }

        if (!empty($match_id)) {
            $steam_ids_array = Array();
            $match = $dota->get_match_by_id($match_id);
            foreach ($match['players'] AS $key => $player) {
                if ($player['account_id'] == 4294967295) {
                    continue;
                }
                $steam_ids_array[$key] = $player['account_id'];
            }
            $profiles = $dota->get_user_profiles($steam_ids_array);
            foreach ($profiles AS $key => $profile) {
                $match['players'][$key]['profile'] = $profile;
            }

            $match['tower_radiant'] = decbin(32768 + $match['tower_status_radiant']);
            $match['tower_dire'] = decbin(32768 + $match['tower_status_dire']);
            $match['barak_radiant'] = decbin(128 + $match['barracks_status_radiant']);
            $match['barak_dire'] = decbin(128 + $match['barracks_status_dire']);

            $this->des->set('spells', $dota->get_spells());
            //$this->des->set('arr', $arr['result']);
            $this->des->set('match', $match);
        }

        $this->des->set('dota', $dota);
        $this->des->display('match');
    }

}

?>