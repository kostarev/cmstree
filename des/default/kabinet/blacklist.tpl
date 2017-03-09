<div>
  <?if($kidals):?>
<div class="pages"><?=$pages;?></div>
    <?foreach($kidals AS $kidala):?>
    <div class="block">
        <b><a href="/kabinet/sites/<?=$kidala['site'];?>"><?=$kidala['url'];?></a></b><br />
        <?if($kidala['wmid']):?><p><b>Wmid:</b>
            <?foreach($kidala['wmid'] AS $wmid):?>
            <a target="_block" href="http://passport.webmoney.ru/asp/certview.asp?wmid=<?=$wmid;?>"><?=$wmid;?></a> 
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
            <?=$kidala['descr'];?>
        </p>
        <?endif;?>
    </div>
    <?endforeach;?>
    <?endif;?>
<div class="pages"><?=$pages;?></div>
</div>