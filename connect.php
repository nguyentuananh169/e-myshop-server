<?php 
	header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, access_token, refresh_token');
    // 
    $conn = mysqli_connect("localhost","root","12345678","db_myshop");
    mysqli_query($conn,"set names 'utf8'");
?>