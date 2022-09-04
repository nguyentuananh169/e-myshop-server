<?php 
    include('../../connect.php');
    include('../../library.php');
    $res = [
        'totalCate' => '',
        'totalPage' => '',
        'page' => '',
        'limit' => '',
        'dataCate'=> []
    ];

    $name = $_GET['_name'];
    $status = $_GET['_status'];
    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    $params="WHERE cate_pro_name LIKE '%$name%'";
    if ($status != '') {
        $params.=" AND cate_pro_status = '$status'";
    }

    $sql = "SELECT * FROM category_product ".$params;
    $rl = mysqli_query($conn, $sql);
    $res['totalCate'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalCate'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $sql2 = "SELECT * FROM category_product ".$params." ORDER BY cate_pro_id DESC LIMIT $start,".$res['limit'];
    $rl2 = mysqli_query($conn, $sql2);

    while($row = mysqli_fetch_assoc($rl2)){
        $cate_pro_id = $row['cate_pro_id'];
        $cate_pro_name = $row['cate_pro_name'];
        $cate_pro_img = $row['cate_pro_img'];
        $cate_pro_status = $row['cate_pro_status'];
        $cate_pro_created_at = $row['cate_pro_created_at'];
        $cate_pro_updated_at = $row['cate_pro_updated_at'];
        array_push($res['dataCate'], ['id' => $cate_pro_id, 'name' => $cate_pro_name, 'img' => $cate_pro_img, 'status' => $cate_pro_status, 'created' => $cate_pro_created_at, 'updated' => $cate_pro_updated_at, 'baseURLImg' => URLImgCatePro()]);
    }

    echo json_encode($res);

?>