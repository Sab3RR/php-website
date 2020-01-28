<div class="main-header">
	<?php if(isset($_SESSION['user'])): ?>
	<input class="search" type="text" placeholder="search...">
	<div class="search-content">
		<ul>
		</ul>
	</div>
	<div class="right">
		<div class="username"><?php echo $_SESSION['user']['email']; ?></div>
		<div class="exit"><a class="exit" href="/logout.php" title="Exit">Exit</a></div>
	</div>
	<?php else: ?>
	<form action="/" method="POST" style="float: right;">
		<input name="email" type="email" placeholder="login...">
		<input name="password" type="password" placeholder="password...">
		<button name="login" type="sumbit">Login</button>
		<span>/</span>
		<button><a href="/register.php">Register</a></button>
	</form>
	<?php endif; ?>
</div>