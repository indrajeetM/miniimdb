<?php 

	$server 	= 'localhost';
	$username	= 'root';
	$password	= '';
	$database 	= 'mini_imdb';

	$conn = mysqli_connect($server,$username,$password,$database) or die('Failed To Connect To Database.');
	//echo '<pre>';print_r($conn);die;

?>