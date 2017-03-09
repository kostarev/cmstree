<div>
<p><img src="/avatar/<?=$user['id'];?>/big" alt="<?=$user['login'];?>"/></p>
<p>Логин: <b><?=$user['login'];?></b></p>
<p>Группа: <b><?=$user['group_title'];?></b> <?if(SiteRead::me()->is_access('change-group')):?> <a title="Изменить группу" href="<?=H;?>/panel/users/change_group/<?=$user['id'];?>">&gt;&gt;</a><?endif;?></p>
<p>Зарегистрирован: <?=$user['reg_date'];?></p>
</div>