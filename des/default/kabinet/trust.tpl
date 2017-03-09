<div>
    <form method="post" action="#">
        <p>Введите сайт, которому можете доверять <input type="text" name="trust" /> 
            <input type="submit" value="Доверять" /></p>
    </form>

    <?if(isset($_GET['del'])):?>
    <div class="predel">
        <a name="del"></a>
        Вы действительно хотите удалить этот сайт из сиска доверенных?
        <form method="post" action="#">
            <input type="hidden" name="del" value="<?=$_GET['del'];?>" /><input type="submit" value="Да" class="red"/> 
            <a href="?">Нет</a>
        </form>
    </div>
    <?endif;?>
    <?if($sites):?>
    <b>Доверительные сайты:</b>
    <?foreach($sites AS $site):?>
    <p><?=$site['url'];?> <a href="?del=<?=$site['id'];?>#del" class="red" title="Удалить">[x]</a></p>
    <?endforeach;?>    
    <?endif;?>
</div>