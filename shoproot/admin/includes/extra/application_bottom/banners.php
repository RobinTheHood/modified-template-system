<?php
/* -----------------------------------------------------------------------------------------
   $Id$

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

  if (basename($PHP_SELF) == 'banner_manager.php'
      && file_exists($modCoreTemplate->getPath('config/banners.php'))
      )
  {
    require_once $modCoreTemplate->getPath('config/banners.php');
  }
