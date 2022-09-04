<?php 
    include('../../connect.php');
    include('../../library.php');
    $res = [];

    $id = $_GET['_id'];

    $sql = "SELECT * FROM news WHERE news_id='$id'";
    $rl = mysqli_query($conn, $sql);
    $news = mysqli_fetch_assoc($rl);
    $num = mysqli_num_rows($rl);

    if ($num <= 0) {
        echo json_encode($res);
        die();
    }

    $sqlCategory = "SELECT * FROM category_news WHERE cate_id = ".$news['cate_id'];
    $rlCategory = mysqli_query($conn, $sqlCategory);
    $category = mysqli_fetch_assoc($rlCategory);
    $cate_name = $category['cate_name'];

    $sqlUser = "SELECT * FROM user WHERE user_id = ".$news['user_id'];
    $rlUser = mysqli_query($conn, $sqlUser);
    $user = mysqli_fetch_assoc($rlUser);
    $user_name = $user['user_name'];
    $user_avatar = $user['user_avatar'];

    array_push($res, [
        'news_id' => $news['news_id'],
        'cate_id' => $news['cate_id'],
        'cate_name' => $cate_name,
        'user_id' => $news['user_id'],
        'user_name' => $user_name,
        'user_avatar' => $user_avatar,
        'news_title' => $news['news_title'],
        'news_summary' => $news['news_summary'],
        'news_content' => $news['news_content'],
        'news_img' => $news['news_img'],
        'news_status' =>  $news['news_status'],
        'news_views' => $news['news_views'],
        'news_created_at' => $news['news_created_at'],
        'news_updated_at' => $news['news_updated_at'],
        'baseURLImgUser' => URLImgUser(),
        'baseURLImg' => URLImgNews()
    ]);

    echo json_encode($res);
?>