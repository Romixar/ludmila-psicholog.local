<?php for($i=0; $i<count($data); $i++){ ?>
<div class="testimonial">
    <div class="spoiler-head heightBlock"><?= $data[$i]->head ?></div>
    <div class="spoiler-body"><?= $data[$i]->body ?></div>
    <br />
    <span class="testBlue"><?= $data[$i]->name ?></span><br/>
    <span class="testBlue"><?= $data[$i]->dateadd ?></span>
</div>
<?php } ?>