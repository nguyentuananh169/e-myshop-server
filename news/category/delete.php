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
        array_push($res, ['error'=>1, 'mes'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'mes'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }

    $sqlCheck = "SELECT * FROM news WHERE cate_id='$id'";
    $rlCheck = mysqli_query($conn, $sqlCheck);

    $check = mysqli_num_rows($rlCheck);

    if ($check > 0) {
        array_push($res, ['error'=> 1,'mes'=> 'Danh mục này còn bài viết, bạn hãy xóa bài viết của danh mục này trước']);
        echo json_encode($res);
        die();
    }

    $sql = "DELETE FROM category_news WHERE cate_id='$id'";
    $rl = mysqli_query($conn, $sql);
    
    $sql2 = "SELECT * FROM category_news WHERE cate_id='$id'";
    $rl2 = mysqli_query($conn, $sql2);

    $num2 = mysqli_num_rows($rl2);
    if ($num2 > 0) {
        array_push($res, ['error'=> 1,'mes'=> 'Xóa thất bại !']);
        echo json_encode($res);
    }else{
        array_push($res, ['error'=> 0,'mes'=> 'Xóa thành công !']);
        echo json_encode($res);
    }
?>