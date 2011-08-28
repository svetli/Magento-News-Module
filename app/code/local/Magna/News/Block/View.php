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

class Magna_News_Block_View extends Mage_Core_Block_Template
{
    public function __construct()
    {
	    parent::__construct();
	    $this->setTemplate('magna_news/view.phtml');
    }
	
    /**
     * Add meta information from news to head block
     *
     * @return Magna_News_Block_View
     */
    protected function _prepareLayout()
    {
		$headBlock = $this->getLayout()->getBlock('head');
		
		if ($headBlock)
		{
			/* set up title */
			$headBlock->setTitle( $this->getNewsData()->getTitle() );
			
			/* set up keywords */
			$keywords = $this->getNewsData()->getMetaKeywords();
			
			if ( $keywords ) {
				$headBlock->setKeywords($keywords);
			} else {
				$headBlock->setKeywords( $this->getNewsData()->getTitle() );
			}
			
			/* set up description */
			$description = $this->getNewsData()->getMetaDescription();
			
			if ( $description ) {
				$headBlock->setDescription($description);
			} else {
				$headBlock->setDescription( Mage::helper('core/string')->substr( $this->getNewsData()->getContent(), 0, 255));
			}
		}
	}
	
	/**
	 * Retrieve current news model from registry
	 * 
	 * @return Magna_News_Model_News
	 */
	public function getNewsData()
	{
		return Mage::registry('current_news');
	}
	
    /**
     * Prepare link to news list for current item
     *
     * @return string
     */
    public function getBackUrl()
    {
        return Mage::getUrl('*/*/list', array());
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
