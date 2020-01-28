<div class="leftside">
	<ul>
		<li><a href="/"><img class="logo" src="img/logo.png" alt="Logo"></a></li>
		<li><a href="/about.php"><img src="img/info.jpg" alt=""></a></li>
		<?php if ($_SESSION['user']['admin'] == 1): ?>
		<li><a href="/orders.php"><img src="img/1.png" alt=""></a></li>
		<li><a href="/panel_admin.php"><img src="img/admin.png" alt=""></a></li>
		<?php endif; ?>
		<li class="noti" noti="<?php echo(count($_SESSION['cart'])) ?>"><a href="/cart.php"><img src="img/cart.png" alt=""></a></li>
	</ul>
</div>