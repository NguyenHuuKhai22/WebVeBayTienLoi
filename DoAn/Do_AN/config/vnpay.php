<?php

return [
    'vnp_TmnCode' => env('VNPAY_TMN_CODE', ''),
    'vnp_HashSecret' => env('VNPAY_HASH_SECRET', ''),
    'vnp_Url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'vnp_ReturnUrl' => env('VNPAY_RETURN_URL', '/vnpay-return'),
    'vnp_ApiUrl' => env('VNPAY_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
];
