<?php
Class DevelopFunc{

 //Вывод sql запросов в виде html кода
    function dbLog2html() {
        $html = '  
<style type="text/css">
table.sys th{background-color: #9999ff; padding:7px; text-align: center;}
table.sys td{background-color: #ccccff; padding:7px; text-align: center;}
table.sys td.left{text-align: left;}
.red{color:red;}
</style>
Data Base
<table class="sys" style="width:100%;">
<tr><th style="width:50px;">№</th><th>SQL <span style="position:absolute;right:110px;"><a title="Закрыть таблицу" href="' . H . '/panel/settings/db_log_off">[X]</a></span></th><th style="width:50px;">Time</th></tr>';
        $arr = $this->db->get_log();
        $i = 0;
        $max_time = 0;
        foreach ($arr AS $val) {
            $max_time = $val[2] > $max_time ? $val[2] : $max_time;
        }

        $hl = new highlightSQL;
        foreach ($arr AS $val) {
            $time = round($val[2], 4);
            if ($val[1] == 'query') {
                $i++;
                $sql = nl2br($hl->highlight($val[0][0]));
                $num = $i;
                $t = '<td ' . (($val[2] == $max_time) ? 'class="red"' : '') . '>' . $time . '</td>';
            } elseif ($val[1] == 'execute') {
                $i++;
                $sql = nl2br($hl->highlight($val[0]));
                $num = $i;
                $t = '<td ' . (($val[2] == $max_time) ? 'class="red"' : '') . '>' . $time . '</td>';
            } elseif ($val[1] == 'parameters') {
                $sql = implode('|', $val[0]);
                $num = '&gt;';
                $t = '<td>&nbsp;</td>';
            }
            $html.='<tr><td>' . $num . '</td><td class="left">' . $sql . '</td>' . $t . '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    //Вывод данных из Memcache
    function memCache2html() {
        $html = '';
        if (!class_exists('Memcache')) {
            return '';
        }
        $cache = new Memcache;
        $cache->connect('127.0.0.1', 11211);

        

            if ($res = $cache->getstats('items') AND !empty($res['items'])) {
                if (MEMCACHE_CRYPT) {
                    return '<b>Memcache</b>: Для отображения таблицы, отключите шифрование кэша в sys/config.php <b>MEMCACHE_CRYPT</b>';
                }
                $html.='<b>Memcache</b>. В целях безопасности включите шифрование кэша в sys/config.php <b>MEMCACHE_CRYPT</b>.
                    <style type="text/css">
table.sys th{background-color: #9999ff; padding:7px; text-align: center;}
table.sys td{background-color: #ccccff; padding:7px; text-align: center;white-space:pre}
table.sys td.left{text-align: left;}
.red{color:red;}
</style><table class="sys" style="width:100%;">
                    <tr><th style="width:50px;">№</th><th>Ключ</th><th>Значение</th><th>Время жизни</th><th><a href="' . H . '/panel/settings/memcache_table_off" title="Закрыть таблицу" class="red">[X]</a></th></tr>';

                $i = 0;
                foreach ($res['items'] AS $key => $val) {
                    $items = $cache->getStats('cachedump', $key);
                    foreach ($items AS $key => $val) {
                        $i++;
                        $live_time = $val[1] - TIME;
                        if ($live_time < 0) {
                            $live_time = 'Вечная';
                        } else {
                            $live_time.=' сек.';
                        }
                        $html.='<tr><td>' . $i . '</td><td>' . $key . '</td>
                        <td class="left">' . htmlspecialchars(print_r($cache->get($key), true)) . '</td>
                        <td>' . $live_time . '</td><td><a class="red" title="Удалить" href="' . H . '/panel/settings/del_memcached/' . $key . '">[X]</a></td></tr>';
                    }
                }

                $html.='</table>';
            }
        
        return $html;
    }
}
?>