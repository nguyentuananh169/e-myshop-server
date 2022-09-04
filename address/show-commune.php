<?php 
include('../connect.php');
    $res=[];
    $district_id=$_GET['_district_id'];
    $sql = "SELECT * FROM commune WHERE district_id='$district_id'";
    $rl = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rl)) {
        $commune_id = $row['commune_id'];
        $commune_name = $row['commune_name'];
        $commune_type = $row['commune_type'];

        array_push($res, ['commune_id' => $commune_id, 'commune_name' => $commune_name, 'commune_type' => $commune_type]);
    }
    echo json_encode($res);
?>
