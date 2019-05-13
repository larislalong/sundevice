<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StockManager
 *
 * @author FRANCIS FOZEU
 */
class StockManager extends StockManagerCore{
    //put your code here
    /**
     * @see StockManagerInterface::getProductRealQuantities()
     */
    public function getProductRealQuantities($id_product, $id_product_attribute, $ids_warehouse = null, $usable = false)
    {
        if (!is_null($ids_warehouse)) {
            // in case $ids_warehouse is not an array
            if (!is_array($ids_warehouse)) {
                $ids_warehouse = array($ids_warehouse);
            }

            // casts for security reason
            $ids_warehouse = array_map('intval', $ids_warehouse);
        }

        $client_orders_qty = 0;

        // check if product is present in a pack
        /*
        if (!Pack::isPack($id_product) && $in_pack = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT id_product_pack, quantity FROM '._DB_PREFIX_.'pack
			WHERE id_product_item = '.(int)$id_product.'
			AND id_product_attribute_item = '.($id_product_attribute ? (int)$id_product_attribute : '0'))) {
            foreach ($in_pack as $value) {
                if (Validate::isLoadedObject($product = new Product((int)$value['id_product_pack'])) &&
                    ($product->pack_stock_type == 1 || $product->pack_stock_type == 2 || ($product->pack_stock_type == 3 && Configuration::get('PS_PACK_STOCK_TYPE') > 0))) {
                    $query = new DbQuery();
                    $query->select('od.product_quantity, od.product_quantity_refunded, pk.quantity');
                    $query->from('order_detail', 'od');
                    $query->leftjoin('orders', 'o', 'o.id_order = od.id_order');
                    $query->where('od.product_id = '.(int)$value['id_product_pack']);
                    $query->leftJoin('order_history', 'oh', 'oh.id_order = o.id_order AND oh.id_order_state = o.current_state');
                    $query->leftJoin('order_state', 'os', 'os.id_order_state = oh.id_order_state');
                    $query->leftJoin('pack', 'pk', 'pk.id_product_item = '.(int)$id_product.' AND pk.id_product_attribute_item = '.($id_product_attribute ? (int)$id_product_attribute : '0').' AND id_product_pack = od.product_id');
                    $query->where('os.shipped != 1');
                    $query->where('o.valid = 1 OR (os.id_order_state != '.(int)Configuration::get('PS_OS_ERROR').'
								   AND os.id_order_state != '.(int)Configuration::get('PS_OS_CANCELED').')');
                    $query->groupBy('od.id_order_detail');
                    if (count($ids_warehouse)) {
                        $query->where('od.id_warehouse IN('.implode(', ', $ids_warehouse).')');
                    }
                    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                    if (count($res)) {
                        foreach ($res as $row) {
                            $client_orders_qty += ($row['product_quantity'] - $row['product_quantity_refunded']) * $row['quantity'];
                        }
                    }
                }
            }
        }*/

        // skip if product is a pack without
        /*
        if (!Pack::isPack($id_product) || (Pack::isPack($id_product) && Validate::isLoadedObject($product = new Product((int)$id_product))
            && $product->pack_stock_type == 0 || $product->pack_stock_type == 2 ||
                    ($product->pack_stock_type == 3 && (Configuration::get('PS_PACK_STOCK_TYPE') == 0 || Configuration::get('PS_PACK_STOCK_TYPE') == 2)))) {
            // Gets client_orders_qty
            $query = new DbQuery();
            $query->select('od.product_quantity, od.product_quantity_refunded');
            $query->from('order_detail', 'od');
            $query->leftjoin('orders', 'o', 'o.id_order = od.id_order');
            $query->where('od.product_id = '.(int)$id_product);
            if (0 != $id_product_attribute) {
                $query->where('od.product_attribute_id = '.(int)$id_product_attribute);
            }
            $query->leftJoin('order_history', 'oh', 'oh.id_order = o.id_order AND oh.id_order_state = o.current_state');
            $query->leftJoin('order_state', 'os', 'os.id_order_state = oh.id_order_state');
            $query->where('os.shipped != 1');
            $query->where('o.valid = 1 OR (os.id_order_state != '.(int)Configuration::get('PS_OS_ERROR').'
						   AND os.id_order_state != '.(int)Configuration::get('PS_OS_CANCELED').')');
            $query->groupBy('od.id_order_detail');
            if (count($ids_warehouse)) {
                $query->where('od.id_warehouse IN('.implode(', ', $ids_warehouse).')');
            }
            $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            if (count($res)) {
                foreach ($res as $row) {
                    $client_orders_qty += ($row['product_quantity'] - $row['product_quantity_refunded']);
                }
            }
        }*/
        // Gets supply_orders_qty
        $query = new DbQuery();

        $query->select('sod.quantity_expected, sod.quantity_received');
        $query->from('supply_order', 'so');
        $query->leftjoin('supply_order_detail', 'sod', 'sod.id_supply_order = so.id_supply_order');
        $query->leftjoin('supply_order_state', 'sos', 'sos.id_supply_order_state = so.id_supply_order_state');
        $query->where('sos.pending_receipt = 1');
        $query->where('sod.id_product = '.(int)$id_product.' AND sod.id_product_attribute = '.(int)$id_product_attribute);
        if (!is_null($ids_warehouse) && count($ids_warehouse)) {
            $query->where('so.id_warehouse IN('.implode(', ', $ids_warehouse).')');
        }

        $supply_orders_qties = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        $supply_orders_qty = 0;
        foreach ($supply_orders_qties as $qty) {
            if ($qty['quantity_expected'] > $qty['quantity_received']) {
                $supply_orders_qty += ($qty['quantity_expected'] - $qty['quantity_received']);
            }
        }

        // Gets {physical OR usable}_qty
        $qty = $this->getProductPhysicalQuantities($id_product, $id_product_attribute, $ids_warehouse, $usable);

        //real qty = actual qty in stock - current client orders + current supply orders
        return ($qty - $client_orders_qty + $supply_orders_qty);
    }
}
