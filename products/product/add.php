<?php 
include('../../connect.php');
include('../../jwt.php');

    $res = [];
    
    $category = $_POST['_category'];
    $brand = $_POST['_brand'];
    $name = $_POST['_name'];
    $qty = $_POST['_qty'];
    $price = $_POST['_price'];
    $sale = $_POST['_sale'];
    $img = $_FILES['_img'];
    $imgs = $_FILES['_imgs'];
    $status = $_POST['_status'];
    $promotion = $_POST['_promotion'];
    $attribute = $_POST['_attribute'];
    $description = $_POST['_description'];

    $imgsInsert = [];

    $time = time();

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

    if ($category == '' || $brand == '' || $name == '' || $qty == '' || $price == '' || $img['name'] == '') {
        array_push($res, ['error'=> 1, 'messages'=> 'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }

    if ($img['type'] != 'image/png' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif') {
        array_push($res, ['error'=> 1, 'messages'=> 'Hình ảnh đại diện bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
        echo json_encode($res);
        die();
    }

    for ($i=0; $i < count($imgs['type']) ; $i++) { 
        if ($imgs['type'][$i] != 'image/png' && $imgs['type'][$i] != 'image/jpeg' && $imgs['type'][$i] != 'image/gif') {
            array_push($res, ['error'=> 1, 'messages'=> 'Hình ảnh chi tiết bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
            echo json_encode($res);
            die();
        }else{
            array_push($imgsInsert, $time.$imgs['name'][$i]);
        }
    }

    if ($sale > 0) {
        $price_sale = $price - ( ($price * $sale) / 100 );
    }else{
        $price_sale = $price;
    }

    $sqlCheckName = "SELECT * FROM product WHERE pro_name='$name'";
    $rlCheckName = mysqli_query($conn, $sqlCheckName);
    $num = mysqli_num_rows($rlCheckName);

    if ($num > 0) {
        array_push($res, [
            'error'=> 1,
            'messages'=> 'Tên sản phẩm đã tồn tại. Vui lòng nhập lại !'
        ]);
        echo json_encode($res);
        die();
    }

    $imgsInsert = json_encode($imgsInsert);
    $sql = "INSERT INTO product(cate_pro_id, brand_pro_id, pro_name, pro_price, pro_cost, pro_img, pro_imgs, pro_qty, pro_sale, pro_status, pro_attr, pro_promotion, pro_des)
            VALUES('$category', '$brand', '$name', '$price_sale', '$price', '".$time.$img['name']."', '$imgsInsert', '$qty', '$sale', '$status', '$attribute', '$promotion', '$description')";
    $rl = mysqli_query($conn, $sql);

    $idIsert = mysqli_insert_id($conn);

    if ($idIsert > 0) {
        move_uploaded_file($img['tmp_name'], '../../images/products/product/'.$time.$img['name']);
        for ($i=0; $i < count($imgs['tmp_name']) ; $i++) { 
            move_uploaded_file($imgs['tmp_name'][$i], '../../images/products/product/'.$time.$imgs['name'][$i]);
        }
        array_push($res, ['error'=> 0,'messages'=> 'Thêm sản phẩm thành công !'
        ]);
        echo json_encode($res);
        die();
    }else{
        array_push($res, ['error'=> 1,'messages'=> 'Thêm sản phẩm thất bại !'
        ]);
        echo json_encode($res);
    }
?>