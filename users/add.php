<?php 
include('../connect.php');
include('../jwt.php');
    $res=[];
    $city_id = $_POST['_city_id'];
    $district_id = $_POST['_district_id'];
    $commune_id = $_POST['_commune_id'];
    $name = $_POST['_name']; 
    $email = $_POST['_email'];
    $password = $_POST['_password'];
    $phone = $_POST['_phone'];
    $address = $_POST['_address'];
    $status = isset($_POST['_status']) ? $_POST['_status'] : 0;
    $level = isset($_POST['_level']) ? $_POST['_level'] : 1;
    $avatar = $_FILES['_avatar'];
    $time = time();

    if($city_id == '' || $district_id == '' || $commune_id == '' || $name == '' || $email == '' || $password == '' || $phone == '' || $address == ''){
        array_push($res, ['error' => 1, 'message' => 'Bạn chưa nhập đủ thông tin!']);
        echo json_encode($res);
        die();
    }

    $sqlCheckEmail = "SELECT * FROM user WHERE user_email = '$email'";
    $rlCheckEmail = mysqli_query($conn, $sqlCheckEmail);
    $checkEmail = mysqli_num_rows($rlCheckEmail);

    if($checkEmail > 0){
        array_push($res, ['error' => 1, 'message' => 'Email bạn nhập đã tồn tại trong hệ thống!']);
        echo json_encode($res);
        die();
    }

    $sqlCheckPhone = "SELECT * FROM user WHERE user_phone = '$phone'";
    $rlCheckPhone = mysqli_query($conn, $sqlCheckPhone);
    $checkPhone = mysqli_num_rows($rlCheckPhone);

    if($checkPhone > 0){
        array_push($res, ['error' => 1, 'message' => 'Số điện thọai bạn nhập đã tồn tại trong hệ thống!']);
        echo json_encode($res);
        die();
    }
    $password_md5 = md5($password);
    if($avatar['name'] == ''){

        $sqlInsert = "INSERT INTO user (city_id, district_id, commune_id, user_name, user_email, user_password, user_phone, user_address, user_status, user_level) VALUES('$city_id', '$district_id', '$commune_id', '$name', '$email', '$password_md5', '$phone', '$address', '$status', '$level')";
        $rlInsert = mysqli_query($conn, $sqlInsert);
        
    }else{

        if ($avatar['type'] != 'image/png' && $avatar['type'] != 'image/jpeg' && $avatar['type'] != 'image/gif') {
            array_push($res, ['error'=> 1, 'message'=> 'Avatar bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
            echo json_encode($res);
            die();
        }
        $sqlInsert = "INSERT INTO user (city_id, district_id, commune_id, user_name, user_email, user_password, user_phone, user_address, user_status, user_level, user_avatar) VALUES('$city_id', '$district_id', '$commune_id', '$name', '$email', '$password_md5', '$phone', '$address', '$status', '$level', '".$time.$avatar['name']."')";
        $rlInsert = mysqli_query($conn, $sqlInsert);

        move_uploaded_file($avatar['tmp_name'], '../images/user/'.$time.$avatar['name']);
    }

    $insert_id = mysqli_insert_id($conn);
    if($insert_id > 0){
        array_push($res, ['error'=> 0, 'message'=> 'Thêm người dùng thành công']);
        echo json_encode($res);
        die();
    }else{
        array_push($res, ['error'=> 1, 'message'=> 'Thêm người dùng thất bại']);
        echo json_encode($res);
        die();
    }
?>
