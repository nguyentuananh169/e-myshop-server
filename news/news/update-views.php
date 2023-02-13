<?php 
    include('../../connect.php');
    
    $news_id = $_POST['_id'];
        
    $sql = "SELECT * FROM news WHERE news_id = '$news_id' ";
    $rl = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($rl);
    
    $update_view = $data['news_views'] + 1;
    
    $sqlUpdate = "UPDATE news SET news_views = '$update_view' WHERE news_id = '$news_id' ";
    $rlUpdate = mysqli_query($conn, $sqlUpdate);
   
?>