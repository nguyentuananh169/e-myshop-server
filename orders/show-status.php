<?php 
    include('../connect.php');
    $res=[];
    
    $sql = "SELECT * FROM orders_status ORDER BY order_status_id ASC";
    $rl = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rl)) {
        array_push($res, [
            'status_id'=>$row['order_status_id'],
            'status_name'=>$row['order_status_name']
        ]);
    }
    
    echo json_encode($res);
?>