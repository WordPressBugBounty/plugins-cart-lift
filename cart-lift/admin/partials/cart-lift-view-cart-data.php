<?php

?>

<table class="wp-list-table widefat fixed striped toplevel_page_cart_lift">
    <thead>
        <tr>
            <td><?php echo __( 'ID', 'cart-lift' ); ?></td>
            <td><?php echo __( 'Name', 'cart-lift' ); ?></td>
            <td><?php echo __( 'Email', 'cart-lift' ); ?></td>
            <td><?php echo __( 'Cart items', 'cart-lift' ); ?></td>
            <td><?php echo __( 'Cart total', 'cart-lift' ); ?></td>
            <td><?php echo __( 'Time', 'cart-lift' ); ?></td>
            <td><?php echo __( 'Unsubscribed', 'cart-lift' ); ?></td>
            <td><?php echo __( 'Provider', 'cart-lift' ); ?></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>#<?php echo $cart_id; ?></td>
            <td><?php echo ''; ?></td>
            <td><?php echo $email; ?></td>
            <td><code><?php echo $cart_contents; ?></code></td>
            <td><?php echo $cart_total; ?></td>
            <td><?php echo $time; ?></td>
            <td><?php echo $unsubscribed; ?></td>
            <td><?php echo $provider; ?></td>
        </tr>
    </tbody>
</table>
