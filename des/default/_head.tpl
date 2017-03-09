<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title><?=$title?></title>
        <link rel="stylesheet" href="<?=H;?>/des/<?=$theme?>/sys.css" type="text/css" />
        <link rel="stylesheet" href="<?=H;?>/des/<?=$theme?>/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?=H;?>/des/<?=$theme?>/tooltip.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?=H;?>/des/<?=$theme?>/menu/pro_dropdown_2.css" type="text/css" />
        <?=$this->head;?>
        <script type="text/javascript" src="<?=H;?>/open/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="/open/script.js"></script>
        <script type="text/javascript" src="<?=H;?>/des/<?=$theme?>/tooltip.js"></script>
        <script type="text/javascript" src="/open/ajax.js"></script>
    </head>
    <body>


        <div id="page">
            <div id="header">
                <h1><a href="<?=H;?>"><?=$_SERVER['HTTP_HOST'];?></a></h1>
                <p><?=$title;?></p>
            </div>


            <div>
                <?if(!$this->user['id']):?>
                <?$this->display('_auth_form');?>  
                <?else:?>
                <p>
                    <img src="<?=$_SESSION['steam']['avatarmedium'];?>" alt='' style='float:left;'/>
                    <strong><?=$this->user['login'];?></strong> <br />
                    <?=$this->user['group_title'];?>  <br />
                    [<a href="<?=H;?>/login/logout" >Выход</a>]
                </p>
                <?endif;?>
            </div>


            <ul id="nav">
                <?=$menu->get_tree_html(0, 'class="top"');?>
            </ul>
            <div id="container">
                <div id="content">
                    <h2><?=$title_html?$title_html:$title;?></h2>