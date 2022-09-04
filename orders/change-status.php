<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $order_id = $_POST['_order_id'];
    $status_id = $_POST['_status_id'];

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
    $data = mysqli_fetch_assoc($rl);
    if($data['user_level'] < 3){
        array_push($res, ['error'=>1, 'message'=>'Chỉ có quản trị viên mới được quyền thay đổi']);
        echo json_encode($res);
        die();
    }
    // Xử lý nếu đơn hàng thất bại
    if ($status_id == 5) {
        // Lấy id sản phẩm và số lượng mua sản phẩm trong bảng orders_product
        $sqlSelect = "SELECT * FROM orders_product WHERE order_id = '$order_id'";
        $rlSelect = mysqli_query($conn, $sqlSelect);
        while ($row = mysqli_fetch_assoc($rlSelect)) {
            $pro_id = $row['pro_id'];
            $qty = $row['pro_qty'];
            // Lấy danh sách số lượng sản phẩm và số lượng đã bán trong bản sản phẩm theo pro_id trong bảng order_product
            $sqlPro = "SELECT * FROM product WHERE pro_id = $pro_id";
            $rlPro = mysqli_query($conn, $sqlPro);
            $product = mysqli_fetch_assoc($rlPro);
            
            $updatedBuyed = $product['pro_buyed'] - $qty;
            $updatedQty = $product['pro_qty'] + $qty;
            // Update lại số lượng và số lượng đã bán trong bảng product
            $sqlUpdate = "UPDATE product SET pro_qty = $updatedQty, pro_buyed = $updatedBuyed WHERE pro_id = $pro_id";
            $rlUpdate = mysqli_query($conn, $sqlUpdate);
        }
    }

    $sql = "UPDATE orders SET order_status_id = '$status_id' WHERE order_id = '$order_id'";
    $rl = mysqli_query($conn, $sql);

    array_push($res, ['error'=>0, 'message'=>'Thay đổi trạng thái thành công']);
    echo json_encode($res);
    die();
?>