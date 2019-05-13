<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since 1.5.0
 */
class Shop extends ShopCore
{

    /**
     * Find the shop from current domain / uri and get an instance of this shop
     * if INSTALL_VERSION is defined, will return an empty shop object
     *
     * @return Shop
     */
    public static function initialize()
    {
        // Find current shop from URL
        if (!($id_shop = Tools::getValue('id_shop')) || defined('_PS_ADMIN_DIR_')) {
            $found_uri = '';
            $is_main_uri = false;
            $host = Tools::getHttpHost();
            $request_uri = rawurldecode(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'');

            $sql = 'SELECT s.id_shop, CONCAT(su.physical_uri, su.virtual_uri) AS uri, su.domain, su.main
					FROM '._DB_PREFIX_.'shop_url su
					LEFT JOIN '._DB_PREFIX_.'shop s ON (s.id_shop = su.id_shop)
					WHERE (su.domain = \''.pSQL($host).'\' OR su.domain_ssl = \''.pSQL($host).'\')
						AND s.active = 1
						AND s.deleted = 0
					ORDER BY LENGTH(CONCAT(su.physical_uri, su.virtual_uri)) DESC';

            $result = Db::getInstance()->executeS($sql);

            $through = false;
            foreach ($result as $row) {
                // An URL matching current shop was found
                if (preg_match('#^'.preg_quote($row['uri'], '#').'#i', $request_uri)) {
                    $through = true;
                    $id_shop = $row['id_shop'];
                    $found_uri = $row['uri'];
                    if ($row['main']) {
                        $is_main_uri = true;
                    }
                    break;
                }
            }

            // If an URL was found but is not the main URL, redirect to main URL
            if ($through && $id_shop && !$is_main_uri) {
                foreach ($result as $row) {
                    if ($row['id_shop'] == $id_shop && $row['main']) {
                        $request_uri = substr($request_uri, strlen($found_uri));
                        $url = str_replace('//', '/', $row['domain'].$row['uri'].$request_uri);
                        $redirect_type = Configuration::get('PS_CANONICAL_REDIRECT');
                        $redirect_code = ($redirect_type == 1 ? '302' : '301');
                        $redirect_header = ($redirect_type == 1 ? 'Found' : 'Moved Permanently');
                        header('HTTP/1.0 '.$redirect_code.' '.$redirect_header);
                        header('Cache-Control: no-cache');
                        header('Location: http://'.$url);
                        exit;
                    }
                }
            }
        }

        $http_host = Tools::getHttpHost();
        $all_media = array_merge(Configuration::getMultiShopValues('PS_MEDIA_SERVER_1'), Configuration::getMultiShopValues('PS_MEDIA_SERVER_2'), Configuration::getMultiShopValues('PS_MEDIA_SERVER_3'));

        if ((!$id_shop && defined('_PS_ADMIN_DIR_')) || Tools::isPHPCLI() || in_array($http_host, $all_media)) {
            // If in admin, we can access to the shop without right URL
            if ((!$id_shop && Tools::isPHPCLI()) || defined('_PS_ADMIN_DIR_')) {
                $id_shop = (int)Configuration::get('PS_SHOP_DEFAULT');
            }

            $shop = new Shop((int)$id_shop);
            if (!Validate::isLoadedObject($shop)) {
                $shop = new Shop((int)Configuration::get('PS_SHOP_DEFAULT'));
            }

            $shop->virtual_uri = '';

            // Define some $_SERVER variables like HTTP_HOST if PHP is launched with php-cli
            if (Tools::isPHPCLI()) {
                if (!isset($_SERVER['HTTP_HOST']) || empty($_SERVER['HTTP_HOST'])) {
                    $_SERVER['HTTP_HOST'] = $shop->domain;
                }
                if (!isset($_SERVER['SERVER_NAME']) || empty($_SERVER['SERVER_NAME'])) {
                    $_SERVER['SERVER_NAME'] = $shop->domain;
                }
                if (!isset($_SERVER['REMOTE_ADDR']) || empty($_SERVER['REMOTE_ADDR'])) {
                    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
                }
            }
        } else {
            $shop = new Shop($id_shop);
            if (!Validate::isLoadedObject($shop) || !$shop->active) {
                // No shop found ... too bad, let's redirect to default shop
                $default_shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));

                // Hmm there is something really bad in your Prestashop !
                if (!Validate::isLoadedObject($default_shop)) {
                    throw new PrestaShopException('Shop not found');
                }

                $params = $_GET;
                unset($params['id_shop']);
                $url = $default_shop->domain;
                if (!Configuration::get('PS_REWRITING_SETTINGS')) {
                    $url .= $default_shop->getBaseURI().'index.php?'.http_build_query($params);
                } else {
                    // Catch url with subdomain "www"
                    if (strpos($url, 'www.') === 0 && 'www.'.$_SERVER['HTTP_HOST'] === $url || $_SERVER['HTTP_HOST'] === 'www.'.$url) {
                        $url .= $_SERVER['REQUEST_URI'];
                    } else {
                        $url .= $default_shop->getBaseURI();
                    }

                    if (count($params)) {
                        $url .= '?'.http_build_query($params);
                    }
                }

                $redirect_type = Configuration::get('PS_CANONICAL_REDIRECT');
                $redirect_code = ($redirect_type == 1 ? '302' : '301');
                $redirect_header = ($redirect_type == 1 ? 'Found' : 'Moved Permanently');
                header('HTTP/1.0 '.$redirect_code.' '.$redirect_header);
                header('Location: http://'.$url);
                exit;
            } elseif (defined('_PS_ADMIN_DIR_') && empty($shop->physical_uri)) {
                $shop_default = new Shop((int)Configuration::get('PS_SHOP_DEFAULT'));
                $shop->physical_uri = $shop_default->physical_uri;
                $shop->virtual_uri = $shop_default->virtual_uri;
            }
        }

        self::$context_id_shop = $shop->id;
        self::$context_id_shop_group = $shop->id_shop_group;
        self::$context = self::CONTEXT_SHOP;

        return $shop;
    }
}
