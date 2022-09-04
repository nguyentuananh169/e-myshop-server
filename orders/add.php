<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $user=$_POST['_user'];
    $product=$_POST['_product'];
    $total_price=$_POST['_total_price'];
    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'message'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $idUser = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$idUser'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'message'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }
    $user = json_decode($user, true);
    $product = json_decode($product, true);

    if (!$user || !$product || !$total_price) {
        array_push($res, ['error'=>1, 'message'=>'Dữ liệu trống! Bạn hãy tải lại trang và thử lại']);
        echo json_encode($res);
        die();
    }
    foreach ($product as $key => $value) {
        // Lấy số lượng trong DB
        $sql="SELECT * FROM product WHERE pro_id=".$value['pro_id'];
        $rl=mysqli_query($conn,$sql);
        $data=mysqli_fetch_assoc($rl);
        // Tính lại số lượng còn và số lượng đã bán trong DB
        $qty_after_order = $data['pro_qty'] - $value['pro_qty'];
        $buyed = $data['pro_buyed'] + $value['pro_qty'];
        // Cập nhật lại số lượng còn và số lượng đã bán trong DB
        $sqlUpdate="UPDATE product SET pro_qty='$qty_after_order', pro_buyed='$buyed' WHERE pro_id=".$value['pro_id'];
        $rlUpdate=mysqli_query($conn, $sqlUpdate);
    }
    // 
    $sql_insert = "INSERT INTO orders(order_user_id, order_city_id, order_district_id, order_commune_id, order_user_name, order_user_email, order_user_phone, order_user_address, order_user_note, order_total_price) VALUES('".$user['user_id']."', '".$user['city_id']."', '".$user['district_id']."', '".$user['commune_id']."', '".$user['user_name']."', '".$user['user_email']."', '".$user['user_phone']."', '".$user['user_address']."', '".$user['user_note']."', '$total_price')";
    $rl_insert = mysqli_query($conn, $sql_insert);
    $insert_id = mysqli_insert_id($conn);
    // 
    foreach ($product as $key => $value) {
        $sql_insert2 = "INSERT INTO orders_product(order_id, pro_id, pro_name, pro_attr, pro_promotion, pro_price, pro_img, pro_qty, pro_total_price) VALUES('$insert_id', '".$product[$key]['pro_id']."', '".$product[$key]['pro_name']."', '".$product[$key]['pro_attr']."', '".$product[$key]['pro_promotion']."', '".$product[$key]['pro_price']."', '".$product[$key]['pro_img']."', '".$product[$key]['pro_qty']."', '".$product[$key]['pro_qty']*$product[$key]['pro_price']."')";
        $rl_insert2 = mysqli_query($conn, $sql_insert2);
    }
    array_push($res, ['error'=>0, 'order_id'=>$insert_id]);
    echo json_encode($res);
    die();
?>