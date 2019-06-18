<?php
/**
 * @category   Discontinued Products
 * @package    LevoSoft_DiscontinuedProducts
 * @author     Hassan Ali Shahzad <levosoft786@gmail.com>
 *
 */

$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

// ad new group Discontinued
$setup->addAttributeGroup('catalog_product', 'Default', 'Discontinued', 1000);


$setup->addAttribute("catalog_product", "levo_discontinued", array(
    'group' => 'Discontinued',
    "type" => "int",
    "backend" => "",
    "frontend" => "",
    "label" => "Discontinued",
    "input" => "select",
    "class" => "",
    "source" => "eav/entity_attribute_source_boolean",
    "global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    "visible" => true,
    "required" => false,
    "user_defined" => true,
    "default" => '',
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
    "visible_on_front" => false,
    "unique" => false,
    "note" => "",
    'sort_order' => 1

));

$setup->addAttribute("catalog_product", "levo_fix_redirect", array(
    'group' => 'Discontinued',
    "type" => "varchar",
    "backend" => "",
    "frontend" => "",
    "label" => "Fix Redirect By",
    "input" => "select",
    "class" => "",
    "source" => "discontinuedproducts/source_fixredirect",
    "global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    "visible" => true,
    "required" => false,
    "user_defined" => true,
    "default" => '',
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
    "visible_on_front" => false,
    "unique" => false,
    "note" => "If you want to redirect on particular product, select above the source like Id or Sku or Url of the product and enter corresponding value below in your store",
    'sort_order' => 2
));

$setup->addAttribute("catalog_product", "levo_fix_redirect_value", array(
    'group' => 'Discontinued',
    "type" => "varchar",
    "backend" => "",
    "frontend" => "",
    "label" => "Value",
    "input" => "text",
    "class" => "",
    "source" => "",
    "global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    "visible" => true,
    "required" => false,
    "user_defined" => true,
    "default" => "",
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
    "visible_on_front" => false,
    "unique" => false,
    "note" => "",
    'sort_order' => 3
));

$setup->addAttribute("catalog_product", "levo_fix_parent_category", array(
    'group' => 'Discontinued',
    "type" => "int",
    "backend" => "",
    "frontend" => "",
    "label" => "Category Page Parent",
    "input" => "select",
    "class" => "",
    "source" => "eav/entity_attribute_source_boolean",
    "global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    "visible" => true,
    "required" => false,
    "user_defined" => true,
    "default" => '',
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
    "visible_on_front" => false,
    "unique" => false,
    "note" => "Redirect on discontinued product's parent category page.",
    'sort_order' => 4

));

$setup->addAttribute("catalog_product", "levo_fix_category", array(
    'group' => 'Discontinued',
    "type" => "varchar",
    "backend" => "",
    "frontend" => "",
    "label" => "Category Page",
    "input" => "select",
    "class" => "",
    "source" => "discontinuedproducts/source_categoryoptions",
    "global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    "visible" => true,
    "required" => false,
    "user_defined" => true,
    "default" => "",
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
    "visible_on_front" => false,
    "unique" => false,
    "note" => "Redirect to category page of above selected category.",
    'sort_order' => 5
));

$setup->addAttribute("catalog_product", "levo_fix_category_products", array(
    'group' => 'Discontinued',
    "type" => "varchar",
    "backend" => "",
    "frontend" => "",
    "label" => "Product Page From Category",
    "input" => "select",
    "class" => "",
    "source" => "discontinuedproducts/source_categoryoptions",
    "global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    "visible" => true,
    "required" => false,
    "user_defined" => true,
    "default" => "",
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
    "visible_on_front" => false,
    "unique" => false,
    "note" => "Redirect to top selling product of above selected category.",
    'sort_order' => 6
));

$setup->addAttribute("catalog_product", "levo_default", array(
    'group' => 'Discontinued',
    "type" => "text",
    "backend" => "eav/entity_attribute_backend_array",
    "frontend" => "",
    "label" => "Product Page Default Fall",
    "input" => "multiselect",
    "class" => "",
    "source" => "discontinuedproducts/source_fixdefault",
    "global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    "visible" => true,
    "required" => false,
    "user_defined" => true,
    "default" => "",
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
    "visible_on_front" => false,
    "unique" => false,
    "note" => "If Not Selected any It will search in related products if not found then in Up Sell products if not found then in cross-sell if not fond then from Parent category",
    'sort_order' => 7
));

$attr = $setup->getAttribute('catalog_product', 'levo_default');
$setup->updateAttribute('catalog_product', $attr['attribute_id'], 'default_value', 'related,up-sell,cross-sell,parent-category');
$installer->endSetup();

