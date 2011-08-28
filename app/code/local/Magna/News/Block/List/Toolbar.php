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
 * @category    Magna
 * @package     Magna_News
 * @copyright   Copyright (c) 2011 Magna Inc. (http://www.magna-studio.eu)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
 
class Magna_News_Block_List_Toolbar extends Mage_Core_Block_Template
{
	const XML_PATH_SORT_FIELD = 'magna_news/frontend/default_sort_field';
	
    /**
     * Products collection
     *
     * @var Mage_Core_Model_Mysql4_Collection_Abstract
     */
    protected $_collection = null;

    /**
     * GET parameter page variable
     *
     * @var string
     */
    protected $_pageVarName     = 'p';
    
    /**
     * GET parameter limit variable
     *
     * @var string
     */
    protected $_limitVarName        = 'limit';
    
    /**
     * GET parameter direction variable
     *
     * @var string
     */
    protected $_directionVarName    = 'dir';
    
    /**
     * Default Order field
     *
     * @var string
     */
    protected $_orderField          = null;
    
    /**
     * Default direction
     *
     * @var string
     */
    protected $_direction           = 'desc';
    
    /**
     * Available page limits for different list modes
     *
     * @var array
     */
    protected $_availableLimit      = null;
    
    /**
     * Default pager block name
     *
     * @var string
     */
    protected $_defaultPagerBlock   = 'page/html_pager';
    
    /**
     * Init Toolbar
     *
     */
    protected function _construct()
    {
		parent::_construct();
        $this->_orderField  = Mage::getStoreConfig(self::XML_PATH_SORT_FIELD);
		$this->setTemplate('magna_news/list/toolbar.phtml');
	}
	
    /**
     * Set collection to pager
     *
     * @param Varien_Data_Collection $collection
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
        $this->_collection->setCurPage($this->getCurrentPage());

        // we need to set pagination only if passed value integer and more that 0
        $limit = (int)$this->getLimit();
        
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }
        if ($this->getCurrentOrder()) {
            $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
        }
        
        return $this;
    }
    
    /**
     * Return products collection instance
     *
     * @return Magna_News_Model_Resource_News_Collection
     */
    public function getCollection()
    {
        return $this->_collection;
    }
    
    /**
     * Return current page from request
     *
     * @return int
     */
    public function getCurrentPage()
    {
        if ($page = (int) $this->getRequest()->getParam($this->getPageVarName())) {
            return $page;
        }
        return 1;
    }
    
    /**
     * Getter for $_limitVarName
     *
     * @return string
     */
    public function getLimitVarName()
    {
        return $this->_limitVarName;
    }
    
    /**
     * Retrieve sort direction GET var name
     *
     * @return string
     */
    public function getDirectionVarName()
    {
        return $this->_directionVarName;
    }
    
    /**
     * Retrieve Limit Pager URL
     *
     * @param int $limit
     * @return string
     */
    public function getLimitUrl($limit)
    {
        return $this->getPagerUrl(array(
            $this->getLimitVarName() => $limit,
            $this->getPageVarName() => null
        ));
    }
    
    /**
     * Retrieve Pager URL
     *
     * @param string $order
     * @param string $direction
     * @return string
     */
    public function getOrderUrl($order, $direction)
    {
        if (is_null($order)) {
            $order = $this->getCurrentOrder() ? $this->getCurrentOrder() : $this->_availableOrder[0];
        }
        return $this->getPagerUrl(array(
            $this->getOrderVarName()=>$order,
            $this->getDirectionVarName()=>$direction,
            $this->getPageVarName() => null
        ));
    }
    
    /**
     * Getter for $_pageVarName
     *
     * @return string
     */
    public function getPageVarName()
    {
        return $this->_pageVarName;
    }
    
    /**
     * Get grit products sort order field
     *
     * @return string
     */
    public function getCurrentOrder()
    {
        return $this->_orderField;
    }
    
    public function isLimitCurrent($limit)
    {
        return $limit == $this->getLimit();
    }
    
    public function getFirstNum()
    {
        $collection = $this->getCollection();
        return $collection->getPageSize()*($collection->getCurPage()-1)+1;
    }

    public function getLastNum()
    {
        $collection = $this->getCollection();
        return $collection->getPageSize()*($collection->getCurPage()-1)+$collection->count();
    }

    public function getTotalNum()
    {
        return $this->getCollection()->getSize();
    }

    public function isFirstPage()
    {
        return $this->getCollection()->getCurPage() == 1;
    }

    public function getLastPageNum()
    {
        return $this->getCollection()->getLastPageNumber();
    }
    
    /**
     * Return current URL with rewrites and additional parameters
     *
     * @param array $params Query parameters
     * @return string
     */
    public function getPagerUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        return $this->getUrl('*/*/*', $urlParams);
    }
    
    /**
     * Retrieve current direction
     *
     * @return string
     */
    public function getCurrentDirection()
    {
        $dir = $this->_getData('_current_grid_direction');
        if ($dir) {
            return $dir;
        }

        $directions = array('asc', 'desc');
        $dir = strtolower($this->getRequest()->getParam($this->getDirectionVarName()));
        // validate direction
        if (!$dir || !in_array($dir, $directions)) {
            $dir = $this->_direction;
        }
        $this->setData('_current_grid_direction', $dir);
        return $dir;
    }
    
    /**
     * Get specified products limit display per page
     *
     * @return string
     */
    public function getLimit()
    {
        $limit = $this->_getData('_current_limit');
        if ($limit) {
            return $limit;
        }

        $limits = $this->getAvailableLimit();
        $defaultLimit = $this->getDefaultPerPageValue();
        if (!$defaultLimit || !isset($limits[$defaultLimit])) {
            $keys = array_keys($limits);
            $defaultLimit = $keys[0];
        }

        $limit = $this->getRequest()->getParam($this->getLimitVarName());
        if (!$limit || !isset($limits[$limit])) {
            $limit = $defaultLimit;
        }

        $this->setData('_current_limit', $limit);
        return $limit;
    }
    
    /**
     * Retrieve available limits for specified view mode
     *
     * @return array
     */
    public function getAvailableLimit()
    {
        if (isset($this->_availableLimit)) {
            return $this->_availableLimit;
        }
        
        $perPageConfigKey = 'magna_news/frontend/per_page_values';
        $perPageValues = (string)Mage::getStoreConfig($perPageConfigKey);
        $perPageValues = explode(',', $perPageValues);
        $perPageValues = array_combine($perPageValues, $perPageValues);
        
        return $perPageValues;
    }
    
    /**
     * Render pagination HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        $pagerBlock = $this->getPagerBlock( 'news_list_toolbar_pager' );
      
        if ($pagerBlock instanceof Varien_Object) {

            /* @var $pagerBlock Mage_Page_Block_Html_Pager */
            $pagerBlock->setAvailableLimit($this->getAvailableLimit());

            $pagerBlock->setUseContainer(true)
                ->setShowPerPage(false)
                ->setShowAmounts(false)
                ->setLimitVarName($this->getLimitVarName())
                ->setPageVarName($this->getPageVarName())
                ->setLimit($this->getLimit())
                ->setCollection($this->getCollection());

            return $pagerBlock->toHtml();
        }

        return '';
    }
    
    public function getPagerBlock()
    {
        if ( $pagerBlock = $this->getChild('news_list_toolbar_pager') ) {
            return $pagerBlock;
        }
        
        return $this->getLayout()->createBlock($this->_defaultPagerBlock, 'news_list_toolbar_pager');
    }
}
