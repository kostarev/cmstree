<div>

    <style>
        table.match {width:950px;}
        table.match th{background-color: #9999ff;padding:8px; text-align: center;}
        table.match td{background-color: #ccccff; text-align: center; pargin:0px;margin:0px;}
        tr.dire td{background-color:#8B7E7E;}
        tr.radiant td{background-color:#C3CB90 ;}
        table.match a{color:#231D6D;}
        table.match a:hover{text-decoration:underline;}
        table.match .leaver{color:#702F1A; font-size:80%;}
        table.match th span{text-decoration:underline;text-decoration-style:dotted;}
        table.match .hero_icon{width:50px;}
        table.match .items img{width:50px;}
        table.match td.items {padding:0px;margin:0px;text-align:left;}
        table.match td.spells img{width:30px;}
        table.match td.spells{text-align:left;padding:0px;}
        td.radiant {width:50px; height:50px;background-color:#73D56D;}
        .tower_radiant{background-image: url(/data/img/tower_radiant.png);}
        td.dire {width:50px; height:50px;background-color:#9A8D8D;}
        .tower_dire{background-image: url(/data/img/tower_dire.png);}
        td.river{width:20px; height:50px;background-color:#71CDEF;}
        div.barak_radiant{width:50px;height:25px;background-image: url(/data/img/barak_radiant.png);padding:0px;margin:0px;}
        div.barak_dire{width:50px;height:25px;background-image: url(/data/img/barak_dire.png);padding:0px;margin:0px;}
        .tron_radiant{background: url(/data/img/tron_radiant.png) no-repeat;;}
        .tron_dire{background: url(/data/img/tron_dire.png) no-repeat;}
    </style>

    <div><b>Матч: <?=$match['match_id'];?></b></div>
    <div>
        <?if($match['radiant_win']):?>Победа света
        <?else:?>Победа тьмы<?endif;?>
    </div>
    <div>Длительность: <?=Func::sec2human($match['duration']);?></div>
    <div>Начало: <?=Func::unix2human($match['start_time']);?></div>
    <div>Конец: <?=Func::unix2human($match['start_time']+$match['duration']);?></div>

    <div>

        <table>
            <tr>
                <td class='radiant'>&nbsp;</td>
                <td title='T4' class='radiant <?=$match['tower_radiant'][5]?'tower_radiant':'';?>'>&nbsp;</td>
                <td class='radiant'>
                    <div title='Барак' class='radiant <?=$match['barak_radiant'][7]?'barak_radiant':'';?>'>&nbsp;</div>
                    <div title='Барак' class='radiant <?=$match['barak_radiant'][6]?'barak_radiant':'';?>'>&nbsp;</div>
                </td>
                <td title='T3' class='radiant <?=$match['tower_radiant'][13]?'tower_radiant':'';?>'>&nbsp;</td>
                <td title='T2' class='radiant <?=$match['tower_radiant'][14]?'tower_radiant':'';?>'>&nbsp;</td>
                <td title='T1' class='radiant <?=$match['tower_radiant'][15]?'tower_radiant':'';?>'>&nbsp;</td>
                <td class='river'>&nbsp;</td>
                <td title='T1' class='dire <?=$match['tower_dire'][15]?'tower_dire':'';?>'>&nbsp;</td>
                <td title='T2' class='dire <?=$match['tower_dire'][14]?'tower_dire':'';?>'>&nbsp;</td>
                <td title='T3' class='dire <?=$match['tower_dire'][13]?'tower_dire':'';?>'>&nbsp;</td>
                <td class='dire'>
                    <div title='Барак' class='dire <?=$match['barak_dire'][7]?'barak_dire':'';?>'>&nbsp;</div>
                    <div title='Барак' class='dire <?=$match['barak_dire'][6]?'barak_dire':'';?>'>&nbsp;</div>
                </td>
                <td title='T4' class='dire <?=$match['tower_dire'][5]?'tower_dire':'';?>'>&nbsp;</td>
                <td class='dire'>&nbsp;</td>
            </tr>
            <tr>
                <td  title='Трон' class='radiant <?=$match['radiant_win']?'tron_radiant':'';?>'>&nbsp;</td>
                <td class='radiant'>&nbsp;</td>
                <td class='radiant'>
                    <div title='Барак' class='radiant <?=$match['barak_radiant'][5]?'barak_radiant':'';?>'>&nbsp;</div>
                    <div title='Барак' class='radiant <?=$match['barak_radiant'][4]?'barak_radiant':'';?>'>&nbsp;</div>
                </td>
                <td title='T3' class='radiant <?=$match['tower_radiant'][10]?'tower_radiant':'';?>'>&nbsp;</td>
                <td title='T2' class='radiant <?=$match['tower_radiant'][11]?'tower_radiant':'';?>'>&nbsp;</td>
                <td title='T1' class='radiant <?=$match['tower_radiant'][12]?'tower_radiant':'';?>'>&nbsp;</td>
                <td class='river'>&nbsp;</td>
                <td title='T1' class='dire <?=$match['tower_dire'][12]?'tower_dire':'';?>'>&nbsp;</td>
                <td title='T2'class='dire <?=$match['tower_dire'][11]?'tower_dire':'';?>'>&nbsp;</td>
                <td title='T3' class='dire <?=$match['tower_dire'][10]?'tower_dire':'';?>'>&nbsp;</td>
                <td class='dire'>
                    <div title='Барак' class='dire <?=$match['barak_dire'][5]?'barak_dire':'';?>'>&nbsp;</div>
                    <div title='Барак' class='dire <?=$match['barak_dire'][4]?'barak_dire':'';?>'>&nbsp;</div>
                </td>
                <td class='dire'>&nbsp;</td>
                <td title='Трон' class='dire <?=$match['radiant_win']?'':'tron_dire';?>'>&nbsp;</td>
            </tr>
            <tr>
                <td class='radiant'>&nbsp;</td>
                <td title='T4' class='radiant <?=$match['tower_radiant'][6]?'tower_radiant':'';?>'>&nbsp;</td>
                <td class='radiant'>
                    <div title='Барак' class='radiant <?=$match['barak_radiant'][3]?'barak_radiant':'';?>'>&nbsp;</div>
                    <div title='Барак' class='radiant <?=$match['barak_radiant'][2]?'barak_radiant':'';?>'>&nbsp;</div>
                </td>
                <td title='T3' class='radiant <?=$match['tower_radiant'][7]?'tower_radiant':'';?>'>&nbsp;</td>
                <td title='T2' class='radiant <?=$match['tower_radiant'][8]?'tower_radiant':'';?>'>&nbsp;</td>
                <td title='T1' class='radiant <?=$match['tower_radiant'][9]?'tower_radiant':'';?>'>&nbsp;</td>
                <td class='river'>&nbsp;</td>
                <td title='T1' class='dire <?=$match['tower_dire'][9]?'tower_dire':'';?>'&nbsp;</td>
                <td title='T2' class='dire <?=$match['tower_dire'][8]?'tower_dire':'';?>'>&nbsp;</td>
                <td title='T3' class='dire <?=$match['tower_dire'][7]?'tower_dire':'';?>'>&nbsp;</td>
                <td class='dire'>
                    <div title='Барак' class='dire <?=$match['barak_dire'][3]?'barak_dire':'';?>'>&nbsp;</div>
                    <div title='Барак' class='dire <?=$match['barak_dire'][2]?'barak_dire':'';?>'>&nbsp;</div>
                </td>
                <td title='T4' class='dire <?=$match['tower_dire'][6]?'tower_dire':''?>'>&nbsp;</td>
                <td class='dire'>&nbsp;</td>
            </tr>
        </table>
    </div>

    <div>cluster: <?=$match['cluster'];?></div>
    <div>first_blood_time: <?=$match['first_blood_time'];?></div>
    <div>lobby_type: <?=$match['lobby_type'];?></div>
    <div>human_players: <?=$match['human_players'];?></div>
    <div>leagueid: <?=$match['leagueid'];?></div>
    <div>positive_votes: <?=$match['positive_votes'];?></div>
    <div>negative_votes: <?=$match['negative_votes'];?></div>
    <div>game_mode: <?=$match['game_mode'];?></div>
    <div>engine: <?=$match['engine'];?></div>
    <div>Запись: <?if($match['replay_salt']):?><a href="http://replay<?=$match['cluster'];?>.valve.net/570/<?=$match['match_id'];?>_<?=$match['replay_salt'];?>.dem.bz2">http://replay<?=$match['cluster'];?>.valve.net/570/<?=$match['match_id'];?>_<?=$match['replay_salt'];?>.dem.bz2</a>
        <?else:?>Отсутствует<?endif;?>
    </div>

    <div>
        Игроки
        <table class='match'>
            <tr>
                <th>&nbsp;</th>
                <th>Игрок</th>
                <th><span title="Уровень">Ур</span></th>
                <th><span title="Убийств">Уб</span</th>
                <th><span title="Смертей">См</span></th>
                <th><span title="Помощь">П</span></th>
                <th><span title="Золото">З</span></th>
                <th><span title="Добито крипов">Lh</span></th>
                <th><span title="Не отдал крипов">D</span></th>
                <th><span title="Золота в минуту">З/м</span></th>
                <th><span title="Опыта в минуту">О/м</span></th>
                <th><span title="Золота потрачено">ЗП</span></th>
                <th><span title="Нанесено урона вражеским героям">Ур.Г</span></th>
                <th><span title="Нанесено урона башням">Ур.Б</span></th>
                <th><span title="Исцеление героев">Исц</span></th>
                <th>Предметы</th>
            </tr>
            <?foreach($match['players'] AS $key =>$val):?>
            <tr class='<?=($val['player_slot']<128)?'radiant':'dire';?>'>
                <td>
                    <img class="hero_icon" src='/data/heroes/<?=$dota->heroes[$val['hero_id']]['title'];?>.png' alt="<?=$dota->get_hero_url($val['hero_id']);?>"/>
                         <!-- <video class="portrait" id="port<?=$val['hero_id'];?>" style="width:80px;" src="/data/portraits/webm/<?=$val['hero_id'];?>.webm" autoplay="autoplay" loop="loop"></video>
                            !-->
                </td>
                <td>
                    <?if($val['account_id']==4294967295):?>Скрыт<?else:?>
                    <a href="/profile/<?=$val['account_id'];?>">
                        <img src="<?=$val['profile']['avatar'];?>" alt="<?=$val['profile']['personaname'];?>" /><br />
                        <?=$val['profile']['personaname'];?></a>
                    <?endif;?>

                    <?=$val['leaver_status']?'<div class="leaver">Покинул игру</div>':'';?>
                </td> 
                <td><?=$val['level'];?></td>
                <td><?=$val['kills'];?></td>
                <td><?=$val['deaths'];?></td>
                <td><?=$val['assists'];?></td>
                <td><?=$val['gold'];?></td>
                <td><?=$val['last_hits'];?></td>
                <td><?=$val['denies'];?></td>
                <td><?=$val['gold_per_min'];?></td>
                <td><?=$val['xp_per_min'];?></td>
                <td><?=$val['gold_spent'];?></td>
                <td><?=$val['hero_damage'];?></td>
                <td><?=$val['tower_damage'];?></td>
                <td><?=$val['hero_healing'];?></td>
                <td class="items">
                    <?for($i=0;$i<6;$i++):
                    if(!$val['item_'.$i])continue;
                    ?>
                    <img src="/data/items/<?=$dota->items[$val['item_'.$i]]['title'];?>.png" title="<?=$dota->items[$val['item_'.$i]]['localized_name'];?>"/>
                    <?endfor;?>
                </td>
            </tr>
            <tr class='<?=($val['player_slot']<128)?'radiant':'dire';?>'><td>&nbsp;</td><td class="spells" colspan="15">
                    <?foreach($val['ability_upgrades'] AS $abUp):?>
                    <img alt="Level <?=$abUp['level'];?><><?=$spells[$abUp['ability']]['name'];?><>Time: <?=Func::sec2human($abUp['time']);?>" src="/data/spellicons/<?=$spells[$abUp['ability']]['icon'];?>.png" />
                    <?endforeach;?>
                </td></tr>
            <?endforeach;?>
        </table>
    </div>

</div>
