<div>
    <?if($success):?>
    Поздравляем с успешной регистрацией<br />
    <a href="<?=$_SESSION['after_login_url'];?>" >Далее</a>

    <?elseif($registration):?>
    <script type="text/javascript">
        jQuery(function() {
        $('#login').change(function(){
            $.ajax({
                    type: "POST",
                    url: '#',
                    data: 'check_login='+$(this).val(),
                    dataType: "script",
                    success: function(msg){                   
                    // alert(msg);
                    }
                });
    });
});    
    </script>
    <form method="post" action="#">
        <?if($this->conf['reg']['invite']):?>
        Регистрация доступна только по приглашениям!
        <p><small>Пригласительный код</small><br /><input type="text" required="required" placeholder="Код приглашения" name="invite" value="<?=$_GET['invite'];?>"/></p>
        <?endif;?>
        <p><small>Логин</small><br /><input type="text" id="login" name="login" required="required" placeholder="Логин" pattern="[A-Za-zА-Яа-яЁё0-9]{3,20}" value="<?=$login;?>"/> <span id="check-login"></span></p>
        <p><small>Пароль</small><br /><input type="password" name="pas" required="required" value="<?=$pas;?>" pattern="[^\s]{5,20}" placeholder="Пароль"/></p>
        <p><small>Повторите пароль</small><br /><input type="password" name="repas" required="required" value="<?=$repas;?>" pattern="[^\s]{5,20}" placeholder="Повторите Пароль"/></p>
        <?if($this->conf['reg']['email']):?>
        <p><small>Email</small><br /><input type="email" name="email" value="<?=$email;?>" <?if($this->conf['reg']['email_must']):?>required="required"<?endif;?>  placeholder="Email"/></p>
        <?endif;?>
        <?if($this->conf['reg']['captcha']):?>
        <p><img src="<?=$captcha->url();?>"  alt="Включите картинки"/></p>
        <p><small>Код с картинки</small><br /><input type="text" required="required" placeholder="Символы на картинке" name="captcha" /></p>
        <?endif;?>
        <p><input type="submit" value="Регистрация"/></p>
    </form>
    <?elseif($email_confirm):?>
    На указанный Вами адрес электронной почты, была выслана инструкция для подтверждения регистрации.
    <?elseif($forget_email):?>
    Инструкция по смене пароля была выслана вам на Email
    <?elseif($forget):?>
    Для восстановления пароля, введите ваш логин либо адрес электронной почты.
    <form method="post" action="#">
        <p><input type="text" placeholder="Ваш Логин или Email" name="emlogin" required="required"/></p>
        <p><img src="<?=$captcha->url();?>" alt="Включите картинки"/></p>
        <p><input type="text" required="required" placeholder="Символы на картинке" name="captcha" /></p>
        <p><input type="submit" value="Продолжить"/></p>
    </form>
    <?elseif($change_pass_success):?>
    Пароль успешно изменён. Можете авторизироваться, используя новый пароль.
    <?elseif($change_pass):?>
    <p>Логин: <b><?=$user['login'];?></b></p>
    <form method="post" action="#">
        <p><input type="password" name="pas" required="required" pattern="[^\s]{5,20}" placeholder="Новый пароль"/></p>
        <p><input type="password" name="repas" required="required" pattern="[^\s]{5,20}" placeholder="Повторите"/></p>
        <p><input type="submit" value="Сменить пароль"/></p>
    </form>
    <?else:?>
    <?$this->display('_auth_form');?>
    <?endif?>







</div>