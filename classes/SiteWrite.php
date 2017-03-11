<?php

Class SiteWrite extends CMS_System {

    //Одиночка паттерн------
    static protected $instance = null;

    //Метод предоставляет доступ к объекту
    static public function me() {
        if (is_null(self::$instance))
            self::$instance = new SiteWrite();
        return self::$instance;
    }

    protected function __construct() {
        parent::__construct();
    }

    //------------------------
    //Сохранение настроек----
    function save_conf($mother, $name, $value) {
        $res = $this->db->prepare("UPDATE config SET value=? WHERE mother=? AND name=?;");
        $res->execute(Array($value, $mother, $name));
        $this->cache->flush();
    }

    //-----------------------
    
    //Сохранение настроек доступа------
    function save_access($group, $action, $value) {
        $res = $this->db->prepare("SELECT id FROM actions WHERE name=?;");
        $res->execute(Array($action));
        if (!$action_data = $res->fetch()) {
            throw new Exception("Action $action not found in DataBase");
        }

        $res = $this->db->prepare("SELECT actions FROM groups WHERE name=?;");
        $res->execute(Array($group));
        if (!$row = $res->fetch()) {
            throw new Exception("Group $group not found in DataBase");
        }
        $act_arr = explode(',', $row['actions']);
        if (array_search($action_data['id'], $act_arr) === false) {
            if ($value) {
                $act_arr[] = $action_data['id'];
            }
        } else {
            if (!$value) {
                $id = array_search($action_data['id'], $act_arr);
                unset($act_arr[$id]);
            }
        }


        foreach ($act_arr AS $key => $val) {
            if (!$val) {
                unset($act_arr[$key]);
            }
        }


        $act_str = implode(',', $act_arr);
        $res = $this->db->prepare("UPDATE groups SET actions=? WHERE name=?;");
        $res->execute(Array($act_str, $group));

        $this->cache->flush();
    }

    //---------------------------------
    //Регистрация юзера------
    function registration($arr) {
        $login = !empty($arr['login']) ? $arr['login'] : false;
        $pas = !empty($arr['pas']) ? $arr['pas'] : false;
        $email = !empty($arr['email']) ? $arr['email'] : '';

        //Валидация данных--------
        if ($email AND ! Func::valid_email($email)) {
            throw new Exception('Не верный адре электронной почты.');
        }

        if (mb_strlen($login, 'utf-8') < 3) {
            throw new Exception('Длина логина должна быть не менее 3х символов.');
        }

        if (mb_strlen($pas, 'utf-8') < 5) {
            throw new Exception('Длина пароля должна быть не менее 5и символов.');
        }

        if (!Func::valid_login($login)) {
            throw new Exception('Запрещённые символы в поле Login! Разрешены только буквы русского и латинского алфавита и цифры.');
        }
        //-------------------------

        $res = $this->db->prepare("SELECT id FROM users WHERE login=?;");
        $res->execute(Array($login));
        if ($row = $res->fetch()) {
            throw new Exception("Пользователь с логином $login уже зарегистрирован. Выберите другой логин.");
        }

        if ($email) {
            $res = $this->db->prepare("SELECT id FROM users WHERE email=?;");
            $res->execute(Array($email));
            if ($row = $res->fetch()) {
                throw new Exception('Пользователь с таким Email уже зарегистрирован.');
            }
        }

        //Удаляем не подтверждённые аккаунты, старше суток
        $this->db->query("DELETE FROM tmp_users WHERE time<UNIX_TIMESTAMP()-3600*24;");

        $res = $this->db->prepare("SELECT login FROM tmp_users WHERE login=?;");
        $res->execute(Array($login));
        if ($row = $res->fetch()) {
            throw new Exception("Пользователь с логином $login ожидает подтверждение регистрации. Выберите другой логин.");
        }

        if ($email) {
            $res = $this->db->prepare("SELECT login FROM tmp_users WHERE email=?;");
            $res->execute(Array($email));
            if ($row = $res->fetch()) {
                throw new Exception('Пользователь с таким Email ожидает подтверждение регистрации.');
            }
        }


        //Шифруем пароль
        $md5pas = md5('cms' . md5($pas));

        //Если требуется подтверждение $email--
        if ($this->conf['reg']['email_must']) {
            if (!$email) {
                throw new Exception('Необходим Email');
            } else {

                $code = Func::rand_string(10);
                $res = $this->db->prepare("INSERT INTO tmp_users (login,pas,email,code,time) VALUES (?,?,?,?,UNIX_TIMESTAMP());");
                if (!$res->execute(Array($login, $md5pas, $email, $code))) {
                    throw new Exception($this->db->errorInfo());
                }

                //Высылаем код для подтверждения email
                $from_name = 'Администрация ' . $_SERVER['HTTP_HOST'];
                $from_email = 'admin@' . $_SERVER['HTTP_HOST'];
                $mail_subject = 'Подтверждение регистрации';
                $mail_text = 'Для подтверждения регистрации на сайте ' . H . ' перейдите по следующей ссылке:' . "\n" . H . '/login/email_confirm/' . $code;
                Func::send_mail($from_name, $from_email, $login, $email, $mail_subject, $mail_text);

                return Array('pas' => $md5pas, 'email' => $email, 'login' => $login);
            }
        }
        //-------------------------------------


        $res = $this->db->prepare("INSERT INTO users (login, pas, email,reg_time) VALUES (?,?,?,UNIX_TIMESTAMP());");
        if (!$res->execute(Array($login, $md5pas, $email))) {
            throw new Exception($this->db->errorInfo());
        }

        $id = $this->db->lastInsertId();
        return Array('id' => $id, 'pas' => $md5pas, 'email' => $email, 'login' => $login);
    }


    function email_confirm($code) {
        $res = $this->db->prepare("SELECT * FROM tmp_users WHERE code=?;");
        $res->execute(Array($code));
        if (!$row = $res->fetch()) {
            throw new Exception('Не верная ссылка, возможно она устарела. Пройдите регистрацию ещё раз.');
        }

        $res = $this->db->prepare("INSERT INTO users (login, pas, email,reg_time) VALUES (?,?,?,UNIX_TIMESTAMP());");
        if (!$res->execute(Array($row['login'], $row['pas'], $row['email']))) {
            throw new Exception($this->db->errorInfo());
        }

        $id = $this->db->lastInsertId();
        $res = $this->db->prepare("DELETE FROM tmp_users WHERE code=?;");
        $res->execute(Array($code));

        return Array('id' => $id, 'pas' => $row['pas'], 'email' => $row['email'], 'login' => $row['login']);
    }
    
    //Создание группы пользователей
    function group_add($name, $title){
        $name = strtolower(trim($name));
        $title = Func::filtr($title);
        
        if(empty($name)){
            throw new Exception('Необходимо задать имя (name) группы (используется в скриптах)');
        }
        if(empty($title)){
            throw new Exception('Необходимо задать название (title) группы');
        }
        
        $res = $this->db->prepare("SELECT * FROM groups WHERE name = ?;");
        $res->execute(Array($name));
        if($res->fetch())
        {
            throw new Exception('Группа '. $name . ' уже существует!');
        }else{
            $res = $this->db->prepare("INSERT INTO groups (name, title) VALUES (?, ?);");
            $res->execute(Array($name, $title));
        }
    }
    //Редактирование группы пользователей
    function group_save($group, $name, $title){
        $group = strtolower(trim($group));
        $name = strtolower(trim($name));
        $title = Func::filtr($title);
         if(empty($name)){
            throw new Exception('Необходимо задать имя (name) группы (используется в скриптах)');
        }
        if(empty($title)){
            throw new Exception('Необходимо задать название (title) группы');
        }
        
        if($group == 'root' OR $group == 'user'){
            $res = $this->db->prepare("UPDATE groups SET title =? WHERE name = ?;");
            $res->execute(Array($title, $group));
        }else{
            $res = $this->db->prepare("UPDATE groups SET name=?, title =? WHERE name = ?;");
            $res->execute(Array($name, $title, $group));
            $res = $this->db->prepare("UPDATE users SET `group` = ? WHERE `group` = ?;");
            $res->execute(Array($name, $group));
        }
    }
    
    //Удаление группы пользователей
    function group_del($group){
        $group = strtolower(trim($group));
        
        if($group == 'root'){
            throw new Exception('Нельзя удалить пользователя root');
        }
        if($group == 'user'){
            throw new Exception('Нельзя удалить пользователя user');
        }
        //Меняем всех пользователей с этой группой на user
        $res = $this->db->prepare("UPDATE users SET `group` = 'user' WHERE `group` = ?;");
        $res->execute(Array($group));
        //Удаляем группу
        $res = $this->db->prepare("DELETE FROM groups WHERE name = ?;");
        $res->execute(Array($group));
    }

    //Смена группы пользователя
    function change_user_group($user, $group) {
        $Ank = new User($user);
        if (!$info = $Ank->get_info()) {
            throw new Exception('Пользователь не найден');
        }

        if ($info['group'] == 'root') {
            throw new Exception('Права Супер админа изменить нельзя.');
        }
        if ($group == 'root') {
            throw new Exception('Права Супер админа дать нельзя');
        }
        if (!isset($this->groups[$group])) {
            throw new Exception('Такой группы не существует.');
        }

        $res = $this->db->prepare("UPDATE users SET `group`=? WHERE id=?;");
        $res->execute(Array($group, $user));

        return true;
    }

    //-----------------------
    //Рекурсивное удаление папки
    function dirDel($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        if ($objs = glob($dir . "/*")) {
            foreach ($objs AS $obj) {
                is_dir($obj) ? $this->dirDel($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    //--------------------------
    //Добавление действия
    function action_add($name, $title) {
        $name = Func::filtr(strtolower(trim($name)));
        $title = Func::filtr($title);
        
         if(empty($name)){
            throw new Exception('Необходимо задать имя (name) действия (используется в скриптах)');
        }
        if(empty($title)){
            throw new Exception('Необходимо задать название (title) действия');
        }
        
        if ($name AND $title) {
            $res = $this->db->prepare("SELECT name FROM actions WHERE name=?;");
            $res->execute(Array($name));
            if (!$res->fetch()) {
                $res = $this->db->prepare("INSERT INTO actions (name, title) VALUES (?, ?);");
                $res->execute(Array($name, $title));
                $this->cache->flush();
            }
        }
    }

    //Удаление действия
    function action_del($id) {
        $res = $this->db->prepare("SELECT * FROM actions WHERE id=?;");
        $res->execute(Array($id));
        if($row = $res->fetch()){
  
            $res = $this->db->query("SELECT * FROM groups;");
            $res1 = $this->db->prepare("UPDATE groups SET actions = ? WHERE name = ?;");
            while($row = $res->fetch()){
                $gr_name = $row['name'];
             $actions_arr = explode(',', $row['actions']);
             
                foreach ($actions_arr AS $key => $val) {
                   if ($id == $val) {
                       unset($actions_arr[$key]);
                   }
               }
               $action_str = implode(',', $actions_arr);               
               $res1->execute(Array($action_str, $gr_name));
            }           
            
        $res = $this->db->prepare("DELETE FROM actions WHERE id=?;");
        $res->execute(Array($id));
        $this->cache->flush();
        }
    }
    
    //Сохранение действия
    function action_save($id, $name, $title){
        $name = Func::filtr(strtolower(trim($name)));
        $title = Func::filtr($title);
        
         if(empty($name)){
            throw new Exception('Необходимо задать имя (name) действия (используется в скриптах)');
        }
        if(empty($title)){
            throw new Exception('Необходимо задать название (title) действия');
        }
        
        if($id < 5){
            $res = $this->db->prepare("UPDATE actions SET title = ? WHERE id = ?;");
            $res->execute(Array($title, $id));
        }else{
            $res = $this->db->prepare("UPDATE actions SET name=?, title = ? WHERE id = ?;");
            $res->execute(Array($name, $title, $id));
        }
    }

}

?>