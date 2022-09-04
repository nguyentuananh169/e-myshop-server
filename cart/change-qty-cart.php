<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $qty=$_POST['_qty'];
    $id=$_POST['_id'];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'message'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }

    $sql = "SELECT * FROM product WHERE pro_id='$id'";
    $rl = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($rl);

    if($qty > $data['pro_qty']){
        array_push($res, ['error'=>1, 'message'=>'Số lượng trong kho không đủ']);
        echo json_encode($res);
    }else{
        array_push($res, ['error'=>0, 'message'=>'ok']);
        echo json_encode($res);
    }
    
?>