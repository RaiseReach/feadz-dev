<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="/source/img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/source/img/favicon.ico" type="image/x-icon">
	@yield('meta')
	<title>@yield('title') - Feadz</title>
	<link href="/source/css/style.min.css" rel="stylesheet">
	@yield('css')
</head>
	<body>
		@include('header')
		<section class="body {{ $body_class or ''}}">
				@yield('user_info')
			<div class="wrap {{ $body_class or 'home' }}">
				@yield('content')
			</div>
		</section>
		@include('footer')
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script>
		  (adsbygoogle = window.adsbygoogle || []).push({
		    google_ad_client: "ca-pub-3629500829854861",
		    enable_page_level_ads: true
		  });
		</script>
		<script src="/source/js/footer.min.js" type="text/javascript"></script>
		<script> var laravel_token=$('meta[name="csrf-token"]').attr("content");</script>
		@yield('script')
	</body>
</html>
