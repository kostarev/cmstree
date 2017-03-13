<?php

Class Controller_users Extends Controller_Base {
    
    public function __construct($args) {
        parent::__construct($args);
        SiteRead::me()->access('panel');
    }

    public function index() {

        $on_page = 30;
        
        $res = $this->db->query("SELECT COUNT(*) AS cnt FROM users;");
        $kolvo=($row=$res->fetch())?$row['cnt']:0;
        $page = isset($this->args[0])?(int)$this->args[0]:1;
        $arr=Func::pages_arr($kolvo,$on_page,$page);
        $max=$arr['max_page'];
        $start=$arr['start'];
        $page=$arr['page'];
        $this->des->set('pages',Func::pages($page,$max,H.'/panel/users/{page}'));
        
        $res = $this->db->query("SELECT users.id,users.login,users.name,groups.title AS group_title
            FROM users
            LEFT JOIN groups ON groups.name=users.group
            ORDER BY users.id
            LIMIT $start,$on_page;");
        
       
         $users = $res->fetchAll();
       
        
        $this->des->set('users',$users);
        $this->des->set('title', 'Панель - Пользователи');
        $this->des->set('title_html', '<a href="'.H.'/panel">Панель</a> - Пользователи');
        $this->des->display('panel/users');
    }
    
    //Смена прав пользователя
    function change_group(){
        SiteRead::me()->access('change-group');
       
        if(!isset($this->args[0])){
            $this->error('Не верная ссылка');
        }

        $user_id = (int)$this->args[0];
        if($this->user['id']==$user_id){
            $this->error('Свою группу изменить нельзя');
        }
        $Anketa = new User($user_id);
        $info = $Anketa->get_info();
        
            //Обработка формы----
            if(isset($_POST['group'])){
                try{
                    SiteWrite::me()->change_user_group($user_id, $_POST['group']);
                    $this->loc($this->back_url);
                }catch(Exception $e){$this->error($e->getMessage(), false);}
            }
            //-------------------
        
        
        $this->des->set('user',$info);
        $this->des->set('change_group',true);
        $this->des->set('title', 'Панель - Пользователи - Смена группы');
        $this->des->set('title_html', '<a href="'.H.'/panel">Панель</a> - <a href="'.H.'/panel/users">Пользователи</a> - '.$info['login']);
        $this->des->display('user');
        $this->des->display('panel/users');
    }

}

?>
