<?php

Class Captcha {

    public function __construct() {
        require(D . '/open/kcaptcha/kcaptcha.php');
    }

    public function url() {
        return H . '/open/kcaptcha/?' . session_name() . '=' . session_id();
    }
    
     public function html() {
        return '<img id="captcha" src="'.$this->url().'" alt="Нажмите для обновления картинки" onClick="document.getElementById(\'captcha\').src=\''.$this->url() . '&\' + Math.random();"/>';
    }

    public function is_access($keystring) {
        $return = (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $keystring);
        unset($_SESSION['captcha_keystring']);
        return $return;
    }

}

?>
