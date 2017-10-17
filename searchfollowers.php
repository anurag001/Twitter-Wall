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

if(isset($_GET["str"]))
{
	$str = $_GET["str"];
}
else
{
	die();
}

$profile = $connection->get('followers/list', array('count'=>10));

foreach($profile as $val)
{

	if(count((array) $val)>0 && !empty((array) $val))
	{
		foreach ($val as $key => $row) 
		{
			if(!empty($row->screen_name) and preg_match('/'.$str.'/',$row->screen_name) or preg_match('/'.$str.'/',$row->name))
			{
?>
		<div style="display: inline-block;">
			<div class="card" style="width:20rem;">
				<div class="card-body">
					<div class="card-block">
						<img class="img-responsive img-thumbnail" src="<?php echo $row->profile_image_url; ?>" alt="Card image cap">
						<h4 class="card-title"><?php echo $row->name;?></h4>
						<p class="card-text text-muted"><?php echo '@'.$row->screen_name; ?></p>
						<button class="btn btn-warning" onclick="pullFollowerTweet(<?php echo $row->id;?>)">Show Tweet</button>
				  	</div>
				</div>
			</div>
		</div>
<?php
			}
			
			
		}
	}
}

?>