<?php

	$db = new PDO('mysql:host=localhost;dbname=bpd;charset=utf8mb4', 'haranzalez', '0926ara7');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

?>