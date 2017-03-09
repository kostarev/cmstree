<div>
    Ваш ID: <b><?=$this->user['id'];?></b><br />
    Ваш API key: <b><?=$api_key;?></b>
    <?if($sites):?>
    <?if(isset($_GET['del'])):?>
    <div class="predel">
        Вы действительно хотите удалить этот сайт из системы?
        <form method="post" action="#">
            <input type="hidden" name="del_site" value="<?=$_GET['del'];?>" /><input type="submit" value="Да" class="red"/> 
            <a href="?">Нет</a>
        </form>
    </div>
    <?endif;?>
    <div class="block">
        Ваши сайты:
        <?foreach($sites AS $val):?>
        <div>id: <?=$val['id'];?> <a href="/kabinet/sites/<?=$val['id'];?>"><?=$val['url'];?></a> [<a class="red" title="удалить" href="?del=<?=$val['id'];?>">x</a>]</div>
        <?endforeach;?>
    </div>
    <?endif;?>

    <div class="block">
        <?if(isset($_GET['add_site'])):?>
        <a href="?" >Отмена</a><br />
        Перед добавлением сайта в систему, установите на сайт код<br />
        <b>&lt;!--<?=$user_key;?>--&gt;</b>
        <form method="post" action="#">
            <p><input type="text" name="url" placeholder="URL сайта" /> 
                <input type="submit" value="Добавить" /></p> 
        </form>
        <?else:?>
        <a href="?add_site" >Добавить сайт</a>
        <?endif;?>
    </div>


    <?if(isset($mysite)):?>
    <div class="block">
        <h3>Чёрный список сайта <?=$mysite['url'];?></h3>

        <div class="block big_form">
            <?if(isset($_GET['add'])):?>
            <a href="?" name="add_kid">Отмена</a>
            <form method="post" action="#">
                <p><small>Wmid</small><br /><textarea name="wmid" placeholder="Все wmid нарушителя с новой строки" ></textarea></p>
                <p><small>Email</small><br /><textarea name="email" placeholder="Все Email нарушителя с новой строки" ></textarea></p>
                <p><small>Skype</small><br /><textarea name="skype" placeholder="Все Skype нарушителя с новой строки" ></textarea></p>
                <p><small>ICQ</small><br /><textarea name="icq" placeholder="Все ICQ нарушителя с новой строки" ></textarea></p>
                <p><small>Имена</small><br /><textarea name="name" placeholder="Все имена нарушителя с новой строки" ></textarea></p>
                <p><small>Сайты</small><br /><textarea name="sites" placeholder="Все сайты нарушителя с новой строки" ></textarea></p>
                <p><small>Характеристика</small><br /><textarea name="descr" placeholder="Характеристика нарушителя" ></textarea></p>
                <p><input type="submit" value="Добавить" /></p>
            </form>
            <?else:?>
            <a href="?add#add_kid" >Добавить запись</a>
            <?endif;?>
        </div>


        <?if($kidals):?>
        <?if(isset($_GET['del_kidala'])):?>
        <div class="predel">
            Вы действительно хотите удалить запись из базы?
            <form method="post" action="#">
                <input type="hidden" name="del_kidala" value="<?=$_GET['del_kidala'];?>" /><input type="submit" value="Да" class="red"/> 
                <a href="?">Нет</a>
            </form>
        </div>
        <?elseif(isset($_GET['hide'])):?>
         <div class="predel">
            Вы действительно хотите скрыть запись? Запись перестанет отображаться в результатах запроса.
            <form method="post" action="#">
                <input type="hidden" name="hide" value="<?=$_GET['hide'];?>" /><input type="submit" value="Да" class="red"/> 
                <a href="?">Нет</a>
            </form>
        </div>
         <?elseif(isset($_GET['show'])):?>
         <div class="predel">
            Вы действительно хотите открыть запись?
            <form method="post" action="#">
                <input type="hidden" name="show" value="<?=$_GET['show'];?>" /><input type="submit" value="Да" class="red"/> 
                <a href="?">Нет</a>
            </form>
        </div>
        <?endif;?>
        <?foreach($kidals AS $kidala):?>
        <div class="block">
            <?if(isset($_GET['merge']) AND $_GET['merge']<>$kidala['id']):?>
            <form method="post" action="#">
                <input type="hidden" name="merge" value="<?=$kidala['id'];?>" />
                <input type="submit" value="Объединение" />
            </form>
            <?endif;?>
            <p><a href="?edit=<?=$kidala['id'];?>#edit">Редактировать</a> 
                <a href="?merge=<?=$kidala['id'];?>">Объединить</a> 
                <?if($kidala['hidden']):?>
                <a href="?show=<?=$kidala['id'];?>">Открыть</a> 
                <?else:?>
                <a href="?hide=<?=$kidala['id'];?>" class="red">Скрыть</a> 
                <?endif;?>
                <a href="?del_kidala=<?=$kidala['id'];?>" class="red">Удалить</a> 
            </p>
            <?if($kidala['wmid']):?><p><b>Wmid:</b>
                <?foreach($kidala['wmid'] AS $wmid):?>
                <a target="_block" href="http://passport.webmoney.ru/asp/certview.asp?wmid=<?=$wmid;?>"><?=$wmid;?></a>,  
                <?endforeach;?>
            </p>
            <?endif;?>

            <?if($kidala['email']):?><p><b>Email:</b>
                <?=implode(', ',$kidala['email']);?>
            </p>
            <?endif;?>

            <?if($kidala['skype']):?><p><b>Skype:</b>
                <?=implode(', ',$kidala['skype']);?>
            </p>
            <?endif;?>

            <?if($kidala['icq']):?><p><b>ICQ:</b>
                <?=implode(', ',$kidala['icq']);?>
            </p>
            <?endif;?>

            <?if($kidala['name']):?><p><b>Имя:</b>
                <?=implode(', ',$kidala['name']);?>
            </p>
            <?endif;?>

            <?if($kidala['sites']):?><p><b>Сайты:</b>
                <?=implode(', ',$kidala['sites']);?>
            </p>
            <?endif;?>

            <?if($kidala['descr']):?><p><b>Характеристика:</b>
                <?=nl2br($kidala['descr']);?>
            </p>
            <?endif;?>
        </div>
        <?endforeach;?>

        <?if(isset($_GET['edit']) AND isset($kidals[$_GET['edit']])): $kidala = $kidals[$_GET['edit']];?>
        <a name="edit"></a>
        <div class="big_form">
            <a href="?" >Отмена</a>
            <form method="post" action="#">
                <p><small>Wmid</small><br /><textarea name="wmid" placeholder="Все wmid нарушителя с новой строки" ><?=implode("\n",$kidala['wmid']);?></textarea></p>
                <p><small>Email</small><br /><textarea name="email" placeholder="Все Email нарушителя с новой строки" ><?=implode("\n",$kidala['email']);?></textarea></p>
                <p><small>Skype</small><br /><textarea name="skype" placeholder="Все Skype нарушителя с новой строки" ><?=implode("\n",$kidala['skype']);?></textarea></p>
                <p><small>ICQ</small><br /><textarea name="icq" placeholder="Все ICQ нарушителя с новой строки" ><?=implode("\n",$kidala['icq']);?></textarea></p>
                <p><small>Имена</small><br /><textarea name="name" placeholder="Все имена нарушителя с новой строки" ><?=implode("\n",$kidala['name']);?></textarea></p>
                <p><small>Сайты</small><br /><textarea name="sites" placeholder="Все сайты нарушителя с новой строки" ><?=implode("\n",$kidala['sites']);?></textarea></p>
                <p><small>Характеристика</small><br /><textarea name="descr" placeholder="Характеристика нарушителя" ><?=$kidala['descr'];?></textarea></p>
                <p><input type="submit" value="Сохранить" /></p>
            </form>
        </div>
        <?endif;?>

    </div>
    <?else:?>
    нет записей
    <?endif;?>  

    <?endif;?>
</div>