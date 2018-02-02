<?php
/**
 * Created by PhpStorm.
 * User: Truong Tuan Dat
 * Date: 9/25/2017
 * Time: 11:34 AM
 */
require_once 'Mage/Checkout/controllers/CartController.php';


class ABSoft_StoreListing_CartController extends Mage_Checkout_CartController {
    /**
     *
     */
    public function addAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_goBack();
            return;
        }
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();
        try {
            $isAjax = Mage::app()->getRequest()->isAjax();
            if($isAjax){
                $response = array();
                try {
                    if (isset($params['qty'])) {
                        $filter = new Zend_Filter_LocalizedToNormalized(
                            array('locale' => Mage::app()->getLocale()->getLocaleCode())
                        );
                        $params['qty'] = $filter->filter($params['qty']);
                    }

                    $product = $this->_initProduct();
                    $related = $this->getRequest()->getParam('related_product');

                    /**
                     * Check product availability
                     */
                    if (!$product) {
                        $response['status'] = 'ERROR';
                        $response['message'] = $this->__('Unable to find Product ID');
                    }

                    $cart->addProduct($product, $params);
                    $quote = Mage::getSingleton('checkout/session')->getQuote();

                    if (!empty($related)) {
                        $cart->addProductsByIds(explode(',', $related));
                    }
//                    $quoteItem = $quote->addProduct($product, $params['qty'],$params['options']);

//                    $quote->collectTotals()->save();

//                    $quoteItem->getId();
                    $cart->save();
                    $this->_getSession()->setCartWasUpdated(true);

                    /**
                     * @todo remove wishlist observer processAddToCart
                     */
                    Mage::dispatchEvent('checkout_cart_add_product_complete',
                        array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
                    );

                    if (!$this->_getSession()->getNoCartRedirect(true)) {
                        if (!$cart->getQuote()->getHasError()){


                            //lấy tất cả các options từ request
                            $options=[];
                            foreach ($params['options'] as $option) {
                                foreach ($option as $optionValue) {
                                    $options[]=$optionValue;
                                }
                            }
                            $product_id= $params['product'];
                            $itemId='';
                            //lấy các options của item trong giỏ hàng
                            $product = Mage::getSingleton('checkout/cart')->getQuote()->getAllItems();
                            foreach ($product as $item) {
                                if($item->getProductId()==$product_id){
                                    //lặp tất cả các option của item gộp vào một mảng
                                    $optionsCart=[];
                                    foreach($item->getBuyRequest()->getOptions() as  $option){
                                        $optionsCart= array_merge($optionsCart,$option);
                                    }
                                    //so sánh mảng options từ request có giống với options trong item
                                    //có => lấy id của item
                                    //không => tiếp túc kiểm tra
                                    $check=array_merge(array_diff($options, $optionsCart), array_diff($optionsCart, $options));
                                    if(count($check)==0){
                                        $itemId= $item->getId();
                                        break;
                                    }
                                }

                            }
                            //trả dữ liệu về cho ajax
                            $quote = Mage::getModel('checkout/session')->getQuote();
//                            $item = $quote->getItemByProduct($product);
                             $url = Mage::getUrl(
                                'checkout/cart/delete',
                                array(
                                    'id' => $itemId,
                                    'form_key' => Mage::getSingleton('core/session')->getFormKey(),
                                    Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => Mage::helper('core/url')->getEncodedUrl()
                                )
                            );
                             $result['url']=$url;
                             $result['item_id']=$itemId;
                            $this->getResponse()->setHeader('Content-type', 'application/json');
                            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode( $result));
                            return;
                        }
                    }
                } catch (Mage_Core_Exception $e) {
                    $msg = "";
                    if ($this->_getSession()->getUseNotice(true)) {
                        $msg = $e->getMessage();
                    } else {
                        $messages = array_unique(explode("\n", $e->getMessage()));
                        foreach ($messages as $message) {
                            $msg .= $message.'<br/>';
                        }
                    }

                    $response['status'] = 'ERROR';
                    $response['message'] = $msg;
                } catch (Exception $e) {
                    $response['status'] = 'ERROR';
                    $response['message'] = $this->__('Cannot add the item to shopping cart.');
                    Mage::logException($e);
                }
//                $this->getResponse()->setHeader('Content-type', 'application/json');

//                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(['detail_cart'=>$detail_cart,'total_cart'=>$total_cart]));
                return;
            }
        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError(Mage::helper('core')->escapeHtml($message));
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
            $this->_goBack();
        }
    }

    public function deleteAction()
    {
        if ($this->_validateFormKey()) {
            $id = (int)$this->getRequest()->getParam('id');
            if ($id) {
                try {
                    $this->_getCart()->removeItem($id)
                        ->save();
                } catch (Exception $e) {
                    $this->_getSession()->addError($this->__('Cannot remove the item.'));
                    Mage::logException($e);
                }
            }
        } else {
            $this->_getSession()->addError($this->__('Cannot remove the item.'));
        }
    }
    public function updateQtyItemAction(){
        if(Mage::app()->getRequest()->isAjax()) {
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $product_sku = Mage::app()->getRequest()->getParam('sku');
            $qty = Mage::app()->getRequest()->getParam('qty');
            $product = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('sku',$product_sku)->getFirstItem();
            $item = $quote->getItemByProduct($product);
            $quote->updateItem($item->getItemId(),['qty'=>$qty]);
            $quote->save();

        }
    }

    public function updateQtyItemHasOptionsAction(){
        if(Mage::app()->getRequest()->isAjax()) {
            $this->_updateShoppingCart();
//            $quote = Mage::getSingleton('checkout/session')->getQuote();
//            $product_sku = Mage::app()->getRequest()->getParam('sku');
//            $qty = Mage::app()->getRequest()->getParam('qty');
//            $item_id = Mage::app()->getRequest()->getParam('item_id');
//            $item = $quote->getItemById($item_id);
//            $quote->updateItem($item->getItemId(),['qty'=>$qty]);
//            $quote->save();

        }
    }
    protected function _updateShoppingCart()
    {
        try {
            $cartData = $this->getRequest()->getParam('cart');
            if (is_array($cartData)) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                foreach ($cartData as $index => $data) {
                    if (isset($data['qty'])) {
                        $cartData[$index]['qty'] = $filter->filter(trim($data['qty']));
                    }
                }
                $cart = $this->_getCart();
                if (! $cart->getCustomerSession()->getCustomer()->getId() && $cart->getQuote()->getCustomerId()) {
                    $cart->getQuote()->setCustomerId(null);
                }

                $cartData = $cart->suggestItemsQty($cartData);
                $cart->updateItems($cartData)
                    ->save();
            }
            $this->_getSession()->setCartWasUpdated(true);
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(Mage::helper('core')->escapeHtml($e->getMessage()));
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot update shopping cart.'));
            Mage::logException($e);
        }
    }
}
