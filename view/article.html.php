<a class="change" href="/">На главную</a>
<?php if( is_string($user_name) ): ?>
	<p>Author: <?="$user_name"?></p>
<?php else: ?>
	<p>Author: <b style="color:#999;letter-spacing:4px;">none</b></p>
<?php endif; ?>
<p><?php echo "Data Publication: $data_add"?></p>
<h1><?=$title?></h1>
<article>
<?=nl2br($content)?>
</article>