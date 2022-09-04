<?php 
    include('../../connect.php');
    include('../../library.php');
    $res = [];
    $status = isset($_GET['status']) ? $_GET['status'] : 0;
    $sql = "SELECT * FROM category_product WHERE cate_pro_status = '$status' ORDER BY cate_pro_id DESC";
    $rl = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($rl)){
        $cate_pro_id = $row['cate_pro_id'];
        $cate_pro_name = $row['cate_pro_name'];
        $cate_pro_img = $row['cate_pro_img'];
        $cate_pro_status = $row['cate_pro_status'];
        $cate_pro_created_at = $row['cate_pro_created_at'];
        $cate_pro_updated_at = $row['cate_pro_updated_at'];
        array_push($res, ['id' => $cate_pro_id, 'name' => $cate_pro_name, 'img' => $cate_pro_img, 'status' => $cate_pro_status, 'created' => $cate_pro_created_at, 'updated' => $cate_pro_updated_at,  'baseURLImg' => URLImgCatePro()]);
    }

    echo json_encode($res);

?>