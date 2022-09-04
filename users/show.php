<?php 
	include('../connect.php');
	include('../library.php');
	
	$res = [
		'totalUser' => '',
        'totalPage' => '',
        'page' => '',
        'limit' => '',
		'dataUser'=> [],
		'messages'=> '',
		'error'=> ''
	];

	$params = '';
	$search_status = '';
	$email = $_GET['_email'];
	$level = $_GET['_level'];
    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    if ($email != '' || $level != '') {
        if($level != ''){
    		$search_level="AND user.user_level = '$level'";
    	}
        $params="WHERE user.user_email LIKE '%$email%' ".$search_level;
    }

    $sql = "SELECT * FROM user ".$params;
	$rl = mysqli_query($conn, $sql);
	$res['totalUser'] = mysqli_num_rows($rl);
	$res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalUser'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

	$sql2 = "SELECT * FROM user INNER JOIN user_level ON user.user_level = user_level.lv_id ".$params." ORDER BY user_id DESC LIMIT $start,".$res['limit'];
	$rl2 = mysqli_query($conn, $sql2);

	while ( $row = mysqli_fetch_assoc($rl2) ) {
		$user_id = $row['user_id'];
		$city_id = $row['city_id'];
		$district_id = $row['district_id'];
		$commune_id = $row['commune_id'];
		$user_name = $row['user_name'];
		$user_email = $row['user_email'];
		$user_phone = $row['user_phone'];
		$user_address = $row['user_address'];
		$user_status = $row['user_status'];
		$user_level = $row['user_level'];
		$user_level_name = $row['lv_name'];
		$user_avatar = $row['user_avatar'];
		$user_created_at = $row['user_created_at'];
		$user_updated_at = $row['user_updated_at'];

		array_push($res['dataUser'], [
			'user_id' => $user_id,
			'city_id' => $city_id,
			'district_id' => $district_id,
			'commune_id' => $commune_id,
			'user_name' => $user_name,
			'user_email' => $user_email,
			'user_phone' => $user_phone,
			'user_address' => $user_address,
			'user_status' => $user_status,
			'user_level' => $user_level,
			'user_level_name' => $user_level_name,
			'user_avatar' => $user_avatar,
			'user_created_at' => $user_created_at,
			'user_updated_at' => $user_updated_at,
			'baseURLImg' => URLImgUser()
		]);
	}

	echo json_encode($res);
?>