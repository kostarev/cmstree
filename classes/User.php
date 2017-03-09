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
    
    //Авторизация по логину и паролю
    function login_pas($login, $pas) {
        $res = $this->db->prepare("SELECT pas,id FROM users WHERE login=?;");
        $res->execute(Array(trim($login)));
        if (!$row = $res->fetch()) {
            throw new Exception("Пользователь с логином $login не найден.");
        }

        $md5pas = md5('cms' . md5(trim($pas)));
        if ($md5pas <> $row['pas']) {
            throw new Exception('Не верный пароль.<br /><a href="' . H . '/login/forget">Забыли пароль?</a>');
        }

        return Array('id' => $row['id'], 'pas' => $md5pas);
    }
    //----------------------
    
    //Авторизация пользователя---
    public function auth() {
        //Уже авторизирован
        if (isset($_SESSION['user_id'])) {
            $this->arr['id'] = $_SESSION['user_id'];
            if (!$arr = $this->get_info()) {
                $this->arr['id'] = 0;
            }
        } else {
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
                        }
                    }
                }
            }
        }
        //Обновляем время последней активности
        $this->update_last_time();
    }

    //Обновление последнего времени посещения
    function update_last_time() {
        //Обновляем время не чаще 1 раза в минуту
        if ((isset($_SESSION['user_last_time']) AND $_SESSION['user_last_time'] < (TIME - 60)) OR empty($_SESSION['user_last_time'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $ua = (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlentities($_SERVER['HTTP_USER_AGENT']) : 'Нет';
            if (!empty($this->arr['id'])) {//Для авторизированных
                if (!empty($_SESSION['user_last_time'])) {
                    $online_plus = TIME - $_SESSION['user_last_time'];
                    if ($online_plus > 600) {
                        $online_plus = 0;
                    }
                } else {
                    $online_plus = 0;
                }
                $res = $this->db->prepare("UPDATE users SET last_time=?, ip=?, ua=?, online_time = online_time + ? WHERE id=?;");
                $res->execute(Array(TIME, $ip, $ua, $online_plus, $this->arr['id']));
            } else {                //Для гостей
                $res = $this->db->prepare("SELECT time FROM guests WHERE `ip`=? AND `ua`=?;");
                $res->execute(Array($ip, $ua));
                if ($row = $res->fetch()) {  //Продливаем время гостя
                    $res = $this->db->prepare("UPDATE guests SET `time`=? WHERE `ip`=? AND `ua`=?;");
                    $res->execute(Array(TIME, $ip, $ua));
                } else {                    //новый гость
                    $ref = (!empty($_SERVER['HTTP_REFERER'])) ? htmlentities($_SERVER['HTTP_REFERER']) : '';
                    $res = $this->db->prepare("INSERT INTO guests (`ip`,`ua`,`time`,`ref`) VALUES (?,?,?,?);");
                    $res->execute(Array($ip, $ua, TIME, $ref));
                }
            }
            //Удаляем гостей, не заходивших 10 минут
            $this->db->query("DELETE FROM guests WHERE time < UNIX_TIMESTAMP() - 600;");
            $_SESSION['user_last_time'] = TIME;
        }
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
