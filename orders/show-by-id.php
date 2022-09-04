<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $id=$_GET['_id'];
    
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
    if($id==''){
        array_push($res, ['error'=>1, 'message'=>'Dữ liệu trống!']);
        echo json_encode($res);
        die();
    }
    $sql2 = "SELECT * FROM orders 
            INNER JOIN orders_status ON orders.order_status_id = orders_status.order_status_id 
            INNER JOIN city ON orders.order_city_id = city.city_id 
            INNER JOIN district ON orders.order_district_id = district.district_id 
            INNER JOIN commune ON orders.order_commune_id = commune.commune_id 
            WHERE orders.order_id='$id'";
    $rl2 = mysqli_query($conn, $sql2);
    $num2 = mysqli_num_rows($rl2);
    if($num2 <= 0){
        array_push($res, ['error'=>1, 'message'=>'Đơn hàng này không tồn tại!']);
        echo json_encode($res);
        die();
    }
    $data = mysqli_fetch_assoc($rl2);

    $sql3 = "SELECT * FROM orders_product WHERE order_id='$id'";
    $rl3 = mysqli_query($conn, $sql3);
    $orders_product=[];
    while ($row = mysqli_fetch_assoc($rl3)) {
        array_push($orders_product, [
            'order_pro_id'=>$row['order_pro_id'],
            'pro_id'=>$row['pro_id'],
            'pro_name'=>$row['pro_name'],
            'pro_attr'=>$row['pro_attr'],
            'pro_promotion'=>$row['pro_promotion'],
            'pro_price'=>$row['pro_price'],
            'pro_img'=>$row['pro_img'],
            'pro_qty'=>$row['pro_qty'],
            'pro_total_price'=>$row['pro_total_price'],
            'order_pro_created_at'=>$row['order_pro_created_at'],
            'order_pro_updated_at'=>$row['order_pro_updated_at'],
        ]);
    }
    array_push($res, [
        'error'=>0, 
        'orders_info'=>[
            'id'=>$data['order_id'],
            'user_id'=>$data['order_user_id'],
            'city_id'=>$data['order_city_id'],
            'city_name'=>$data['city_name'],
            'district_id'=>$data['order_district_id'],
            'district_name'=>$data['district_name'],
            'commune_id'=>$data['order_commune_id'],
            'commune_name'=>$data['commune_name'],
            'user_name'=>$data['order_user_name'],
            'user_email'=>$data['order_user_email'],
            'user_phone'=>$data['order_user_phone'],
            'user_address'=>$data['order_user_address'],
            'user_note'=>$data['order_user_note'],
            'order_admin_note'=>$data['order_admin_note'],
            'total_price'=>$data['order_total_price'],
            'status_id'=>$data['order_status_id'],
            'status_name'=>$data['order_status_name'],
            'created_at'=>$data['order_created_at'],
            'updated_at'=>$data['order_updated_at'],
        ],
        'orders_product'=>$orders_product
    ]);
    echo json_encode($res);
?>