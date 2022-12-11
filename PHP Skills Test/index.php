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
    $existing_data_json = file_get_contents('data.json');
    $temp_array = json_decode($existing_data_json,true);
    //echo '<pre>'; print_r($temp_array); echo '</pre>';

    if ($temp_array == null){
        $data_clean = array(0 => $data);
        $json_data = json_encode($data_clean);
    }else{
        foreach ($temp_array as $key=>$product){
            //echo 'value for product name in json';
            //echo '<pre>'; print_r($product['product_name']); echo '</pre>';
            //echo 'value for submitted product name';
            //echo '<pre>'; print_r($data['product_name']); echo '</pre>';

            // if product already in list, update values.
            if($product['product_name'] == $data['product_name']){
                unset($temp_array[$key]);
                //echo 'value updated array';
                //echo '<pre>'; print_r($temp_array); echo '</pre>';

                $json_data = json_encode($temp_array);
            }
        }
        array_push ($temp_array,$data);
        $json_data = json_encode($temp_array);

    }
    file_put_contents('data.json', $json_data);
}

$json = file_get_contents('data.json');
$data = json_decode($json, true);
//echo '<pre>'; print_r($data); echo '</pre>';
if ($data != null && count($data) > 0) {
    echo '<table>';
    echo '<tr>';
    echo '<th>||Product name</th>';
    echo '<th>||Quantity in stock</th>';
    echo '<th>||Price per item</th>';
    echo '<th>||Datetime Submitted</th>';
    echo '<th>||Total Value</th>';
    echo '</tr>';
    $totalValues = array();
    foreach ($data as $row) {
        $totalValue = $row['price'] * $row['quantity'];
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
    $grandTotal = 0;
    foreach ($totalValues as $value) {
        $grandTotal = $grandTotal + $value;
    }
    echo 'Grand Total Value: $'.$grandTotal;
}
?>
</body>
</html>
