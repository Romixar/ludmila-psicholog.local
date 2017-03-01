<div class="container">
<div itemprop="itemListElement" itemscope itemtype="http://schema.org/Offer" class="view templatemo_homewrapper">
     <div class="panel-heading">
       <div itemprop="itemOffered" itemscope itemtype="http://schema.org/Service" data-service-id="<?= $data->id ?>" role="button" class="btn btn-default btn-xs spoiler-trigger"><span itemprop="name"><?= $data->title ?></span></div>
     </div>
     <div>
         <div class="panel-body">
            <img src="/images/<?= $data->img ?>" class="psychelp shadow" alt="<?= $data->title ?>" title="<?= $data->title ?>">
            <h1><?= $data->title ?></h1>
            <?= $data->description ?>
         </div>
     </div>
</div>
</div>