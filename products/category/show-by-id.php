<?php 
    include('../../connect.php');
    include('../../library.php');
    $res = [];
 
    $id = $_GET['_id'];
    $sql = "SELECT * FROM category_product WHERE cate_pro_id = '$id'";
    $rl = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($rl);
    
    array_push($res, [
        'id' => $data['cate_pro_id'], 
        'name' => $data['cate_pro_name'], 
        'img' => $data['cate_pro_img'], 
        'status' => $data['cate_pro_status'], 
        'created' => $data['cate_pro_created_at'], 
        'updated' => $data['cate_pro_updated_at'],
        'baseURLImg' => URLImgCatePro()
        ]
    );
    echo json_encode($res);

?>