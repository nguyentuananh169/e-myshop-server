<?php 
	include('../../connect.php');
	include('../../jwt.php');

	$response = [];
	$name = trim($_POST['_name']);
	$status = $_POST['_status'];

	$headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'message'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($response, ['error'=>1, 'message'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($response);
        die();
    }

	if ($name == '' || $status == '') {
		array_push($response, ['error' => 1, 'mes' => 'Bạn chưa nhập đủ thông tin']);
		echo json_encode($response); 
		die();
	}

	$sql = "SELECT * FROM category_news WHERE cate_name = '$name'";
	$rl = mysqli_query($conn, $sql);
	$num = mysqli_num_rows($rl);

	if ($num > 0) {
		array_push($response, ['error' => 1, 'mes' => 'Tên danh mục đã tồn tại']);
		echo json_encode($response); 
		die();
	}

	$sqlInsert = "INSERT INTO category_news(cate_name, cate_status) 
					VALUES('$name', '$status')";
	$rlInsert = mysqli_query($conn, $sqlInsert);

	$insert_id = mysqli_insert_id($conn);

	if ($insert_id > 0) {
		array_push($response, ['error' => 0, 'mes' => 'Thêm mới thành công']);
		echo json_encode($response);

	}else{
		array_push($response, ['error' => 1, 'mes' => 'Thêm mới thất bại']);
		echo json_encode($response);

	}

	
?>