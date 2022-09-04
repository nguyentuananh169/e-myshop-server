<?php 
    include('../../connect.php');
    include('../../jwt.php');
    $res = [];

    $id = $_POST['_id'];
    $category = $_POST['_category'];
    $title = $_POST['_title'];
    $summary = $_POST['_summary'];
    $img = $_FILES['_img'];
    $status = $_POST['_status'];
    $content = $_POST['_content'];

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

    if ($id == '') {
        array_push($res, ['error'=> 1, 'message'=> 'Không tìm thấy id tin tức! Bạn hãy tải lại trang']);
        echo json_encode($res);
        die();
    }
    $img = $_FILES['_img'];

    if ($category == '' || $title == '' || $summary == '' || $status == '' || $content == '') {
        array_push($res, ['error'=> 1, 'message'=> 'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }

    $sqlCheckTitle = "SELECT * FROM news WHERE news_title='$title' AND news_id != '$id'";
    $rlCheckTitle = mysqli_query($conn, $sqlCheckTitle);
    $num = mysqli_num_rows($rlCheckTitle);

    if ($num > 0) {
        array_push($res, [
            'error'=> 1,
            'message'=> 'Tiêu đề tin tức đã tồn tại. Vui lòng nhập lại !'
        ]);
        echo json_encode($res);
        die();
    }

    if($img['name'] != ''){
        if ($img['type'] != 'image/png' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif') {
            array_push($res, ['error'=> 1, 'message'=> 'Hình ảnh đại diện bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
            echo json_encode($res);
            die();
        }

        $sqlSelect = "SELECT * FROM news WHERE news_id = '$id'";
        $rlSelect = mysqli_query($conn, $sqlSelect);
        $data = mysqli_fetch_assoc($rlSelect);

        unlink('../../images/news/'.$data['news_img']);

        move_uploaded_file($img['tmp_name'], '../../images/news/'.$time.$img['name']);

        $sqlUpdate = "UPDATE news SET cate_id='$category', news_title='$title', news_summary='$summary', news_img='".$time.$img['name']."', news_status='$status', news_content='$content' WHERE news_id = '$id'";
        $rlUpdate = mysqli_query($conn, $sqlUpdate);
        $data = mysqli_fetch_assoc($rlUpdate);

        array_push($res, ['error'=> 0, 'message'=> 'Xửa thành công']);
            echo json_encode($res);
        die();

    }else{
        $sqlUpdate = "UPDATE news SET cate_id='$category', news_title='$title', news_summary='$summary', news_status='$status', news_content='$content' WHERE news_id = '$id'";
        $rlUpdate = mysqli_query($conn, $sqlUpdate);
        $data = mysqli_fetch_assoc($rlUpdate);

        array_push($res, ['error'=> 0, 'message'=> 'Xửa thành công']);
            echo json_encode($res);
        die();
    }
?>