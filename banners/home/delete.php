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
        array_push($res, ['error'=>1, 'message'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $time = time();

    $sqlSelect = "SELECT * FROM banner_home WHERE bh_id='$id'";
    $rlSelect = mysqli_query($conn, $sqlSelect);

    $num = mysqli_num_rows($rlSelect);

    if ($num <= 0) {
        array_push($res, ['error'=> 1,'message'=> 'Banner này không có trong dữ liệu! Bạn vui lòng thử tải lại trang']);
        echo json_encode($res);
        die();
    }

    $data = mysqli_fetch_assoc($rlSelect);

    $sql = "DELETE FROM banner_home WHERE bh_id='$id'";
    $rl = mysqli_query($conn, $sql);
    
    $sqlSelect2 = "SELECT * FROM banner_home WHERE bh_id='$id'";
    $rlSelect2 = mysqli_query($conn, $sqlSelect2);

    $num2 = mysqli_num_rows($rlSelect2);

    if ($num2 > 0) {
        array_push($res, ['error'=> 1,'message'=> 'Xóa thất bại !']);
        echo json_encode($res);
    }else{
        unlink('../../images/banner/'.$data['bh_img']);
        array_push($res, ['errror'=> 0,'message'=> 'Xóa thành công !']);
        echo json_encode($res);
    }
?>