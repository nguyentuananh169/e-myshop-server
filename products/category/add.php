<?php 
include('../../connect.php');
include('../../jwt.php');
    $res = [];

    $name = $_POST['_name'] ;
    $img = $_FILES['_img'] ;
    $status = $_POST['_status'] ;

    $time = time();

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

    if ($name == '' || $img == '' || $status == '') {
        array_push($res, ['err'=> 1, 'mess'=> 'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }

    if ($img['type'] != 'image/png' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif') {
        array_push($res, ['err'=> 1, 'mess'=> 'File bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
        echo json_encode($res);
        die();
    }

    $sqlCheckName = "SELECT * FROM category_product WHERE cate_pro_name='$name'";
    $rlCheckName = mysqli_query($conn, $sqlCheckName);
    $num = mysqli_num_rows($rlCheckName);

    if ($num > 0) {
        array_push($res, [
            'err'=> 1,
            'mess'=> 'Tên danh mục đã tồn tại. Vui lòng nhập lại tên danh mục !'
        ]);
        echo json_encode($res);
        die();
    }

    $sql = "INSERT INTO category_product(cate_pro_name, cate_pro_img, cate_pro_status)
            VALUES('$name', '".$time.$img['name']."', '$status')";
    $rl = mysqli_query($conn, $sql);

    $idIsert = mysqli_insert_id($conn);

    if ($idIsert > 0) {
        move_uploaded_file($img['tmp_name'], '../../images/products/category/'.$time.$img['name']);
        array_push($res, ['err'=> 0,'mess'=> 'Thêm danh mục thành công !'
        ]);
        echo json_encode($res);
        die();
    }else{
        echo json_encode($dataCate);
    }
?>