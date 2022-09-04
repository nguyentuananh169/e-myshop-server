<?php 
    include('../../connect.php');
    include('../../jwt.php');
    $res = [];

    $id = $_POST['_id'];
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

    if ($id == '') {
        array_push($res, ['error'=> 1, 'messages'=> 'không tìm thấy id sản phẩm, bạn hãy tải lại trang']);
        echo json_encode($res);
        die();
    }

    if ($category == '' || $brand == '' || $name == '' || $qty == '' || $price == '') {
        array_push($res, ['error'=> 1, 'messages'=> 'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }

    if ($sale > 0) {
        $price_sale = $price - ( ($price * $sale) / 100 );
    }else{
        $price_sale = $price;
    }

    $sqlCheckName = "SELECT * FROM product WHERE pro_name='$name' AND pro_id != '$id'";
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

    if($img['name'] != ''){
        if ($img['type'] != 'image/png' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif') {
            array_push($res, ['error'=> 1, 'messages'=> 'Hình ảnh đại diện bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
            echo json_encode($res);
            die();
        }

        $sqlSelect = "SELECT * FROM product WHERE pro_id = '$id'";
        $rlSelect = mysqli_query($conn, $sqlSelect);
        $data = mysqli_fetch_assoc($rlSelect);

        unlink('../../images/products/product/'.$data['pro_img']);

        move_uploaded_file($img['tmp_name'], '../../images/products/product/'.$time.$img['name']);
    }

    if(count($imgs['name']) > 0){
        $imgsInsert = [];
        for ($i=0; $i < count($imgs['type']); $i++) { 

            if ($imgs['type'][$i] != 'image/png' && $imgs['type'][$i] != 'image/jpeg' && $imgs['type'][$i] != 'image/gif') {
                array_push($res, ['error'=> 1, 'messages'=> 'Hình ảnh đại diện bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
                echo json_encode($res);
                die();
            }else{
                array_push($imgsInsert, $time.$imgs['name'][$i]);
            }

        }

        $sqlSelect = "SELECT * FROM product WHERE pro_id = '$id'";
        $rlSelect = mysqli_query($conn, $sqlSelect);
        $data = mysqli_fetch_assoc($rlSelect);

        for ($i=0; $i < count(json_decode($data['pro_imgs'])); $i++) { 
            unlink('../../images/products/product/'.$data['pro_imgs'][$i]);
        }

        for ($j=0; $j < count($imgs['tmp_name']); $j++) { 
            move_uploaded_file($imgs['tmp_name'][$j], '../../images/products/product/'.$time.$imgs['name'][$j]);
        }
    }

    if($img['name'] == '' && count($imgs['name']) <= 0){

        $sql = "UPDATE product SET cate_pro_id='$category', brand_pro_id='$brand', pro_name='$name', pro_price='$price_sale', pro_cost='$price', pro_qty='$qty', pro_sale='$sale', pro_status='$status', pro_attr='$attribute', pro_promotion='$promotion', pro_des='$description' WHERE pro_id='$id'";

        $rl = mysqli_query($conn, $sql);

        array_push($res, ['error'=> 0,'messages'=> 'Xửa sản phẩm thành công !'
        ]);
        echo json_encode($res);

    }
    elseif ($img['name'] != '' && count($imgs['name']) <= 0) {

        $sql = "UPDATE product SET cate_pro_id='$category', brand_pro_id='$brand', pro_name='$name', pro_price='$price_sale', pro_cost='$price', pro_qty='$qty', pro_sale='$sale', pro_status='$status', pro_attr='$attribute', pro_promotion='$promotion', pro_des='$description', pro_img='".$time.$img['name']."' WHERE pro_id='$id'";

        $rl = mysqli_query($conn, $sql);

        array_push($res, ['error'=> 0,'messages'=> 'Xửa sản phẩm thành công !'
        ]);
        echo json_encode($res);


    }
    elseif ($img['name'] == '' && count($imgs['name']) > 0) {

        $imgsInsert = json_encode($imgsInsert);
        $sql = "UPDATE product SET cate_pro_id='$category', brand_pro_id='$brand', pro_name='$name', pro_price='$price_sale', pro_cost='$price', pro_qty='$qty', pro_sale='$sale', pro_status='$status', pro_attr='$attribute', pro_promotion='$promotion', pro_des='$description', pro_imgs='$imgsInsert' WHERE pro_id='$id'";

        $rl = mysqli_query($conn, $sql);

        array_push($res, ['error'=> 0,'messages'=> 'Xửa sản phẩm thành công !'
        ]);
        echo json_encode($res);
    }
    else{

        $imgsInsert = json_encode($imgsInsert);
        $sql = "UPDATE product SET cate_pro_id='$category', brand_pro_id='$brand', pro_name='$name', pro_price='$price_sale', pro_cost='$price', pro_qty='$qty', pro_sale='$sale', pro_status='$status', pro_attr='$attribute', pro_promotion='$promotion', pro_des='$description', pro_imgs='$imgsInsert', pro_img='".$time.$img['name']."' WHERE pro_id='$id'";

        $rl = mysqli_query($conn, $sql);

        array_push($res, ['error'=> 0,'messages'=> 'Xửa sản phẩm thành công !'
        ]);
        echo json_encode($res);

    }
?>