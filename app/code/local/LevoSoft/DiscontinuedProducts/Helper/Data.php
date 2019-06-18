<?php
/**
 * @category   Discontinued Products
 * @package    LevoSoft_DiscontinuedProducts
 * @author     Hassan Ali Shahzad <levosoft786@gmail.com>
 *
 */
class LevoSoft_DiscontinuedProducts_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function categoryBestSellingProducts($catId,$noOfProucts){

        // get most viewed products for current category
        $products = Mage::getResourceModel('reports/product_collection')
            ->addAttributeToSelect('*')
            ->addOrderedQty()
            ->setStoreId(Mage::app()->getStore()->getId())
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addCategoryFilter(Mage::getModel('catalog/category')->setStoreId(Mage::app()->getStore()->getId())->load($catId))
            ->addAttributeToFilter('status',array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
            ->setOrder('ordered_qty', 'desc')
            ->setPageSize($noOfProucts);

        Mage::getSingleton('catalog/product_status')
            ->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')
            ->addVisibleInCatalogFilterToCollection($products);
        Mage::getSingleton('cataloginventory/stock')
            ->addInStockFilterToCollection($products);

        return $products;
    }
}
	 