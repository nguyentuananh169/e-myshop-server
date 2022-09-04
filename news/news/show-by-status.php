<?php 
    include('../../connect.php');
    include('../../library.php');
    $res = [
        'totalNews' => '',
        'totalPage' => '',
        'page' => '',
        'limit' => '',
        'dataNews'=> []
    ];

    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;
    
    $sql = "SELECT * FROM news WHERE news_status='0'";
    $rl = mysqli_query($conn, $sql);
    $res['totalNews'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalNews'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $sql2 = "SELECT * FROM news WHERE news_status='0' ORDER BY news_id DESC LIMIT $start,".$res['limit'];
    $rl2 = mysqli_query($conn, $sql2);

    while($row = mysqli_fetch_assoc($rl2)){
        $news_id = $row['news_id'];
        $cate_id = $row['cate_id'];
        $user_id = $row['user_id'];
        $news_title = $row['news_title'];
        $news_summary = $row['news_summary'];
        $news_content = $row['news_content'];
        $news_img = $row['news_img'];
        $news_status =  $row['news_status'];
        $news_views = $row['news_views'];
        $news_created_at =  $row['news_created_at'];
        $news_updated_at = $row['news_updated_at'];

        $sqlCategory = "SELECT * FROM category_news WHERE cate_id = '$cate_id'";
        $rlCategory = mysqli_query($conn, $sqlCategory);
        $data = mysqli_fetch_assoc($rlCategory);
        $cate_name = $data['cate_name'];

        $sqlUser = "SELECT * FROM user WHERE user_id = '$user_id'";
        $rlUser = mysqli_query($conn, $sqlUser);
        $data2 = mysqli_fetch_assoc($rlUser);
        $user_name = $data2['user_name'];
        $user_avatar = $data2['user_avatar'];

        array_push($res['dataNews'], [
            'news_id' => $news_id,
            'cate_id' => $cate_id,
            'cate_name' => $cate_name,
            'user_id' => $user_id,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar,
            'news_title' => $news_title,
            'news_summary' => $news_summary,
            'news_content' => $news_content,
            'news_img' => $news_img,
            'news_status' =>  $news_status,
            'news_views' => $news_views,
            'news_created_at' =>  $news_created_at,
            'news_updated_at' => $news_updated_at,
            'baseURLImgUser' => URLImgUser(),
            'baseURLImg' => URLImgNews()
        ]);
    }

    echo json_encode($res);
?>