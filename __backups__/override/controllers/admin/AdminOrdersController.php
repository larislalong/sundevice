<?php
/**
 * @property Order $object
 */
class AdminOrdersController extends AdminOrdersControllerCore
{
    public function __construct()
    {
        parent::__construct();        
        // $this->_select .= ', (SELECT product_old_price FROM `'._DB_PREFIX_.'order_detail` od WHERE od.id_order = a.id_order) as product_old_price';

        // $this->fields_list = array_merge($this->fields_list, array(
            // 'product_old_price' => array(
                // 'title' => $this->l('Prix normal'),
                // 'havingFilter' => true,
            // ),
        // ));
    }
}
