<?php

Class Controller_modules Extends Controller_Base {

    public function __construct($args) {
        parent::__construct($args);

        SiteRead::me()->access('change-modules');
    }

    function index() {
        //Получаем список модулей
        $modules = Modules::me()->get_modules();

        //Удаление модуля
        if (isset($_GET['del']) AND isset($_POST['confirm'])) {
            try {
                Modules::me()->del($_GET['del']);
            } catch (Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->loc(H . '/panel/modules');
        }
        
        //Загрузка файла---
        if (isset($_FILES['file'])) {

            if ($_FILES['file']['error']) {
                $this->error('Ошибка загрузки файла');
            }

            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if ($ext <> 'smod') {
                $this->error('Допускаются только файлы с расширением <b>smod</b>');
            }

            $zip = new ZipArchive();
            if ($zip->open($_FILES['file']['tmp_name']) === true) {
                $zip->close();

                $mod_fname = Modules::me()->modules_dir . '/' . $_FILES['file']['name'];
                if (is_file($mod_fname)) {
                    $this->error('Файл с именем ' . $_FILES['file']['name'] . ' уже есть в папке модулей.');
                }
                if (!move_uploaded_file($_FILES['file']['tmp_name'], $mod_fname)) {
                    $this->error('Ошибка перемещения файла в папку модулей');
                }

                $this->loc(H . '/panel/modules');
            } else {
                $this->error('Ошибка открытия. Возможно, загруженный файл не является zip архивом.');
            }
        }
        //-----------------

        $this->des->set('modules', $modules);
        $this->des->set('installed', true);
        $this->des->set('title', 'Панель - Модули');
        $this->des->set('title_html', '<a href="' . H . '/panel">Панель</a> - Модули');
        $this->des->display('panel/modules');
    }

    //Установка модуля
    function info() {
        if (!isset($this->args[0])) {
            $this->error('Не верная ссылка');
        }
        $fname = $this->args[0];

        try {
            if (Modules::me()->install($fname)) {
                $this->loc(H . '/panel/modules/installed');
            } else {
                $this->des->set('files', Modules::me()->all_files);
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->des->set('title', $fname);
        $this->des->set('module', $fname);
        $this->des->set('info', true);
        $this->des->display('panel/modules');
    }

    function uninstall() {

        if (!isset($this->args[0])) {
            $this->error('Не верная ссылка');
        }
        $fname = $this->args[0];

        try{
            Modules::me()->uninstall($fname);
        }  catch (Exception $e){
            $this->error($e->getMessage());
        }

        $this->loc(H . '/panel/modules/installed');
    }
}
?>
