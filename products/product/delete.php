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
        array_push($res, ['error'=>1, 'messages'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'messages'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }

    $sqlSelect = "SELECT * FROM product WHERE pro_id='$id'";
    $rlSelect = mysqli_query($conn, $sqlSelect);

    $num = mysqli_num_rows($rlSelect);

    if ($num <= 0) {
        array_push($res, ['error'=> 1,'messages'=> 'Sản phẩm không có trong dữ liệu! Bạn vui lòng thử tải lại trang']);
        echo json_encode($res);
        die();
    }

    $data = mysqli_fetch_assoc($rlSelect);

    $imgs = json_decode($data['pro_imgs']);

    $sql = "DELETE FROM product WHERE pro_id='$id'";
    $rl = mysqli_query($conn, $sql);
    
    $sqlSelect2 = "SELECT * FROM product WHERE pro_id='$id'";
    $rlSelect2 = mysqli_query($conn, $sqlSelect2);

    $num2 = mysqli_num_rows($rlSelect2);

    if ($num2 > 0) {
        array_push($res, ['error'=> 1,'messages'=> 'Xóa thất bại !']);
        echo json_encode($res);
    }else{
        unlink('../../images/products/product/'.$data['pro_img']);
        for ($i=0; $i < count($imgs) ; $i++) { 
            unlink('../../images/products/product/'.$imgs[$i]);
        }

        $sql1 = "DELETE FROM comments WHERE pro_id = '$id'";
        $rl1 = mysqli_query($conn, $sql1);

        $sql2 = "DELETE FROM rating WHERE pro_id = '$id'";
        $rl2 = mysqli_query($conn, $sql2);

        $sql3 = "DELETE FROM product_wish WHERE pro_id = '$id'";
        $rl3 = mysqli_query($conn, $sql3);

        array_push($res, ['error'=> 0,'messages'=> 'Xóa thành công !']);
        echo json_encode($res);
    }
?>