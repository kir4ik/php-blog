<a class="change" href="/">На главную</a>
<?php if(!empty($errors)): ?>
	<?php foreach($errors as $value): ?>
		<p class="text_error">
			<?php echo $value[0] ?>
		</p>
	<?php endforeach; ?>
<?php endif; ?>
<form class="inner write" method="post">
	Название<br>
	<input type="text" name="name" value="<?=$name?>"><br>
	Контент<br>
	<textarea name="content" spellcheck="false"><?=$content?></textarea><br>
	<input type="submit" value="Сохранить изменения">
</form>