<?php 
	include('../../connect.php');
	include('../../jwt.php');

	$response = [];
	$category = $_POST['_category'];
	$title = trim($_POST['_title']);
	$summary = trim($_POST['_summary']);
	$img = $_FILES['_img'];
	$status = $_POST['_status'];
	$content = $_POST['_content'];

	$time = time();

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
        array_push($res, ['error'=>1, 'message'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }

	if ($category == '' || $title == '' || $summary == '' || $img['name'] == '' || $status == '' || $content== '' ){
		array_push($response, ['error' => 1, 'message' => 'Bạn chưa nhập đủ thông tin']);
		echo json_encode($response); 
		die();
	}

	if ($img['type'] != 'image/png' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif') {
    	array_push($response, ['error' => 1, 'message' => 'Hình ảnh đại diện bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
		echo json_encode($response); 
    	die();
    }

	$sql = "SELECT * FROM news WHERE news_title = '$title'";
	$rl = mysqli_query($conn, $sql);
	$num = mysqli_num_rows($rl);

	if ($num > 0) {
		array_push($response, ['error' => 1, 'message' => 'Tên tiêu đề đã tồn tại']);
		echo json_encode($response); 
		die();
	}

	$sqlInsert = 
		"INSERT INTO 
		news(cate_id, user_id, news_title, news_summary, news_content, news_img, news_status) 
		VALUES('$category', '$user_id', '$title', '$summary', '$content', '".$time.$img['name']."', '$status')";
	$rlInsert = mysqli_query($conn, $sqlInsert);

	$insert_id = mysqli_insert_id($conn);

	if ($insert_id > 0) {
		move_uploaded_file($img['tmp_name'], '../../images/news/'.$time.$img['name']);
		array_push($response, ['error' => 0, 'message' => 'Thêm mới thành công']);
		echo json_encode($response);

	}else{
		array_push($response, ['error' => 1, 'message' => 'Thêm mới thất bại']);
		echo json_encode($response);

	}

	
?>