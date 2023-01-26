<?php
/* -----------------------------------------------------------------------------------------
   $Id: 90_semknox.php 13231 2021-01-26 07:56:21Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  if (defined('MODULE_SEMKNOX_SYSTEM_STATUS')
      && MODULE_SEMKNOX_SYSTEM_STATUS == 'true'
      && isset($_GET['q'])
      )
  {
    // create smarty elements
    $smarty = new Smarty;

    // include boxes
    require $modCoreTemplate->getPath('source/boxes.php');

    // build breadcrumb
    $breadcrumb->add(NAVBAR_TITLE1_ADVANCED_SEARCH, xtc_href_link(FILENAME_ADVANCED_SEARCH));
    $breadcrumb->add(NAVBAR_TITLE2_ADVANCED_SEARCH, xtc_href_link(FILENAME_ADVANCED_SEARCH_RESULT, xtc_get_all_get_params(array('filter', 'show', 'filter_id', 'cat'))));

    require (DIR_WS_INCLUDES.'header.php');
  
    $smarty->assign('language', $_SESSION['language']);
    
    if (is_file($modCoreTemplate->getPath('module/semknox_listing.html'))) {
      $module = $modCoreTemplate->fetch($smarty, 'module/semknox_listing.html');
    } else {
      $module = $smarty->fetch(DIR_FS_EXTERNAL.'semknox/templates/semknox_listing.html');
    }
    
    $smarty->assign('main_content', $module);
  
    $smarty->assign('language', $_SESSION['language']);
    if (!defined('RM')) {
      $smarty->load_filter('output', 'note');
    }
    $modCoreTemplate->display($smarty, 'index.html');
    include ('includes/application_bottom.php');
    exit();
  }