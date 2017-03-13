<div>
    <?if($change_group):
    if($user['group']=='root'):?>
    Группу <b>Супер админ</b> изменить нельзя
    <?else:?>

    <form method="post" action="#">
        <p>
            <select name="group">
                <?foreach($this->groups AS $gr):
                if($gr['name']=='root'){continue;}
                ?>
                <option value="<?=$gr['name'];?>" <?=($gr['name']==$user['group'])?'selected="selected"':'';?>><?=$gr['title'];?></option>
                <?endforeach;?>
            </select>
                <input type="submit" value="Сохранить" />
        </p>
    </form>
    <?endif;?>
    <?else:?>
    <?if($pages):?><div class="pages"><?=$pages;?></div><?endif;?>
    <table class="sys">
        <tr><th>ID</th><th>Логин</th><th>Имя</th><th>Группа</th></tr>
        <?foreach($users AS $val):?>
        <tr><td><?=$val['id'];?></td><td><a href="<?=H;?>/user/<?=$val['id'];?>"><?=$val['login'];?></a></td><td><?=$val['name'];?></td><td><?=$val['group_title'];?></td></tr>
        <?endforeach;?>
    </table>
    <?if($pages):?><div class="pages"><?=$pages;?></div><?endif;?>
    <?endif;?>
</div>