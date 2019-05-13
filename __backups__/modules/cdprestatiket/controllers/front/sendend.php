<?php
/**
* cdprestatiket :: Customer ticket Information of the product
*
* @author    contact@cleanpresta.com (www.cleanpresta.com)
* @copyright 2013-2014 cleandev.net
* @license   You only can use module, nothing more!
*/

class CdPrestaTiketSendendModuleFrontController extends ModuleFrontController
{
	public $errors = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->context = Context::getContext();
	}
	
	public function postProcess()
	{
		$cdpt_module = new CdPrestaTiket();
		
		$customer_infos = array();
		$customer_infos['lastname'] = $this->context->customer->lastname;
		$customer_infos['firstname'] = $this->context->customer->firstname;
		$customer_infos['email'] = $this->context->customer->email;
			
		/*Traitement de l'appel du formeulaire dans le hook*/
		if (Tools::getValue('cdptproducthook'))
		{	
			// infos product
			$product_infos = array();
			$cdpt_product = new product((int)Tools::getValue('cdptproducthook'));
			$product_infos['id'] = $cdpt_product->id;
			
			$this->context->smarty->assign(array(
				'cdpt_controller11' => $this->context->link->getModuleLink($cdpt_module->name, 'sendend'),
				'cdpt_customer_infos' => $customer_infos,
				'product_infos' => $product_infos
				));
			return $this->setTemplate('cdpt-Form-Hook.tpl');
			
		}
		
		/*traitement de validation du formulaire de hook*/
		elseif(Tools::isSubmit('cdpt_submitMessageHook'))
		{
			$email = Tools::getValue('email');
			$product = Tools::getValue('id_product');
			$message = Tools::getValue('message');
			$lastname = Tools::getValue('lastname');
			$firstname = Tools::getValue('firstname');
			$extension = array('.txt', '.rtf', '.doc', '.docx', '.pdf', '.zip', '.png', '.jpeg', '.gif', '.jpg');
			$fileAttachment = Tools::fileAttachment('fileUpload');
			
			if (!empty($fileAttachment['name']) && $fileAttachment['error'] != 0)
				$this->errors[] = Tools::displayError('An error occurred during the file-upload process.');
			else if (!empty($fileAttachment['name']) && !in_array( Tools::strtolower(Tools::substr($fileAttachment['name'], -4)), $extension) && !in_array( Tools::strtolower(Tools::substr($fileAttachment['name'], -5)), $extension))
				$this->errors[] = Tools::displayError('Bad file extension');
			else
			{
			
				if (!empty($email) && !empty($product) && !empty($message))
				{
					$name = null;
					if (!empty($lastname) || !empty($firstname))
						$name = 'Names : '.$lastname.' '.$firstname.'<br>';
					
					if (!empty($name))
						$message = $name.$message;
					
					if (Validate::isCleanHtml($message) && Validate::isEmail($email))
					{
						$contact = new Contact((int)Configuration::get('cdpt_CONTACT_ID'), $this->context->language->id); //
						$customer = $this->context->customer;
						if (!$customer->id) $customer->getByEmail($email);
							
						if(($id_customer_thread = (int)Db::getInstance()->getValue('
							SELECT cm.id_customer_thread FROM '._DB_PREFIX_.'customer_thread cm
							WHERE cm.id_shop = '.(int)$this->context->shop->id.' AND  id_contact='.(int)$contact->id.' AND email = "'.$email.'" AND id_product='.(int)$product)))
						{
							$ct = new CustomerThread($id_customer_thread);
						}
						else
						{
							$ct = new CustomerThread();
							$ct->id_customer = (int)$customer->id;    
							$ct->email = $email; 
							$ct->token = Tools::passwdGen(12); 
						}  
						$ct->status = 'open';
						$ct->id_lang = (int)$this->context->language->id;
						$ct->id_shop = (int)$this->context->shop->id;
						$ct->id_contact = (int)$contact->id;
						$ct->id_order = 0;
						$ct->id_product = (int)$product;
						$ct->save();
						
						if($ct->id)
						{
							$cm = new CustomerMessage();
							$cm->id_customer_thread = $ct->id;
							$cm->message = $message;
							if (isset($fileAttachment['rename']) && !empty($fileAttachment['rename']) && rename($fileAttachment['tmp_name'], _PS_UPLOAD_DIR_.basename($fileAttachment['rename'])))
							{
								$cm->file_name = $fileAttachment['rename'];
								@chmod(_PS_UPLOAD_DIR_.basename($fileAttachment['rename']), 0664);
							}
							$cm->ip_address = ip2long(Tools::getRemoteAddr());
							$cm->user_agent = $_SERVER['HTTP_USER_AGENT'];
							if (!$cm->add())
							{
								$this->errors[] = Tools::displayError('An error occurred while sending the message.');
							}
							else
							{
								$product = new Product((int)$product);
								$product_name = $product->name[Context::getContext()->language->id];
								$var_list = array(
									'{message}' => Tools::stripslashes($message),
									'{email}' =>  $email,
									'{product_id}' => $product->id,
									'{product_name}' => $product_name,
								);
								if (isset($fileAttachment['name']))
									$var_list['{attached_file}'] = $fileAttachment['name'];
								
								$emailDir = _PS_MODULE_DIR_ . $this->module->name . '/mails/';
								// admin notification
								if (!empty($contact->email))
								{
									Mail::Send($this->context->language->id, 'cdpt_admin', '[ '.$product_name.' ] : '.$this->module->l('Ticket product'), $var_list, $contact->email, $contact->name, $email, ($customer->id ? $customer->firstname.' '.$customer->lastname : ''), $fileAttachment, null, $emailDir);
								}		
								//front notification
								Mail::Send($this->context->language->id, 'cdpt_front', $this->module->l('Your Ticket has been correctly sent'), $var_list, $email, null, null, null, $fileAttachment, null, $emailDir);
							}
						}
						else
							$this->errors[] = Tools::displayError('An error occurred while sending the message.');
					}
					else
						$this->errors[] = Tools::displayError('Invalid email address or Invalid message.');
				}
				else
					$this->errors[] = Tools::displayError('Invalid email address or Invalid message.');
			}
			
			if (count($this->errors) >= 1)
					array_unique($this->errors);
				else
					$this->context->smarty->assign('confirmation', 1);
			$product_infos['id'] = $product;
			
			$this->context->smarty->assign(array(
				'cdpt_controller11' => $this->context->link->getModuleLink($cdpt_module->name, 'sendend'),
				'cdpt_customer_infos' => $customer_infos,
				'product_infos' => $product_infos
				));
			return $this->setTemplate('cdpt-Form-Hook.tpl');
		}
		
		/*Traitement de la pagination*/
		
		else
		{
			
		}
	}
	
	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(_THEME_CSS_DIR_.'contact-form.css');
		$this->addJS(_THEME_JS_DIR_.'contact-form.js');
		$this->addJS(_PS_JS_DIR_.'validate.js');
	}
}
