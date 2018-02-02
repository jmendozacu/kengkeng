<?php

/**
 * Project name 05.Development
 * Created by:
 * User: Hoang Van Cong
 * Company: Theappnow.com
 * Email: conghv@theappnow.com
 * Date: 7/24/2017
 * Time: 5:12 PM
 */
class Theappnow_PromotionalService_Model_Order_Pdf_Creditmemo extends Mage_Sales_Model_Order_Pdf_Creditmemo
{
    /**
     * Insert totals to pdf page
     *
     * @param  Zend_Pdf_Page $page
     * @param  Mage_Sales_Model_Abstract $source
     * @return Zend_Pdf_Page
     */
    protected function insertTotals($page, $source){
        $order = $source->getOrder();
        $totals = $this->_getTotalsList($source);
        $lineBlock = array(
            'lines'  => array(),
            'height' => 15
        );
        $newLogoArtworkLine = [];
        $newLogoArtworkSetup  = $order->getNewLogoArtworkSetup();

        if($newLogoArtworkSetup != 0){
            $newLogoArtworkLine = [
                [
                    'text'      => 'New logo artwork setup',
                    'feed'      => 475,
                    'align'     => 'right',
                    'font'      => 'bold'
                ],
                [
                    'text'      =>  $order->formatPriceTxt($newLogoArtworkSetup),
                    'feed'      => 565,
                    'align'     => 'right',
                    'font'      => 'bold'
                ],
            ];
        }

        foreach ($totals as $total) {
            $total->setOrder($order)
                ->setSource($source);

            if ($total->canDisplay()) {
                $total->setFontSize(10);
                foreach ($total->getTotalsForDisplay() as $totalData) {
                    $lineBlock['lines'][] = array(
                        array(
                            'text'      => $totalData['label'],
                            'feed'      => 475,
                            'align'     => 'right',
                            'font_size' => $totalData['font_size'],
                            'font'      => 'bold'
                        ),
                        array(
                            'text'      => $totalData['amount'],
                            'feed'      => 565,
                            'align'     => 'right',
                            'font_size' => $totalData['font_size'],
                            'font'      => 'bold'
                        ),
                    );
                    if($totalData['label'] == 'Subtotal:' && $newLogoArtworkSetup != 0){
                        $lineBlock['lines'][] = $newLogoArtworkLine;
                    }

                }
            }
        }
        $this->y -= 20;
        $page = $this->drawLineBlocks($page, array($lineBlock));
        return $page;
    }
}