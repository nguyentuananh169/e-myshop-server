<?php 
include('../connect.php');
include('../jwt.php');
include('../library.php');
    $res = [
        'error'=>0,
        'message'=>'',
        'limit' => 10,
        'page' => 1,
        'totalPage' => 0,
        'totalProduct' => 0,
        'wishList'=>[]
    ];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        $res['error'] = 1;
        $res['message'] = $verify['msg'];
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        $res['error'] = 1;
        $res['message'] = 'Tài không tồn tại trong hệ thống';
        echo json_encode($res);
        die();
    }

    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    $sql = "SELECT * FROM product_wish WHERE user_id = '$user_id'";
    $rl = mysqli_query($conn, $sql);
    $res['totalProduct'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalProduct'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $sql = "SELECT * FROM product_wish WHERE user_id = '$user_id' ORDER BY product_wish.id DESC LIMIT $start, $limit";
    $rl = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rl)) {
        $pro_id = $row['pro_id']; 

        $sql2 = "SELECT * FROM product WHERE pro_id = '$pro_id'";
        $rl2 = mysqli_query($conn, $sql2);
        $data = mysqli_fetch_assoc($rl2);

        $pro_name = $data['pro_name'];
        $pro_price = $data['pro_price'];
        $pro_cost = $data['pro_cost'];
        $pro_img = $data['pro_img'];
        $pro_sale = $data['pro_sale'];
        $pro_status = $data['pro_status'];
        $pro_promotion = $data['pro_promotion'];
        // Lấy số đánh giá
        $sqlRT = "SELECT * FROM rating WHERE pro_id = '$pro_id' AND r_parent_id = '0'";
        $rlRT = mysqli_query($conn, $sqlRT);
        $num = mysqli_num_rows($rlRT);
        if ($num > 0) {
            $totalRating = $num;
            $star = 0;
            while ($row = mysqli_fetch_assoc($rlRT)) {
                $star += $row['r_star']; 
            }
            $star = round($star/$totalRating, 1);
        }else{
            $totalRating = 0;
            $star = 0;
        }

        array_push($res['wishList'], [
            'pro_id' => $pro_id,
            'pro_name' => $pro_name,
            'pro_price' => $pro_price,
            'pro_cost' => $pro_cost,
            'pro_img' => $pro_img,
            'pro_sale' => $pro_sale,
            'pro_status' => $pro_status,
            'pro_promotion' => $pro_promotion,
            'total_rating' => $totalRating,
            'baseURLImg' => URLImgProduct(), 
            'star' => $star,
        ]);
    }
    echo json_encode($res);
?>