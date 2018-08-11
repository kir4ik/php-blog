<form class="inner enter" method="post">
	<p>Логин</p>
	<input type="text" name="login" placeholder="login" value="<?=$login?>">
	<p>Пароль</p>
	<input type="password" name="password" placeholder="password" value="<?=$password?>"><br>
	<input type="submit" value="Sign Up">
</form>
<?php if(!empty($errors)): ?>
	<?php foreach($errors as $value): ?>
		<p class="text_error">
			<?php echo $value[0] ?>
		</p>
	<?php endforeach; ?>
<?php endif; ?>