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


require('./lib/fpdf.php');

if(!empty($_GET["str"]))
{
	$str = $_GET["str"];

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
				$index++;
			}
		}
	}
}

class PDF extends FPDF
{
// Page header
function Header()
{
	// Logo
	$this->Image('./images/logo.jpg',10,6,30);
	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Move to the right
	$this->Cell(80);
	// Title
	$this->Cell(50,10,'Followers List',1,0,'C');
	// Line break
	$this->Ln(30);
}

// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}




// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Cell(0,10,'List of Followers with their Twitter Handler',0,1);
foreach($array as $val)
	$pdf->Cell(0,10,$val,0,1);
$pdf->Output();

?>
