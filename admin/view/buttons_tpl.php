
<a href="/admin/logout" style="float:right; margin-right:40px">выход</a>

<h1>Админ-панель</h1>

<hr/>
<div style="padding:3px 0px; background-color:#55AFE6;">
<!--    <form action="">-->
    <div style="margin:auto auto; width:55%;">
        <p>
           
           <?php foreach(Config::$routes as $k => $v){
            
                    if($k !== 'logout'){
            
            ?>
           
               <button type="submit" name="ctrl" value="<?= $k ?>" class="but"><?= $v[1] ?></button>    
               
           <?php    }
            } ?>
           
        </p>
    </div>
<!--    </form>     -->
</div>
<hr/>