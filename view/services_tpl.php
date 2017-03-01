<?php for($i=0; $i<count($data); $i++){ ?>
<div itemprop="itemListElement" itemscope itemtype="http://schema.org/Offer" class="panel panel-default">
     <div class="panel-heading">
<!--   id="but11" - должен быть у тех, которые надо открывать      -->
       <div itemprop="itemOffered" itemscope itemtype="http://schema.org/Service" role="button" class="btn btn-default btn-xs spoiler-trigger"><span itemprop="name"><?= $data[$i]->title ?></span></div>
     </div>
     <div class="panel-collapse collapse out">
         <div class="panel-body">
            <img src="images/<?= $data[$i]->img ?>" class="psychelp shadow" alt="<?= $data[$i]->title ?>" title="<?= $data[$i]->title ?>">
            <h1><?= $data[$i]->title ?></h1>
            <?= $data[$i]->description ?>
            <p><a href="/view/<?= $data[$i]->id ?>">ПОДРОБНЕЕ</a></p>
         </div>
     </div>
</div>
<?php } ?>