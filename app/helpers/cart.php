<?php
if (!function_exists('ensure_session')) {
    function ensure_session()
    {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
    }
}

if (!function_exists('get_cart_raw')) {
    function get_cart_raw()
    {
        ensure_session();
        return $_SESSION['cart'] ?? [];
    }
}

if (!function_exists('cart_count')) {
    function cart_count()
    {
        $cart = get_cart_raw();
        if (!is_array($cart)) {
            return 0;
        }

        $count = 0;
        foreach ($cart as $k => $v) {
            // support shapes: ['id' => qty] or [ ['id'=>..,'qty'=>..], ... ] or ['id'=>['quantity'=>..], ...]
            if (is_array($v)) {
                $count += (int)($v['quantity'] ?? $v['qty'] ?? 0);
            } elseif (is_numeric($k)) {
                // numeric index - value might be item array or qty
                if (is_numeric($v)) {
                    $count += (int)$v;
                } else {
                    $count += 1;
                }
            } else {
                // associative id => qty
                $count += (int)$v;
            }
        }

        return $count;
    }
}

if (!function_exists('cart_items')) {
    /**
     * Build cart items array from session cart and product catalog.
     * Expected $catalog: array of product arrays with at least 'id', 'price', 'name', 'image' keys.
     */
    function cart_items(array $catalog)
    {
        $cart = get_cart_raw();
        if (!is_array($cart) || empty($catalog)) {
            return [];
        }

        // Build quick lookup by id (string)
        $lookup = [];
        foreach ($catalog as $p) {
            $id = (string)($p['id'] ?? '');
            if ($id !== '') {
                $lookup[$id] = $p;
            }
        }

        $items = [];
        foreach ($cart as $key => $val) {
            $id = null;
            $qty = 0;

            if (is_array($val)) {
                // item stored as ['id'=>..., 'qty'=>N] or ['id'=>..., 'quantity'=>N]
                $id = (string)($val['id'] ?? $val['product_id'] ?? $key);
                $qty = (int)($val['qty'] ?? $val['quantity'] ?? 0);
            } else {
                // keyed by product id: '123' => 2  OR numeric index with value as qty (less common)
                $id = (string)$key;
                $qty = (int)$val;
            }

            if ($id === '' || $qty <= 0) {
                continue;
            }

            if (!isset($lookup[$id])) {
                // product not found in catalog — skip
                continue;
            }

            $prod = $lookup[$id];
            $price = (float)($prod['price'] ?? 0);
            $items[] = [
                'id' => $prod['id'],
                'name' => $prod['name'] ?? '',
                'image' => $prod['image'] ?? '',
                'price' => $price,
                'qty' => $qty,
                'subtotal' => $price * $qty,
            ];
        }

        return $items;
    }
}
