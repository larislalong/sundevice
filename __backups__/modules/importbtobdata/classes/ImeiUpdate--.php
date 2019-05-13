<?php
/**
 * 2015-2017 Crystals Services
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.crystals-services.com/ for more information.
 *
 * @author Crystals Services Sarl <contact@crystals-services.com>
 * @copyright 2015-2017 Crystals Services Sarl
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *          International Registered Trademark & Property of Crystals Services Sarl
 */

class ImeiUpdate
{
    public static function updateAllImei($delivered = true)
    {
		$deliveredState = Configuration::get('PS_OS_DELIVERED');
        $sql = 'SELECT o.id_order, o.reference FROM ' . _DB_PREFIX_ .'orders o WHERE ' . (int) $deliveredState . ' ' . ($delivered ? 'IN' : 'NOT IN') .
		' (SELECT id_order_state FROM ' . _DB_PREFIX_ .'order_history oh WHERE o.id_order = oh.id_order)';
		$result = Db::getInstance()->executeS($sql);
		foreach($result as $row){
			self::updateOrderImei($row['id_order'], $row['reference'], !$delivered);
		}
    }
	
	public static function updateOrderImei($id_order, $reference, $changeState = true)
    {
		$username = '2F2ZXByb2Rsb2dpbnNoaXBwbWVudAZA';
		$password = 'GFzc3dvcmRmb3JzYXZlc2hpcHBtZW50';
		//$id_order = 58;
		//$reference = 'UBDZHNGYH';
		$url = 'https://api.next-wireless.co/rest/b2b/orders/' . $reference . '/imeis/';
		/*$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Content-Type:application/json\r\n" .
				"Accept:application/json\r\n" .
				"Authorization: Basic " . base64_encode("$username:$password")
			)
		);
		$context = stream_context_create($opts);
		$result = file_get_contents($url, false, $context);*/
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Accept: application/json'
			)
		);

		$result = curl_exec($curl);
		curl_close($curl);
		if(!empty($result)){
			$data = json_decode($result, false, 512, JSON_BIGINT_AS_STRING);
            if (isset($data->imeis) && is_array($data->imeis)){
                $tabkidp = array();
                foreach($data->imeis as $value){
                    //on construit le tableau des idp couplé au imei
                    $tabkidp[$value->idp][] = $value->imei;
                }
                foreach ($tabkidp as $key => $value) {
                    $where = ' (id_order = '. (int) $id_order .') AND (idp = '. (int) $key .')';
                    Db::getInstance()->update('order_detail', array('emei' => pSQL(implode('<br/>', $value), true)), $where);
                }
                if($changeState){
                    $history = new OrderHistory();
                    $history->changeIdOrderState(Configuration::get('PS_OS_DELIVERED'), $id_order);
                }
            }
		}
    }
}
