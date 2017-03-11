<?php

Class SiteRead Extends CMS_System {
    
    //singleton
    static protected $instance = null;
    //Метод предоставляет доступ к объекту
    static public function me(){
        if (is_null(self::$instance))
            self::$instance = new SiteRead();
        return self::$instance;
    }
    
    protected function __construct() {
        parent::__construct();
    }
    //------------------------

    //Проверка доступа группе--
    function is_group_access($group, $action) {
        if ($group == 'root') {
            return true;
        }
        return isset($this->groups[$group]['actions_arr'][$action]);
    }

    //------------------------
    //Проверка доступа группе--
    function group_access($group, $action) {
        if (!$this->is_group_access($group, $action)) {
            $this->error('Доступ закрыт');
        }
        return true;
    }

    //------------------------
    //Возвращает true или false
    function is_access($action) {
        return $this->is_group_access($this->user['group'], $action);
    }

    //Выбивает ошибку, если доступ запрещён
    function access($action) {
        if (!$this->is_access($action)) {
            $this->error('Доступ закрыт');
        }
        return true;
    }

    //Вывод текста с обработкой смайлов и бб кодов
    function out($text) {
        $text = Func::links($text);
        $text = $this->registry['bb']->out($text);


        if (strstr($text, '[nosmile]')) {
            $text = str_replace('[nosmile]', '', $text);
        } else {
            $text = $this->registry['smiles']->out($text);
        }

        $text = str_replace("<br />", "\n", $text);
        $text = nl2br($text);
        $text = preg_replace('#[\r\n]#', '', $text);

        return $text;
    }
    
    //Получение настроек сайта, которые не загрузились автоматически
    function getConfig($mother){
        $res = $this->db->prepare("SELECT name, value FROM config WHERE mother = ?;");
        $res->execute(Array($mother));
        while($row=$res->fetch()){
            $this->conf[$mother][$row['name']] = $row['value'];
        }
    }

}

?>
