<?php

/**
 * @category   Discontinued Products
 * @package    LevoSoft_DiscontinuedProducts
 * @author     Hassan Ali Shahzad <levosoft786@gmail.com>
 *
 */
class LevoSoft_DiscontinuedProducts_Model_Observer
{

    public $noOfProducts = 1;

    public function discontinuedProductsRedirect(Varien_Event_Observer $observer)
    {
        //Mage::dispatchEvent('admin_session_user_login_success', array('user'=>$user));
        //$user = $observer->getEvent()->getUser();
        //$user->doSomething();
        $event = $observer->getEvent();
        $product = $event->getProduct();
        $productUrl = "";


        if ($product->getLevoDiscontinued() > 0) {
            //var_dump($product->getData());exit;

            /*
             * redirect on fix=> id,sku,url
             * */
            if ($product->getLevoFixRedirect() != null) {
                switch ($product->getLevoFixRedirect()) {
                    case 'id':
                        $productUrl = Mage::getBaseUrl() . Mage::getResourceSingleton('catalog/product')->getAttributeRawValue($product->getLevoFixRedirectValue(), 'url_path', Mage::app()->getStore());
                        if (!empty($productUrl)) $this->_redirectTo($productUrl);
                        break;
                    case 'sku':
                        $productInstance = Mage::getModel('catalog/product')->loadByAttribute('sku', $product->getLevoFixRedirectValue());
                        if ($productInstance) {
                            $productUrl = $productInstance->getProductUrl();
                            if (!empty($productUrl)) $this->_redirectTo($productUrl);
                        }
                        break;
                    case 'url':
                        $productUrl = $product->getLevoFixRedirectValue();
                        if (!empty($productUrl)) $this->_redirectTo($productUrl);
                        break;
                }
            }

            /* Redirect to current products parent category page*/

            if ($product->getLevoFixParentCategory() > 0) {
                $categoryIds = $product->getCategoryIds();
                $productUrl = $this->_categoryUrl($categoryIds[0]);
                if (!empty($productUrl)) $this->_redirectTo($productUrl);
            }

            /* Redirect to given category page*/
            if ($product->getLevoFixCategory() != null) {
                $catId = (integer)$product->getLevoFixCategory();
                $productUrl = $this->_categoryUrl($catId);

                if (!empty($productUrl)) $this->_redirectTo($productUrl);
            }

            if ($product->getLevoFixCategoryProducts() != null) {

                $catId = (integer)$product->getLevoFixCategoryProducts();
                $productUrl = $this->_categoryBestSellingProducts($catId);

                if (!empty($productUrl)) $this->_redirectTo($productUrl);

            }

            if ($product->getLevoDefault() != null && count(explode(',', $product->getLevoDefault())) < 4) {

                $defaults = explode(',', $product->getLevoDefault());

                if (in_array('related', $defaults)) {

                    $this->_relatedRedirect($product);
                }

                if (in_array('up-sell', $defaults)) {
                    $this->_upSellRedirect($product);
                }

                if (in_array('cross-sell', $defaults)) {
                    $this->_crossSellRedirect($product);
                }

                if (in_array('parent-category', $defaults)) {
                    $this->_parentCategoryRedirect($product);
                }
            } else { // in this case no need to check present in $product->getLevoDefault()
                $this->_relatedRedirect($product);
                $this->_upSellRedirect($product);
                $this->_crossSellRedirect($product);
                $this->_parentCategoryRedirect($product);
            }

        }
    }

    /**
     * @param $productUrl
     */
    protected function _redirectTo($productUrl)
    {
        //echo $productUrl;
        //echo Mage::getBaseUrl();
        // exit;
        if (filter_var($productUrl, FILTER_VALIDATE_URL) === FALSE || $productUrl == Mage::getBaseUrl()) {
            return false;
        } else {
            Mage::app()->getResponse()
                ->setRedirect($productUrl, 301)
                ->sendResponse();
            exit();
        }
    }

    /**
     * @param $product object
     * @param $type
     * @return array
     *
     * Note: Right now this function is returing single products url in future it will return array of products depend on Backend settings
     */
    protected function _getSuggestedProductUrl($product, $type)
    {
        $suggestedProducts = array();
        $productUrl = "";
        switch ($type) {
            case 'related':
                $suggestedProducts = $product->getRelatedProducts();
                break;

            case 'up-sell':
                $suggestedProducts = $product->getUpSellProducts();
                break;

            case 'cross-sell':
                $suggestedProducts = $product->getCrossSellProducts();
                break;

        }
        if (count($suggestedProducts) > 0) {
            for ($i = 0; $i < $this->noOfProducts; $i++) {
                $suggestedProduct = $suggestedProducts[$i];
                $productUrl = $suggestedProduct->getProductUrl();
            }
        }
        return $productUrl;
    }

    /**
     * @param $catId
     * @return product url
     */
    protected function _categoryBestSellingProducts($catId)
    {
        $catId = (integer)$catId;
        $suggestedProducts = Mage::helper('discontinuedproducts')->categoryBestSellingProducts($catId, $this->noOfProducts);
        foreach ($suggestedProducts as $suggestedProduct)
            $productUrl = $suggestedProduct->getProductUrl();
        return $productUrl;
    }

    /**
     * @param $product
     */
    protected function _categoryUrl($catId)
    {
        $categoryOfProduct = Mage::getModel('catalog/category')->load($catId);
        return $categoryOfProduct->getUrl();
    }

    /**
     * @param $product
     */
    protected function _relatedRedirect($product)
    {
        $productUrl = $this->_getSuggestedProductUrl($product, 'related');
        if (!empty($productUrl)) $this->_redirectTo($productUrl);
    }

    /**
     * @param $product
     */
    protected function _upSellRedirect($product)
    {
        $productUrl = $this->_getSuggestedProductUrl($product, 'up-sell');
        if (!empty($productUrl)) $this->_redirectTo($productUrl);
    }

    /**
     * @param $product
     */
    protected function _crossSellRedirect($product)
    {
        $productUrl = $this->_getSuggestedProductUrl($product, 'cross-sell');
        if (!empty($productUrl)) $this->_redirectTo($productUrl);
    }

    /**
     * @param $product
     */
    protected function _parentCategoryRedirect($product)
    {
        $catId = $product->getCategoryIds();
        $productUrl = $this->_categoryBestSellingProducts($catId[0]);
        if (!empty($productUrl)) $this->_redirectTo($productUrl);
    }

}
