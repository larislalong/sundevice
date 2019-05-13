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

class JmarketplaceSellercommentsModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/sellercomments.css', 'all');
        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/jquery.rating.pack.js', 'all');
        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/sellercomments.js', 'all');
    }
    
    public function init()
    {
        parent::init();
        
        $seller = new Seller((int)Tools::getValue('id_seller'));
        $meta = array();
        
        $meta['meta_title'] = $seller->name.' - '.Configuration::get('PS_SHOP_NAME').' - '.$this->module->l('Ratings and comments', 'sellercomments');

        $this->context->smarty->assign($meta);
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('submitSellerComment')) {
            $title = (string)Tools::getValue('title');
            $content = (string)Tools::getValue('content');
            $customer_name = Tools::isSubmit('customer_name');
            $id_seller = (int)Tools::getValue('id_seller');
            $seller = new Seller($id_seller);
            $id_guest = 0;
            $id_customer = $this->context->customer->id;
            if (!$id_customer) {
                $id_guest = $this->context->cookie->id_guest;
                $id_customer = 0;
            }
            
            if (Tools::getValue('id_order_product')) {
                $string_order_product = Tools::getValue('id_order_product');
                $id_order_product = explode('-', $string_order_product);
                $id_order = (int)$id_order_product[0];
                $order = new Order($id_order);
                $order_reference = $order->reference;
                
                $id_product = (int)$id_order_product[1];
                $product = new Product($id_product, null, $this->context->language->id);
                $product_name = $product->name;
            } else {
                $id_order = 0;
                $id_product = 0;
                $order_reference = '';
                $product_name = '';
            }
            
            if (!$title || !Validate::isGenericName($title)) {
                $this->errors[] = $this->module->l('Title is incorrect.', 'sellercomments');
            }
            
            if (!$content || !Validate::isMessage($content)) {
                $this->errors[] = $this->module->l('Comment is incorrect.', 'sellercomments');
            }
            
            if (!$id_customer && (!$customer_name || !$customer_name || !Validate::isGenericName($customer_name))) {
                $this->errors[] = $this->module->l('Customer name is incorrect.', 'sellercomments');
            }
            
            if (!$this->context->customer->id && !Configuration::get('JMARKETPLACE_ALLOW_GUEST_COMMENT')) {
                $this->errors[] = $this->module->l('You must be connected to send a comment', 'sellercomments');
            }
            
            if (!count(Tools::getValue('criterion'))) {
                $this->errors[] = $this->module->l('You must give a rating.', 'sellercomments');
            }

            if (!$id_seller) {
                $this->errors[] = $this->module->l('Seller not found.', 'sellercomments');
            }
            
            if (SellerComment::isAlreadyComment($id_seller, $id_customer, $id_guest, $id_order, $id_product) > 0) {
                $this->errors[] = $this->module->l('Your comment has been sent.', 'sellercomments');
            }

            if (count($this->errors) == 0) {
                $comment = new SellerComment();
                $comment->title = $title;
                $comment->content = strip_tags($content);
                $comment->id_seller = $id_seller;
                $comment->id_order = $id_order;
                $comment->order_reference = $order_reference;
                $comment->id_product = $id_product;
                $comment->product_name = $product_name;
                $comment->id_customer = $id_customer;
                $comment->id_guest = $id_guest;
                $comment->customer_name = (string)Tools::getValue('customer_name');
                
                if (!$comment->customer_name) {
                    $comment->customer_name = pSQL($this->context->customer->firstname.' '.$this->context->customer->lastname);
                }
                
                $comment->grade = 0;

                if (Configuration::get('JMARKETPLACE_MODERATE_COMMENTS') == 1) {
                    $comment->validate = 0;
                } else {
                    $comment->validate = 1;
                }

                $comment->save();

                $grade_sum = 0;

                foreach (Tools::getValue('criterion') as $id_seller_comment_criterion => $grade) {
                    $grade_sum += $grade;
                    $seller_comment_criterion = new SellerCommentCriterion($id_seller_comment_criterion);
                    if ($seller_comment_criterion->id) {
                        $seller_comment_criterion->addGrade($comment->id, $grade);
                    }
                }

                if (count(Tools::getValue('criterion')) >= 1) {
                    $comment->grade = $grade_sum / count(Tools::getValue('criterion'));
                    $comment->save();
                }

                if (Configuration::get('JMARKETPLACE_MODERATE_COMMENTS') == 1 && Configuration::get('JMARKETPLACE_SEND_COMMENT_ADMIN') == 1) {
                    $id_seller_email = false;
                    $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                    $to_name = Configuration::get('PS_SHOP_NAME');
                    $from = Configuration::get('PS_SHOP_EMAIL');
                    $from_name = Configuration::get('PS_SHOP_NAME');
                    $template = 'base';
                    $reference = 'new-comment-admin';
                    $id_seller_email = SellerEmail::getIdByReference($reference);

                    if ($id_seller_email) {
                        $seller_email = new SellerEmail($id_seller_email, Configuration::get('PS_LANG_DEFAULT'));
                        $vars = array("{shop_name}", "{seller_name}", "{grade}", "{comment}");
                        $values = array(Configuration::get('PS_SHOP_NAME'), $seller->name, ceil($comment->grade), nl2br($comment->content));
                        $subject_var = $seller_email->subject;
                        $subject_value = str_replace($vars, $values, $subject_var);
                        $content_var = $seller_email->content;
                        $content_value = str_replace($vars, $values, $content_var);

                        $template_vars = array(
                            '{content}' => $content_value,
                            '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                        );

                        $iso = Language::getIsoById(Configuration::get('PS_LANG_DEFAULT'));

                        if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                            Mail::Send(
                                Configuration::get('PS_LANG_DEFAULT'),
                                $template,
                                $subject_value,
                                $template_vars,
                                $to,
                                $to_name,
                                $from,
                                $from_name,
                                null,
                                null,
                                dirname(__FILE__).'/../../mails/'
                            );
                        }
                    }
                }

                if (Configuration::get('JMARKETPLACE_MODERATE_COMMENTS') == 0 && Configuration::get('JMARKETPLACE_SEND_COMMENT_SELLER') == 1) {
                    $seller = new Seller($id_seller);
                    $id_seller_email = false;
                    $to = $seller->email;
                    $to_name = $seller->name;
                    $from = Configuration::get('PS_SHOP_EMAIL');
                    $from_name = Configuration::get('PS_SHOP_NAME');
                    $template = 'base';
                    $reference = 'new-comment-seller';
                    $id_seller_email = SellerEmail::getIdByReference($reference);

                    if ($id_seller_email) {
                        $seller_email = new SellerEmail($id_seller_email, $seller->id_lang);
                        $vars = array("{shop_name}", "{grade}", "{comment}");
                        $values = array(Configuration::get('PS_SHOP_NAME'), ceil($comment->grade), nl2br($comment->content));
                        $subject_var = $seller_email->subject;
                        $subject_value = str_replace($vars, $values, $subject_var);
                        $content_var = $seller_email->content;
                        $content_value = str_replace($vars, $values, $content_var);

                        $template_vars = array(
                            '{content}' => $content_value,
                            '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                        );

                        $iso = Language::getIsoById($seller->id_lang);

                        if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                            Mail::Send(
                                $seller->id_lang,
                                $template,
                                $subject_value,
                                $template_vars,
                                $to,
                                $to_name,
                                $from,
                                $from_name,
                                null,
                                null,
                                dirname(__FILE__).'/../../mails/'
                            );
                        }
                    }
                }

                $this->context->smarty->assign('confirmation', 1);
            } else {
                $this->context->smarty->assign('errors', $this->errors);
            }
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (!Tools::getValue('id_seller')) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SELLER_RATING') == 0) {
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'selleraccount', array(), true));
        }
        
        $id_seller = Tools::getValue('id_seller');
        $seller = new Seller($id_seller);
        
        $params = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_products = Jmarketplace::getJmarketplaceLink('jmarketplace_sellerproductlist_rule', $params);
        
        if (!Configuration::get('JMARKETPLACE_SELLER_RATING')) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }

        $param = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $param2 = array('id_seller' => $seller->id);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $param);
        $url_seller_comments = $this->context->link->getModuleLink('jmarketplace', 'sellercomments', $param2, true);
        
        $seller_comments = SellerComment::getBySeller($id_seller);
        
        if (Configuration::get('PS_SSL_ENABLED') == 1) {
            $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
        } else {
            $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
        }
        
        if (file_exists(_PS_IMG_DIR_.'sellers/'.$seller->id_customer.'.jpg')) {
            $this->context->smarty->assign(array('photo' => $url_shop.'img/sellers/'.$seller->id_customer.'.jpg'));
        } else {
            $this->context->smarty->assign(array('photo' =>  $url_shop.'modules/jmarketplace/views/img/profile.jpg'));
        }
        
        if (Tools::getValue('confirmation')) {
            $this->context->smarty->assign(array('confirmation' => 1));
        }
        
        $resum_grade = array();
        $seller_comment_criterions = SellerCommentCriterion::getCriterions($this->context->language->id, true);
        if (is_array($seller_comment_criterions)) {
            foreach ($seller_comment_criterions as $scc) {
                $grades = SellerComment::getGradeBySeller($id_seller, $this->context->language->id);
                if (is_array($grades)) {
                    $grade_criterion = 0;
                    foreach ($grades as $gc) {
                        if ($scc['id_seller_comment_criterion'] == $gc['id_seller_comment_criterion']) {
                            $grade_criterion = $grade_criterion + $gc['grade'];
                        }
                    }
                    
                    $number_comments = SellerComment::getCommentNumber($id_seller);
                    if ($number_comments > 0) {
                        $grade = ceil($grade_criterion / $number_comments);
                    } else {
                        $grade = 0;
                    }
                    
                    $resum_grade[$scc['id_seller_comment_criterion']] = array(
                        'name' => $scc['name'],
                        'grade' => $grade,
                    );
                }
            }
        }
        
        $num_comments = SellerComment::getCommentNumber($id_seller);
        
        $num_rows_grade = array();
        $per_rows_grade = array();
        for ($i=5; $i>0; $i--) {
            if ($num_comments > 0) {
                $num_rows_grade[$i] = (int)SellerComment::getNumRowsBySellerAndGrade($id_seller, $i);
                $per_rows_grade[$i] = Tools::ps_round((SellerComment::getNumRowsBySellerAndGrade($id_seller, $i) * 100) / $num_comments, 2);
            } else {
                $num_rows_grade[$i] = 0;
                $per_rows_grade[$i] = 0;
            }
        }
        
        $this->context->smarty->assign(array(
            'logged' => $this->context->customer->isLogged(true),
            'allow_guests' => Configuration::get('JMARKETPLACE_ALLOW_GUEST_COMMENT'),
            'allow_customer_bought' => Configuration::get('JMARKETPLACE_COMMENT_BOUGHT'),
            'moderate' => Configuration::get('JMARKETPLACE_MODERATE_COMMENTS'),
            'num_comments' => $num_comments,
            'rating' => SellerComment::getRatings($id_seller),
            'num_rows_grade' => $num_rows_grade,
            'per_rows_grade' => $per_rows_grade,
            'seller' => $seller,
            'seller_comments' => $seller_comments,
            'seller_link' => $url_seller_profile,
            'url_seller_comments' => $url_seller_comments,
            'criterions' => $seller_comment_criterions,
            'resum_grade' => $resum_grade,
            'show_logo' => Configuration::get('JMARKETPLACE_SHOW_LOGO'),
            'url_seller_products' => $url_seller_products
        ));
        
        if (Tools::getValue('id_order')) {
            $this->context->smarty->assign('id_order', (int)Tools::getValue('id_order'));
        } else {
            $this->context->smarty->assign('id_order', 0);
        }
        
        $is_customer_bought = 0;
        $products_bought_from_this_seller = array();
        
        if ($this->context->customer->isLogged(true)) {
            $bought_products = $this->context->customer->getBoughtProducts();
            if (is_array($bought_products) && count($bought_products) > 0) {
                foreach ($bought_products as $bp) {
                    if (SellerProduct::existAssociationSellerProduct($bp['product_id']) == $id_seller) {
                        $is_customer_bought = 1;
                        $products_bought_from_this_seller[] = array(
                            'id_order_product' => $bp['id_order'].'-'.$bp['product_id'],
                            'reference' => $bp['reference'],
                            'order_date_add' => $bp['date_add'],
                            'product_name' => $bp['product_name'],
                        );
                    }
                }
            }
        }
        
        $this->context->smarty->assign('is_customer_bought', $is_customer_bought);
        $this->context->smarty->assign('products_bought_from_this_seller', $products_bought_from_this_seller);
        
        $this->setTemplate('sellercomments.tpl');
    }
}
