<?php
/* -----------------------------------------------------------------------------------------
   $Id: newsletter.php 13588 2021-06-15 16:10:06Z GTB $

   XTC-NEWSLETTER_RECIPIENTS RC1 - Contribution for XT-Commerce http://www.xt-commerce.com
   by Matthias Hinsche http://www.gamesempire.de

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce www.oscommerce.com 
   (c) 2003	 nextcommerce www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

// include smarty
include(DIR_FS_BOXES_INC . 'smarty_default.php');

// set cache id
$cache_id = md5('lID:'.$_SESSION['language']);

if (!$box_smarty->is_cached($modCoreTemplate->getPath('boxes/box_newsletter.html'), $cache_id) || !$cache) {
  $box_smarty->assign('FORM_ACTION', xtc_draw_form('sign_in', xtc_href_link(FILENAME_NEWSLETTER, '', $request_type)));
  $box_smarty->assign('FIELD_EMAIL',xtc_draw_input_field('email', '', 'maxlength="50"'));
  $box_smarty->assign('BUTTON',xtc_image_submit('button_login_newsletter.png', IMAGE_BUTTON_LOGIN));
  $box_smarty->assign('FORM_END','</form>');
}

if (!$cache) {
  $box_newsletter = $modCoreTemplate->fetch($box_smarty, 'boxes/box_newsletter.html');
} else {
  $box_newsletter = $modCoreTemplate->fetch($box_smarty, 'boxes/box_newsletter.html', $cache_id);
}

$smarty->assign('box_NEWSLETTER',$box_newsletter);
?>