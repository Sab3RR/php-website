<?php
try {
	$db = new PDO('mysql:host=localhost; dbname=rush_shop', 'root', '123456');
} catch (PDOException $e) {
	exit($e->getMessage());
}
session_start();

$category = $db->query('SELECT * FROM `category`')->fetchAll();
$product = $db->query('SELECT * FROM `product` WHERE `id` = '.$_GET['id'])->fetchAll();
$product = $product[0];
if (!$product)
	header("Location: /");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin page</title>
	<link rel="stylesheet" href="css/styles.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
	<?php include 'template/leftside.php'; ?>
	<div class="main">
		<?php include 'template/main-header.php'; ?>
		<div class="main-content main-admin main-edit">
				<form class="edit_product" action="/" method="POST">
					<input type="text" style="display: none;" name="id" value="<?php echo($product['id']) ?>">
					<img class="img" src="<?php echo($product['img']) ?>" alt="">
					<div class="block">
						<span>Name: <?php echo($product['name']) ?></span>
						<br>
						<span>Cost: <?php echo($product['cost']) ?></span>
						<br>
						</select>
						<input value="<?php echo $product['id'] ?>" style="display: none" name="id" type="text">
						<input name="count" value="1" type="number" min="1" max="5" style="margin:auto;">
						<button type="submit" name="operation_cart" value="1">Add to cart</button>
					</div>
				</form>
		</div>
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>