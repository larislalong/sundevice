<?php
/**
* 2007-2018 PrestaShop
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
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class CSVSellerProductLog
{
    public static function add($string = '', $id_seller = 0)
    {
        $log = date('Y-m-d H:i:s').' - '.$string."\n";
        $filename = _PS_MODULE_DIR_.'/jmarketplace/log/'.$id_seller.'/log.txt';
        $fp = fopen($filename, 'a');
        fwrite($fp, $log);
        fclose($fp);
        chmod($filename, 0777);
    }
    
    public static function create($mode = 'w', $id_seller = 0)
    {
        if (!is_dir(_PS_MODULE_DIR_.'/jmarketplace/log/'.$id_seller)) {
            mkdir(_PS_MODULE_DIR_.'/jmarketplace/log/'.$id_seller, 0777);
        }
        
        CSVSellerProductLog::delete($id_seller);
        $filename = _PS_MODULE_DIR_.'/jmarketplace/log/'.$id_seller.'/log.txt';
        $fp = fopen($filename, $mode);
        fwrite($fp, '');
        fclose($fp);
        chmod($filename, 0777);
    }
    
    public static function delete($id_seller)
    {
        $filename = _PS_MODULE_DIR_.'/jmarketplace/log/'.$id_seller.'/log.txt';
        if (file_exists($filename)) {
            unlink(_PS_MODULE_DIR_.'/jmarketplace/log/'.$id_seller.'/log.txt');
        }
    }
}
