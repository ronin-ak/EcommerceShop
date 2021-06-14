<?php

include '../db.php';

session_start();

extract($_POST);

if (isset($_POST['readRecord'])) {
    $data = '
        <table class="table table-bordered table-striped">
        <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Preview</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Customer</th>
            <th>Delivered</th>
        </tr>';

    $shopid = $_SESSION['shopid'];
    
    $displayquery = "SELECT * FROM `product` WHERE `shopid` = '$shopid' ORDER BY `proid` desc";
    $result = mysqli_query($con, $displayquery);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)){
            $product[] = $row['proid'];
        }
    }    
    foreach ($product as $proid) {
        $displayquery = "SELECT * FROM `ordertable` WHERE `productid` = '$proid' and `status` = 0 ORDER BY `oid` DESC";
        $result = mysqli_query($con, $displayquery);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $pid = $row['productid'];
                $uid = $row['userid'];
                $userquery = "SELECT * FROM `user` WHERE `uid` = '$uid'";
                $getuser = mysqli_query($con, $userquery);
                $urow = mysqli_fetch_assoc($getuser);
                $username = $urow['fname'] .' '. $urow['lname'];
                $proquery = "SELECT * FROM `product` WHERE `proid` = '$pid'";
                $getpro = mysqli_query($con, $proquery);
                $prow = mysqli_fetch_assoc($getpro);
                $proname = $prow['pname'];
                $image = $prow['pimagename'];
                $price = $prow['proprice'];
                $pqty = 1;


                $data .= '
                    <tr>
                        <td>' . $row['oid'] . '</td>
                        <td>' . $proname . '</td>
                        <td><img src="../uploads/products/' . $image . '" class="mx-auto d-block" widht="40px" height="40px"></td>
                        <td>' . $price . '</td>
                        <td>' . $pqty . '</td>
                        <td>' . $username . '</td>
                        <td>
                            <button onclick="Delivered(' . $row['oid'] . ')" class="btn btn-danger mx-auto d-block">
                                Delivered
                            </button>
                        </td>
                    </tr>';
            }
        }
    }

    
    $data .= '</table>';
    echo $data;
}

if (isset($_POST['id'])) {
    $productid = $_POST['id'];
    $query = "UPDATE `ordertable` SET `status` = 1 WHERE `oid` = '$productid'";
    mysqli_query($con, $query);
}
?>