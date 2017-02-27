<?php for($i=0; $i<count($data); $i++){ ?>
<div class="slide-item">

    <img itemprop="image" src="http://img.youtube.com/vi/<?= $data[$i]->url ?>/1.jpg" alt="<?= $data[$i]->title ?>" title="<?= $data[$i]->title ?>" tabindex="2" >

    <span itemprop="name" class="slide-title"><?= $data[$i]->title ?></span>
    <span class="autor"><?= $data[$i]->author ?></span>
    
</div>
<?php } ?>