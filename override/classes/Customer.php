<?php
class Customer extends CustomerCore
{
	public $commercial;
	
	
    public static $definition = array(
        'table' => 'customer',
        'primary' => 'id_customer',
        'fields' => array(
            'secure_key' =>                array('type' => self::TYPE_STRING, 'validate' => 'isMd5', 'copy_post' => false),
            'lastname' =>                    array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 32),
            'firstname' =>                    array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 32),
            'email' =>                        array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => true, 'size' => 128),
            'passwd' =>                    array('type' => self::TYPE_STRING, 'validate' => 'isPasswd', 'required' => true, 'size' => 32),
            'last_passwd_gen' =>            array('type' => self::TYPE_STRING, 'copy_post' => false),
            'id_gender' =>                    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'birthday' =>                    array('type' => self::TYPE_DATE, 'validate' => 'isBirthDate'),
            'newsletter' =>                array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'newsletter_date_add' =>        array('type' => self::TYPE_DATE,'copy_post' => false),
            'ip_registration_newsletter' =>    array('type' => self::TYPE_STRING, 'copy_post' => false),
            'optin' =>                        array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'website' =>                    array('type' => self::TYPE_STRING, 'validate' => 'isUrl'),
            'company' =>                    array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            // 'siret' =>                        array('type' => self::TYPE_STRING, 'validate' => 'isSiret'),
            'siret' =>                        array('type' => self::TYPE_STRING),
            // 'ape' =>                        array('type' => self::TYPE_STRING, 'validate' => 'isApe'),
            'ape' =>                        array('type' => self::TYPE_STRING),
            'outstanding_allow_amount' =>    array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'copy_post' => false),
            'show_public_prices' =>            array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'id_risk' =>                    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'copy_post' => false),
            'max_payment_days' =>            array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'copy_post' => false),
            'active' =>                    array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'deleted' =>                    array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'note' =>                        array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'size' => 65000, 'copy_post' => false),
			'commercial' => array('type' => self::TYPE_STRING, 'validate' => 'isName', 'size' => 50),
            'is_guest' =>                    array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'id_shop' =>                    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'id_shop_group' =>                array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'id_default_group' =>            array('type' => self::TYPE_INT, 'copy_post' => false),
            'id_lang' =>                    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'date_add' =>                    array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false),
            'date_upd' =>                    array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false),
        ),
    );
	
    public function add($autodate = true, $null_values = true)
    {
        $this->active = false;
        $this->commercial = '';
		return parent::add($autodate, $null_values);
    }

    public function update($nullValues = false)
    {
        // var_dump($this);die; 

        return parent::update(true);
    }

    public function toggleStatus()
    {
		$oldStatus = $this->active;
		$id = $this->id;
		
        parent::toggleStatus();

        /* Change status to active/inactive */
        $return = Db::getInstance()->execute('
		UPDATE `'._DB_PREFIX_.bqSQL($this->def['table']).'`
		SET `date_upd` = NOW()
		WHERE `'.bqSQL($this->def['primary']).'` = '.(int)$this->id);
		
		if(!$oldStatus and $return){
			Mail::Send(
				(int)$this->id_lang,
				'customer_activation',
				(int)$this->id_lang == 1 ? 'Votre compte a été activer' : 'Your account has been activated',
				array(
					'{firstname}' => $this->firstname,
					'{lastname}' => $this->lastname,
                ),
				$this->email,
				$this->firstname.' '.$this->lastname,
				null,
				null,
				null,
				null,
				_PS_MAIL_DIR_,
				false,
				(int)$this->id_shop
			);
		}
		
        return $return;
    }


    /*
	public function update($nullValues = false)
    {
		$oldStatus = $this->active;
		$id = $this->id;
		
		$return = parent::update(true);
		
		
		sleep(1);
		$currentCustomer = new Customer($id);
		if(Validate::isLoadedObject($currentCustomer)){
			$newStatus = $currentCustomer->active;
			// var_dump($oldStatus);echo '<hr><hr>';
			// var_dump($newStatus);die;
			if($return and $newStatus){
				Mail::Send(
					(int)Context::getContext()->language->id,
					'customer_activation',
					Mail::l('Votre compte a été activer', (int)Context::getContext()->language->id),
					array(
						'{firstname}' => $currentCustomer->firstname,
						'{lastname}' => $currentCustomer->lastname,
					),
					$currentCustomer->email,
					$currentCustomer->firstname.' '.$currentCustomer->lastname,
					null,
					null,
					null,
					null,
					_PS_MAIL_DIR_,
					false,
					(int)$currentCustomer->id_shop
				);
			}
		}
		
        return $return;
    }
	
	public static function sendMailOnCustomerActivation($id, $oldStatus){
		
	}
	*/
	
}
