<?php
/**
* cdprestatiket :: Customer ticket Information of the product
*
* @author    contact@cleanpresta.com (www.cleanpresta.com)
* @copyright 2013-2014 cleandev.net
* @license   You only can use module, nothing more!
*/

class CleansavDefaultModuleFrontController extends ModuleFrontController
{
	public $errors = array();

	public function __construct()
	{
		parent::__construct();
		$this->context = Context::getContext();
	}

	public function postProcess()
	{
		$cdpt_module = new Cleansav();

		/*traitement de l'appel du formulaire d'enregistrement*/
		if (Tools::getValue('form') == "formulaire")
		{
			$this->assignOrderList();

			$email = Tools::safeOutput(Tools::getValue('from',
				((isset($this->context->cookie) && isset($this->context->cookie->email) && Validate::isEmail($this->context->cookie->email)) ? $this->context->cookie->email : '')));
			$this->context->smarty->assign(array(
				'errors' => $this->errors,
				'email' => $email,
				'fileupload' => Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD')
			));

			$this->context->smarty->assign(array(
				'contacts' => Contact::getContacts($this->context->language->id),
				'message' => html_entity_decode(Tools::getValue('message')),
				'cdpt_controller1' => $this->context->link->getModuleLink($cdpt_module->name, 'default'),
				'cdpt_SAV' => Configuration::get('cdpt_CONTACT_ID')
			));
			$has_address = $this->context->customer->getAddresses($this->context->language->id);
			$this->context->smarty->assign(array(
					'has_customer_an_address' => empty($has_address),
					'voucherAllowed' => (int)CartRule::isFeatureActive(),
					'returnAllowed' => (int)Configuration::get('PS_ORDER_RETURN')
			));

			return $this->setTemplate('cdpt-contact-form.tpl');
		}

		/*Enregistrement des données du formulaire*/

		elseif (Tools::isSubmit('cdpt_submitMessage'))
		{
			$extension = array('.txt', '.rtf', '.doc', '.docx', '.pdf', '.zip', '.png', '.jpeg', '.gif', '.jpg');
			$fileAttachment = Tools::fileAttachment('fileUpload');
			$message = Tools::getValue('message'); // Html entities is not usefull, iscleanHtml check there is no bad html tags.
			if (!($from = trim(Tools::getValue('from'))) || !Validate::isEmail($from))
				$this->errors[] = Tools::displayError('Invalid email address.');
			else if (!$message)
				$this->errors[] = Tools::displayError('The message cannot be blank.');
			else if (!Validate::isCleanHtml($message))
				$this->errors[] = Tools::displayError('Invalid message');
			else if (!($id_contact = (int)(Tools::getValue('id_contact'))) || !(Validate::isLoadedObject($contact = new Contact($id_contact, $this->context->language->id))))
				$this->errors[] = Tools::displayError('Please select a subject from the list provided. ');
			else if (!empty($fileAttachment['name']) && $fileAttachment['error'] != 0)
				$this->errors[] = Tools::displayError('An error occurred during the file-upload process.');
			else if (!empty($fileAttachment['name']) && !in_array( Tools::strtolower(Tools::substr($fileAttachment['name'], -4)), $extension) && !in_array( Tools::strtolower(Tools::substr($fileAttachment['name'], -5)), $extension))
				$this->errors[] = Tools::displayError('Bad file extension');
			else
			{
				$customer = $this->context->customer;
				if (!$customer->id)
					$customer->getByEmail($from);

				$contact = new Contact($id_contact, $this->context->language->id);

				$id_order = (int)$this->getOrder();

				if (!((
						($id_customer_thread = (int)Tools::getValue('id_customer_thread'))
						&& (int)Db::getInstance()->getValue('
						SELECT cm.id_customer_thread FROM '._DB_PREFIX_.'customer_thread cm
						WHERE cm.id_customer_thread = '.(int)$id_customer_thread.' AND cm.id_shop = '.(int)$this->context->shop->id.' AND token = \''.pSQL(Tools::getValue('token')).'\'')
					) || (
						$id_customer_thread = CustomerThread::getIdCustomerThreadByEmailAndIdOrder($from, $id_order)
					)))
				{
					$fields = Db::getInstance()->executeS('
					SELECT cm.id_customer_thread, cm.id_contact, cm.id_customer, cm.id_order, cm.id_product, cm.email
					FROM '._DB_PREFIX_.'customer_thread cm
					WHERE email = \''.pSQL($from).'\' AND cm.id_shop = '.(int)$this->context->shop->id.' AND ('.
						($customer->id ? 'id_customer = '.(int)($customer->id).' OR ' : '').'
						id_order = '.(int)$id_order.')');
					$score = 0;
					foreach ($fields as $row)
					{
						$tmp = 0;
						if ((int)$row['id_customer'] && $row['id_customer'] != $customer->id && $row['email'] != $from)
							continue;
						if ($row['id_order'] != 0 && $id_order != $row['id_order'])
							continue;
						if ($row['email'] == $from)
							$tmp += 4;
						if ($row['id_contact'] == $id_contact)
							$tmp++;
						if (Tools::getValue('id_product') != 0 && $row['id_product'] == Tools::getValue('id_product'))
							$tmp += 2;
						if ($tmp >= 5 && $tmp >= $score)
						{
							$score = $tmp;
							$id_customer_thread = $row['id_customer_thread'];
						}
					}
				}
				$old_message = Db::getInstance()->getValue('
					SELECT cm.message FROM '._DB_PREFIX_.'customer_message cm
					LEFT JOIN '._DB_PREFIX_.'customer_thread cc on (cm.id_customer_thread = cc.id_customer_thread)
					WHERE cc.id_customer_thread = '.(int)($id_customer_thread).' AND cc.id_shop = '.(int)$this->context->shop->id.'
					ORDER BY cm.date_add DESC');
				if ($old_message == $message)
				{
					$this->context->smarty->assign('alreadySent', 1);
					$contact->email = '';
					$contact->customer_service = 0;
				}

				if ($contact->customer_service)
				{
					if ((int)$id_customer_thread)
					{
						$ct = new CustomerThread($id_customer_thread);
						$ct->status = 'open';
						$ct->id_lang = (int)$this->context->language->id;
						$ct->id_contact = (int)($id_contact);
						$ct->id_order = (int)$id_order;
						if ($id_product = (int)Tools::getValue('id_product'))
							$ct->id_product = $id_product;
						$ct->update();
					}
					else
					{
						$ct = new CustomerThread();
						if (isset($customer->id))
							$ct->id_customer = (int)($customer->id);
						$ct->id_shop = (int)$this->context->shop->id;
						$ct->id_order = (int)$id_order;
						if ($id_product = (int)Tools::getValue('id_product'))
							$ct->id_product = $id_product;
						$ct->id_contact = (int)($id_contact);
						$ct->id_lang = (int)$this->context->language->id;
						$ct->email = $from;
						$ct->status = 'open';
						$ct->token = Tools::passwdGen(12);
						$ct->add();
					}

					if ($ct->id)
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
							$this->errors[] = Tools::displayError('An error occurred while sending the message.');
					}
					else
						$this->errors[] = Tools::displayError('An error occurred while sending the message.');
				}

				if (!count($this->errors))
				{
					$var_list = array(
									'{order_name}' => '-',
									'{attached_file}' => '-',
									'{message}' => Tools::nl2br(Tools::stripslashes($message)),
									'{email}' =>  $from,
									'{product_name}' => '',
								);

					if (isset($fileAttachment['name']))
						$var_list['{attached_file}'] = $fileAttachment['name'];

					$id_product = (int)Tools::getValue('id_product');

					if (isset($ct) && Validate::isLoadedObject($ct) && $ct->id_order)
					{
						$order = new Order((int)$ct->id_order);
						$var_list['{order_name}'] = $order->getUniqReference();
						$var_list['{id_order}'] = (int)$order->id;
					}

					if ($id_product)
					{
						$product = new Product((int)$id_product);
						if (Validate::isLoadedObject($product) && isset($product->name[Context::getContext()->language->id]))
							$var_list['{product_name}'] = $product->name[Context::getContext()->language->id];
					}

					if (empty($contact->email))
						Mail::Send($this->context->language->id, 'cdpt_contact_form', ((isset($ct) && Validate::isLoadedObject($ct)) ? sprintf(Mail::l('Your message has been correctly sent #ct%1$s #tc%2$s'), $ct->id, $ct->token) : Mail::l('Your message has been correctly sent')), $var_list, $from, null, null, null, $fileAttachment);
					else
					{
						if (!Mail::Send($this->context->language->id, 'contact', Mail::l('Message from contact form').' [no_sync]',
							$var_list, $contact->email, $contact->name, $from, ($customer->id ? $customer->firstname.' '.$customer->lastname : ''),
									$fileAttachment) ||
								!Mail::Send($this->context->language->id, 'cdpt_contact_form', ((isset($ct) && Validate::isLoadedObject($ct)) ? sprintf(Mail::l('Your message has been correctly sent #ct%1$s #tc%2$s'), $ct->id, $ct->token) : Mail::l('Your message has been correctly sent')), $var_list, $from, null, $contact->email, $contact->name, $fileAttachment))
									$this->errors[] = Tools::displayError('An error occurred while sending the message.');
					}
				}

				if (count($this->errors) > 1)
					array_unique($this->errors);
				else
					$this->context->smarty->assign('confirmation', 1);
			}
			if(Tools::getValue('id_messageth'))
			{
				return $this->filListeOne($cdpt_module, (int)Tools::getValue('id_messageth'));
			}
			else
			{
				return $this->listTicketAll($cdpt_module);
			}

		}

		/*filset of message*/
		elseif (Tools::isSubmit('cdpt_submitAllMessage'))
		{
			return $this->filListeOne($cdpt_module);
		}

		/*Traitement de la pagination*/

		else
		{
			return $this->listTicketAll($cdpt_module);
		}
	}

	/*Function to plate a fil list*/
	public function filListeOne($cdpt_module, $id_messageth = null)
	{
		$ID_MessageTh = 0;

		$nbr_par_page = (int)Configuration::get('cdpt_NUMBER_PAGE');

		if($id_messageth)
		{
			$ID_MessageTh = $id_messageth;
		}
		else{
			for ( $i = 0; $i < $nbr_par_page; $i++)
			{
				if(Tools::getValue('cdptmessage_'.$i))
					$ID_MessageTh = (int)Tools::getValue('cdptmessage_'.$i);
			}
		}
			$messageID = Db::getInstance()->Executes('SELECT ct.id_contact as IDC, cm.id_employee as IDE, cm.id_customer_message as ID, ct.id_customer_thread as IDM, cl.name as DEPT, cm.message as message, ct.id_order as id_order, ct.status as status, ct.id_product as id_product
					FROM '._DB_PREFIX_.'customer_thread ct
					LEFT JOIN '._DB_PREFIX_.'customer_message cm
						ON (ct.id_customer_thread = cm.id_customer_thread)
					LEFT JOIN '._DB_PREFIX_.'contact_lang cl
						ON (cl.id_contact = ct.id_contact AND cl.id_lang = '.(int)$this->context->language->id.')
					LEFT OUTER JOIN '._DB_PREFIX_.'employee e
						ON e.id_employee = cm.id_employee
					LEFT OUTER JOIN '._DB_PREFIX_.'customer c
						ON (c.email = ct.email)
					WHERE ct.id_customer = '.(int)$this->context->customer->id.' AND cm.id_customer_thread = '.$ID_MessageTh.'
					ORDER BY cm.date_add');

			$this->context->smarty->assign(array(
				'cdpt_controller1' => $this->context->link->getModuleLink($cdpt_module->name, 'default'),
				'cdpt_messageID' => $messageID,
				'cdpt_Names' => $this->context->customer->firstname.' '.$this->context->customer->lastname,
				'cdpt_email' => $this->context->customer->email,
				'cdpt_IDmessageth' => $ID_MessageTh,
				));
			return $this->setTemplate('cdpt-message-list.tpl');
	}

	/*Function to list a tickets */
	public function listTicketAll($cdpt_module)
	{
		$limite = 1;
			$nbr_par_page = (int)Configuration::get('cdpt_NUMBER_PAGE');

			$nombre_messages = Db::getInstance()->getValue('SELECT COUNT(DISTINCT ct.id_customer_thread)
					FROM '._DB_PREFIX_.'customer_thread ct
					LEFT JOIN '._DB_PREFIX_.'customer_message cm
						ON (ct.id_customer_thread = cm.id_customer_thread)
					LEFT JOIN '._DB_PREFIX_.'contact_lang cl
						ON (cl.id_contact = ct.id_contact AND cl.id_lang = '.(int)$this->context->language->id.')
					LEFT OUTER JOIN '._DB_PREFIX_.'employee e
						ON e.id_employee = cm.id_employee
					LEFT OUTER JOIN '._DB_PREFIX_.'customer c
						ON (c.email = ct.email)
					WHERE ct.id_customer = '.(int)$this->context->customer->id.' AND cm.id_employee = 0
				');

			if (Tools::isSubmit('cdpt_submitPage'))
			{
				$i = 1;
				for ($var=1; $var <= $nombre_messages; $var = $var + $nbr_par_page)
				{
					if(Tools::getValue('cdptpage_'.$i))
						$limite = (int)Tools::getValue('cdptpage_'.$i);
					$i++;
				}
			}

			$message = Db::getInstance()->Executes('SELECT ct.id_customer_thread as IDM, cm.id_customer_message as ID, cl.name as DEPT, cm.message as message, ct.id_order as id_order, ct.status as status, ct.id_product as id_product
					FROM '._DB_PREFIX_.'customer_thread ct
					LEFT JOIN '._DB_PREFIX_.'customer_message cm
						ON (ct.id_customer_thread = cm.id_customer_thread)
					LEFT JOIN '._DB_PREFIX_.'contact_lang cl
						ON (cl.id_contact = ct.id_contact AND cl.id_lang = '.(int)$this->context->language->id.')
					LEFT OUTER JOIN '._DB_PREFIX_.'employee e
						ON e.id_employee = cm.id_employee
					LEFT OUTER JOIN '._DB_PREFIX_.'customer c
						ON (c.email = ct.email)
					WHERE ct.id_customer = '.(int)$this->context->customer->id.' AND cm.id_employee = 0
					GROUP BY ct.id_customer_thread
					ORDER BY cm.date_add DESC
					LIMIT '.(($limite - 1)*$nbr_par_page).' , '.$nbr_par_page);

			$this->context->smarty->assign(array(
					'cdpt_controller1' => $this->context->link->getModuleLink($cdpt_module->name, 'default', array('form' => 'formulaire')),
					'cdpt_controller10' => $this->context->link->getModuleLink($cdpt_module->name, 'default', array('action' => 'fil')),
					'cdpt_fields_message' => $message,
					'cdpt_nbr_message' => $nombre_messages,
					'cdpt_nbr_page' => $nbr_par_page,
					'cdpt_status' => "closed",
					'cdpt_num_page' => $limite,
				));
				$has_address = $this->context->customer->getAddresses($this->context->language->id);
        $this->context->smarty->assign(array(
            'has_customer_an_address' => empty($has_address),
            'voucherAllowed' => (int)CartRule::isFeatureActive(),
            'returnAllowed' => (int)Configuration::get('PS_ORDER_RETURN')
        ));
			return $this->setTemplate('cdptListingMessage.tpl');
	}

	/**
	 * Assign template vars related to order list and product list ordered by the customer
	 */
	protected function assignOrderList()
	{
		if ($this->context->customer->isLogged())
		{
			$this->context->smarty->assign('isLogged', 1);

			$products = array();
			$result = Db::getInstance()->executeS('
			SELECT id_order
			FROM '._DB_PREFIX_.'orders
			WHERE id_customer = '.(int)$this->context->customer->id.' ORDER BY date_add');
			$orders = array();

			foreach ($result as $row)
			{
				$order = new Order($row['id_order']);
				$date = explode(' ', $order->date_add);
				$tmp = $order->getProducts();
				foreach ($tmp as $val)
					$products[$row['id_order']][$val['product_id']] = array('value' => $val['product_id'], 'label' => $val['product_name']);

				$orders[] = array('value' => $order->id, 'label' => $order->getUniqReference().' - '.Tools::displayDate($date[0], null) , 'selected' => (int)$this->getOrder() == $order->id);
			}

			$this->context->smarty->assign('orderList', $orders);
			$this->context->smarty->assign('orderedProductList', $products);
		}
	}

	protected function getOrder()
	{
		$id_order = false;
		if (!is_numeric($reference = Tools::getValue('id_order')))
		{
			$orders = Order::getByReference($reference);
			if ($orders)
				foreach ($orders as $order)
				{
					$id_order = $order->id;
					break;
				}
		}
		else
			$id_order = Tools::getValue('id_order');
		return (int)$id_order;
	}

	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(_PS_MODULE_DIR_.'/cleansav/views/css/sb-admin-2.css');
		$this->addJS(_THEME_JS_DIR_.'contact-form.js');
		$this->addJS(_PS_JS_DIR_.'validate.js');
	}
}
