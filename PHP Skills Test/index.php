<!DOCTYPE html>
<html>
<head>
    <title>Product Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <h1>Product Form</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="product_name">Product Name:</label>
            <input type="text" class="form-control" id="product_name" name="product_name" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity in Stock:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="price">Price per Item:</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<br>
    
    
<?php
//when user clicks submit, creates an array with user input   
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $dateTimeSubmitted = date("Y-m-d H:i:s");
    $data =array(
        'product_name' => $product_name,
        'quantity' => $quantity,
        'price' => $price,
        'datetime' => $dateTimeSubmitted
    );
    
    //get data from data.json, and decode it to an array.
    $existing_data_json = file_get_contents('data.json');
    $temp_array = json_decode($existing_data_json,true);
    
    //if this is the first entry, json encode $data and stage for addition to data.json
    if ($temp_array == null){
        $data_clean = array(0 => $data);
        $json_data = json_encode($data_clean);
    }else{
        //check data.json to see if product_name already exists
        foreach ($temp_array as $key=>$product){
            if($product['product_name'] == $data['product_name']){
                //if product_name exists removes it from array
                unset($temp_array[$key]);
            }
        }
        //add new data to array and json encode
        array_push ($temp_array,$data);
        $json_data = json_encode($temp_array);
    }
    //update data.json
    file_put_contents('data.json', $json_data);
}

    
//get data.json
$json = file_get_contents('data.json');
$data = json_decode($json, true);
//confirms that data is available before showing table
if ($data != null && count($data) > 0) {
    //print table header
    echo '<table>';
    echo '<tr>';
    echo '<th>||Product name</th>';
    echo '<th>||Quantity in stock</th>';
    echo '<th>||Price per item</th>';
    echo '<th>||Datetime Submitted</th>';
    echo '<th>||Total Value</th>';
    echo '</tr>';
    //set up array for grand total calculation
    $totalValues = array();
    
    //print table row for each product
    foreach ($data as $row) {
        //calculate total value of product
        $totalValue = $row['price'] * $row['quantity'];
        //collect total value for grand total calculation
        array_push($totalValues,$totalValue);
        //echo '<pre>'; print_r($row); echo '</pre>';
        echo '<tr>';
        echo '<td>||' . $row['product_name'] . '</td>';
        echo '<td>||' . $row['quantity'] . '</td>';
        echo '<td>||$' . $row['price'] . '</td>';
        echo '<td>||' . $row['datetime'] . '</td>';
        echo '<td>||$' . $totalValue . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    //clear value for $grandTotal
    $grandTotal = 0;
    //add all total values
    foreach ($totalValues as $value) {
        $grandTotal = $grandTotal + $value;
    }
    //print grand total
    echo 'Grand Total Value: $'.$grandTotal;
}
?>
</body>
</html>
