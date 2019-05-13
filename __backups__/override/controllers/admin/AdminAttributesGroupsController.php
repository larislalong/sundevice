<?php

class AdminAttributesGroupsController extends AdminAttributesGroupsControllerCore
{
    public function renderFormAttributes()
    {
        $attributes_groups = AttributeGroup::getAttributesGroups($this->context->language->id);

        $this->table = 'attribute';
        $this->identifier = 'id_attribute';

        $this->show_form_cancel_button = true;
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Values'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Attribute group'),
                    'name' => 'id_attribute_group',
                    'required' => true,
                    'options' => array(
                        'query' => $attributes_groups,
                        'id' => 'id_attribute_group',
                        'name' => 'name'
                    ),
                    'hint' => $this->l('Choose the attribute group for this value.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Value'),
                    'name' => 'name',
                    'lang' => true,
                    'required' => true,
                    'hint' => $this->l('Invalid characters:').' <>;=#{}'
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description'),
                    'name' => 'attr_desc',
					'lang' => true,
					'cols' => 60,
					'rows' => 20,
					'class' => 'rte',
					'autoload_rte' => true,
                    'required' => false,
                    'hint' => $this->l('Invalid characters:').' <>;=#{}'
                )
            )
        );

        if (Shop::isFeatureActive()) {
            // We get all associated shops for all attribute groups, because we will disable group shops
            // for attributes that the selected attribute group don't support
            $sql = 'SELECT id_attribute_group, id_shop FROM '._DB_PREFIX_.'attribute_group_shop';
            $associations = array();
            foreach (Db::getInstance()->executeS($sql) as $row) {
                $associations[$row['id_attribute_group']][] = $row['id_shop'];
            }

            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso',
                'values' => Shop::getTree()
            );
        } else {
            $associations = array();
        }

        $this->fields_form['shop_associations'] = Tools::jsonEncode($associations);

        $this->fields_form['input'][] = array(
            'type' => 'color',
            'label' => $this->l('Color'),
            'name' => 'color',
            'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        );

        $this->fields_form['input'][] = array(
            'type' => 'file',
            'label' => $this->l('Texture'),
            'name' => 'texture',
            'hint' => array(
                $this->l('Upload an image file containing the color texture from your computer.'),
                $this->l('This will override the HTML color!')
            )
        );

        $this->fields_form['input'][] = array(
            'type' => 'current_texture',
            'label' => $this->l('Current texture'),
            'name' => 'current_texture'
        );

        $this->fields_form['input'][] = array(
            'type' => 'closediv',
            'name' => ''
        );

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
        );

        $this->fields_form['buttons'] = array(
            'save-and-stay' => array(
                'title' => $this->l('Save then add another value'),
                'name' => 'submitAdd'.$this->table.'AndStay',
                'type' => 'submit',
                'class' => 'btn btn-default pull-right',
                'icon' => 'process-icon-save'
            )
        );

        $this->fields_value['id_attribute_group'] = (int)Tools::getValue('id_attribute_group');

        // Override var of Controller
        $this->table = 'attribute';
        $this->className = 'Attribute';
        $this->identifier = 'id_attribute';
        $this->lang = true;
        $this->tpl_folder = 'attributes/';

        // Create object Attribute
        if (!$obj = new Attribute((int)Tools::getValue($this->identifier))) {
            return;
        }

        $str_attributes_groups = '';
        foreach ($attributes_groups as $attribute_group) {
            $str_attributes_groups .= '"'.$attribute_group['id_attribute_group'].'" : '.($attribute_group['group_type'] == 'color' ? '1' : '0').', ';
        }

        $image = '../img/'.$this->fieldImageSettings['dir'].'/'.(int)$obj->id.'.jpg';

        $this->tpl_form_vars = array(
            'strAttributesGroups' => $str_attributes_groups,
            'colorAttributeProperties' => Validate::isLoadedObject($obj) && $obj->isColorAttribute(),
            'imageTextureExists' => file_exists(_PS_IMG_DIR_.$this->fieldImageSettings['dir'].'/'.(int)$obj->id.'.jpg'),
            'imageTexture' => $image,
            'imageTextureUrl' => Tools::safeOutput($_SERVER['REQUEST_URI']).'&deleteImage=1'
        );

        $this->fields_form['shop_associations'] = Tools::jsonEncode($associations);
        return AdminController::renderForm();
    }

}