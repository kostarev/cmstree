<div>
    <div>
        <form method="post" action="#">
            <table class="sys">
                <tr><th>Доступ\Группа</th>
                    <?foreach($this->groups AS $val):
                    if($val['name']=='root'){continue;}?>
                    <th><?=$val['title']?> (<?=$val['name']?>) <a title='Редактировать' href="?edit_group=<?=$val['name'];?>">[Р]</a><?if($val['name']<>'root' AND $val['name']<>'user'):?> <a title="Удалить" class="red" href='?del_group=<?=$val['name'];?>'>[X]</a><?endif;?></th>
                    <?endforeach;?>
                </tr>

                <?foreach($this->actions AS $act):?>
                <tr>
                    <td><?=$act['title'];?> <b>(<?=$act['name'];?>)</b> <a title='Редактировать' href="?edit_action=<?=$act['id'];?>">[Р]</a> <?if($act['id'] > 4):?> <a title="Удалить" class="red" href="?del=<?=$act['id']?>">[X]</a><?endif;?></td>
                    <?foreach($this->groups AS $gr):
                    if($gr['name']=='root'){continue;}?>
                    <td><input type="checkbox" name="<?=$gr['name'];?>[<?=$act['name'];?>]" <?=isset($gr['actions_arr'][$act['name']])?'checked="checked"':'';?> value="1" /></td>
                    <?endforeach;?>
                </tr>
                <?endforeach;?>
            </table>
            <p><input type="submit" name="save" value="Сохранить" /></p>
        </form>

        <?if(isset($_GET['del'])){
        $this->confirmForm('Вы действительно хотите удалить этот параметр доступа?', '?');
        }elseif(isset($_GET['del_group'])){
        $this->confirmForm('Вы действительно хотите удалить эту группу пользователей?', '?');       
        }
        ?>         

    </div>

    <?if(isset($_GET['edit_group'])):?>
    <div style="margin:20px;">Изменение группы пользователей
        <form method="post" action="#">
            <input type="text" name="group_name" placeholder="name" required="required" value="<?=$this->groups[$_GET['edit_group']]['name'];?>"/>
            <input type="text" name="group_title" placeholder="title"  required="required" value="<?=$this->groups[$_GET['edit_group']]['title'];?>"/>
            <input type="submit" name="save_group" value="Сохранить" />
            <a href="?" class="button"><span>Отмена</span></a>
        </form>
    </div>
    <?elseif(isset($_GET['edit_action'])):?>
    <div style="margin:20px;">Изменение параметра доступа
        <form method="post" action="#">
            <input type="text" name="action_name" placeholder="name" required="required" value="<?=$this->actions[$_GET['edit_action']]['name'];?>"/>
            <input type="text" name="action_title" placeholder="title"  required="required" value="<?=$this->actions[$_GET['edit_action']]['title'];?>"/>
            <input type="submit" name="save_action" value="Сохранить" />
            <a href="?" class="button"><span>Отмена</span></a>
        </form>
    </div>
    <?else:?>
    <div style="margin:20px;">Добавление действия, требующего проверки доступа
        <form method="post" action="#">
            <input type="text" name="action_name" placeholder="name" required="required"/>
            <input type="text" name="action_title" placeholder="title"  required="required"/>
            <input type="submit" name="add_action" value="Добавить" />
        </form>
    </div>

    <div style="margin:20px;">Добавление группы пользователей
        <form method="post" action="#">
            <input type="text" name="group_name" placeholder="name"  required="required"/>
            <input type="text" name="group_title" placeholder="title"  required="required"/>
            <input type="submit" name="add_group" value="Добавить" />
        </form>
    </div>
    <?endif;?>
</div>