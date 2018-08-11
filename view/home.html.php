<a class="change" href="/add">Добавить</a><br>
<form class="inner" action="/edit">
	<select name="id">
	<?php	foreach($articles as $article){ ?>
		<option value="<?=$article['id']?>" title="<?=$article['name']?>"><?=$article['name']?></option>
	<?php } ?>
	</select>
	<button class="change" type="submit">Редактировать</button>
	<button class="change delete" name="delete" type="submit">Удалить</button>
</form>