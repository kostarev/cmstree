<div>
<p><img src="/avatar/<?=$user['id'];?>/big" alt="<?=$user['login'];?>"/></p>
<p>Логин: <b><?=$user['login'];?></b></p>
<p>Группа: <b><?=$user['group_title'];?></b> <?if(SiteRead::me()->is_access('change-group')):?> <a title="Изменить группу" href="<?=H;?>/panel/users/change_group/<?=$user['id'];?>">&gt;&gt;</a><?endif;?></p>
<p>Зарегистрирован: <?=date('H:i <b>d.m.Y</b>', $user['reg_time']);?></p>
<p>Последняя активность: <?=date('H:i <b>d.m.Y</b>', $user['last_time']);?></p>
</div>