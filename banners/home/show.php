<?php 
    include('../../connect.php');
    include('../../jwt.php');
    include('../../library.php');
    $res = [
        'bannerList'=>[],
        'page'=>'',
        'limit'=>'',
        'totalPage'=>'',
        'totalBanner'=>'',
    ];

    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    $sql = "SELECT * FROM banner_home ";
    $rl = mysqli_query($conn, $sql);
    $res['totalBanner'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalBanner'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $sql = "SELECT * FROM banner_home ORDER BY bh_id DESC LIMIT $start,".$res['limit'];
    $rl = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rl)) {
        $bh_id = $row['bh_id'];
        $bh_img = $row['bh_img'];
        $bh_link = $row['bh_link'];
        $bh_created_at = $row['bh_created_at'];
        $bh_updated_at = $row['bh_updated_at'];

        array_push($res['bannerList'], ['bh_id' => $bh_id, 'bh_img' => $bh_img, 'bh_link' => $bh_link, 'bh_created_at' => $bh_created_at, 'bh_updated_at' => $bh_updated_at, 'baseURLImg' => URLImgBanner()]);
    }
    echo json_encode($res);

?>