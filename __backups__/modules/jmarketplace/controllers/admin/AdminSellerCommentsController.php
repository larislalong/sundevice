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

class AdminSellerCommentsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller_comment';
        $this->className = 'SellerComment';
        $this->lang = true;
        
        $this->context = Context::getContext();

        parent::__construct();
    }

    public function renderList()
    {
        if (Tools::isSubmit('addseller_comment_criterion') || Tools::isSubmit('updateseller_comment_criterion')) {
            return $this->renderCriterionForm();
        }
        return $this->renderConfigForm().$this->renderCriterionList().$this->renderModerateLists().$this->renderCommentsList();
    }
    
    public function renderConfigForm()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this->module;
        $helper->name_controller = $this->module->name;
        $helper->identifier = $this->identifier;
        $helper->token = $this->token;
        $helper->languages = $languages;
        $helper->currentIndex = 'index.php?controller=AdminSellerComments';
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submitSellerCommentsSettings';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Seller comments settings')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Moderate comments'),
                    'name' => 'JMARKETPLACE_MODERATE_COMMENTS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow comments to guests'),
                    'name' => 'JMARKETPLACE_ALLOW_GUEST_COMMENT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow comment only if the customer has purchased'),
                    'name' => 'JMARKETPLACE_COMMENT_BOUGHT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to seller for each comment sended'),
                    'name' => 'JMARKETPLACE_SEND_COMMENT_SELLER',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('If moderate comments is active, the seller receive a email when a comment has been validated by administrator. If moderate comments is not active, the seller receive a email when a comment has been sended'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to administrator for each comment sended'),
                    'name' => 'JMARKETPLACE_SEND_COMMENT_ADMIN',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerCommentsSettings',
                'title' => $this->l('Save'),
            ),
        );
        
        $helper->fields_value['JMARKETPLACE_MODERATE_COMMENTS'] = Configuration::get('JMARKETPLACE_MODERATE_COMMENTS');
        $helper->fields_value['JMARKETPLACE_ALLOW_GUEST_COMMENT'] = Configuration::get('JMARKETPLACE_ALLOW_GUEST_COMMENT');
        $helper->fields_value['JMARKETPLACE_COMMENT_BOUGHT'] = Configuration::get('JMARKETPLACE_COMMENT_BOUGHT');
        $helper->fields_value['JMARKETPLACE_SEND_COMMENT_ADMIN'] = Configuration::get('JMARKETPLACE_SEND_COMMENT_ADMIN');
        $helper->fields_value['JMARKETPLACE_SEND_COMMENT_SELLER'] = Configuration::get('JMARKETPLACE_SEND_COMMENT_SELLER');

        return $helper->generateForm($this->fields_form);
    }
    
    public function renderCriterionList()
    {
        $criterions = SellerCommentCriterion::getCriterions($this->context->language->id, false);

        $fields_list = array(
            'id_seller_comment_criterion' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'type' => 'text',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'active' => 'status',
                'type' => 'bool',
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->listTotal = count($criterions);
        $helper->actions = array('edit');
        $helper->show_toolbar = false;
        $helper->toolbar_btn['new'] = array(
            'href' => 'index.php?controller=AdminSellerComments&addseller_comment_criterion&token='.$this->token,
            'desc' => $this->l('Add New Criterion', null, null, false)
        );
        $helper->module = $this->module;
        $helper->identifier = 'id_seller_comment_criterion';
        $helper->title = $this->l('Review Criteria');
        $helper->table = 'seller_comment_criterion';
        $helper->token = $this->token;
        $helper->currentIndex = 'index.php?controller=AdminSellerComments';

        return $helper->generateList($criterions, $fields_list);
    }
    
    public function renderCriterionForm()
    {
        $id_seller_comment_criterion = 0;
        $title = $this->l('Add new seller comment criterion');
        if (Tools::getValue('id_seller_comment_criterion')) {
            $id_seller_comment_criterion = (int)Tools::getValue('id_seller_comment_criterion');
            $seller_comment_criterion = new SellerCommentCriterion((int)$id_seller_comment_criterion, $this->context->language->id);
            $title = $this->l('Editing seller comment criterion').' '.$seller_comment_criterion->name;
        }

        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this->module;
        $helper->name_controller = $this->module->name;
        $helper->identifier = $this->identifier;
        $helper->token = $this->token;
        $helper->languages = $languages;
        $helper->currentIndex = 'index.php?controller=AdminSellerComments';
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submitAddSellerCommentsCriterion';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $title
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_seller_comment_criterion',
                ),
                array(
                    'type' => 'text',
                    'lang' => true,
                    'label' => $this->l('Criterion name'),
                    'name' => 'name',
                    'col' => 3,
                ),
                array(
                    'type' => 'switch',
                    'is_bool' => true,
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitAddSellerCommentsCriterion',
                'title' => $this->l('Save'),
            ),
        );
        
        if ($id_seller_comment_criterion == 0) {
            $helper->fields_value['id_seller_comment_criterion'] = 0;
            $helper->fields_value['active'] = 1;
            foreach ($languages as $lang) {
                $helper->fields_value['name'][$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
            }
        } else {
            $helper->fields_value['id_seller_comment_criterion'] = $seller_comment_criterion->id;
            $helper->fields_value['active'] = $seller_comment_criterion->active;
            foreach ($languages as $lang) {
                $helper->fields_value['name'][$lang['id_lang']] = $seller_comment_criterion->name;
            }
        }

        return $helper->generateForm($this->fields_form);
    }
    
    public function renderModerateLists()
    {
        $return = null;
        if (Configuration::get('JMARKETPLACE_MODERATE_COMMENTS')) {
            $comments = SellerComment::getByValidate(0, false);
            
            $fields_list = $fields_list = array(
                'id_seller_comment' => array(
                    'title' => $this->l('ID'),
                    'type' => 'text',
                ),
                'title' => array(
                    'title' => $this->l('Review title'),
                    'type' => 'text',
                ),
                'content' => array(
                    'title' => $this->l('Review'),
                    'type' => 'text',
                ),
                'grade' => array(
                    'title' => $this->l('Rating'),
                    'type' => 'text',
                    'suffix' => '/5',
                ),
                'customer_name' => array(
                    'title' => $this->l('Author'),
                    'type' => 'text',
                ),
                'name' => array(
                    'title' => $this->l('Seller'),
                    'type' => 'text',
                ),
                'order_reference' => array(
                    'title' => $this->l('Order'),
                    'type' => 'text',
                ),
                'product_name' => array(
                    'title' => $this->l('Product'),
                    'type' => 'text',
                ),
                'date_add' => array(
                    'title' => $this->l('Date of publication'),
                    'type' => 'date',
                ),
                'validate' => array(
                    'title' => $this->l('Approve'),
                    'active' => 'status',
                    'type' => 'bool',
                ),
            );

            $helper = new HelperList();
            $helper->shopLinkType = '';
            $helper->simple_header = true;
            $helper->actions = array('delete');
            $helper->show_toolbar = false;
            $helper->module = $this->module;
            $helper->listTotal = count($comments);
            $helper->identifier = 'id_seller_comment';
            $helper->title = $this->l('Reviews waiting for approval');
            $helper->table = 'seller_comment';
            $helper->token = $this->token;
            $helper->currentIndex = 'index.php?controller=AdminSellerComments';

            $return .= $helper->generateList($comments, $fields_list);
        }

        return $return;
    }
    
    public function renderCommentsList()
    {
        $comments = SellerComment::getByValidate(1, false);

        $fields_list = array(
            'id_seller_comment' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'title' => array(
                'title' => $this->l('Review title'),
                'type' => 'text',
            ),
            'content' => array(
                'title' => $this->l('Review'),
                'type' => 'text',
            ),
            'grade' => array(
                'title' => $this->l('Rating'),
                'type' => 'text',
                'suffix' => '/5',
            ),
            'customer_name' => array(
                'title' => $this->l('Author'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Seller'),
                'type' => 'text',
            ),
            'order_reference' => array(
                'title' => $this->l('Order'),
                'type' => 'text',
            ),
            'product_name' => array(
                'title' => $this->l('Product'),
                'type' => 'text',
            ),
            'date_add' => array(
                'title' => $this->l('Time of publication'),
                'type' => 'date',
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = array('delete');
        $helper->show_toolbar = false;
        $helper->module = $this->module;
        $helper->listTotal = count($comments);
        $helper->identifier = 'id_seller_comment';
        $helper->title = $this->l('Approved Reviews');
        $helper->table = 'seller_comment';
        $helper->token = $this->token;
        $helper->currentIndex = 'index.php?controller=AdminSellerComments';

        return $helper->generateList($comments, $fields_list);
    }
   
    public function postProcess()
    {
        if (Tools::isSubmit('submitSellerCommentsSettings')) {
            Configuration::updateValue('JMARKETPLACE_MODERATE_COMMENTS', Tools::getValue('JMARKETPLACE_MODERATE_COMMENTS'));
            Configuration::updateValue('JMARKETPLACE_COMMENT_BOUGHT', Tools::getValue('JMARKETPLACE_COMMENT_BOUGHT'));
            
            if (Configuration::get('JMARKETPLACE_COMMENT_BOUGHT') == 1) {
                Configuration::updateValue('JMARKETPLACE_ALLOW_GUEST_COMMENT', 0);
            } else {
                Configuration::updateValue('JMARKETPLACE_ALLOW_GUEST_COMMENT', Tools::getValue('JMARKETPLACE_ALLOW_GUEST_COMMENT'));
            }
            
            Configuration::updateValue('JMARKETPLACE_SEND_COMMENT_ADMIN', Tools::getValue('JMARKETPLACE_SEND_COMMENT_ADMIN'));
            Configuration::updateValue('JMARKETPLACE_SEND_COMMENT_SELLER', Tools::getValue('JMARKETPLACE_SEND_COMMENT_SELLER'));
        }
        
        if (Tools::isSubmit('submitAddSellerCommentsCriterion')) {
            $id_seller_comment_criterion = (int)Tools::getValue('id_seller_comment_criterion');
            
            if ($id_seller_comment_criterion > 0) {
                $seller_comment_criterion = new SellerCommentCriterion($id_seller_comment_criterion);
            } else {
                $seller_comment_criterion = new SellerCommentCriterion();
            }

            $seller_comment_criterion->active = Tools::getValue('active');
          
            $languages = Language::getLanguages();
            foreach ($languages as $lang) {
                $seller_comment_criterion->name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
            }

            if ($id_seller_comment_criterion > 0) {
                $seller_comment_criterion->update();
            } else {
                $seller_comment_criterion->add();
                
                //add grades by each comment
                $comments = SellerComment::getAll();
                if ($comments) {
                    foreach ($comments as $comment) {
                        $seller_comment_criterion->addGrade($comment['id_seller_comment'], $comment['grade']);
                    }
                }
            }
            
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminSellerComments').'&token='.$this->token);
        }
        
        if (Tools::isSubmit('deleteseller_comment_criterion')) {
            $seller_comment_criterion = new SellerCommentCriterion((int)Tools::getValue('id_seller_comment_criterion'));
            if ($seller_comment_criterion->id) {
                $seller_comment_criterion->delete();
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminSellerComments').'&token='.$this->token);
        }
        
        if (Tools::isSubmit('statusseller_comment_criterion')) {
            $seller_comment_criterion = new SellerCommentCriterion((int)Tools::getValue('id_seller_comment_criterion'));
            if ($seller_comment_criterion->id) {
                $seller_comment_criterion->active = (int)(!$seller_comment_criterion->active);
                $seller_comment_criterion->save();
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminSellerComments').'&token='.$this->token);
        }
        
        if (Tools::isSubmit('statusseller_comment')) {
            $comment = new SellerComment((int)Tools::getValue('id_seller_comment'));
            if ($comment->id) {
                $comment->validate = 1;
                $comment->update();
                
                if (Configuration::get('JMARKETPLACE_SEND_COMMENT_SELLER') == 1) {
                    $seller = new Seller($comment->id_seller);
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
                        $values = array(Configuration::get('PS_SHOP_NAME'), $comment->grade, nl2br($comment->content));
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
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminSellerComments').'&token='.$this->token);
        }
        
        if (Tools::isSubmit('deleteseller_comment')) {
            $seller_comment = new SellerComment((int)Tools::getValue('id_seller_comment'));
            if ($seller_comment->id) {
                $seller_comment->delete();
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminSellerComments').'&token='.$this->token);
        }
    }
}
