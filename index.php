<?php
	require 'vendor/autoload.php';
	use Abraham\TwitterOAuth\TwitterOAuth;
	define('CONSUMER_KEY', 'U3ubT4J9pFW4GxXjWpNaMdfC6');
	define('CONSUMER_SECRET', 'he6UiAKz69KfOi2owNzy13FrXhiLzvyEEMRhKwbD9jVhTaul6g');
	define('OAUTH_CALLBACK', 'http://127.0.0.1/tweet/callback.php');
	 error_reporting( 0 );

?>
<!DOCTYPE html>
<html lang="eng">
	<head>
<?php
		require_once("./assets/header.php");
?>
	<script type="text/javascript" src="./js/index.js"></script>
	</head>

	<body>
		<nav class="navbar fixed-top navbar-dark bg-info">
			<div class="container">
				<div class="row">
					<div class="col-auto mr-auto">
						<a class="navbar-brand" href="./index.php">
							<h2 class="banner">Twitter Wall</h2>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-auto">
<?php
					session_start();

		    		if(!empty($_SESSION['access_token']))
					{
						echo '<a class="btn btn-outline-light glow" href="./logout.php">Logout</a>';
					}
					else
					{
						echo '<a class="btn btn-outline-light glow" href="http://127.0.0.1/tweet/help.php">Login with Twitter</a>';
					}

?>
					</div>
				</div>
			</div>
		</nav>
		<br>
		<br>
<?php
		if(!empty($_SESSION['access_token']))
		{
			$access_token = $_SESSION['access_token'];
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
            $user = $connection->get("account/verify_credentials");
?>
			
		<section style="margin-top: 80px;">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-3">
						<div class="card">
							<img class="card-img-top img-responsive img" src="<?php echo $user->profile_banner_url; ?>" alt="Card image cap">
							<div class="card-body">
								<div class="card-block">
									<img class="img-responsive img-thumbnail" src="<?php echo $user->profile_image_url; ?>" alt="Card image cap">
									<h4 class="card-title"><?php echo $user->name;?></h4>
									<p class="text-muted"><?php echo "@".$user->screen_name; ?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-9 col-md-9">
						<h4 class="display-4" style="padding-bottom:15px;">Tweet Display</h4>
						<div id="error-msg" style="display: none;"></div>
						<div class="tweet-display">
						</div>
					</div>
				</div>
			</div>
		</section>
		<br>
		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-3">
						<h4 class="display-4" style="padding-bottom:15px;">Followers</h4>
					</div>
					<div class="col-lg-3">
						<div class="form-group" style="padding-top:10px;">
							
							<input type="text" class="form-control" placeholder="Twitter Handler" aria-label="Username" id="username" onkeyup="followerSuggest(this.value)">
						
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group" style="padding-top:10px;">
							<select id="download-type" class="form-control">
								<option value="">Select Download Type</option>
								<option value="csv">CSV</option>
								<option value="xml">XML</option>
								<option value="pdf">PDF</option>
								<option value="json">JSON</option>
								<option value="xls">XLS</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group" style="padding-top:10px;">
							<button class="btn btn-info" onclick="downloadFollowers()">Download</button>
							<div id="downloadResponse"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="text-muted">You can search your followers by name and handler. But on adding @, you will get results of his/her followers. You can download it also.<i>In case of Overloading of requests cause temporary loss in API.Please try after sometime.</i></div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="error-follower-msg" style="display: none;"></div>
						<div class="follower-display">
						</div>
					</div>
				</div>
			</div>
		</section>
		<br>

<?php
		}
		else
		{
?>
		<section id="heroic">
			<div class="container">
				<div class="jumbotron bg-fluid">
					<span class="display-1">Twitter Wall</span>
				</div>
			</div>
		</section>
<?php
		}

		require_once("./assets/footer.php");
?>	
	<script type="text/javascript">
		function pullPost() 
		{
			$(".tweet-display").html('<img src="./images/loader.gif"> Loading Tweets...');
			$.ajax({
				url: 'tweets.php',
				method: "get",
				success: function(data) {
					setTimeout(function() {
						$("#error-msg").slideUp();
					}, 3000);
					$('.tweet-display').html(data);
				},
				error: function() {
					$("#error-msg").html('There is some error occured').fadeIn();
					setTimeout(function() {
						$("#error-msg").fadeOut();
					}, 3000);
				}
			});
		}

		function pullFollowers()
		{
			$(".follower-display").html('<img src="./images/loader.gif"> Loading Followers...');
			$.ajax({
				url: 'followers.php',
				method: "get",
				success: function(data) {
					setTimeout(function() {
						$("#error-follower-msg").slideUp();
					}, 3000);
					$('.follower-display').html(data);
				},
				error: function() {
					$("#error-follower-msg").html('There is some error occured').fadeIn();
					setTimeout(function() {
						$("#error-follower-msg").fadeOut();
					}, 3000);
				}
			});
		}

		function pullFollowerTweet(followerId)
		{
			$(".tweet-display").html('<img src="./images/loader.gif"> Loading Follower Tweets...');
			$.ajax({
				url: 'followertweets.php',
				method: "get",
				data:"followerId="+followerId,
				success: function(data) {
					setTimeout(function() {
						$("#error-msg").slideUp();
					}, 3000);
					$('.tweet-display').html(data);
				},
				error: function() {
					$("#error-msg").html('There is some error occured').fadeIn();
					setTimeout(function() {
						$("#error-msg").fadeOut();
					}, 3000);
				}
			});
		}

		function followerSuggest(str)
		{

			var search = str;
			if(search.charAt(0)=="@")
			{
				var key = search.split("@")[1];
				var patt = new RegExp("@");
				if(patt.test(key)==true)
				{
					alert("You can not use @ more than once");
				}
				else
				{
					$(".follower-display").html('<img src="./images/loader.gif">Loading Followers...');
						$.ajax({
							url: 'suggestfollowers.php',
							method: "get",
							data:"str="+key,
							success: function(data) {
								setTimeout(function() {
									$("#error-follower-msg").slideUp();
								}, 3000);
								$('.follower-display').html(data);
							},
							error: function() {
								$("#error-follower-msg").html('There is some error occured').fadeIn();
								setTimeout(function() {
									$("#error-follower-msg").fadeOut();
								}, 3000);
							}
						});
				}
			}
			else
			{
				$(".follower-display").html('<img src="./images/loader.gif">Loading Followers...');
					$.ajax({
						url: 'searchfollowers.php',
						method: "get",
						data:"str="+search,
						success: function(data) {
							setTimeout(function() {
								$("#error-follower-msg").slideUp();
							}, 3000);
							$('.follower-display').html(data);
						},
						error: function() {
							$("#error-follower-msg").html('There is some error occured').fadeIn();
							setTimeout(function() {
								$("#error-follower-msg").fadeOut();
							}, 3000);
						}
					});
			}

		}

		function downloadFollowers()
		{

			var search = $("#username").val();
			var download = $("#download-type").val();
			if(search.charAt(0)=="@")
			{
				var key = search.split("@")[1];
				var patt = new RegExp("@");
				if(patt.test(key)==true)
				{
					alert("You can not use @ more than once");
				}
				else
				{
					$("#downloadResponse").html('<img src="./images/loader.gif"> Loading...');
					if(download=="pdf")
					{
						window.location.href="pdf.php?str="+search;
					}
					else
					{
						$.ajax({
							url: 'download.php',
							method: "get",
							data:"str="+search+"&download="+download,
							success: function(data) {
								$("#downloadResponse").html(data);
							},
							error: function(resp) {
								console.log(resp);
								alert("Problem in Downloading files");
							}
						});
					}
				}
			}
			else
			{
				alert("You can download list only through twitter handlers");
			}
		}

		window.onload = function() {
			pullPost();
			pullFollowers();
			
		};
	</script>
	</body>
</html>
