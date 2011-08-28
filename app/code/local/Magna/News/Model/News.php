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
 * News Model
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_Model_News extends Mage_Core_Model_Abstract
{
    const STATUS_ACTIVE     = 1;
    const STATUS_NOT_ACTIVE = 0;
    
    /**
     * Init
     *
     */
    protected function _construct()
    {
        $this->_init('magna_news/news');
    }
    
    /**
     * Load object data
     *
     * @param   mixed $id
     * @param   string $field
     * @return  Magna_News_Model_News
     */
    public function load($id, $field=null)
    {        
        return parent::load($id, $field);
    }
    
    /**
     * Url to read article
     *
     * @return string
     */
    public function getUrl()
    {
        return Mage::getUrl('magna_news', array('news_id' => $this->getId(), '_secure' => true));
    }
    
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }
}
