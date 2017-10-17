 <?php
     session_start();
      error_reporting( 0 );

    require 'vendor/autoload.php';
    use Abraham\TwitterOAuth\TwitterOAuth;
    define('CONSUMER_KEY', 'U3ubT4J9pFW4GxXjWpNaMdfC6');
    define('CONSUMER_SECRET', 'he6UiAKz69KfOi2owNzy13FrXhiLzvyEEMRhKwbD9jVhTaul6g');
    define('OAUTH_CALLBACK', 'http://127.0.0.1/tweet/callback.php');

    if(!empty($_SESSION['access_token']))
    {
                $access_token = $_SESSION['access_token'];
                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
                $user = $connection->get("account/verify_credentials");
?>
                <br>
                <img class="img-responsive" src="<?php echo $user->profile_image_url; ?>">
                <h3>
                    <?php
                    echo "@".$user->screen_name;
                    ?>
                    <br>
                    <small>
                        <?php
                        echo $user->name;
                        echo "<br>";
                        echo "Status: ".$user->description;
                        ?>
                    </small>
                </h3>
<?php
    }
?>