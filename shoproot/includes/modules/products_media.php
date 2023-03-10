<?php
/* -----------------------------------------------------------------------------------------
   $Id: products_media.php 14501 2022-05-31 09:03:37Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
 -----------------------------------------------------------------------------------------
   based on:
   (c) 2003 nextcommerce (products_media.php,v 1.8 2003/08/25); www.nextcommerce.org
   (c) 2003 XT-Commerce (products_media.php 1259 2005-09-29 16:11:19Z mz)

   Released under the GNU General Public License
 ---------------------------------------------------------------------------------------*/

// include needed functions
require_once (DIR_FS_INC.'xtc_filesize.inc.php');

$module_smarty = new Smarty;
$module_smarty->assign('language', $_SESSION['language']);
$module_smarty->assign('tpl_path', $modCoreTemplate->getUrl(''));

// set cache ID
if (!CacheCheck()) {
  $cache = false;
  $module_smarty->caching = 0;
  $cache_id = null;
} else {
  $cache = true;
  $module_smarty->caching = 1;
  $module_smarty->cache_lifetime = CACHE_LIFETIME;
  $module_smarty->cache_modified_check = CACHE_CHECK == 'true';
  $cache_id = md5('lID:'.$_SESSION['language'].'|csID:'.$_SESSION['customers_status']['customers_status_id'].'|pID:'.$product->data['products_id']);
}

if (!$module_smarty->is_cached($modCoreTemplate->getPath('module/products_media.html'), $cache_id) || !$cache) {
  $content_query = xtDBquery("SELECT *
                                FROM ".TABLE_PRODUCTS_CONTENT."
                               WHERE products_id = '".$product->data['products_id']."'
                                 AND languages_id = '".(int) $_SESSION['languages_id']."'
                                     ".CONTENT_CONDITIONS);

  if (xtc_db_num_rows($content_query, true) > 0) {
    $module_content = array ();

    while ($content_data = xtc_db_fetch_array($content_query, true)) {
    
      $icon = xtc_image(DIR_WS_ICONS.'filetype/icon_link.gif');
      $filename = ($content_data['content_link'] != '') ? '<a href="'.$content_data['content_link'].'" target="_blank">'.$content_data['content_name'].'</a>' : $content_data['content_name'];
    
      $button = '';
      $filesize = '';
      if ($content_data['content_link'] == '') {
        $allowed_content_types = array('html','htm','txt','bmp','jpg','jpeg','gif','png','tif');
        $content_file_parts = explode('.', $content_data['content_file']);
        $content_file_type = end($content_file_parts);
        if (!is_file(DIR_WS_ICONS.'filetype/icon_'.$content_file_type.'.gif')) {
          $content_file_type = 'link';
        }
        $icon = xtc_image(DIR_WS_ICONS.'filetype/icon_'.$content_file_type.'.gif');
        if (in_array($content_file_type,$allowed_content_types)) {
          $btnlink_parameters = defined('TPL_POPUP_CONTENT_LINK_PARAMETERS') ? TPL_POPUP_CONTENT_LINK_PARAMETERS : POPUP_CONTENT_LINK_PARAMETERS;
          $btnlink_class = defined('TPL_POPUP_CONTENT_LINK_CLASS') ? TPL_POPUP_CONTENT_LINK_CLASS : POPUP_CONTENT_LINK_CLASS;
          $button = '<a target="_blank"'.
                    ' href="'.xtc_href_link(FILENAME_MEDIA_CONTENT, 'coID='.$content_data['content_id'].$btnlink_parameters).'"'.
                    ' class="'.$btnlink_class.'">'.
                    xtc_image_button('button_view.gif', TEXT_VIEW).
                    '</a>';
          $filesize = xtc_filesize($content_data['content_file']);
        } elseif ($content_data['content_file'] != '') {
          $button = '<a target="_blank"'.
                    ' href="'.xtc_href_link('media/products/'.$content_data['content_file']).'">'.
                    xtc_image_button('button_download.gif', TEXT_DOWNLOAD).
                    '</a>';
          $filesize = xtc_filesize($content_data['content_file']);
        }
      }
    
      $module_content[] = array (
        'ICON' => $icon,
        'FILENAME' => $filename,
        'DESCRIPTION' => $content_data['file_comment'],
        'FILESIZE' => $filesize,
        'BUTTON' => $button,
        'HITS' => $content_data['content_read']
      );
    }

    $module_smarty->assign('module_content', $module_content);
  }
}

$module = $modCoreTemplate->fetch($module_smarty, 'module/products_media.html', $cache_id);
$info_smarty->assign('MODULE_products_media', !empty($module) ? trim($module) : $module);
