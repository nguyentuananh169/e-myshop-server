<?php 
include('../../connect.php');
include('../../jwt.php');
    $res = [];
    $id = $_GET['_id'] ;

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['err'=>1, 'mes'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['err'=>1, 'mes'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }

    $sqlCheck = "SELECT * FROM product WHERE brand_pro_id='$id'";
    $rlCheck = mysqli_query($conn, $sqlCheck);

    $check = mysqli_num_rows($rlCheck);

    if ($check > 0) {
        array_push($res, ['err'=> 1,'mes'=> 'Thương hiệu này còn sản phẩm, bạn hãy xóa sản phẩm của thương hiệu này trước']);
        echo json_encode($res);
        die();
    }

    $sqlSelect = "SELECT * FROM brand_product WHERE brand_pro_id='$id'";
    $rlSelect = mysqli_query($conn, $sqlSelect);

    $num = mysqli_num_rows($rlSelect);

    if ($num <= 0) {
        array_push($res, ['err'=> 1,'mes'=> 'Thương hiệu không có trong dữ liệu! Bạn vui lòng thử tải lại trang']);
        echo json_encode($res);
        die();
    }

    $data = mysqli_fetch_assoc($rlSelect);

    $sql = "DELETE FROM brand_product WHERE brand_pro_id='$id'";
    $rl = mysqli_query($conn, $sql);
    
    $sqlSelect2 = "SELECT * FROM brand_product WHERE brand_pro_id='$id'";
    $rlSelect2 = mysqli_query($conn, $sqlSelect2);

    $num2 = mysqli_num_rows($rlSelect2);

    if ($num2 > 0) {
        array_push($res, ['err'=> 1,'mes'=> 'Xóa thất bại !']);
        echo json_encode($res);
    }else{
        unlink('../../images/products/brand/'.$data['brand_pro_img']);
        array_push($res, ['err'=> 0,'mes'=> 'Xóa thành công !']);
        echo json_encode($res);
    }
?>