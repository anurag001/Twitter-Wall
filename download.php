<?php
session_start();
require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', 'U3ubT4J9pFW4GxXjWpNaMdfC6');
define('CONSUMER_SECRET', 'he6UiAKz69KfOi2owNzy13FrXhiLzvyEEMRhKwbD9jVhTaul6g');
define('OAUTH_CALLBACK', 'http://127.0.0.1/tweet/callback.php');
$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
 error_reporting( 0 );


if(isset($_GET["str"]) && isset($_GET["download"]))
{
	$str = $_GET["str"];
	$download = $_GET["download"];

	if(strpos($str,"@")!=0)
	{
		die();
	}
	else
	{
		$key = explode("@", $str)[1];
	}
}
else
{
	die();
}

$profile = $connection->get('followers/list', array('screen_name'=>$key));

$array = array();
$json = array();
$index=0;

foreach($profile as $val)
{
	if(count((array) $val)>0 && !empty((array) $val))
	{
		foreach ($val as $key => $row) 
		{
			if(!empty($row->screen_name))
			{
				$array[$index] = $row->name." ".$row->screen_name;
				$json[$index]["name"] = $row->name;
				$json[$index]["handler"] = $row->screen_name;
				$index++;
			}
		}
	}
}

if($download == "json")
{
	$fp = fopen('followers.json', 'w');
	fwrite($fp, json_encode($json));
	fclose($fp);

	$file = "http://127.0.0.1/tweet/callback.php/followers.json";
   	$filename = "followers.json";

   	echo '<a href="download_file.php?filename='.$filename.'">Download Followers.json</a>';
}
else if($download == "xls")
{
	
	$fp = fopen('followers.xls', 'w');

	fwrite($fp, "Name");
	fwrite($fp,"\t");
	fwrite($fp, "Followers");
	fwrite($fp, "\n");

	foreach ($array as $value) 
	{
		fwrite($fp, explode(" ", $value)[0]);
		fwrite($fp,"\t");
		fwrite($fp, explode(" ", $value)[1]);
		fwrite($fp, "\n");
	}

	fclose($fp);

	$filename = "followers.xls";
	echo '<a href="download_file.php?filename='.$filename.'">Download Followers.xls</a>';

}
else if ($download == "xml") 
{
	$fp = fopen('followers.xml', 'w');

 	$writer = new XMLWriter();  
	$writer->openURI("followers.xml");   
	$writer->startDocument('1.0','UTF-8');  
	$count = count($array); 
	$writer->setIndent($count);

	foreach ($array as $value) 
	{
		$writer->startElement('followers');  
		$writer->writeElement('name',explode(" ", $value)[0]);  
		$writer->writeElement('handler', explode(" ", $value)[1]);  
		$writer->endElement();   
	}   
	
	$writer->endDocument();   
	$writer->flush(); 

	fclose($fp);

	$filename = "followers.xml";
	echo '<a href="download_file.php?filename='.$filename.'">Download Followers.xml</a>';
}
else if($download == "csv")
{

	$fp = fopen('followers.csv', 'w');
	$insertRow =2;

		
			
	fputcsv($fp,$array);

	

	fclose($fp);

	$filename = "followers.csv";
	echo '<a href="download_file.php?filename='.$filename.'">Download Followers.csv</a>';
}
else if($download == "pdf")
{
	
}

?>