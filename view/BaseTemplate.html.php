<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title><?=$title?></title>
	<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
	<header>
		<ul class="wrapper">
			<li><a href="/">Главная</a></li>
<!-- 			<li><form class="search" action="#">
				<input type="text" name="query" placeholder="поиск...">
				<button class="btn" name="search">поиск</button>
			</form></li> -->
			<li><a href="/login"><?=$current_user_name?></a></li>
		</ul>
	</header>
	<div class="wrapper mid">
		<nav>
			<?php foreach($artNames as $id => $artName): ?>
				<a href="/article/<?=$id?>" title="<?=$artName?>"><?=$shortArtNames[$id]?></a>
			<?php endforeach; ?>
		</nav>
		<main>
			<?=$content?>
		</main>
	</div>
</body>
</html>