<?php 
include('../../connect.php');
include('../../jwt.php');
    $res = [];
    $id = $_GET['_id'];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['err'=>1, 'mess'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['err'=>1, 'mess'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }

    $sqlCheck = "SELECT * FROM product WHERE cate_pro_id='$id'";
    $rlCheck = mysqli_query($conn, $sqlCheck);

    $check = mysqli_num_rows($rlCheck);

    if ($check > 0) {
        array_push($res, ['err'=> 1,'mess'=> 'Danh mục này còn sản phẩm, bạn hãy xóa sản phẩm của danh mục này trước']);
        echo json_encode($res);
        die();
    }

    $sqlSelect = "SELECT * FROM category_product WHERE cate_pro_id='$id'";
    $rlSelect = mysqli_query($conn, $sqlSelect);
    $data = mysqli_fetch_assoc($rlSelect);
    unlink('../../images/products/category/'.$data['cate_pro_img']);

    $sql = "DELETE FROM category_product WHERE cate_pro_id='$id'";
    $rl = mysqli_query($conn, $sql);
    
    $sqlSelect2 = "SELECT * FROM category_product WHERE cate_pro_id='$id'";
    $rlSelect2 = mysqli_query($conn, $sqlSelect2);

    $num2 = mysqli_num_rows($rlSelect2);
    if ($num2 > 0) {
        array_push($res, ['err'=> 1,'mess'=> 'Xóa thất bại !']);
        echo json_encode($res);
    }else{
        array_push($res, ['err'=> 0,'mess'=> 'Xóa thành công !']);
        echo json_encode($res);
    }
?>