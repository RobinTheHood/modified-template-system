<?php
/* -----------------------------------------------------------------------------------------
   $Id: shipping_block.php 13811 2021-11-10 14:16:53Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  $module_smarty = new Smarty;
  $shipping_block = '';
  if (xtc_count_shipping_modules() > 0) {
    $showtax = $_SESSION['customers_status']['customers_status_show_price_tax'];
    $module_smarty->assign('FREE_SHIPPING', $free_shipping);
    # free shipping or not...
    if ($free_shipping == true) {
      $module_smarty->assign('FREE_SHIPPING_TITLE', FREE_SHIPPING_TITLE);
      $module_smarty->assign('FREE_SHIPPING_DESCRIPTION', sprintf(FREE_SHIPPING_DESCRIPTION, $xtPrice->xtcFormat($free_shipping_value_over, true, 0, true)).xtc_draw_hidden_field('shipping', 'free_free'));
      $module_smarty->assign('FREE_SHIPPING_ICON', (isset($quotes[$i]['icon'])) ? $quotes[$i]['icon'] : '');
    } else {
      $radio_buttons = 0;
      #loop through installed shipping methods...
      for ($i = 0, $n = sizeof($quotes); $i < $n; $i ++) {
        if (!isset($quotes[$i]['error'])) {
          for ($j = 0, $n2 = sizeof($quotes[$i]['methods']); $j < $n2; $j ++) {
            # set the radio button to be checked if it is the method chosen
            $quotes[$i]['methods'][$j]['radio_buttons'] = $radio_buttons;
            $checked = ((isset($_SESSION['shipping']) && is_array($_SESSION['shipping']) && array_key_exists('id', $_SESSION['shipping']) && $quotes[$i]['id'].'_'.$quotes[$i]['methods'][$j]['id'] == $_SESSION['shipping']['id']) ? true : false);
            if (($checked == true) || ($n == 1 && $n2 == 1)) {
              $quotes[$i]['methods'][$j]['checked'] = 1;
            }
            if (($n > 1) || ($n2 > 1)) {
              if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 || !isset($quotes[$i]['tax'])) {
                $quotes[$i]['tax'] = 0;
              }
              $quotes[$i]['methods'][$j]['price'] = $xtPrice->xtcFormat($xtPrice->xtcAddTax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax']), true, 0, true);						
              $quotes[$i]['methods'][$j]['radio_field'] = xtc_draw_radio_field('shipping', $quotes[$i]['id'].'_'.$quotes[$i]['methods'][$j]['id'], $checked, 'id="rd-'.$radio_buttons.'"');
            } else {
              if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0) {
                $quotes[$i]['tax'] = 0;
              }
              $quotes[$i]['methods'][$j]['price'] = $xtPrice->xtcFormat($xtPrice->xtcAddTax($quotes[$i]['methods'][$j]['cost'], isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0), true, 0, true).xtc_draw_hidden_field('shipping', $quotes[$i]['id'].'_'.$quotes[$i]['methods'][$j]['id']);
              if (CHECK_CHEAPEST_SHIPPING_MODUL == 'true') {
                $quotes[$i]['methods'][$j]['radio_field'] = xtc_draw_radio_field('shipping', $quotes[$i]['id'].'_'.$quotes[$i]['methods'][$j]['id'], $checked, 'id="rd-'.$radio_buttons.'"');
              }
            }
            $radio_buttons ++;
          }
        }
      }
      $module_smarty->assign('module_content', $quotes);
    }
    $module_smarty->caching = 0;
    $module_smarty->assign('language', $_SESSION['language']);
    $shipping_block = $modCoreTemplate->fetch($module_smarty, 'module/checkout_shipping_block.html');
  }
?>