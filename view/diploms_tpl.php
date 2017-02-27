<?php for($i=0; $i<count($data); $i++){ ?>
<div class="col-sm-3">
    <div class="templatemo_gallery">
        <div class="gallery-item">
				<img src="images/gallery/<?= $data[$i]->img ?>" alt="<?= $data[$i]->title ?>" title="<?= $data[$i]->title ?>">
			<div class="overlay">
		        <a href="images/gallery/<?= $data[$i]->img ?>" data-rel="lightbox" class="fa fa-search" title="<?= $data[$i]->title ?>"></a>
		    </div>
		</div>
    </div>
</div>
<?php } ?>