<?php
$email_cible = 'larislalong@gmail.com';
$current_date = date('-1 day');
$sql = 'SELECT d.id_order,o.reference AS order_reference, os.name AS order_state, d.product_name, d.product_reference, d.product_price, d.product_quantity, o.payment, o.date_upd, o.date_add, CONCAT_WS(" ", g.lastname, g.firstname) AS customer, g.id_customer, CONCAT_WS(" ", ad.address1, ad.address2, "Code Postal:", ad.postcode, ad.city, ad.other, "Telephone: ", ad.phone, "Mobile: ", ad.phone_mobile) AS delevery_address, CONCAT_WS(" ", ai.address1, ai.address2,"Code postal:",  ai.postcode, ai.city, ai.other, "Telephone: ", ai.phone, "Mobile: ", ai.phone_mobile) AS invoice_address, gl.name AS group_name, s.quantity AS quantity_in_stock, g.email
	FROM sundev_order_detail d
	LEFT JOIN sundev_orders o ON (d.id_order = o.id_order)
	LEFT JOIN sundev_address ad ON (o.id_address_delivery = ad.id_address)
	LEFT JOIN sundev_address ai ON (o.id_address_invoice = ai.id_address)
	LEFT JOIN sundev_stock_available s ON (d.product_id = s.id_product)
	LEFT JOIN sundev_customer g ON (o.id_customer = g.id_customer)
	LEFT JOIN sundev_group_lang gl ON (g.id_default_group = gl.id_group) AND gl.name LIKE "client%"
	LEFT JOIN sundev_order_state_lang os ON (o.current_state = os.id_order_state)
	WHERE os.id_lang = 1 AND o.date_add >= "'.$current_date.'"
	GROUP BY gl.name, d.id_order, d.product_reference
	ORDER BY d.id_order DESC';

?>