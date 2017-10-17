<?php
session_start();
 error_reporting( 0 );

require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', 'U3ubT4J9pFW4GxXjWpNaMdfC6');
define('CONSUMER_SECRET', 'he6UiAKz69KfOi2owNzy13FrXhiLzvyEEMRhKwbD9jVhTaul6g');
define('OAUTH_CALLBACK', 'http://127.0.0.1/tweet/callback.php');
$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

if(isset($_GET["followerId"]))
{
	$followerid =$_GET["followerId"];  
}
else
{
	die();
}

$tweets = $connection->get('statuses/user_timeline', array('result_type'=> 'recent', 'count' =>10,'user_id'=>$followerid));


$flag=0;

?>

	<div id="carouselTweets" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner" role="listbox">
	
<?php
foreach($tweets as $val)
{
	if(count((array) $val)>0 && !empty((array) $val) && !empty($val->text))
	{
		if($flag==0)
		{
			$flag=1;
?>
			<div class="carousel-item active">
	        	<div class="card d-block img-fluid">
	        		<div class="card-body">
	        			<img class="img-responsive img-thumbnail" src="<?php echo $val->user->profile_image_url; ?>" alt="Card image cap">
						<h4 style="color: #009381;"><?php echo $val->user->screen_name; ?></h4>
						<p class="lead">
						    <?php echo $val->text;?>
						</p>
	           		</div>
	 			</div>
	       	</div>
<?php

		}
		else
		{
?>
			<div class="carousel-item">
				<div class="card d-block img-fluid">
					<div class="card-body">
						<img class="img-responsive img-thumbnail" src="<?php echo $val->user->profile_image_url; ?>" alt="Card image cap">
						<h4 style="color: #009381;"><?php echo $val->user->screen_name; ?></h4>
						<p class="lead">
						    <?php echo $val->text;?>
						</p>
			   		</div>
				</div>
			</div>
<?php

		}
	}
	else
	{
		echo '<span class="alert alert-danger">Sorry! No tweets are there</span>';
	}
}
?>
		</div>
		<a class="carousel-control-prev move" href="#carouselTweets" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next move" href="#carouselTweets" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>