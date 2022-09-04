<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $name=$_POST['_name'];
    $email=$_POST['_email'];
    $title=$_POST['_title'];
    $content=$_POST['_content'];

    if($name == '' || $email == '' || $title == '' || $content == ''){
        array_push($res, ['error'=>1, 'message'=>'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }
    

    $sql = "INSERT INTO contact(c_name, c_email, c_title, c_content) VALUES('$name','$email','$title','$content')";
    $rl = mysqli_query($conn, $sql);
 
    $insert_id = mysqli_insert_id($conn);

    if($insert_id > 0){
        array_push($res, ['error'=>0, 'message'=>'Gửi tin nhắn thành công']);
        echo json_encode($res);
        die();
    }else{
        array_push($res, ['error'=>1, 'message'=>'Gửi tin nhắn thất bại']);
        echo json_encode($res);
        die();
    }
    
?>