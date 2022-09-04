<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[
        'totalOrder' => '',
        'totalPage' => '',
        'page' => '',
        'limit' => '',
        'orderList'=>[],
        'searchId'=> '',
        'searchStatusId'=> '',
        'messages'=> '',
        'error'=> 0
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
    $idUser = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$idUser'";
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
    
    
    $sql = "SELECT * FROM orders WHERE order_user_id = '$idUser'";
    $rl = mysqli_query($conn, $sql);
    $res['totalOrder'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalOrder'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $sql2 = "SELECT * FROM orders 
            INNER JOIN orders_status ON orders.order_status_id = orders_status.order_status_id 
            WHERE order_user_id = '$idUser' ORDER BY order_id DESC LIMIT $start, $limit";
    $rl2 = mysqli_query($conn, $sql2);
    while ($row = mysqli_fetch_assoc($rl2)) {
        array_push($res['orderList'], [
            'order_id'=>$row['order_id'],
            'user_id'=>$row['order_user_id'],
            'city_id'=>$row['order_city_id'],
            'district_id'=>$row['order_district_id'],
            'commune_id'=>$row['order_commune_id'],
            'user_name'=>$row['order_user_name'],
            'user_email'=>$row['order_user_email'],
            'user_phone'=>$row['order_user_phone'],
            'user_address'=>$row['order_user_address'],
            'order_user_note'=>$row['order_user_note'],
            'order_admin_note'=>$row['order_admin_note'],
            'order_total_price'=>$row['order_total_price'],
            'order_status_id'=>$row['order_status_id'],
            'order_status_name'=>$row['order_status_name'],
            'order_created_at'=>$row['order_created_at'],
            'order_updated_at'=>$row['order_updated_at'],
        ]);
    }
    
    echo json_encode($res);
?>