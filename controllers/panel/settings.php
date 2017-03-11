<?php

Class Controller_Settings Extends Controller_Base {

    public function __construct($args) {
        parent::__construct($args);
        SiteRead::me()->access('panel-settings');
    }

    function index() {
        
        
        //Считывание настроек из базы----------------------
        $raw_config = Array();
        $config = Array();
        $conf_array = Array();
        $conf_dirs = Array(0);

        if ($row = $this->db->get("SELECT * FROM config ORDER BY mother;", 60)) {
            foreach ($row AS $val) {
                if ($val['value'] == 'directory' AND $val['mother'] == '0') {
                    $conf_dirs[$val['name']] = $val;
                } else {
                    $raw_config[] = $val;
                }
            }
        }


        foreach ($raw_config AS $val) {
            if (isset($conf_dirs[$val['mother']]) AND $val['mother']) {
                $config[$val['mother']][$val['name']] = $val['value'];
                $conf_array[$val['mother']][$val['name']] = $val;
            } else {
                $config[$val['name']] = $val['value'];
                $conf_array[$val['name']] = $val;
            }
        } 
        
        $title = '';
        $settings_dir = isset($this->args[0]) ? $this->args[0] : false;
        $title_html = ' - Настройки';
        $configs = Array();
        if ($settings_dir) {
            if (!isset($conf_array[$settings_dir])) {
                $this->error('Раздел настроек ' . $settings_dir . ' не найден.');
            } elseif($conf_dirs[$settings_dir]['group'] AND $conf_dirs[$settings_dir]['group']<>$this->user['group'] AND $this->user['group']<>'root'){
                $this->error('У вас нет доступа к редактированию этих настроек.');
            }else{
                $title = ' - ' . $conf_dirs[$settings_dir]['title'];
                $title_html = ' - <a href="' . H . '/panel/settings">Настройки</a>' . $title;
                $configs = $conf_array[$settings_dir];
            }

            //Обработка формы-----------
            if (isset($_POST['save'])) {

                foreach ($configs AS $key => $val) {
                    if (!isset($_POST['conf'][$key])) {
                        $configs[$key]['value'] = 0;
                    } else {
                        $configs[$key]['value'] = $_POST['conf'][$key];
                    }

                    if ($val['type'] == 'int') {
                        $configs[$key]['value'] = (real) $configs[$key]['value'];
                    }

                    SiteWrite::me()->save_conf($settings_dir, $key, $configs[$key]['value']);
                }

                $this->loc(H . '/panel/settings/' . $settings_dir);
            }
            //--------------------------
        }

        $this->des->set('conf_dirs', $conf_dirs);
        $this->des->set('settings_dir', $settings_dir);
        $this->des->set('configs', $configs);
        $this->des->set('title', 'Панель - Настройки' . $title);
        $this->des->set('title_html', '<a href="' . H . '/panel">Панель</a>' . $title_html);
        $this->des->set('myvar', 'Моя переменная');
        $this->des->display('panel/settings');
    }

    function db_log_off() {
        if ($this->user['group'] <> 'root') {
            $this->error('Доступ закрыт');
        }
        SiteWrite::me()->save_conf('developer', 'sql_table', 0);
        $this->loc($this->back_url);
    }

    function memcache_table_off() {
        if ($this->user['group'] <> 'root') {
            $this->error('Доступ закрыт');
        }
        SiteWrite::me()->save_conf('developer', 'memcache_table', 0);
        $this->loc($this->back_url);
    }

    function del_memcached() {

        if ($this->user['group'] <> 'root') {
            $this->error('Доступ закрыт');
        }

        if (!isset($this->args[0])) {
            $this->error('Не верная ссылка');
        }
        if ($this->cache) {
            $this->cache->delete($this->args[0]);
        }
        $this->loc($this->back_url);
    }

}

?>