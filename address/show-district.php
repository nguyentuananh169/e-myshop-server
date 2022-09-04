<?php 
include('../connect.php');
    $res=[];
    $city_id=$_GET['_city_id'];
    $sql = "SELECT * FROM district WHERE city_id='$city_id'";
    $rl = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rl)) {
        $district_id = $row['district_id'];
        $district_name = $row['district_name'];
        $district_type = $row['district_type'];

        array_push($res, ['district_id' => $district_id, 'district_name' => $district_name, 'district_type' => $district_type]);
    }
    echo json_encode($res);
?>
