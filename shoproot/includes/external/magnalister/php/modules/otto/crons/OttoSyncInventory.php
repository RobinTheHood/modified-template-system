<?php
/*
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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

require_once(DIR_MAGNALISTER_MODULES.'magnacompatible/crons/MagnaCompatibleSyncInventory.php');

class OttoSyncInventory extends MagnaCompatibleSyncInventory {

    protected function initMLProduct() {
        global $_MagnaSession;
        parent::initMLProduct();
        MLProduct::gi()->setPriceConfig(OttoHelper::loadPriceSettings($this->mpID));
        MLProduct::gi()->setQuantityConfig(OttoHelper::loadQuantitySettings($this->mpID));
        MLProduct::gi()->useMultiDimensionalVariations(true);
        MLProduct::gi()->setOptions(array(
            'includeVariations' => true,
            'sameVariationsToAttributes' => false,
            'purgeVariations' => true,
            'useGambioProperties' => (getDBConfigValue('general.options', '0', 'old') == 'gambioProperties')
        ));
    }

    protected function initConfig() {
        parent::initConfig();
        $stockSync = getDBConfigValue('otto.stocksync.tomarketplace', $this->mpID, 'auto');
        if ($stockSync == 'auto_zero_stock') {
            $this->config['StockSync'] = 'auto';
        }
        // like for eBay, not 'quantity.maxquantity'
        $iQuantityMax = (int)getDBConfigValue('otto.maxquantity', $this->mpID, 0);
        if ($iQuantityMax) {
            $this->config['QuantityMax'] = $iQuantityMax;
        }
    }

    protected function updateCustomFields(&$data) {
        if (array_key_exists('NewQuantity', $data)) {
            $data['Quantity'] = $data['NewQuantity']['Value'];
            unset($data['NewQuantity']);
        }
    }

    /* catch wrongly uploaded items */
    protected function identifySKU() {
        parent::identifySKU();
        if (empty($this->cItem['pID'])
            && (getDBConfigValue('general.keytype', '0') != 'artNr')
            && is_numeric($this->cItem['SKU'])) {
            $this->cItem['pID'] = $this->cItem['SKU'];
        }
    }


}
