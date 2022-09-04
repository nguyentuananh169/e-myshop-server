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
    $new_password = $_POST['_new_password'];
    $re_new_password = $_POST['_re_new_password'];
    $phone = $_POST['_phone'];
    $address = $_POST['_address'];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'message'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }

    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'message'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }

    if($city_id == '' || $district_id == '' || $commune_id == '' || $name == '' || $email == '' || $phone == '' || $address == ''){
        array_push($res, ['error' => 1, 'message' => 'Bạn chưa nhập đủ thông tin!']);
        echo json_encode($res);
        die();
    }

    $sqlCheckEmail = "SELECT * FROM user WHERE user_email = '$email' AND user_id != '$user_id'";
    $rlCheckEmail = mysqli_query($conn, $sqlCheckEmail);
    $checkEmail = mysqli_num_rows($rlCheckEmail);

    if($checkEmail > 0){
        array_push($res, ['error' => 1, 'message' => 'Email đã tồn tại']);
        echo json_encode($res);
        die();
    }

    $sqlCheckPhone = "SELECT * FROM user WHERE user_phone = '$phone' AND user_id != '$user_id'";
    $rlCheckPhone = mysqli_query($conn, $sqlCheckPhone);
    $checkPhone = mysqli_num_rows($rlCheckPhone);

    if($checkPhone > 0){
        array_push($res, ['error' => 1, 'message' => 'Số điện thọai đã tồn tại']);
        echo json_encode($res);
        die();
    }

    if($password == '' || $new_password == '' || $re_new_password == ''){

        $sqlUpdate = "UPDATE user SET user_name='$name', user_email='$email', user_phone='$phone', city_id='$city_id', district_id='$district_id', commune_id='$commune_id', user_address='$address' WHERE user_id = '$user_id'";
        $rlUpdate = mysqli_query($conn, $sqlUpdate);
        
    }else{
        $password_md5 = md5($password);

        $sqlCheckPassword = "SELECT * FROM user WHERE user_id = '$user_id'";
        $rlCheckPassword = mysqli_query($conn, $sqlCheckPassword);
        $data = mysqli_fetch_assoc($rlCheckPassword);

        if($password_md5 != $data['user_password']){
            array_push($res, ['error'=> 1, 'message'=> 'Bạn nhập sai mật khẩu cũ']);
            echo json_encode($res);
            die();
        }

        if($new_password != $re_new_password){
            array_push($res, ['error'=> 1, 'message'=> 'Mật khẩu nhập lại chưa đúng']);
            echo json_encode($res);
            die();
        }
        $new_password_md5 = md5($new_password);
        $sqlUpdate = "UPDATE user SET user_name='$name', user_email='$email', user_phone='$phone', city_id='$city_id', district_id='$district_id', commune_id='$commune_id', user_address='$address', user_password='$new_password_md5' WHERE user_id = '$user_id'";
        $rlUpdate = mysqli_query($conn, $sqlUpdate);
    }

    array_push($res, ['error'=> 0, 'message'=> 'Cập nhật thành công']);
    echo json_encode($res);
    die();
?>
