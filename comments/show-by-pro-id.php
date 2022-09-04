<?php 
    include('../connect.php');
    include('../jwt.php');
    include('../library.php');
    $res=[
        'commentsList'=>[],
        'limit' => 10,
        'page' => 1,
        'totalPage' => 0,
        'totalComments' => 0,
    ];
    $g_pro_id = $_GET['_pro_id'];
    
    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    $sql = "SELECT * FROM comments WHERE pro_id = '$g_pro_id' AND cmt_parent_id = 0";
    $rl = mysqli_query($conn, $sql);
    $res['totalComments'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalComments'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];
    
    $sql = "SELECT * FROM comments INNER JOIN user ON comments.user_id = user.user_id WHERE pro_id = '$g_pro_id' AND cmt_parent_id = 0 ORDER BY cmt_id DESC LIMIT $start, $limit";
    $rl = mysqli_query($conn, $sql);
    $total = $res['totalComments'] = mysqli_num_rows($rl);
    while ($row = mysqli_fetch_assoc($rl)) {
        $cmt_id = $row['cmt_id'];
        $pro_id = $row['pro_id'];
        $user_id = $row['user_id'];
        $user_name = $row['user_name'];
        $user_avatar = $row['user_avatar'];
        $user_level = $row['user_level'];
        $cmt_parent_id = $row['cmt_parent_id'];
        $cmt_content = $row['cmt_content'];
        $cmt_status = $row['cmt_status'];
        $cmt_created_at = $row['cmt_created_at'];
        $cmt_updated_at = $row['cmt_updated_at'];
        $sql2 = "SELECT * FROM comments INNER JOIN user ON comments.user_id = user.user_id WHERE pro_id = '$g_pro_id' AND cmt_parent_id = '$cmt_id' ORDER BY cmt_id ASC";
        $rl2 = mysqli_query($conn, $sql2);
        $reply = [];
        $isAdminReply = false;
        while ($row2 = mysqli_fetch_assoc($rl2)) {
            $cmt_id2 = $row2['cmt_id'];
            $pro_id2 = $row2['pro_id'];
            $user_id2 = $row2['user_id'];
            $user_name2 = $row2['user_name'];
            $user_avatar2 = $row2['user_avatar'];
            $user_level2 = $row2['user_level'];
            $cmt_parent_id2 = $row2['cmt_parent_id'];
            $cmt_content2 = $row2['cmt_content'];
            $cmt_status2 = $row2['cmt_status'];
            $cmt_created_at2 = $row2['cmt_created_at'];
            $cmt_updated_at2 = $row2['cmt_updated_at'];
            if ($user_level2 > 1) {
                $isAdminReply = true;
            }
            array_push($reply, [
                'cmt_id'=>$cmt_id2,
                'pro_id'=>$pro_id2,
                'user_id'=>$user_id2,
                'user_name'=>$user_name2,
                'user_avatar'=>$user_avatar2,
                'user_level'=>$user_level2,
                'cmt_parent_id'=>$cmt_parent_id2,
                'cmt_content'=>$cmt_content2,
                'cmt_status'=>$cmt_status2,
                'cmt_created_at'=>$cmt_created_at2,
                'cmt_updated_at'=>$cmt_updated_at2,
                'baseURLImg'=>URLImgUser(),
            ]);
        }
        array_push($res['commentsList'], [
            'cmt_id'=>$cmt_id,
            'pro_id'=>$pro_id,
            'user_id'=>$user_id,
            'user_name'=>$user_name,
            'user_avatar'=>$user_avatar,
            'user_level'=>$user_level,
            'cmt_parent_id'=>$cmt_parent_id,
            'cmt_content'=>$cmt_content,
            'cmt_status'=>$cmt_status,
            'admin_reply' => $isAdminReply,
            'cmt_created_at'=>$cmt_created_at,
            'cmt_updated_at'=>$cmt_updated_at,
            'baseURLImg'=>URLImgUser(),
            'reply'=>$reply
        ]);
    }

    echo json_encode($res);
    die();

?>