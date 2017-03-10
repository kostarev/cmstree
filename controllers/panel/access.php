<?php

Class Controller_access Extends Controller_Base {
    
    public function __construct($args) {
        parent::__construct($args);
        
        if($this->user['group']<>'root'){
            $this->error('Ошибка доступа');
        }
    }

    public function index() {

        //Сохранение данных-------
        if (isset($_POST['save'])) {

            foreach ($this->groups AS $gr) {
                foreach ($this->actions AS $act) {
                    
                    try{
                        SiteWrite::me()->save_access($gr['name'], $act['name'], isset($_POST[$gr['name']][$act['name']]));
                    }  catch (Exception $ex){$this->error($ex->getMessage());}
                    
                }
            }
            $this->loc(H.'/panel/access');
        }
        //Добавление действия----
        if(isset($_POST['add'])){
            try{
                SiteWrite::me()->action_add($_POST['action_name'],$_POST['action_title']);
            } catch (Exception $ex) {
            $this->error($ex->getMessage());
            }
            $this->loc(H.'/panel/access');
        }
        //Удаление действия
        if(isset($_GET['del']) AND isset($_POST['confirm'])){
            try{
                SiteWrite::me()->action_del($_GET['del']);
            } catch (Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->loc(H.'/panel/access');
        }
        
        

        
        $this->des->set('title', 'Настройки доступа');
        $this->des->set('title_html', '<a href="'.H.'/panel">Панель</a> - Настройки доступа');
        $this->des->display('panel/access');
    }

}

?>
