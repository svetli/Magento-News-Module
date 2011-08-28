<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magna
 * @package     Magna_News
 * @copyright   Copyright (c) 2011 Magna Inc. (http://www.magna-studio.eu)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
 
class Magna_News_Block_List extends Mage_Core_Block_Template
{
    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = 'magna_news/list_toolbar';
	
	const DEFAULT_ITEMS_COUNT = 5;
	
	/**
	 * News Collection
	 * 
	 * @var Magna_News_Model_Mysql4_News_Collection
	 */
	private $_newsCollection;
	private $_itemsCount;
	
	public function getLoadedNewsCollection()
	{
		return $this->_getNewsCollection();
	}
	
	protected function _beforeToHtml()
	{
	    /* init toolbar */
	    $toolbar = $this->getToolbarBlock();
		
	    /* init collection */
	    $collection = $this->_getNewsCollection();
	    
	    // set collection to toolbar and apply sort
	    if ( $toolbar )
	    {
			$toolbar->setCollection($collection);
			$this->setChild('toolbar', $toolbar);
		}
	    
	    $collection->load();
	    return parent::_beforeToHtml();
	}
	
    /**
     * Retrieve loaded news collection
     *
     * @return Magna_News_Model_Mysql4_News_Collection
     */
	protected function _getNewsCollection()
	{	
		if ( is_null( $this->_newsCollection ) )
		{
			$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
			
			$collection = Mage::getResourceModel('magna_news/news_collection')
			    ->addStoreFilter( Mage::app()->getStore()->getId() )
			    ->setActiveFilter()
			    ->addFieldToFilter('date_posted', array('or'=> array(
				0 => array('date' => true, 'to' => $todayDate),
				1 => array('is' => new Zend_Db_Expr('null')))
			    ), 'left')
			    ->addFieldToFilter('date_closed', array('or'=> array(
				0 => array('date' => true, 'from' => $todayDate),
				1 => array('is' => new Zend_Db_Expr('null')))
			    ), 'left');
			    
			if ( Mage::registry( 'current_news' ) )
			{
			    $collection->addExcludeFilter( Mage::registry('current_news') );
			}
			
			if ( $this->getItemsCount() ) {
				$collection->setPageSize($this->getItemsCount())->setCurPage(1);
			}
			
			$this->_newsCollection = $collection;
		}
		
		return $this->_newsCollection;
	}
	
	public function stripContent($content)
	{
		return Mage::helper('core/string')->truncate( $content, 255);
	}
	
	public function getReadMoreUrl(Magna_News_Model_News $item)
	{
		return Mage::getUrl($item->getIdentifier());
	}
	
    /**
     * Set how much items should be displayed at once.
     *
     * @param $count
     * @return Magna_News_Block_List
     */
	public function setItemsCount($count)
	{
		$this->_itemsCount = $count;
		return $this;
	}
	
    /**
     * Get how much items should be displayed at once.
     *
     * @return int
     */
    public function getItemsCount()
    {
        if ( $this->_itemsCount != null )
        {
            return $this->_itemsCount;
        }
        
        return self::DEFAULT_ITEMS_COUNT;
    }
    
    /**
     * Retrieve Toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function getToolbarBlock()
    {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        return null;
    }
    
    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }
	
    /**
     * Format date in long format
     *
     * @param string $date
     * @return string
     */
    public function dateFormat($date)
    {
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_LONG);
    }
}
