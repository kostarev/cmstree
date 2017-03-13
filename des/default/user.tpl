<div>
<p><img src="/avatar/<?=$user['id'];?>/big" alt="<?=$user['login'];?>"/></p>
<p>Имя: <b><?=$user['name'];?></b> <?if($user['id']==$this->user['id']):?>[<a href='?change'>Редактировать</a>]<?endif;?></p>
<p>Группа: <b><?=$user['group_title'];?></b> <?if(SiteRead::me()->is_access('change-group') AND $user['id']<>$this->user['id']):?> <a title="Изменить группу" href="<?=H;?>/panel/users/change_group/<?=$user['id'];?>">&gt;&gt;</a><?endif;?></p>
<p>Зарегистрирован: <?=date('H:i <b>d.m.Y</b>', $user['reg_time']);?></p>
<p>Последняя активность: <?=date('H:i <b>d.m.Y</b>', $user['last_time']);?></p>
</div>



<?if($user['id']==$this->user['id'] AND isset($_GET['change'])):?>
<script type="text/javascript">
        jQuery(function() {
        $('#newName').change(function(){
            $.ajax({
                    type: "POST",
                    url: '#',
                    data: 'check_name='+$(this).val(),
                    dataType: "script",
                    success: function(msg){                   
                    // alert(msg);
                    }
                });
    });
});    
    </script>
<div>
    Редактировать профиль
    <form method="post" action="#">
        <p>Имя: <input id="newName" type="text" name="name" value="<?=$user['name'];?>" placeholder="Отображаемое имя"/> <span id="check-name"></span></p>
        <p><input class="button" type="submit" name="save" value="Сохранить" /></p>
    </form>    
</div>
<?endif;?>