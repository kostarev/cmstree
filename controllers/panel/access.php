<?php

Class Controller_access Extends Controller_Base {

    public function __construct($args) {
        parent::__construct($args);

        if ($this->user['group'] <> 'root') {
            $this->error('Ошибка доступа');
        }
    }

    public function index() {

        //Сохранение данных-------
        if (isset($_POST['save'])) {

            foreach ($this->groups AS $gr) {
                foreach ($this->actions AS $act) {

                    try {
                        SiteWrite::me()->save_access($gr['name'], $act['name'], isset($_POST[$gr['name']][$act['name']]));
                    } catch (Exception $ex) {
                        $this->error($ex->getMessage());
                    }
                }
            }
            $this->loc(H . '/panel/access');
        }
        //Добавление действия----
        if (isset($_POST['add_action'])) {
            try {
                SiteWrite::me()->action_add($_POST['action_name'], $_POST['action_title']);
            } catch (Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->loc(H . '/panel/access');
        }
        //Удаление действия
        if (isset($_GET['del']) AND isset($_POST['confirm']) AND $_GET['del'] > 4) {
            try {
                SiteWrite::me()->action_del($_GET['del']);
            } catch (Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->loc(H . '/panel/access');
        }
        //Сохранение действия
         if(isset($_GET['edit_action']) AND isset($_POST['save_action'])){
            try {
                SiteWrite::me()->action_save($_GET['edit_action'], $_POST['action_name'], $_POST['action_title']);
            } catch (Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->loc(H . '/panel/access');
        }
        //Добавление группы пользователей
        if (isset($_POST['add_group'])) {
            try {
                SiteWrite::me()->group_add($_POST['group_name'], $_POST['group_title']);
            } catch (Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->loc(H . '/panel/access');
        }
        //Удаление группы пользователей
        if (isset($_GET['del_group']) AND isset($_POST['confirm'])) {
            try {
                SiteWrite::me()->group_del($_GET['del_group']);
            } catch (Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->loc(H . '/panel/access');
        }
        //Сохранение группы пользователей
        if(isset($_GET['edit_group']) AND isset($_POST['save_group'])){
            try {
                SiteWrite::me()->group_save($_GET['edit_group'], $_POST['group_name'], $_POST['group_title']);
            } catch (Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->loc(H . '/panel/access');
        }



        $this->des->set('title', 'Настройки доступа');
        $this->des->set('title_html', Menu::me()->navigation_html('access', Array('<a href="{url}">{title}</a> - ', '{title}')));
        $this->des->display('panel/access');
    }

}

?>
