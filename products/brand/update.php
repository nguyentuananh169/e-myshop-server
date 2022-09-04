<?php 
    include('../../connect.php');
    include('../../jwt.php');

    $res = [];
    $id = $_POST['_id'];
    $category_id = $_POST['_cate_id'];
    $name = $_POST['_name'];
    $img = $_FILES['_img'];

    $time = time();

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

    if ($id == '' || $category_id == '' || $name == '') {
        array_push($res, ['err'=> 1,'mes'=> 'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }

    $sqlCheckDuplicates = "SELECT * FROM brand_product WHERE brand_pro_name='$name' AND cate_pro_id = '$category_id' AND brand_pro_id != '$id'";
    $rlCheckDuplicates = mysqli_query($conn, $sqlCheckDuplicates);
    $num = mysqli_num_rows($rlCheckDuplicates);

    if ($num > 0) {
        array_push($res, ['err'=> 1,'mes'=> 'Tên thương hiệu của danh mục này đã tồn tại ! Vui nhập tên thương hiệu hoặc chọn danh mục khác']);
        echo json_encode($res);
        die();
    }

    if ($img == '') {
        $sqlUpdate = "UPDATE brand_product SET cate_pro_id='$category_id', brand_pro_name='$name' WHERE brand_pro_id='$id'";
        $rlUpdate = mysqli_query($conn, $sqlUpdate);
        array_push($res, ['err'=> 0,'mes'=> 'Xửa thương hiệu thành công !']);
        echo json_encode($res);
    }else{
        if ($img['type'] != 'image/png' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif') {
            array_push($res, ['err'=> 1,'mes'=> 'File bạn nhập không đúng định dạng (PNG, JPEG, GIF) !']);
            echo json_encode($res);
            die();
        }

        $sqlSelect = "SELECT * FROM brand_product WHERE brand_pro_id = '$id'";
        $rlSelect = mysqli_query($conn, $sqlSelect);
        $data = mysqli_fetch_assoc($rlSelect);

        unlink('../../images/products/brand/'.$data['brand_pro_img']);

        move_uploaded_file($img['tmp_name'], '../../images/products/brand/'.$time.$img['name']);

        $sqlUpdate = "UPDATE brand_product SET cate_pro_id='$category_id', brand_pro_name='$name', brand_pro_img='".$time.$img['name']."' WHERE brand_pro_id='$id'";
        $rlUpdate = mysqli_query($conn, $sqlUpdate);

        array_push($res, ['err'=> 0,'mes'=> 'Xửa thương hiệu thành công !']);
        echo json_encode($res);
    }
?>