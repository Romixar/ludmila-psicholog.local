<hr/>
<div class="divauth">
   
<!--   /admin/main-->
    <form class="login" action="" method="post">
    
        <p>
            <p class="white"><strong>Логин:</strong></p>
            <input autofocus class="inpauth" type="text" name="login" value="<?= @$data['login'] ?>" />
        </p>
        <p>
            <p class="white"><strong>Пароль:</strong></p>
            <input class="inpauth" type="password" name="password" value="<?= @$data['password'] ?>" />
        </p>
        
        <button type="submit" name="do_login" value="do_login" >Войти</button>
<!--        <input type="submit" name="do_login" value="enter" />-->
    
    </form>
                
</div>
<hr/>