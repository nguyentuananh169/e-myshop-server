<?php 
include('../connect.php');
    $res = [];
    $id = $_GET['_city_id'];
    $params = '';
    if ($id) {
        $params = " WHERE city_id='$id'";
    }
    $sql = "SELECT * FROM city ".$params;
    $rl = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($rl)) {
        $city_id = $row['city_id'];
        $city_name = $row['city_name'];
        $city_type = $row['city_type'];

        array_push($res, ['city_id' => $city_id, 'city_name' => $city_name, 'city_type' => $city_type]);
    }
    echo json_encode($res);
?>
