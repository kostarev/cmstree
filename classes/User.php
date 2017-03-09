<?php

Class User Extends CMS_System Implements ArrayAccess {

    protected $arr = Array();

    public function __construct($id = 0) {
        parent::__construct();

        $this->arr['id'] = $id;
        $this->arr['group'] = 'guest';
    }

    //Получаем всю инфу о пользователе
    function get_info() {
        $res = $this->db->prepare("SELECT users.*,groups.title AS group_title
                FROM users
                LEFT JOIN groups ON groups.name=users.group
                WHERE users.id=?;");
        $res->execute(Array($this->arr['id']));
        if ($row = $res->fetch()) {
            $row['reg_date'] = Func::unix2human($row['reg_time'], 'H:i <b>d.m.Y</b>');
            foreach ($row AS $key => $val) {
                $this->arr[$key] = $val;
            }
            return $row;
        } else {
            return false;
        }
    }

    //Возвращает массив данных юзера
    function arr() {
        return $this->arr;
    }

    //Авторизация пользователя---
    public function auth() {

        //Уже авторизирован
        if (isset($_SESSION['user_id'])) {
            $this->arr['id'] = $_SESSION['user_id'];
            if (!$arr = $this->get_info()) {
                $this->arr['id'] = 0;
                return false;
            } else {
                return true;
            }
        }

        //Авторизация по кукам----
        if (isset($_COOKIE['id']) AND isset($_COOKIE['p'])) {

            $res = $this->db->prepare("SELECT pas FROM users WHERE id=?;");
            $res->execute(Array($_COOKIE['id']));
            if ($row = $res->fetch()) {
                //Пароли совпадают, авторизируем
                if ($_COOKIE['p'] == $row['pas']) {
                    $_SESSION['user_id'] = $_COOKIE['id'];
                    $this->arr['id'] = $_SESSION['user_id'];
                    if (!$arr = $this->get_info()) {
                        $this->arr['id'] = 0;
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        //------------------------
    }

    //---------------------------
    //Информация о пользователе----
    function get($param) {
        if (!$this->arr['id']) {
            return false;
        }

        $res = $this->db->prepare("SELECT ? AS param FROM users WHERE id=?;");
        $res->execute(Array($param, $this->arr['id']));
        if (!$row = $res->fetch()) {
            return false;
        }

        return $row['param'];
    }

    public function offsetExists($offset) {
        return isset($this->arr[$offset]);
    }

    public function offsetGet($offset) {
        return $this->arr[$offset];
    }

    public function offsetSet($offset, $value) {
        $this->arr[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->arr[$offset]);
    }

    //-----------------------------
}

?>
