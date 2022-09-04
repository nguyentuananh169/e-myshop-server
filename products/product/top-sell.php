<?php 
	include('../../connect.php');
	$res = [
		'totalProduct' => '',
        'totalPage' => '',
        'page' => '',
        'limit' => '',
		'dataProduct'=> []
	];
    $limit = isset($_GET['_limit']) && $_GET['_limit'] != '' ? (int)$_GET['_limit'] : 10;
    $page = isset($_GET['_page']) && $_GET['_page'] != '' ? (int)$_GET['_page'] : 1;

    $sql = "SELECT * FROM product WHERE pro_buyed > 0";
	$rl = mysqli_query($conn, $sql);
	$res['totalProduct'] = mysqli_num_rows($rl);
	$res['limit'] = $limit;
    $res['page'] = $page;
    $res['totalPage'] = ceil($res['totalProduct'] / $res['limit']);
    $start = ($res['page'] - 1) * $res['limit'];

	$sql2 = "SELECT * FROM product INNER JOIN category_product ON product.cate_pro_id = category_product.cate_pro_id
			INNER JOIN brand_product ON product.brand_pro_id = brand_product.brand_pro_id WHERE product.pro_buyed > 0 ORDER BY product.pro_buyed DESC LIMIT $start, $limit";
	$rl2 = mysqli_query($conn, $sql2);

	while ( $row = mysqli_fetch_assoc($rl2) ) {
		$pro_id = $row['pro_id'];
		$cate_pro_id = $row['cate_pro_id'];
		$cate_pro_name = $row['cate_pro_name'];
		$brand_pro_id = $row['brand_pro_id'];
		$brand_pro_name = $row['brand_pro_name'];
		$pro_name = $row['pro_name'];
		$pro_price = $row['pro_price'];
		$pro_cost = $row['pro_cost'];
		$pro_img = $row['pro_img'];
		$pro_imgs = $row['pro_imgs'];
		$pro_qty = $row['pro_qty'];
		$pro_sale = $row['pro_sale'];
		$pro_status = $row['pro_status'];
		$pro_attr = $row['pro_attr'];
		$pro_promotion = $row['pro_promotion'];
		$pro_buyed = $row['pro_buyed'];
		$pro_view = $row['pro_view'];
		$pro_des = $row['pro_des'];
		$pro_created_at = $row['pro_created_at'];
		$pro_updated_at = $row['pro_updated_at'];

		// Lấy số đánh giá
		$sqlRT = "SELECT * FROM rating WHERE pro_id = '$pro_id' AND r_parent_id = '0'";
		$rlRT = mysqli_query($conn, $sqlRT);
		$num = mysqli_num_rows($rlRT);
		if ($num > 0) {
			$totalRating = $num;
			$star = 0;
			while ($row = mysqli_fetch_assoc($rlRT)) {
				$star += $row['r_star']; 
			}
			$star = round($star/$totalRating, 1);
		}else{
			$totalRating = 0;
			$star = 0;
		}

		array_push($res['dataProduct'], [
			'pro_id' => $pro_id,
			'cate_pro_id' => $cate_pro_id,
			'cate_pro_name' => $cate_pro_name,
			'brand_pro_id' => $brand_pro_id,
			'brand_pro_name' => $brand_pro_name,
			'pro_name' => $pro_name,
			'pro_price' => $pro_price,
			'pro_cost' => $pro_cost,
			'pro_img' => $pro_img,
			'pro_imgs' => $pro_imgs,
			'pro_qty' => $pro_qty,
			'pro_sale' => $pro_sale,
			'pro_status' => $pro_status,
			'pro_attr' => $pro_attr,
			'pro_promotion' => $pro_promotion,
			'pro_buyed' => $pro_buyed,
			'pro_view' => $pro_view,
			'pro_des' => $pro_des,
			'total_rating' => $totalRating,
			'star' => $star,
			'pro_created_at' => $pro_created_at,
			'pro_updated_at' => $pro_updated_at
		]);
	}

	echo json_encode($res);
?>