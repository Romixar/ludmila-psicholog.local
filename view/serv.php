

<?php for($i=0; $i<count($this -> data); $i++){ ?>
	<h2><?= $this -> data[$i]['title'] ?></h2>
	<p><?= $this -> data[$i]['img'] ?></p>
	<p><?= $this -> data[$i]['description'] ?></p>
	
<?php } ?>