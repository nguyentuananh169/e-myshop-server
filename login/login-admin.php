<?php 
    include('../connect.php');
    include('../jwt.php');
    include('../library.php');
    $res = [];

    $email = $_POST['_email'];
    $password = $_POST['_password'];

    if($email == '' || $password == ''){
        array_push($res, ['error'=> 1, 'message'=> 'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }

    $sqlCheckEmail = "SELECT * FROM user WHERE user_email = '$email' ";
    $rlCheckEmail = mysqli_query($conn, $sqlCheckEmail);
    $checkEmail = mysqli_num_rows($rlCheckEmail);

    $passwordMd5 = md5($password);
    if($checkEmail == 0){
        array_push($res, ['error'=> 1, 'message'=> 'Email bạn nhập không đúng']);
        echo json_encode($res);
        die();
    }

    $sqlCheckPassword = "SELECT * FROM user WHERE user_email = '$email' AND user_password = '$passwordMd5'";
    $rlCheckPassword = mysqli_query($conn, $sqlCheckPassword);
    $checkPassword = mysqli_num_rows($rlCheckPassword);

    if($checkPassword == 0){
        array_push($res, ['error'=> 1, 'message'=> 'Mật khẩu bạn nhập không đúng']);
        echo json_encode($res);
        die();
    }
    $data = mysqli_fetch_assoc($rlCheckPassword);

    if($data['user_status'] != 0){
        array_push($res, ['error'=> 1, 'message'=> 'Tài khoản của bạn đã bị khóa. Liên hệ Admin để giải quyết']);
        echo json_encode($res);
        die();
    }

    if($data['user_level'] == 1){
        array_push($res, ['error'=> 1, 'message'=> 'Bạn không có quyền truy cập vào trang Admin']);
        echo json_encode($res);
        die();
    }

    array_push($res, [
        'error' => 0,
        'access_token' => createAccessToken(['user_id'=> $data['user_id']]),
        'refresh_token' => createRefreshToken(['user_id'=> $data['user_id']]),
        'user' => [
            'user_id'=>$data['user_id'],
            'user_name'=>$data['user_name'],
            'user_email'=>$data['user_email'],
            'user_avatar'=>$data['user_avatar'],
            'baseURLImg' => URLImgUser(), 
            'user_phone'=>$data['user_phone']
            
        ],
        'auth'=> true,
        'admin' => true
    ]);
    echo json_encode($res);
?>