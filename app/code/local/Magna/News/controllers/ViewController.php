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
 
 /**
 * News View Controller
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_ViewController extends Mage_Core_Controller_Front_Action
{
	public function showAction()
	{
		/* load news */
		$news = $this->_loadItem( (int) $this->getRequest()->getParam('news_id') );
		
		if ( !$news ) {
			$this->_forward('noroute');
			return;
		}
		
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function listAction()
	{
		$this->loadLayout();
		
		$this->renderLayout();
	}
	
	/**
	 * Load news model with data by passed id.
	 * 
	 * @param int $itemId
	 * @return bool | Magna_News_Model_News
	 */
	protected function _loadItem( $itemId )
	{
		if ( !$itemId ) {
			return false;
		}
		
		$news = Mage::getModel( 'magna_news/news' )
				->setStoreId( Mage::app()->getStore()->getId() )
				->load($itemId);
				
		if ( !$news->getId() || $news->getClosed() ) {
			return false;
		}
		
		Mage::register( 'current_news' , $news );
		
		return $news;
	}
}
