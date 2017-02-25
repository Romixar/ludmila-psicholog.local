<?php foreach(Config::$routes as $k => $v){
            
        if($k !== 'logout'){ ?>
           
            <button type="submit" name="ctrl" value="<?= $k ?>" class="but"><?= $v[1] ?></button>    
               
<?php   }
    } ?>