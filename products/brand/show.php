<?php 
    include('../../connect.php');
    include('../../library.php');
    $res = [
        'totalBrand' => '',
        'totalPage' => '',
        'page' => '',
        'limit' => '',
        'dataBrand'=> []
    ];

    $params_ct = '';
    $name = $_GET['_name'];
    $cate_id = $_GET['_cate_id'];
    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    if($cate_id != ''){
        $params_ct .= "AND brand_product.cate_pro_id = '$cate_id'";
    }

    $sql = "SELECT * FROM brand_product INNER JOIN category_product 
            ON brand_product.cate_pro_id=category_product.cate_pro_id WHERE brand_pro_name LIKE '%$name%' $params_ct";
    $rl = mysqli_query($conn, $sql);
    $res['totalBrand'] = mysqli_num_rows($rl);
    $res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalBrand'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

    $sql2 = "SELECT * FROM brand_product INNER JOIN category_product 
            ON brand_product.cate_pro_id=category_product.cate_pro_id WHERE brand_pro_name LIKE '%$name%' $params_ct ORDER BY brand_pro_id DESC LIMIT $start,".$res['limit'];
    $rl2 = mysqli_query($conn, $sql2);

    while($row = mysqli_fetch_assoc($rl2)){
        $brand_pro_id = $row['brand_pro_id'];
        $cate_pro_id = $row['cate_pro_id'];
        $cate_pro_name = $row['cate_pro_name'];
        $brand_pro_name = $row['brand_pro_name'];
        $brand_pro_img = $row['brand_pro_img'];
        $brand_pro_created_at = $row['brand_pro_created_at'];
        $brand_pro_updated_at = $row['brand_pro_updated_at'];
        array_push($res['dataBrand'], ['id' => $brand_pro_id, 'cate_id' => $cate_pro_id, 'cate_name' => $cate_pro_name,'name' => $brand_pro_name, 'img' => $brand_pro_img, 'created' => $brand_pro_created_at, 'updated' => $brand_pro_updated_at,'baseURLImg' => URLImgBrandPro()]);
    }

    echo json_encode($res);

?>