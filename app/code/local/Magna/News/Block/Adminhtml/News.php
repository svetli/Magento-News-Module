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
 
class Magna_News_Block_Adminhtml_News extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
		$this->_controller = 'adminhtml_news';
		$this->_blockGroup = 'magna_news';
		$this->_headerText = Mage::helper('magna_news')->__('News Manager');
		$this->_addButtonLabel = Mage::helper('magna_news')->__('Add New Item');
		parent::__construct();
    }
}
