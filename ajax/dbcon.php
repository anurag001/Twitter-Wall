<?php
	try{
		$pdo=new PDO("mysql:host=localhost;dbname=ecommerce",'root','');
	}
	catch(PDOException $ex)
	{
		echo $ex->getMessage();
		die();
	}

?>