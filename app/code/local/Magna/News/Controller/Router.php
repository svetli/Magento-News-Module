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
 * News Controller Router
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    /**
     * Initialize Controller Router
     *
     * @param Varien_Event_Observer $observer
     */
    public function initControllerRouters($observer)
    {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getEvent()->getFront();
        $front->addRouter('magna_news', $this);
    }
    
    /**
     * Validate and Match News and modify request
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        
        $condition = new Varien_Object(array(
            'identifier' => $identifier,
            'continue'   => true
        ));
        
        Mage::dispatchEvent('magna_news_controller_router_match_before', array(
            'router'    => $this,
            'condition' => $condition
        ));
        
        $identifier = $condition->getIdentifier();
       
        if ( $condition->getRedirectUrl() )
        {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($condition->getRedirectUrl())
                ->sendResponse();
                
            $request->setDispatched(true);
            return true;
        }

        if ( !$condition->getContinue() )
        {
            return false;
        }
        
        $news   = Mage::getModel('magna_news/news');
        $newsId = $news->checkIdentifier($identifier, Mage::app()->getStore()->getId());
     
        if ( !$newsId )
        {
            return false;
        }
        
        $request->setModuleName('magna_news')
            ->setControllerName('view')
            ->setActionName('show')
            ->setParam('news_id', $newsId);
            
        $request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $identifier
        );

        return true;
    }
}
