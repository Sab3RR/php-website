<?php
try {
	$db = new PDO('mysql:host=localhost; dbname=rush_shop', 'root', '123456');
} catch (PDOException $e) {
	exit($e->getMessage());
}
session_start();
$product = $db->query('SELECT * FROM `product`')->fetchAll();
if ($_POST['delete_product'])
{
	foreach ($_SESSION['cart'] as $key => $value) {
		if ($value['id'] == $_POST['delete_product'])
		{
			unset($_SESSION['cart'][$key]);
			$success[] = 'Item removed from cart.';
			break ;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Cart page</title>
	<link rel="stylesheet" href="css/styles.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
	<?php include 'template/leftside.php'; ?>
	<div class="main">
		<?php include 'template/main-header.php'; ?>
		<div class="main-content cart">
				<?php
				if (!empty($errors))
					echo '<div class="echo-error">'.array_shift($errors).'</div>';
				if (!empty($success))
					echo '<div class="echo-success">'.array_shift($success).'</div>';
				?>
				<?php
				$total = 0;
				foreach ($_SESSION['cart'] as $key => $value) {
					$pod = 0;
					foreach ($product as $k => $v) {
						if ($value['id'] == $v['id'])
						{
							$pod = $v;
							break ;
						}
					}
					if ($pod == 0)
						break ;
					$total += ($value['count']*$pod['cost']);
					echo("<div class='block'>
						<form name='dell' method='POST'>
						<ul>
						<li><div class='img'><img src='".((file_exists($pod['img']) ? $pod['img'] : '/img/error.svg'))."'></div></li>
						<li><span>".$pod['name']." | ".$pod['cost']." (x1) ($)</span></li>
						<li><span>Count: ".$value['count']." | total: ".($value['count']*$pod['cost'])." ($)</span></li>
						<li><button type='submit' name='delete_product' value='".$value['id']."'>Delete</button></li>
						</ul>
						</form>
						</div>");
					} ?>
				<?php if ($_SESSION['user']): ?>
				<form method="POST" class="cart-total" action="/checkout.php">
					<span>Total: <?php echo($total); $_SESSION['total'] = $total?>($)</span>
					<button type="submit" class="btn-checkout" value="ok" name="checkout">Checkout</button>
				</form>
				<?php else: ?>
				<div class="cart-total">
					<a href="/register.php">Register</a>
				</div>
				<?php endif; ?>
		</div>
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>