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

class Magna_News_Model_Mysql4_News_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
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
            return $this->addStoresFilter($condition);
        }
        else
        {
            return parent::addFieldToFilter($field, $condition);
        }
    }
    
    /**
     * Deprecated
     *
     * @param   int|array   $storeId
     * @return  Magna_News_Model_Mysql4_News_Collection
     */
    public function addStoresFilter($store)
    {
        return $this->addStoreFilter($store);
    }

    /**
     * Add Stores Filter
     *
     * @param   int|array $storeId
     * @return  Magna_News_Model_Mysql4_News_Collection
     */
    public function addStoreFilter($storeId, $withAdmin = true)
    {
        $this->getSelect()->join(
            array('store_table' => $this->getTable('magna_news/magna_news_store')),
            'main_table.news_id = store_table.news_id',
            array()
        )
        ->where('store_table.store_id in (?)', ($withAdmin ? array(0, $storeId) : $storeId))
        ->group('main_table.news_id');

        return $this;
    }
    
    /**
     * Add stores data
     *
     * @return Magna_News_Model_Mysql4_News_Collection
     */
    public function addStoreData()
    {
        $newsIds        = $this->getColumnValues('news_id');
        $storesToNews   = array();

        if (count($newsIds) > 0)
        {
            $select = $this->getConnection()->select()
                    ->from($this->getTable('magna_news/magna_news_store'))
                    ->where('news_id IN(?)', $newsIds);
            $result = $this->getConnection()->fetchAll($select);

            foreach ($result as $row)
            {
                if (!isset($storesToNews[$row['news_id']]))
                {
                    $storesToNews[$row['news_id']] = array();
                }
                $storesToNews[$row['news_id']][] = $row['store_id'];
            }
        }

        foreach ($this as $item)
        {
            if(isset($storesToNews[$item->getId()]))
            {
                $item->setStores($storesToNews[$item->getId()]);
            }
            else
            {
                $item->setStores(array());
            }
        }

        return $this;
    }

    public function addSelectStores()
    {
        $newsId = $this->getId();
        $select = $this->getConnection()->select()
                ->from($this->getTable('magna_news/magna_news_store'))
                ->where('news_id = ?', $newsId);
        
        $result = $this->getConnection()->fetchAll($select);
        $stores = array();
        
        foreach ($result as $row)
        {
            $stores[] = $row['store_id'];
        }
        
        $this->setSelectStores($stores);

        return $this;
    }    
}
