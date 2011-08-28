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
 * News Mysql4 Model
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_Model_Resource_News extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Store model
     *
     * @var     null | Mage_Core_Model_Store
     */
    protected $_store = null;    
    
    /**
     * Init
     *
     */
    protected function _construct()
    {
        $this->_init('magna_news/magna_news', 'news_id');
    }
    
    /**
     * Process page data before saving
     *
     * @param   Mage_Core_Model_Abstract $object
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        foreach ( array('date_closed', 'update_time') as $dataKey )
        {
            if ( !$object->getData($dataKey) )
            {
                $object->setData($dataKey, new Zend_Db_Expr('NULL'));
            }
        }
        
        $todayDate = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        
        // if is new item creation time is now
        if ( !$object->getDatePosted() )
        {
            $object->setDatePosted( $todayDate );
        }
        
        // update time is now
        $object->setUpdateTime( $todayDate );
        
        return $this;
    }
   
    public function load(Mage_Core_Model_Abstract $object, $value, $field=null)
    {
        if ( !is_numeric($value) )
        {
            $field = 'identifier';
        }
        
        return parent::load($object, $value, $field);
    }    
    
    /**
     * Assign page to store views
     *
     * @param   Mage_Core_Model_Abstract $object
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('news_id = ?', $object->getId());
        
        $this->_getWriteAdapter()->delete($this->getTable('magna_news/magna_news_store'), $condition);

        foreach ( (array)$object->getData('stores') as $store )
        {
            $storeArray = array();
            $storeArray['news_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($this->getTable('magna_news/magna_news_store'), $storeArray);
        }

        return parent::_afterSave($object);
    }
    
    /**
     * Assign stores
     * 
     * @param   Mage_Core_Model_Abstract $object
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('magna_news/magna_news_store'))
            ->where('news_id = ?', $object->getId());

        if ( $data = $this->_getReadAdapter()->fetchAll($select) )
        {
            $storesArray = array();
            
            foreach ( $data as $row )
            {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }

        return parent::_afterLoad($object);
    }    
    
    /**
     * Check if news identifier exist for specific store
     * return news id if news exists
     *
     * @param   string $identifier
     * @param   int $storeId
     * @return  int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        $select = $this->_getReadAdapter()->select()->from(array('main_table'=>$this->getMainTable()), 'news_id')
            ->join(array('cps' => $this->getTable('magna_news/magna_news_store')), 'main_table.news_id = `cps`.news_id')
            ->where('main_table.identifier=?', $identifier)
            ->where('main_table.active=1 AND `cps`.store_id IN (' . Mage_Core_Model_App::ADMIN_STORE_ID . ', ?) ', $storeId)
            ->order('store_id DESC');

        return $this->_getReadAdapter()->fetchOne($select);
    }    
    
    /**
     * Set store model
     *
     * @param   Mage_Core_Model_Store $store
     * @return  Mage_News_Model_Mysql4_News
     */
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Retrieve store model
     *
     * @return  Mage_Core_Model_Store
     */
    public function getStore()
    {
        return Mage::app()->getStore($this->_store);
    }    
}
