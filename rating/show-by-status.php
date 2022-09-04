<?php 
    include('../connect.php');
    include('../jwt.php');
    include('../library.php');
    $res=[];
    $status = isset($_GET['_status']) ? $_GET['_status'] : 0;

    $sql = "SELECT * FROM rating INNER JOIN user ON rating.user_id = user.user_id WHERE rating.r_parent_id = '0' AND r_status = '$status' ORDER BY r_id DESC";
    $rl = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rl)) {
        $r_id = $row['r_id'];
        $pro_id = $row['pro_id'];
        $user_id = $row['user_id'];
        $user_name = $row['user_name'];
        $user_avatar = $row['user_avatar'];
        $user_level = $row['user_level'];
        $r_parent_id = $row['r_parent_id'];
        $r_content = $row['r_content'];
        $r_star = $row['r_star'];
        $r_status = $row['r_status'];
        $r_created_at = $row['r_created_at'];
        $r_updated_at = $row['r_updated_at'];

        $sql2 = "SELECT * FROM rating INNER JOIN user ON rating.user_id = user.user_id WHERE r_parent_id = '$r_id' ORDER BY r_id ASC";
        $rl2 = mysqli_query($conn, $sql2);
        $reply = [];
        while ($row2 = mysqli_fetch_assoc($rl2)) {
            $r_id2 = $row2['r_id'];
            $pro_id2 = $row2['pro_id'];
            $user_id2 = $row2['user_id'];
            $user_name2 = $row2['user_name'];
            $user_avatar2 = $row2['user_avatar'];
            $user_level2 = $row2['user_level'];
            $r_parent_id2 = $row2['r_parent_id'];
            $r_content2 = $row2['r_content'];
            $r_star2 = $row2['r_star'];
            $r_status2 = $row2['r_status'];
            $r_created_at2 = $row2['r_created_at'];
            $r_updated_at2 = $row2['r_updated_at'];
            array_push($reply, [
                'r_id'=>$r_id2,
                'pro_id'=>$pro_id2,
                'user_id'=>$user_id2,
                'user_name'=>$user_name2,
                'user_avatar'=>$user_avatar2,
                'user_level'=>$user_level2,
                'r_parent_id'=>$r_parent_id2,
                'r_content'=>$r_content2,
                'r_star'=>$r_star2,
                'r_status'=>$r_status2,
                'r_created_at'=>$r_created_at2,
                'r_updated_at'=>$r_updated_at2,
                'baseURLImg' => URLImgUser()
            ]);
        }
        array_push($res, [
            'r_id'=>$r_id,
            'pro_id'=>$pro_id,
            'user_id'=>$user_id,
            'user_name'=>$user_name,
            'user_avatar'=>$user_avatar,
            'user_level'=>$user_level,
            'r_parent_id'=>$r_parent_id,
            'r_content'=>$r_content,
            'r_star'=>$r_star,
            'r_status'=>$r_status,
            'r_created_at'=>$r_created_at,
            'r_updated_at'=>$r_updated_at,
            'baseURLImg' => URLImgUser(), 
            'reply'=>$reply
        ]);
    }

    echo json_encode($res);
    die();

?>