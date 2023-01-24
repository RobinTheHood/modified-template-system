<?php
/**
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                          m a g n a l i s t e r
 *                                      boost your Online-Shop
 *
 * -----------------------------------------------------------------------------
<<<<<<< HEAD
 * $Id$
 *
 * (c) 2010 - 2016 RedGecko GmbH -- http://www.redgecko.de
=======
 * $Id: 074.sql.php $
 *
 * (c) 2016 RedGecko GmbH -- http://www.redgecko.de
>>>>>>> master
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

$queries = array();
$functions = array();

function ml_db_update_98() {
    if (date('Y-m-d') == '2017-10-26') {
        MagnaDB::gi()->delete(TABLE_MAGNA_CONFIG, array(
            'mpID' => 0,
            'mkey' => 'general.apiurl',
            'value' => 'http://api.hades.magnalister.com/API/',
        ));
    }
}

$functions[] = 'ml_db_update_98';
