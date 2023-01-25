<?php
/* -----------------------------------------------------------------------------------------
   $Id: reviews.php 14481 2022-05-24 13:02:44Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(reviews.php,v 1.48 2003/05/27); www.oscommerce.com
   (c) 2003 nextcommerce (reviews.php,v 1.12 2003/08/17); www.nextcommerce.org
   (c) 2006 XT-Commerce (reviews.php 1238 2005-09-24)

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

include ('includes/application_top.php');

// include needed functions
require_once (DIR_FS_INC.'xtc_word_count.inc.php');
require_once (DIR_FS_INC.'xtc_date_long.inc.php');
require_once (DIR_FS_INC.'xtc_date_short.inc.php');

if ($_SESSION['customers_status']['customers_status_read_reviews'] == '0') {
  xtc_redirect(xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
}

// create smarty
$smarty = new Smarty;
$smarty->assign('language', $_SESSION['language']);
$smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');

$reviews_query_raw = "SELECT r.reviews_id,
                        left(rd.reviews_text, 250) as reviews_text,
                             r.reviews_rating,
                             r.date_added,
                             r.customers_name,
                             p.products_id,
                             p.products_image,
                             pd.products_name,
                             pd.products_heading_title
                        FROM ".TABLE_REVIEWS." r
                        JOIN ".TABLE_REVIEWS_DESCRIPTION." rd
                             ON r.reviews_id = rd.reviews_id
                                AND rd.languages_id = '".(int)$_SESSION['languages_id']."'
                        JOIN ".TABLE_PRODUCTS." p
                             ON p.products_id = r.products_id
                        JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                             ON p.products_id = pd.products_id
                                AND trim(pd.products_name) != ''
                                AND pd.language_id = '".(int)$_SESSION['languages_id']."'
                       WHERE p.products_status = '1'
                             ".PRODUCTS_CONDITIONS_P."
                         AND r.reviews_status = '1'
                    ORDER BY r.reviews_id DESC";
                    
$reviews_split = new splitPageResults($reviews_query_raw, (isset($_GET['page']) ? (int)$_GET['page'] : 1), MAX_DISPLAY_NEW_REVIEWS);

$module_data = array ();
if ($reviews_split->number_of_rows > 0) {

  if (!is_file(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/pagination.html')) {
    $pagination = '<div style="width:100%;font-size:smaller">
                     <div style="float:left">'.$reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS).'</div>
                     <div style="float:right">'.TEXT_RESULT_PAGE.' '.$reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, xtc_get_all_get_params(array ('page', 'info', 'x', 'y'))).'</div>
                     <br style="clear:both" />
                   </div>';
  } else {
    $smarty->assign('DISPLAY_COUNT', $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS));
    $smarty->assign('DISPLAY_LINKS', $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, xtc_get_all_get_params(array ('page', 'info', 'x', 'y'))));
    $smarty->caching = 0;
    $pagination = $modCoreTemplate->fetch($smarty, 'module/pagination.html');
  }
  $smarty->assign('NAVBAR', $pagination);
  $smarty->assign('PAGINATION', $pagination);
  
  $reviews_query = xtc_db_query($reviews_split->sql_query);
  while ($reviews = xtc_db_fetch_array($reviews_query)) {
    $module_data[] = array (
      'PRODUCTS_IMAGE' => $product->productImage($reviews['products_image'], 'thumbnail'),
      'PRODUCTS_LINK' => xtc_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id='.$reviews['products_id'].'&reviews_id='.$reviews['reviews_id']),
      'PRODUCTS_NAME' => $reviews['products_name'],
      'PRODUCTS_HEADING_TITLE' => $reviews['products_heading_title'],
      'AUTHOR' => $reviews['customers_name'],
      'DATE' => xtc_date_short($reviews['date_added']),
      'TEXT' => '('.sprintf(TEXT_REVIEW_WORD_COUNT, xtc_word_count($reviews['reviews_text'], ' ')).') <br />'.nl2br(encode_htmlspecialchars($reviews['reviews_text'])).'...',
      'TEXT_PLAIN' => nl2br(encode_htmlspecialchars($reviews['reviews_text'])).'...',
      'RATING' => xtc_image('templates/'.CURRENT_TEMPLATE.'/img/stars_'.$reviews['reviews_rating'].'.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating']),'','','itemprop="rating"'),
      'RATING_VOTE' => $reviews['reviews_rating']
    );
  }
  $smarty->assign('module_content', $module_data);
}

// include boxes
require $modCoreTemplate->getPath('source/boxes.php');

$breadcrumb->add(NAVBAR_TITLE_REVIEWS, xtc_href_link(FILENAME_REVIEWS));
require (DIR_WS_INCLUDES.'header.php');

if ($messageStack->size('product_reviews') > 0) {
  $smarty->assign('error_message', $messageStack->output('product_reviews'));
}
if ($messageStack->size('product_reviews', 'success') > 0) {
  $smarty->assign('success_message', $messageStack->output('product_reviews', 'success'));
}

if (defined('REVIEWS_PURCHASED_INFOS') && REVIEWS_PURCHASED_INFOS != '') {
  $shop_content_data = $main->getContentData(REVIEWS_PURCHASED_INFOS);
  $smarty->assign('REVIEWS_NOTE', $main->getContentLink(REVIEWS_PURCHASED_INFOS, $shop_content_data['content_title'], $request_type, false));
}

$smarty->caching = 0;
$main_content = $modCoreTemplate->fetch($smarty, 'module/reviews.html');

$smarty->assign('main_content', $main_content);
$smarty->caching = 0;
if (!defined('RM'))
  $smarty->load_filter('output', 'note');
$modCoreTemplate->display($smarty, 'index.html');
include ('includes/application_bottom.php');