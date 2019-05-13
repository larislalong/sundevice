<?php

class AdminPosslideshowController extends ModuleAdminController
{
	public function __construct()
	{
		$this->table = 'pos_slideshow';
		$this->className = 'Nivoslideshow';
		$this->lang = true;
		$this->bootstrap = true;
		$this->deleted = false;
		$this->colorOnBackground = false;
		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));
        Shop::addTableAssociation($this->table, array('type' => 'shop'));
		$this->context = Context::getContext();
                
                $this->fieldImageSettings = array(
 			'name' => 'image',
 			'dir' => 'blockslideshow'
 		);
                $this->imageType = "jpg";
		
		parent::__construct();
	}
        
        public function renderList() {
            
            $this->addRowAction('edit');
            $this->addRowAction('delete');
            $this->bulk_actions = array(
                'delete' => array(
                    'text' => $this->l('Delete selected'),
                    'confirm' => $this->l('Delete selected items?')
                )
            );

            $this->fields_list = array(
                'id_pos_slideshow' => array(
                    'title' => $this->l('ID'),
                    'align' => 'center',
                    'width' => 25
                ),
                  'title' => array(
                    'title' => $this->l('Title'),
                    'width' => 90,
                ),
                  'link' => array(
                    'title' => $this->l('Link'),
                    'width' => 90,
                ),
                
                'description' => array(
                    'title' => $this->l('Desscription'),
                    'width' => '300',
                 ),
				  'active' => array(
                                'title' => $this->l('Status'),
                                'width' => '70',
                                'align' => 'center',
                                'active' => 'status',
                                'type' => 'bool',
                                'orderby' => false
                    ),
                  'porder' => array(
                    'title' => $this->l('Order'),
                    'width' => 10,
                ),
				
            );
            
            $this->fields_list['image'] = array(
                'title' => $this->l('Image'),
                'width' => 70,
                "image" => $this->fieldImageSettings["dir"]
            );
//            

            $lists = parent::renderList();
            parent::initToolbar();

            return $lists;
    }
    
    		public function processSave()
	{
		if ($this->id_object)
		{
				$id = $this->id_object.'_1'; 
		
			if (file_exists(_PS_TMP_IMG_DIR_.$this->table.'_mini_'.$id.'.'.$this->imageType) && !unlink(_PS_TMP_IMG_DIR_.$this->table.'_mini_'.$id.'.'.$this->imageType))
			$this->object = $this->loadObject();
			return $this->processUpdate();
		}
		else
			return $this->processAdd();
	}
    
    public function renderForm() {
        
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Slideshow'),
                'image' => '../img/admin/cog.gif'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title:'),
                    'name' => 'title',
                    'size' => 40,
					'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Link:'),
                    'name' => 'link',
                    'size' => 40,
					 'lang' => true,
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image:'),
                    'name' => 'image',
                    'desc' => $this->l('Upload  a banner from your computer.')
                ),
              array(
                'type' => 'textarea',
                'label' => $this->l('Description'),
                'name' => 'description',
                'autoload_rte' => TRUE,
                'lang' => true,
                'required' => TRUE,
                'rows' => 5,
                'cols' => 40,
                'hint' => $this->l('Invalid characters:') . ' <>;=#{}'
               ),
			array(
					'type' => 'switch',
					'label' => $this->l('Enabled'),
					'name' => 'active',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
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
					'hint' => $this->l('Enable or disable customer login.')
				),
				array(
                    'type' => 'text',
                    'label' => $this->l('Order:'),
                    'name' => 'porder',
                    'size' => 40,
                    'require' => false
                ),
            ),
             'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );
                 if (Shop::isFeatureActive())
                $this->fields_form['input'][] = array(
                        'type' => 'shop',
                        'label' => $this->l('Shop association:'),
                        'name' => 'checkBoxShopAsso',
                );

        if (!($obj = $this->loadObject(true)))
            return;


        return parent::renderForm();
    }
    

}
