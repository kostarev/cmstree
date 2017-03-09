<div>
    <div style="margin: 15px;">
        <form method="post" action="#">
            <input type="submit" name="add" value="Сгенерировать приглашение" />
        </form>
    </div>

    <?if($invites):?>
    <?foreach($invites AS $inv):?>
    <?if($inv['activated']==0):?>
    <div class="green"><b><?=$inv['code'];?></b> - не использован - <input type="text" onclick="this.select();" style="width:300px;" value="<?=H;?>/login/registration?invite=<?=$inv['code'];?>" readonly="readonly"/>
    <a href="?del=<?=$inv['id'];?>" class="red" title="Удалить">[x]</a>  
        <?elseif($inv['activated']==-1):?>
        <div><b><?=$inv['code'];?></b> - ожидает регистрации                                                                 
            <?else:?>
            <div><b><?=$inv['code'];?></b> - приглашён <a href="/user/<?=$inv['activated'];?>"><?=$inv['login'];?></a>
                <?endif;?>
            </div>
            <?endforeach;?>
            <?else:?>
            У Вас нет приглашений
            <?endif;?>
        </div>