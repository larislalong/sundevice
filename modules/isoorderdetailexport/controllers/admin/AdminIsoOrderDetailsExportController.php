<?php

class AdminIsoOrderDetailsExportController extends ModuleAdminController {
    /**
     * @see AdminController->init();
     */
    public function init()
    {

        parent::init();

        // Just redirect to the module configuration page
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules') . '&configure=' . Tools::safeOutput($this->module->name));

    }

}
