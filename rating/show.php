<?php 
    include('../connect.php');
    include('../jwt.php');
    include('../library.php');
    $res=[
        'ratingList'=>[],
        'star1'=>0,
        'star2'=>0,
        'star3'=>0,
        'star4'=>0,
        'star5'=>0,
        'rating'=>0,
        'totalRating' => 0,
        'limit' => 10,
        'page' => 1,
        'totalPage' => 0,
    ];
    $g_pro_id = $_GET['_pro_id'];
    // đánh giá 1 sao
    $sql1Star = "SELECT * FROM rating WHERE pro_id = '$g_pro_id' AND r_parent_id = 0 AND r_star = 1";
    $rl1Star = mysqli_query($conn, $sql1Star);
    $res['star1'] = mysqli_num_rows($rl1Star);
    // đánh giá 2 sao
    $sql2Star = "SELECT * FROM rating WHERE pro_id = '$g_pro_id' AND r_parent_id = 0 AND r_star = 2";
    $rl2Star = mysqli_query($conn, $sql2Star);
    $res['star2'] = mysqli_num_rows($rl2Star);
    // đánh giá 3 sao
    $sql3Star = "SELECT * FROM rating WHERE pro_id = '$g_pro_id' AND r_parent_id = 0 AND r_star = 3";
    $rl3Star = mysqli_query($conn, $sql3Star);
    $res['star3'] = mysqli_num_rows($rl3Star);
    // đánh giá 4 sao
    $sql4Star = "SELECT * FROM rating WHERE pro_id = '$g_pro_id' AND r_parent_id = 0 AND r_star = 4";
    $rl4Star = mysqli_query($conn, $sql4Star);
    $res['star4'] = mysqli_num_rows($rl4Star);
    // đánh giá 5 sao
    $sql5Star = "SELECT * FROM rating WHERE pro_id = '$g_pro_id' AND r_parent_id = 0 AND r_star = 5";
    $rl5Star = mysqli_query($conn, $sql5Star);
    $res['star5'] = mysqli_num_rows($rl5Star);
    // 
    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 19;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    $sql = "SELECT * FROM rating WHERE pro_id = '$g_pro_id' AND r_parent_id = 0";
    $rl = mysqli_query($conn, $sql);
    $res['totalRating'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalRating'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $rating = 0;
    while ($row = mysqli_fetch_assoc($rl)) {
        $rating += $row['r_star'];
    }
    
    // 
    $sql = "SELECT * FROM rating INNER JOIN user ON rating.user_id = user.user_id WHERE pro_id = '$g_pro_id' AND r_parent_id = 0 ORDER BY r_id DESC LIMIT $start, $limit";
    $rl = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_assoc($rl)) {
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

        $sql2 = "SELECT * FROM rating INNER JOIN user ON rating.user_id = user.user_id WHERE pro_id = '$g_pro_id' AND r_parent_id = '$r_id' ORDER BY r_id ASC";
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
    if($res['totalRating'] > 0 && $rating > 0){
        $res['rating'] = round($rating / $res['totalRating'], 1);
    }
    echo json_encode($res);
    die();

?>