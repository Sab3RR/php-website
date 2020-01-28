<?php

$db_name = 'rush_shop';
$db_dsn = "mysql:host=localhost;";
$db_user = 'root';
$db_pass = '123456';

$_category_table = "CREATE TABLE IF NOT EXISTS `category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);";

$_checkout_table = "CREATE TABLE IF NOT EXISTS `checkout` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `products` varchar(255) NOT NULL,
  `user_id` int(9) NOT NULL,
  `total` int(255) NOT NULL,
  PRIMARY KEY (`id`)
);";

$_product_table = "CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `img` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category` varchar(256) NOT NULL,
  `category_2` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `category_3` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `cost` int(255) NOT NULL,
  PRIMARY KEY (`id`)
);";

$_user_table = "CREATE TABLE IF NOT EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `admin` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);";


$message = '';

try {

    $db = new PDO($db_dsn, $db_user, $db_pass);

    $stmt = $db->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$db_name}'");

    if (!((bool)$stmt->fetchColumn())) {

        $db->query("CREATE DATABASE IF NOT EXISTS $db_name;");
        $db->query("use $db_name;");
        $db->query($_user_table);
        $db->query($_product_table);
        $db->query($_checkout_table);
        $db->query($_category_table);


        $message = "System successfully installed";

    } else {
        $message = "System already installed";
    }



} catch (Exception $e) {
    echo $e->getMessage();
}

?>


<div>
    <?=$message;?>
</div>
