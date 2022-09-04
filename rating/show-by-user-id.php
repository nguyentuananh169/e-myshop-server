<?php 
    include('../connect.php');
    include('../jwt.php');
    include('../library.php');
    $res=[
        'error'=>0,
        'message'=>'',
        'limit' => 10,
        'page' => 1,
        'totalPage' => 0,
        'totalRating' => 0,
        'ratingList'=>[]
    ];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        $res['error']=1;
        $res['message']=$verify['msg'];
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        $res['error']=1;
        $res['message']='Tài không tồn tại trong hệ thống';
        echo json_encode($res);
        die();
    }
    
    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    $sql = "SELECT * FROM rating WHERE user_id = '$user_id' AND r_parent_id = 0";
    $rl = mysqli_query($conn, $sql);
    $res['totalRating'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalRating'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $sql = "SELECT * FROM rating INNER JOIN user ON rating.user_id = user.user_id WHERE rating.user_id = '$user_id' AND r_parent_id = 0 ORDER BY r_id DESC LIMIT $start, $limit";
    $rl = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rl)) {

        $rating += $row['r_star'];

        $r_id = $row['r_id'];
        $pro_id = $row['pro_id'];
        $user_id = $row['user_id'];
        $user_name = $row['user_name'];
        $user_avatar = $row['user_avatar'];
        $user_level = $row['user_level'];
        $r_parent_id = $row['r_parent_id'];
        $r_content = $row['r_content'];
        $r_star = $row['r_star'];
        $r_status = $row['r_status'];
        $r_created_at = $row['r_created_at'];
        $r_updated_at = $row['r_updated_at'];

        $sql2 = "SELECT * FROM rating INNER JOIN user ON rating.user_id = user.user_id WHERE rating.user_id = '$user_id' AND r_parent_id = '$r_id' ORDER BY r_id ASC";
        $rl2 = mysqli_query($conn, $sql2);
        $reply = [];
        while ($row2 = mysqli_fetch_assoc($rl2)) {
            $r_id2 = $row2['r_id'];
            $pro_id2 = $row2['pro_id'];
            $user_id2 = $row2['user_id'];
            $user_name2 = $row2['user_name'];
            $user_avatar2 = $row2['user_avatar'];
            $user_level2 = $row2['user_level'];
            $r_parent_id2 = $row2['r_parent_id'];
            $r_content2 = $row2['r_content'];
            $r_star2 = $row2['r_star'];
            $r_status2 = $row2['r_status'];
            $r_created_at2 = $row2['r_created_at'];
            $r_updated_at2 = $row2['r_updated_at'];
            array_push($reply, [
                'r_id'=>$r_id2,
                'pro_id'=>$pro_id2,
                'user_id'=>$user_id2,
                'user_name'=>$user_name2,
                'user_avatar'=>$user_avatar2,
                'user_level'=>$user_level2,
                'r_parent_id'=>$r_parent_id2,
                'r_content'=>$r_content2,
                'r_star'=>$r_star2,
                'r_status'=>$r_status2,
                'r_created_at'=>$r_created_at2,
                'r_updated_at'=>$r_updated_at2,
                'baseURLImg' => URLImgUser(), 
            ]);
        }
        array_push($res['ratingList'], [
            'r_id'=>$r_id,
            'pro_id'=>$pro_id,
            'user_id'=>$user_id,
            'user_name'=>$user_name,
            'user_avatar'=>$user_avatar,
            'user_level'=>$user_level,
            'r_parent_id'=>$r_parent_id,
            'r_content'=>$r_content,
            'r_star'=>$r_star,
            'r_status'=>$r_status,
            'r_created_at'=>$r_created_at,
            'r_updated_at'=>$r_updated_at,
            'baseURLImg' => URLImgUser(), 
            'reply'=>$reply
        ]);
    }

    echo json_encode($res);
    die();

?>