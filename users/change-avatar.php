<?php 
include('../connect.php');
include('../jwt.php');
include('../library.php');
    $res = [];
    $avatar = $_FILES['_avatar'];
    $time = time();

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
    $data = mysqli_fetch_assoc($rl);

    if ($avatar['type'] != 'image/png' && $avatar['type'] != 'image/jpeg' && $avatar['type'] != 'image/gif') {
        array_push($res, ['error'=> 1, 'message'=> 'Avatar bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
        echo json_encode($res);
        die();
    }
    $sqlUpdate = "UPDATE user SET user_avatar = '".$time.$avatar['name']."' WHERE user_id = '$user_id'";
    $rlUpdate = mysqli_query($conn, $sqlUpdate);

    move_uploaded_file($avatar['tmp_name'], '../images/user/'.$time.$avatar['name']);
    unlink('../images/user/'.$data['user_avatar']);

    array_push($res, ['error'=> 0, 'message'=> 'Thay đổi avatar thành công', 'avatar'=> $time.$avatar['name'], 'baseURLImg' => URLImgUser()]);
    echo json_encode($res);
?>