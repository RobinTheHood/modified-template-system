<?php
/* -----------------------------------------------------------------------------------------
   $Id: ssl_check.php 13676 2021-08-11 13:21:52Z GTB $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ssl_check.php,v 1.1 2003/03/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (ssl_check.php,v 1.9 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

include ('includes/application_top.php');

$smarty = new Smarty;
$smarty->assign('language', $_SESSION['language']);
$smarty->assign('tpl_path', $modCoreTemplate->getUrl(''));

// include boxes
require $modCoreTemplate->getPath('source/boxes.php');

$breadcrumb->add(NAVBAR_TITLE_SSL_CHECK, xtc_href_link(FILENAME_SSL_CHECK));

require (DIR_WS_INCLUDES.'header.php');

// set cache ID
if (!CacheCheck()) {
  $cache = false;
  $smarty->caching = 0;
  $cache_id = null;
} else {
  $cache = true;
  $smarty->caching = 1;
  $smarty->cache_lifetime = CACHE_LIFETIME;
  $smarty->cache_modified_check = CACHE_CHECK == 'true';
  $cache_id = md5('lID:'.$_SESSION['language']);
}

if (!$smarty->is_cached($modCoreTemplate->getPath('module/ssl_check.html'), $cache_id) || !$cache) {
  $smarty->assign('BUTTON_CONTINUE', '<a href="'.xtc_href_link(FILENAME_DEFAULT).'">'.xtc_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE).'</a>');
}

$main_content = $modCoreTemplate->fetch($smarty, 'module/ssl_check.html', $cache_id);

$smarty->assign('language', $_SESSION['language']);
$smarty->assign('main_content', $main_content);
$smarty->caching = 0;
if (!defined('RM'))
	$smarty->load_filter('output', 'note');
$modCoreTemplate->display($smarty, 'index.html');
include ('includes/application_bottom.php');