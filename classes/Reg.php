<?php
//Регистрация
Class Reg extends CMS_System {
    
    private $salt = 'cms';
    
    //singleton паттерн------
    static protected $instance = null;

    //Метод предоставляет доступ к объекту
    static public function me() {
        if (is_null(self::$instance))
            self::$instance = new Reg();
        return self::$instance;
    }

    protected function __construct() {
        parent::__construct();
    }
    
    
    //md5 password
    function md5password($pas){
        return md5($this->salt . md5($pas));
    }


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
        $md5pas = $this->md5password($pas);        

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


        $res = $this->db->prepare("INSERT INTO users (login, name, pas, email, reg_time) VALUES (?,?,?,?,UNIX_TIMESTAMP());");
        if (!$res->execute(Array($login, $login, $md5pas, $email))) {
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

        $res = $this->db->prepare("INSERT INTO users (login, name, pas, email,reg_time) VALUES (?,?,?,?,UNIX_TIMESTAMP());");
        if (!$res->execute(Array($row['login'], $row['login'], $row['pas'], $row['email']))) {
            throw new Exception($this->db->errorInfo());
        }

        $id = $this->db->lastInsertId();
        $res = $this->db->prepare("DELETE FROM tmp_users WHERE code=?;");
        $res->execute(Array($code));

        return Array('id' => $id, 'pas' => $row['pas'], 'email' => $row['email'], 'login' => $row['login']);
    }
    
    //Проверка имени на занятость
    function nameIsFree($name){
        $res = $this->db->prepare("SELECT id FROM users WHERE name=?;");
        $res->execute(Array($name));
        if($res->fetch()){
            return false;
        }else{
            return true;
        }
    }
}
