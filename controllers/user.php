<?php

Class Controller_user Extends Controller_Base {

    function index() {

        if (!isset($this->args[0])) {
            $this->error('Не верная ссылка');
        }

        $id = (int) $this->args[0];
        $Ank = new User($id);

        if (!$info = $Ank->get_info()) {
            $this->error('Пользователь не найден в базе данных.');
        }

        //Редактирование профиля
        if ($info['id'] == $this->user['id'] AND isset($_GET['change']) AND isset($_POST['save'])) {
            $arr['name'] = Func::filtr($_POST['name']);
            if ($arr['name'] <> $this->user['name']) {
                if (mb_strlen($arr['name'], 'UTF-8') < 3 OR mb_strlen($arr['name'], 'UTF-8') > 15) {
                    error('<span class="red">Длина имени от 3х до 15и символов.</span>');
                }
                //Проверяем не занято ли имя
                if (Reg::me()->nameIsFree($arr['name'])) {
                    try {
                        $this->user->save($arr);
                        $this->loc('/user/' . $id);
                    } catch (Exception $ex) {
                        $this->error($ex->getMessage());
                    }
                } else {
                    $this->error('Имя ' . $arr['name'] . ' занято!');
                }
            }
        }

        //Ajax Проверка занятости имени
        if (isset($_POST['check_name'])) {
            $this->des->auto_head = false;
            //Длина имени
            if (mb_strlen($_POST['check_name'], 'UTF-8') < 3 OR mb_strlen($_POST['check_name'], 'UTF-8') > 15) {
                $str = '<span class="red">Длина имени от 3х до 15и символов.</span>';
            } else {
                if (Reg::me()->nameIsFree($_POST['check_name'])) {
                    $str = '<span class="green">Имя свободно</span>';
                } else {
                    $str = '<span class="red">Имя занято!</span>';
                }
            }
            echo "$('#check-name').html('$str');";
            exit;
        }

        $this->des->set('user', $info);
        $this->des->set('title', 'Анкета - ' . $info['name']);
        $this->des->display('user');
    }

}

?>