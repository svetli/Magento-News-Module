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

/**
 * News Mysql4 Collection Model
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_Model_Resource_News_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Init
     *
     */
    public function _construct()
    {
        $this->_init('magna_news/news');
    }
    
    /**
     * Get sql for get record count
     *
     * @return string
     */
    public function getSelectCountSql()
    {
        $select = parent::getSelectCountSql();

        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::GROUP);
        $select->reset(Zend_Db_Select::HAVING);
        $select->columns('COUNT(DISTINCT main_table.news_id)');
        return $select;
    }
    
    /**
     * Apply quote item(s) filter to collection
     *
     * @param  int|array $item
     * @return Magna_News_Model_Mysql4_News_Collection
     */
    public function addExcludeFilter($item)
    {        
        if ( empty($item) )
        {
            $this->_totalRecords = 0;
            $this->_setIsLoaded(true);
        }
        else if ( $item instanceof Magna_News_Model_News )
        {
          $this->getSelect()->where('main_table.news_id NOT IN(?)', $item->getId());
        }
        else
        {
            $this->getSelect()->where('main_table.news_id NOT IN(?)', $item);
        }
        
        return $this;
    }
    
    /**
     * Redefine default filters
     *
     * @param   string  $field
     * @param   mixed   $condition
     * @return  Varien_Data_Collection_Db
     */
    public function addFieldToFilter($field, $condition=null)
    {
        if ($field == 'stores')
        {
            return $this->addStoreFilter($condition);
        }
        else
        {
            return parent::addFieldToFilter($field, $condition);
        }
    }

    /**
     * Add filter by store
     *
     * @param array | int $storeId
     * @param bool $allFilter
     * @return Magna_News_Model_Resource_News_Collection
     */
    public function addStoreFilter($storeId, $allFilter = true)
    {        
        if ( !$this->getFlag('store_filter') )
        {
            $this->getSelect()->joinLeft(
                array('news_store' => $this->getTable('magna_news/magna_news_store')),
                'main_table.news_id = news_store.news_id'
            );
            
            $this->getSelect()->where('news_store.store_id IN (?)', $storeId);
            $this->getSelect()->group('main_table.news_id');
            
            if ($this->getFlag('relation') && $allFilter) {
                $this->getSelect()->where('relation.store_id IN (?)', $storeId);
            }
            if ($this->getFlag('prelation') && $allFilter) {
                $this->getSelect()->where('prelation.store_id IN (?)', $storeId);
            }
            
            $this->setFlag('store_filter', true);
        }
        
        return $this;
    }
    
    /**
     * Adds filtering by active
     *
     * @return Magna_News_Model_Resource_News_Collection
     */
    public function setActiveFilter()
    {
        $this->getSelect()->where('main_table.active = ?', Magna_News_Model_News::STATUS_ACTIVE);
        return $this;
    }    
}
