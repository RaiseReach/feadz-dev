<header>
    <!-- mobile view -->
    <nav id="myNavmenu" class="navmenu navmenu-default navmenu-fixed-right offcanvas" role="navigation">
		@if (Auth::guest())
			<ul class="nav navbar-nav navbar-right">
				<button type="button" class="auth log-in" data-dismiss="modal" data-toggle="modal" data-toggle="offcanvas" data-target="#log-in">LOG IN</button>
				<button type="button" class="auth sign-up" data-dismiss="modal" data-toggle="modal" data-toggle="offcanvas" data-target="#sign-up">SIGN UP</button>
			</ul>
		@else
		<ul class="nav navmenu-nav">
	    	<?php
	    		$photo = Auth::user()->photo;
	    		$photo = $photo == '' ? '/source/img/default-avatar.png' : '/files/uploads/' . $photo;
	    	?>
	    	<a class="toggle" href="#"><img src="{{ $photo }}" alt="avatar"><div class="welcome-text">Welcome, <br><div class="username">{{ Auth::user()->name }}</div></div><button class="logout" onclick="window.location.href = '/logout'"></button></a>
			<li class="active"><a href="#">Create</a></li>
			<li><a href="/create/story" class="story">Feadz Story</a></li>
			<li><a href="/create/gifmaker" class="gif">Feadz GIF Maker</a></li>
			<li><a href="/create/snip" class="snip">Feadz Snip</a></li>
			<li><a href="/create/rankedlist" class="rankedlist">Ranked list</a></li>
			<li><a href="/create/flipcards" class="flipcard">Flip Cards</a></li>
			<li><a href="/create/meme" class="meme">Meme Tools</a></li>
			<li class="active"><a href="#">Profile</a></li>
    		@if (Auth::user()->role == 'admin')
    		<li><a class="profile-submenu" href="/admin">Admin Panel</a></li>
    		@endif
			<li><a class="profile-submenu" href="/user/profile">Profile info</a></li>
			<li><a class="profile-submenu" href="/user/settings">Profile Settings</a></li>
			<li><a class="profile-submenu" href="/user/stats">My Stats</a></li>
		</ul>
		@endif
	</nav>
	<div class="navbar-mobile navbar-default navbar-fixed-top" style="display: none;">
		@if (Auth::guest())
		    <ul class="navbar-search">
			    <form class="navbar-form-mobile navbar-right">
			    	<input type="text" class="form-control search-text-mobile" placeholder="Search text...">
			    	<button type="button" class="btn-search-mobile"></button>
			    	<div class="border-form"> </div>
			    </form>
			</ul>
			<div class="left-block"></div>
			<a class="navbar-brand" href="/">
				<img alt="Brand" src="/source/img/brand.svg">
			</a>
			<button type="button" class="navbar-login"></button>
			<div class="login-signup-butts">
				<button type="button" class="auth sign-up" data-dismiss="modal" data-toggle="modal" data-toggle="offcanvas" data-target="#sign-up">SIGN UP</button>
				<button type="button" class="auth log-in" data-dismiss="modal" data-toggle="modal" data-toggle="offcanvas" data-target="#log-in">LOG IN</button>
			</div>
		@else
		    <ul class="navbar-search">
			    <form class="navbar-form-mobile navbar-right">
			    	<input type="text" class="form-control search-text-mobile" placeholder="Search text...">
			    	<button type="button" class="btn-search-mobile"></button>
			    	<div class="border-form"> </div>
			    </form>
			</ul>
			<div class="left-block"></div>
			<a class="navbar-brand" href="/">
				<img alt="Brand" src="/source/img/brand.svg">
			</a>
			<button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		@endif
	</div>
	<!--  -->
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span> 
	      </button>
	      <a class="navbar-brand" href="/">
	        <img alt="Brand" src="/source/img/brand.svg">
	      </a>
	    </div>

	    <div class="collapse navbar-collapse" id="myNavbar">
		    <ul class="nav navbar-nav navbar-center menu flex">
		      <li><a href="/posts/newest">Newest</a></li>
		      <li><a href="/posts/popular">Popular</a></li>
		    @foreach(\App\Category::all() as $category)
		      <li><a href="{{ url('/search/category', $category->category) }}">{{ $category->category }}</a></li>
		    @endforeach
		    </ul>
		    @if (Auth::guest())
		    <ul class="nav navbar-nav navbar-right">
		    	<button type="button" class="auth log-in" data-dismiss="modal" data-toggle="modal" data-target="#log-in">LOG IN</button>
		    	<button type="button" class="auth sign-up" data-dismiss="modal" data-toggle="modal" data-target="#sign-up">SIGN UP</button>
		    </ul>
		    @else

		    @php
		    $notifications = Auth::user()->unreadNotifications;
		    @endphp
		    <ul class="nav navbar-nav navbar-right">
				<li class="dropdown reminder {{ count($notifications) != 0 ? 'active' : ''}}">
			        <span class="new-message"></span>
			        <ul class="dropdown-menu">
			          @foreach($notifications as $notification)
			          <li><a>{{ $notification->data['message'] }}</a></li>
			          @endforeach
			        </ul>
			    </li>
			    <li class="dropdown">
			    	<?php
			    		$photo = Auth::user()->photo;
			    		$photo = $photo == '' ? '/source/img/default-avatar.png' : '/files/uploads/' . $photo;
			    	?>
			    	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="{{ $photo }}" alt="avatar"><div class="welcome-text">Welcome, <br><div class="username">{{ Auth::user()->name }}</div></div></a>
			    	<ul class="dropdown-menu">
			    		<span class="caret-down"> </span>
			    		@if (Auth::user()->role == 'admin')
			    		<li><a href="/admin">Admin Panel</a></li>
			    		@endif
			    		<li><a href="/user/profile">Profile info</a></li>
			    		<li><a href="/user/settings">Profile Settings</a></li>
			    		<li><a href="/user/stats">My Stats</a></li>
			    		<li><a href="/logout">Logout</a></li>

			    	</ul>
			    </li>
			    <li><button type="button" class="upload-tool" data-dismiss="modal" data-toggle="modal" data-target="#upload-tool"> UPLOAD </button></li>
		    </ul>
		    @endif
		    <ul class="nav navbar-nav navbar-right">
			    <form class="navbar-form navbar-right">
			    	<input type="text" class="form-control search-text" placeholder="Search text...">
			    	<button type="button" class="btn-search"></button>
			    	<div class="border-form"> </div>
			    </form>
			</ul>
		</div>
	  </div>
	</nav>
	<div class="scrollbar-inner scroll-bar_center">
		<div class="scrollmenu-tags" id='scroll-center'>
			@include('tags')
		</div>
	</div>
	<div class="modal fade" id="log-in" role="dialog">
		<div class="modal-dialog auth" >
			<div class="modal-content">
				<button type="button" class="btn-close" data-dismiss="modal"> </button>
				<div class="title">Log In</div>
				<div class="description">Log In with your email address</div>
				<form class="auth" id="log-in-form" autocomplete="off">
					<input type="text" name="email" class="email" placeholder="Email" autocomplete="off">
					<input type="password" name="password" class="password" placeholder="Password" autocomplete="off">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</form>
				<div class="alerts log-in"> </div>
				<div class="other">
					<div class="info">Dont have an account?</div>
					<a class="forgot-password" href="#" data-dismiss="modal" data-toggle="modal" data-target="#forgot-password">Forgot your password?</a>
					<a class="sign-up" href="#" data-dismiss="modal" data-toggle="modal" data-target="#sign-up">Sign Up</a>
				</div>
				<button type="button" class="send" id="log-in-submit">LOG IN</button>
				<div class="or">OR</div>
				<div class="social">
					<div class="description">Log In with a social network </div>
					<div class="btn-social">
						<a href="/login/facebook" class="social facebook"> </a>
						<a href="/login/google" class="social google"> </a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="sign-up" role="dialog">
		<div class="modal-dialog auth" >
			<div class="modal-content">
				<button type="button" class="btn-close" data-dismiss="modal"> </button>
				<div class="title">Sign Up</div>
				<div class="description">Sign Up with your email address</div>
				<form class="auth" id="sign-up-form" autocomplete="off">
					<input type="text" name="email" class="email" placeholder="Email" autocomplete="off">
					<input type="text" name="name" class="name" placeholder="Name" autocomplete="off">
					<input type="password" name="password" class="password" placeholder="Password">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</form>
				<div class="alerts sign-up"> </div>
				<div class="other">
					<a class="forgot-password sign-up" href="#" data-dismiss="modal" data-toggle="modal" data-target="#forgot-password">Forgot your password?</a>
				</div>
				<button type="button" class="send" id="sign-up-submit">SIGN UP</button>
				<div class="or">OR</div>
				<div class="social">
					<div class="description">Sign Up with a social network</div>
					<div class="btn-social">
						<a href="/login/facebook" class="social facebook"> </a>
						<a href="/login/google" class="social google"> </a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="forgot-password" role="dialog">
		<div class="modal-dialog reminder" >
			<div class="modal-content">
				<button type="button" class="btn-close" data-dismiss="modal"> </button>
				<div class="title">Password Reminder</div>
				<div class="description">Your Email address</div>
				<form class="auth" id="forgot-password-form" autocomplete="off">
					<input type="text" name="email" class="email" placeholder="Email">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</form>
				<div class="alerts forgot-password"> </div>
				<div class="side-helpers">
					<button type="button" class="btn-forgot" id="forgot-password-submit">SEND</button>
					Dont have an account? <br/><a href="#" data-dismiss="modal" data-toggle="modal" data-target="#sign-up">Sign Up</a>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="upload-tool" role="dialog">
		<div class="modal-dialog upload-tool" >
			<div class="modal-content">
				<button type="button" class="btn-close" data-dismiss="modal"> </button>
				<div class="title">Upload</div>
				<div class="description">Select format and share with the world</div>
				<div class="btn-tools">
					<div class="top">
						<button class="select-tool story active" data-tool="story">Feadz Story</button>
						<button class="select-tool gifmaker" data-tool="gifmaker">Feadz GIF Maker</button>
						<button class="select-tool snip" data-tool="snip">Feadz Snip</button>
					</div>
					<div class="bottom">
						<button class="select-tool rankedlist" data-tool="rankedlist">Ranked list</button>
						<button class="select-tool flipcards" data-tool="flipcards">Flip Cards</button>
						<button class="select-tool meme" data-tool="meme">Meme Tool</button>
					</div>
				</div>
				<button type="button" class="create" data-tool="story" >CREATE</button>
			</div>
		</div>
	</div>
</header>