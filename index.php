<?php
try {
	$db = new PDO('mysql:host=localhost; dbname=rush_shop', 'root', '123456');
} catch (PDOException $e) {
	exit($e->getMessage());
}
session_start();
if ($_GET['category'] == 1 || !$_GET['category'])
	$product = $db->query('SELECT * FROM `product`')->fetchAll();
else {
	$product = $db->query('SELECT * FROM `product` WHERE `category` = "'.$_GET['category'].'" OR `category_2` = "'.$_GET['category'].'" OR `category_3` = "'.$_GET['category'].'"')->fetchAll();
}
$category = $db->query('SELECT * FROM `category`')->fetchAll();
if (!$_SESSION['cart'])
	$_SESSION['cart'] = array();
if ($_POST['operation_cart'] == 1) {
	$errors = array();
	$success = array();
	$added = 0;
	foreach ($_SESSION['cart'] as $key => $value) {
		if ($value['id'] == $_POST['id'])
		{
			if ($value['count'] + $_POST['count'] < 6)
			{
				$_SESSION['cart'][$key]['count'] = $value['count'] + $_POST['count'];		
				$success[] = 'Item added to existing.';
			}
			else
				$errors[] = 'Cannot add more than 5 items to cart.';
			$added = 1;
			break ;
		}
	}
	if ($added == 0)
	{
		array_push($_SESSION['cart'], array('id' => $_POST['id'], 'count' => $_POST['count']));
		$success[] = 'Item added to your cart.';
	}
} else if ($_POST['operation_cart'] == -99) {
	$success[] = 'Trash successfully cleared.';
	unset($_SESSION['cart']);
	$_SESSION['cart'] = array();
} else if (isset($_POST['login']))
{
	$password = $db->query('SELECT * FROM `users` WHERE `email` = \''.$_POST['email'].'\'');
	$password = $password->fetchAll();
	if (isset($password) && password_verify($_POST['password'], $password[0]['password']))
	{
		$_SESSION['user'] = $password[0];
		$success[] = 'OK';
	}
	else
		$errors[] = 'Wrong password or login!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Rush 00 by vyunak</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="stylesheet" href="css/styles.min.css">
</head>
<body>
	<?php include 'template/leftside.php'; ?>
	<div class="main">
		<?php include 'template/main-header.php'; ?>
		<div class="main-container">
			<div class="main-category">
				<ul>
					<li><span>Category</span></li>
					<?php foreach ($category as $key => $value) {
						echo("<li><a href='?category=".$value['id']."'>".$value['name']."</a></li>");
					} ?> 
				</ul>
			</div>
			<div class="main-content">
				<?php
				if (!empty($errors))
					echo '<div class="echo-error">'.array_shift($errors).'</div>';
				if (!empty($success))
					echo '<div class="echo-success">'.array_shift($success).'</div>';
				if (count($product) > 0) {
					
					foreach ($product as $key => $value) {
						echo('<div class="block">
							<form action="/" method="POST">
							<input value="'.$value['id'].'" style="display: none" name="id" type="text">
							<ul>
							<li><div class="img"><img src="'.((file_exists($value['img']) ? $value['img'] : '/img/error.svg')).'" alt="'.$value['name'].'"></div></li>
							<li><span class="name">'.$value['name'].'</span> | <span class="price">'.$value['cost'].' ($)</span></li>
							<li><input name="count" value="1" type="number" min="1" max="5"> <button type="sumbit" name="operation_cart" value="1">Add to cart</button></li>
							</ul>
							</form>
							</div>');
					}

				} else {
					echo("<h1 style='margin: auto;text-align: center;'>Category is empty!</h1>");
				}
				?>
				<!-- $_SESSION['cart'] -->
			</div>
		</div>
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>