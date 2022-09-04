<?php 
	include('../../connect.php');
	include('../../jwt.php');

		$res = [];
		$id = $_POST['_id'];
		$img = $_FILES['_img'];
	    $link = $_POST['_link'];
	    $headers = apache_request_headers();
	    $token = $headers['access_token'];
	    $token = str_replace('Bearer ', '', $token);
	    $verify = verifyAccessToken($token);
	    if ($verify['err']) {
	        array_push($res, ['error'=>1, 'mes'=>$verify['msg']]);
	        echo json_encode($res);
	        die();
	    }
	    $time = time();

	    if ($id == '' || $link == '') {
	    	array_push($res, ['error'=> 1,'mes'=> 'Bạn chưa nhập đủ thông tin']);
        	echo json_encode($res);
	    	die();
	    }

	    if ($img == '') {
	    	$sqlUpdate = "UPDATE banner_home SET bh_link='$link' WHERE bh_id='$id'";
	    	$rlUpdate = mysqli_query($conn, $sqlUpdate);
	    	array_push($res, ['error'=> 0,'message'=> 'Xửa banner thành công !']);
        	echo json_encode($res);
	    }else{
	    	if ($img['type'] != 'image/png' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif') {
	    		array_push($res, ['error'=> 1,'message'=> 'File bạn nhập không đúng định dạng (PNG, JPEG, GIF) !']);
        		echo json_encode($res);
		    	die();
		    }

		    $sqlSelect = "SELECT * FROM banner_home WHERE bh_id = '$id'";
	    	$rlSelect = mysqli_query($conn, $sqlSelect);
	    	$data = mysqli_fetch_assoc($rlSelect);

	    	unlink('../../images/banner/'.$data['bh_img']);

	    	move_uploaded_file($img['tmp_name'], '../../images/banner/'.$time.$img['name']);

		    $sqlUpdate = "UPDATE banner_home SET bh_img='".$time.$img['name']."', bh_link='$link' WHERE bh_id='$id'";
	    	$rlUpdate = mysqli_query($conn, $sqlUpdate);

	    	array_push($res, ['error'=> 0,'message'=> 'Xửa banner thành công !']);
        	echo json_encode($res);
	    }
?>