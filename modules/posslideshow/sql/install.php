<?php

    // Init
    $sql = array();

    // Create Table in Database
    $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_slideshow` (
                      `id_pos_slideshow` int(10) NOT NULL AUTO_INCREMENT,
                      `porder` int NOT NULL,
					  `active` int NOT NULL,
					  PRIMARY KEY (`id_pos_slideshow`)
                    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
					
	$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_slideshow_lang` (
						`id_pos_slideshow` int(11) unsigned NOT NULL,
						`id_lang` int(11) unsigned NOT NULL,
						`title` varchar(250) NOT NULL,
						`link` varchar(250) NOT NULL DEFAULT "#",
						`description` longtext NOT NULL,
						`image` longtext NOT NULL,
                        PRIMARY KEY (`id_pos_slideshow`,`id_lang`)
                    ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';									

    $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_slideshow_shop` (
                        `id_pos_slideshow` int(11) unsigned NOT NULL,
                        `id_shop` int(11) unsigned NOT NULL,
                        PRIMARY KEY (`id_pos_slideshow`,`id_shop`)
                    ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
					
    
    $doc = new DOMDocument();
    $file = _PS_MODULE_DIR_ . DS . 'posslideshow' . DS . 'sql' . DS . 'slideshow.xml';
    $doc->load($file);
    $blocks = $doc->getElementsByTagName("slideshow");
    foreach ($blocks as $block) {
        $ids = $block->getElementsByTagName("id_pos_slideshow");
        $id = $ids->item(0)->nodeValue;
        $actives = $block->getElementsByTagName("active");
        $active = $actives->item(0)->nodeValue;
		$porders = $block->getElementsByTagName("porder");
        $porder = $porders->item(0)->nodeValue;
        //  echo $id.'-'.$title.'-'.$description.'-'.$link.'-'.$porder; echo "<br>";
        $sql[] = "insert into `" . _DB_PREFIX_ . "pos_slideshow` (`id_pos_slideshow`,`porder`,`active`) 
				  values('".$id."','".$porder."','".$active."');";
    }
	
	
	$blocklangs = $doc->getElementsByTagName("slideshow_lang");
    foreach ($blocklangs as $block) {
        $ids = $block->getElementsByTagName("id_pos_slideshow");
        $id = $ids->item(0)->nodeValue;
        $titles = $block->getElementsByTagName("title");
        $title = $titles->item(0)->nodeValue;
        $links = $block->getElementsByTagName("link");
        $link = $links->item(0)->nodeValue;
        $descriptions = $block->getElementsByTagName("description");
        $description = $descriptions->item(0)->nodeValue;
		$id_langs = $block->getElementsByTagName('id_lang');
		$id_lang = $id_langs->item(0)->nodeValue;
        $sql[] = "insert into `" . _DB_PREFIX_ . "pos_slideshow_lang` (`id_pos_slideshow`,`id_lang`, `title`, `link`, `description`) 
           values('".$id."','".$id_lang."','".$title."','".$link."','".$description."');";
    }

    $blockshops = $doc->getElementsByTagName("slideshow_shop");
    foreach ($blockshops as $blockshop) {
        $ids = $blockshop->getElementsByTagName("id_pos_slideshow");
        $id = $ids->item(0)->nodeValue;
        $id_shops = $blockshop->getElementsByTagName("id_shop");
        $id_shop = $id_shops->item(0)->nodeValue;
        //echo $id.'-'.$id_shop;
        $sql[] = "insert into `" . _DB_PREFIX_ . "pos_slideshow_shop`(`id_pos_slideshow`, `id_shop`) 
                VALUES('" . $id . "','" . $id_shop . "')";
    }