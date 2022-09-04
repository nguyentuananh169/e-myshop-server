<?php 
    include('../connect.php');
    include('../jwt.php');
    $res = [];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'mes'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'mes'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }
    
    $sqlSelect = "SELECT * FROM orders WHERE order_status_id = '4'";
    $rlSelect = mysqli_query($conn, $sqlSelect);
    $num = mysqli_num_rows($rlSelect);
    $num2 = 0;
    while ($row = mysqli_fetch_assoc($rlSelect)) {
        $num2 += $row['order_total_price'];
    }

    $sqlSelect2 = "SELECT * FROM orders WHERE order_status_id = '5'";
    $rlSelect2 = mysqli_query($conn, $sqlSelect2);
    $num3 = mysqli_num_rows($rlSelect2);
    
    array_push($res, ['error'=>0, 'statistical_orders_success'=>$num, 'statistical_orders_cancel'=>$num3, 'statistical_turnover'=>$num2]);
    echo json_encode($res);
?>