<?php 
	include('../../connect.php');
	include('../../jwt.php');

	$response = [];
	$category = $_POST['_cate_id'];
	$name = $_POST['_name'];
	$img = $_FILES['_img'];

	$time = time();

	$headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['err'=>1, 'mes'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($response, ['err'=>1, 'mes'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($response);
        die();
    }

	if ($category == '' || $name == ''|| $img== '') {
		array_push($response, ['err' => 1, 'mes' => 'Bạn chưa nhập đủ thông tin']);
		echo json_encode($response); 
		die();
	}

	if ($img['type'] != 'image/png' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif') {
    	array_push($response, ['err'=> 1, 'mes'=> 'File bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
    	echo json_encode($response);
    	die();
    }

	$sql = "SELECT * FROM brand_product WHERE brand_pro_name = '$name' AND cate_pro_id = '$category'";
	$rl = mysqli_query($conn, $sql);
	$num = mysqli_num_rows($rl);

	if ($num > 0) {
		array_push($response, ['err' => 1, 'mes' => 'Tên thương hiệu đã tồn tại']);
		echo json_encode($response); 
		die();
	}

	$sqlInsert = "INSERT INTO brand_product(cate_pro_id, brand_pro_name, brand_pro_img) 
					VALUES('$category', '$name', '".$time.$img['name']."')";
	$rlInsert = mysqli_query($conn, $sqlInsert);

	$insert_id = mysqli_insert_id($conn);

	if ($insert_id > 0) {

		move_uploaded_file($img['tmp_name'], '../../images/products/brand/'.$time.$img['name']);

		array_push($response, ['err' => 0, 'mes' => 'Thêm mới thành công']);
		echo json_encode($response);

	}else{

		array_push($response, ['err' => 1, 'mes' => 'Thêm mới thất bại']);
		echo json_encode($response);

	}

	
?>