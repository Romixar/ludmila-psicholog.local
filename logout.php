<?php

require 'db.php';

unset($_SESSION['logged_user']);

header('Location: '.$config::HOST_ADDRESS.'login.php');
//header('Location: http://ludmila-psicholog.ru/login.php');



