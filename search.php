<?php

	if ($_POST['name']) {
		try {
			$db = new PDO('mysql:host=localhost; dbname=rush_shop', 'root', '123456');
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
		$r = $db->query('SELECT * FROM `product` WHERE `name` like "%'.$_POST['name'].'%"')->fetchAll();
		echo(json_encode(array('success' => 1, 'content' => $r)));
	} else {
		echo(json_encode(array('error' => 1)));
	}
?>