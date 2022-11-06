<?php 
function cors(){
// Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
} 
	cors(); 
	
	
$data_row 		= 	file_get_contents("php://input");
$decoded 	    = 	json_decode($data_row, true);
		
$orderId = '121';
$userId = $decoded['userId'];
$orderDetails = array();
$orderDetails["products"] = $decoded['orderDetail'];
$deliveryDetails["address"] = $decoded['deliveryDetails'];
$deliveryDetails["deliverySlot"] = $decoded['deliverySlot'];
$paymentStatus = $decoded['paymentStatus'];
$totalAmount = $decoded['totalAmount'];

//print_r($deliveryDetails);
$deliveryDetails = json_encode($deliveryDetails);
$cart = json_encode($orderDetails);
//print_r($deliveryDetails);
//echo $cart;
//die;

//echo "You have CORS!";

	$servername = "localhost";
	$username = "majtechn_retailb2b";
	$password = "0,&2LI{y+%=b";
	$dbname = "majtechn_retailb2b";
	// Check connection
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	  $arr = ["status"=>"connect error"];
	  
	}
	
	/*
	$query = "SELECT `id` FROM `orders` ORDER BY `id` DESC LIMIT 1"; 
	$result = mysqli_query($conn, $query); 
	$data = mysqli_fetch_array($result); 
	*/
	
	//$sql = "INSERT INTO orders (orderId,userId,orderDetail,totalAmount,deliveryDetails, paymentStatus) VALUES ('".$orderId."','".$userId."','".$cart."','".$totalAmount."','".$deliveryDetails."','".$paymentStatus."')";
	
	$sql = "INSERT INTO orders (orderId,userId,orderDetail,totalAmount,deliveryDetails, paymentStatus)
SELECT CONCAT('TIJ',(`id`+1)) AS `orderId`,'".$userId."','".$cart."','".$totalAmount."','".$deliveryDetails."','".$paymentStatus."' FROM orders ORDER BY `id` DESC LIMIT 1";
	
	// Working Query
	/*
	INSERT INTO orders (orderId,userId,orderDetail,totalAmount,deliveryDetails, paymentStatus)
SELECT CONCAT('TIJ',(`id`+1)) AS `orderId`,'1','{"products":[{"id":43,"name":"Saffola","price":4500,"splPrice":2500,"weight":50,"weightClass":"Ltr","productImage":"1618214489.jpg","amount":1},{"id":42,"name":"chocolate","price":10000,"splPrice":7550,"weight":100,"weightClass":"Gm","productImage":"1618214503.png","amount":1}]}','10050','','COD' FROM orders ORDER BY `id` DESC
 LIMIT 1
 */
	
	//echo $sql;
	
	//die;
	if ($conn->query($sql) === TRUE) {
		$arr = ["status"=>"success"];
	  
	} else {
		$arr = ["status"=>"error"];
	}
echo json_encode($arr);


die;



/*
$headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin'
        ];
*/