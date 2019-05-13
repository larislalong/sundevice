<?php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
include(dirname(__FILE__).'/posstaticblocks.php');

 $pos = new posstaticblocks();
 $name_module = $_POST['module_id'];
 $module = Module::getInstanceByName($name_module);
 $id_module = $module->id;
 $hooks = $pos->getHooksByModuleId($id_module);
 $hookArrays = array();
 foreach($hooks as $key => $hook) {
	$hookArrays[$key] = array('id_hook'=>$hook['name'], 'name' => $hook['name']);
 }
 $json = json_encode($hookArrays); 
die(json_encode($json));

?>
