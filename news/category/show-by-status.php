<?php 
    include('../../connect.php');
    $res = [];

    $sql = "SELECT * FROM category_news WHERE cate_status = '0'";
    $rl = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($rl)){
        $cate_id = $row['cate_id'];
        $cate_name = $row['cate_name'];
        $cate_status = $row['cate_status'];
        $cate_created_at = $row['cate_created_at'];
        $cate_updated_at = $row['cate_updated_at'];
        array_push($res, ['id' => $cate_id, 'name' => $cate_name, 'status' => $cate_status, 'created' => $cate_created_at, 'updated' => $cate_updated_at]);
    }

    echo json_encode($res);

?>