<div>
    <div>
    <form method="post" action="#">
        <table class="sys">
            <tr><th>Доступ\Группа</th>
                <?foreach($this->groups AS $val):
                if($val['name']=='root'){continue;}?>
                <th><?=$val['title']?> (<?=$val['name']?>)</th>
                <?endforeach;?>
                <th class="red" title="Удалить">[X]</th>
            </tr>

            <?foreach($this->actions AS $act):?>
            <tr>
                <td title="<?=$act['name'];?>"><?=$act['title'];?></td>
                <?foreach($this->groups AS $gr):
                if($gr['name']=='root'){continue;}?>
                <td><input type="checkbox" name="<?=$gr['name'];?>[<?=$act['name'];?>]" <?=isset($gr['actions_arr'][$act['name']])?'checked="checked"':'';?> value="1" /></td>
                <?endforeach;?>
                <td><a title="Удалить" class="red" href="?del=<?=$act['name']?>">[X]</a></td>
            </tr>
            <?endforeach;?>
        </table>
        <p><input type="submit" name="save" value="Сохранить" /></p>
    </form>
       <?php 
       if(isset($_GET['del'])){
       $this->confirmForm('Вы действительно хотите удалить этот параметр доступа?', '?');
       }
       ?>         
                
    </div>
                <div>Добавление действия, требующего проверки доступа
                    <form method="post" action="#">
                        <input type="text" name="action_name" placeholder="action_name" />
                        <input type="text" name="action_title" placeholder="action_title" />
                        <input type="submit" name="add" value="Добавить" />
                    </form>
                </div>
</div>