<?php 
    include('../../connect.php');
    $res = [
        'totalCate' => '',
        'totalPage' => '',
        'page' => '',
        'limit' => '',
        'dataCate'=> []
    ];

    $params = '';
    $name = trim($_GET['_name']);
    $status = $_GET['_status'];
    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    $params="WHERE cate_name LIKE '%$name%'";
    if ($status != '') {
        $params.=" AND cate_status = '$status'";
    }

    $sql = "SELECT * FROM category_news ".$params;
    $rl = mysqli_query($conn, $sql);
    $res['totalCate'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalCate'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $sql2 = "SELECT * FROM category_news ".$params." ORDER BY cate_id DESC LIMIT $start,".$res['limit'];
    $rl2 = mysqli_query($conn, $sql2);

    while($row = mysqli_fetch_assoc($rl2)){
        $cate_id = $row['cate_id'];
        $cate_name = $row['cate_name'];
        $cate_status = $row['cate_status'];
        $cate_created_at = $row['cate_created_at'];
        $cate_updated_at = $row['cate_updated_at'];
        array_push($res['dataCate'], ['id' => $cate_id, 'name' => $cate_name, 'status' => $cate_status, 'created' => $cate_created_at, 'updated' => $cate_updated_at]);
    }

    echo json_encode($res);

?>