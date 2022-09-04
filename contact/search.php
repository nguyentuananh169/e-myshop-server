<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[
        'contactList' => [],
        'limit' => 10,
        'page' => 1,
        'totalPage' => 0,
        'totalContact' => 0,
    ];

    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;
    $status = $_GET['_status'];
    $email = $_GET['_email'];

    $params = "WHERE c_email LIKE '%".$email."%' AND c_parent_id = 0 ";
    if ($status != '') {
        $params .= " AND c_status = '".$status."'";
    }

    $sql = "SELECT * FROM contact ".$params;
    $rl = mysqli_query($conn, $sql);
    $res['totalContact'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalContact'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $sql = "SELECT * FROM contact ".$params." ORDER BY c_id DESC LIMIT $start,".$res['limit'];
    $rl = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rl)) {
        $c_id = $row['c_id'];
        $c_name = $row['c_name'];
        $c_email = $row['c_email'];
        $c_title = $row['c_title'];
        $c_content = $row['c_content'];
        $c_status = $row['c_status'];
        $c_created_at = $row['c_created_at'];
        $c_updated_at = $row['c_updated_at'];
        $reply = [];
        $sql2 = "SELECT * FROM contact WHERE c_parent_id = '$c_id' ORDER BY c_id ASC";
        $rl2 = mysqli_query($conn, $sql2);
        while ( $row2 = mysqli_fetch_assoc($rl2)) {
            array_push($reply, [
                'c_id'=>$row2['c_id'],
                'c_name'=>$row2['c_name'],
                'c_email'=>$row2['c_email'],
                'c_title'=>$row2['c_title'],
                'c_content'=>$row2['c_content'],
                'c_status'=>$row2['c_status'],
                'c_created_at'=>$row2['c_created_at'],
                'c_updated_at'=>$row2['c_updated_at']
            ]);
        }
        array_push($res['contactList'], [
            'c_id'=>$c_id,
            'c_name'=>$c_name,
            'c_email'=>$c_email,
            'c_title'=>$c_title,
            'c_content'=>$c_content,
            'c_status'=>$c_status,
            'c_created_at'=>$c_created_at,
            'c_updated_at'=>$c_updated_at,
            'reply'=>$reply
        ]);
    }

    echo json_encode($res);
    die();

?>