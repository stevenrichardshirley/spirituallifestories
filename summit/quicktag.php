<p>
	
<form action="<?= $_SERVER['PHP_SELF'] ?>" id="tags">
	<input type="text" id="taginput" />
	<div id="notice">You are only allowed to add 5 tags per post.</div>
	<div id="counter"></div>
	<ul id="taglist"></ul>
</form>

<script>
	$('#taginput').quickTag({
		allowedTags	: 5,
		limitation 	: 50,
		coloring 	: true,
		colors		: ['#ffb4db', '#ff8ac7', '#ff62b3', '#ff0386'],
		img			: 'images/close.png',
		fade		: 300,
		focus		: true,
		notice		: $('#notice'),
		counter		: $('#counter'),
		isForm		: $('#tags')
	});
</script>

</p>