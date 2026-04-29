<?php
/*
Plugin Name: PayHere Custom Integration
*/

add_shortcode('payhere_button', function () {

    $merchant_id = '1235476';
    $merchant_secret = 'MjM5MjYxOTE3MTExMzE3MzkxMDI3OTk0NzE3MzQzNTQwMjIwNTU4';

    $order_id = 'ORDER_' . time();
    $amount = '50.00';
    $currency = 'LKR';

    // Generate secure hash
    $hash = strtoupper(
        md5(
            $merchant_id .
            $order_id .
            number_format((float) $amount, 2, '.', '') .
            $currency .
            strtoupper(md5($merchant_secret))
        )
    );

    ob_start();
    ?>

    <form method="post" action="https://sandbox.payhere.lk/pay/checkout">
        <input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>">
        <input type="hidden" name="return_url" value="<?php echo site_url('/payment-success'); ?>">
        <input type="hidden" name="cancel_url" value="<?php echo site_url('/payment-cancel'); ?>">
        <input type="hidden" name="notify_url" value="<?php echo site_url('/wp-json/payhere/v1/notify'); ?>">

        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <input type="hidden" name="items" value="Test Product">
        <input type="hidden" name="currency" value="<?php echo $currency; ?>">
        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <input type="hidden" name="hash" value="<?php echo $hash; ?>">

        <input type="hidden" name="first_name" value="Test">
        <input type="hidden" name="last_name" value="User">
        <input type="hidden" name="email" value="test@example.com">
        <input type="hidden" name="phone" value="0771234567">
        <input type="hidden" name="address" value="Colombo">
        <input type="hidden" name="city" value="Colombo">
        <input type="hidden" name="country" value="Sri Lanka">

        <button type="submit">Pay with PayHere</button>
    </form>

    <?php
    return ob_get_clean();
});

add_action('rest_api_init', function () {
    register_rest_route('payhere/v1', '/notify', [
        'methods' => 'POST',
        'callback' => 'payhere_notify_handler',
        'permission_callback' => '__return_true',
    ]);
});

function payhere_notify_handler($request)
{

    $data = $request->get_params();

    // Log data for debugging
    error_log('PayHere Notify: ' . print_r($data, true));

    return [
        'status' => 'OK'
    ];
}