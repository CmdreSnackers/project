<?php

if(isset($_GET['billplz'])) {

    $string = 'billplzid' . $_GET['billplz']['id'] . '|billplzpaid_at' . $_GET['billplz']['paid_at'] . '|billplzpaid' . $_GET['billplz']['paid'];

    $signature = hash_hmac('sha256', $string, BILLPLZ_X_SIGNATURE);


    if($signature === $_GET['billplz']['x_signature']) {


        $status = $_GET['billplz']['paid'] === 'true' ? 'completed' : 'failed';


        Products::updateOrder(
            $_GET['billplz']['id'], 
            $status
        );

        header('Location: /orders');
        exit;

    } else {
        echo 'Invalid Signature';
    }

} else {
    echo 'No Data Found';
}