<?php

class Theappnow_Baseprice_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getPrice($_product, $tier_price)
    {
        // make new first level for tierprice
        $result = array();
        $minimumQty = $_product->getStockItem()->getMinSaleQty();
        if ($minimumQty == 0) {
            $minimumQty = $_product->getResource()->getAttribute('minimum_qty_configuable')
                ->getFrontend()->getValue($_product);
        }
        // get category id
        $categoryIds = $_product->getCategoryIds();
        if (count($categoryIds)) {
            $firstCategoryId = $categoryIds[0];
            $_category = Mage::getModel('catalog/category')->load($firstCategoryId);
            $categoryid = $_category->getId();
        }
        //get baseprice


        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = 'SELECT price_value FROM theappnow_baseprice WHERE category_id = ' . (int)$categoryid . ' LIMIT 1';
        $profitprice = $readConnection->fetchOne($query);
        $first = array(
            'qty' => (int)$minimumQty,
            'price' => self::getFinalTierPriceEach($_product->getPrice(), 0, $profitprice)
        );
        array_push(
            $result, $first);
        foreach ($tier_price as $key => $item) {
            array_push($result, array(
                'qty' => (int)$item['price_qty'],
                'price' => self::getFinalTierPriceEach($_product->getPrice(), $item['price'], $profitprice)
            ));
        }
        return $result;
    }

    public function getFinalPriceFrontend($_product, $baseprice)
    {
        $categoryIds = $_product->getCategoryIds();
        if (count($categoryIds)) {
            $firstCategoryId = $categoryIds[0];
            $_category = Mage::getModel('catalog/category')->load($firstCategoryId);
        }
        $categoryid = $_category->getId();
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = 'SELECT price_value FROM theappnow_baseprice WHERE category_id = ' . (int)$categoryid . ' LIMIT 1';
        $profit = $readConnection->fetchOne($query);
        $baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
        $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        return Mage::helper('directory')->currencyConvert($baseprice * 110 / 100 + $profit - $sale, $baseCurrencyCode, $currentCurrencyCode);

    }

    public function getFinalTierPriceEach($baseprice, $sale, $profit)
    {
        $baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
        $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        return Mage::helper('directory')->currencyConvert($baseprice * 110 / 100 + $profit - $sale, $baseCurrencyCode, $currentCurrencyCode);
    }

    public function getTotalTierPrice($_product, $number_of_products)
    {

        $baseprice = $_product->getPrice();
        $minimumQty = $_product->getStockItem()->getMinSaleQty();
        $categoryIds = $_product->getCategoryIds();
        if (count($categoryIds)) {
            $firstCategoryId = $categoryIds[0];
            $_category = Mage::getModel('catalog/category')->load($firstCategoryId);
        }
        $categoryid = $_category->getId();
        //get baseprice

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        //  $tableName = $resource->getTableName('theappnow_tierprice');
        $query = 'SELECT price_value FROM theappnow_baseprice WHERE category_id = ' . (int)$categoryid . ' LIMIT 1';
        $profit = $readConnection->fetchOne($query);
        $tier_price = $_product->getResource()->getAttribute('tier_price')
            ->getFrontend()->getValue($_product);
        $total = $tier_price[0]['price_qty'] * ($baseprice * 110 / 100 + $profit);
        // echo '-'.$total.'-';
        foreach ($tier_price as $key => $item) {

            if ($number_of_products <= $tier_price[$key]['price_qty'] - $tier_price[$key - 1]['price_qty']) {
                $value = $number_of_products * ($baseprice * 110 / 100 + $profit - $item['price']);
                $total += $value;

                return $total;
            } else {
                if ($key + 1 == count($tier_price)) {
                    $number_of_products = $number_of_products + $tier_price[$key - 1]['price_qty'] - $tier_price[$key]['price_qty'];
                    $value = $number_of_products * ($baseprice * 110 / 100 + $profit - $item['price']);
                    $total += $number_of_products * ($baseprice * 110 / 100 + $profit - $item['price']);

                    return $total;
                } else {
                    $value = ($tier_price[$key + 1]['price_qty'] - $tier_price[$key]['price_qty']) * ($baseprice * 110 / 100 + $profit - $item['price']);
                    $total += $value;
                    $number_of_products = $number_of_products + $tier_price[$key - 1]['price_qty'] - $tier_price[$key]['price_qty'];

                }

            }
        }
    }
}

?>