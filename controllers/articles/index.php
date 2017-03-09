<?php
Class Controller_Index Extends Controller_Base {

    function index() {
        
            $this->des->set('title', 'Статьи'); 
        
            $this->des->display('index');
            
            echo '<pre>';
            print_r($_SESSION);
            echo '</pre>';
            
        }
}
?>