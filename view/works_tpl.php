<?php for($i=0; $i<count($data); $i++){ ?>
    <li itemprop="itemOffered" itemscope itemtype="http://schema.org/Service">
        <p itemprop="name" class="bluetext"><?= $data[$i]->title ?></p>
    </li>
<?php } ?>