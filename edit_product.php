<?php
try {
	$db = new PDO('mysql:host=localhost; dbname=rush_shop', 'root', '123456');
} catch (PDOException $e) {
	exit($e->getMessage());
}
session_start();
if ($_SESSION['user']['admin'] != 1 || !$_GET)
	header("Location: /");

if ($_POST['edit_product'] == 'OK')
{
	$re = $db->query('UPDATE `product` SET `name` = "'.$_POST['name'].'", `cost` = "'.$_POST['cost'].'", `category` = "'.$_POST['category'].'", `category_2` = "'.$_POST['category_2'].'", `category_3` = "'.$_POST['category_3'].'" WHERE `id` = '.$_POST['id'])->fetchAll();
	header("Location: /");
}
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
			<?php if($_SESSION['user']['admin'] == 1): ?>
				<form class="edit_product" method="POST">
					<input type="text" style="display: none;" name="id" value="<?php echo($product['id']) ?>">
					<img class="img" src="<?php echo($product['img']) ?>" alt="">
					<div class="block">
						<span>Name: </span><input type="text" name="name" value="<?php echo($product['name']) ?>">
						<span>Cost: </span><input type="number" name="cost" value="<?php echo($product['cost']) ?>">
						<select name="category">
							<?php
							foreach ($category as $key => $value) {
								if ($product['category'] == $value['id'])
									echo("<option selected value='".$value['id']."'>".$value['name']."</option>");
								else
									echo("<option value='".$value['id']."'>".$value['name']."</option>");
							}
							?>
						</select>
						<select name="category_2">
							<?php
							foreach ($category as $key => $value) {
								if ($product['category_2'] == $value['id'])
									echo("<option selected value='".$value['id']."'>".$value['name']."</option>");
								else
									echo("<option value='".$value['id']."'>".$value['name']."</option>");
							}
							?>
						</select>
						<select name="category_3">
							<?php
							foreach ($category as $key => $value) {
								if ($product['category_3'] == $value['id'])
									echo("<option selected value='".$value['id']."'>".$value['name']."</option>");
								else
									echo("<option value='".$value['id']."'>".$value['name']."</option>");
							}
							?>
						</select>
						<button type="submit" name="edit_product" value="OK">Edit</button>
					</div>
				</form>
			<?php endif; ?>
		</div>
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>