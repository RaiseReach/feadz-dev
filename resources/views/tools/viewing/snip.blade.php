<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta property="og:title" content="{{ $snip['title'] }}" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="{{ $snip['iframe_url'] }}" />
	<title>{{ $snip['title'] }}</title>
	<link href="/source//css/style.min.css" rel="stylesheet">
</head>
	<body class="view_snip_page">
		<div class="snip">
			<div class="adv">
				<div class="adv-image" style="background-image: url({ $adv['href'] }})"><a href="{ $adv['url'] }}" style="height: 87px;"></a></div>
				<div class="adv-href">
					<div class="close"><a href="{{ $snip['iframe_url'] }}"><img src="/img/close.png" /></a></div>
					<div class="text"><a href="{ $adv['url'] }}" target="_blank">{ $adv['text']; }}</a></div>
				</div>
			</div>
			<iframe id="main-iframe" src="{{ $snip['iframe_url'] }}"></iframe>
		</div>

	<script src="/source/js/footer.min.js"></script>
	<script>
		var title = "{{ $snip['title'] }}";
		if(title != "") document.title = title;
	</script>
	</body>
</html>