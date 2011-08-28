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
 
 class Magna_News_Adminhtml_NewsController extends Mage_Adminhtml_Controller_Action
 {
     /**
      *  Default action
      * 
      */
    public function indexAction()
    {
	$this->_title( $this->__('Magna') )
	     ->_title( $this->__('Manage Items') );
	
	$this->_initAction();
	$this->renderLayout();
    } 

    /**
     *	Add new item
     * 
     */
    public function newAction()
    {
	$this->_forward('edit');
    }
    
    /**
     * Edit item
     * 
     */
    public function editAction()
    {
	// 1. Get ID and create model
	$id = (int) $this->getRequest()->getParam('news_id');
	$model = Mage::getModel('magna_news/news');

	// 2. Initial checking
	if ( $id )
	{
	    $model->load($id);
	
	    if ( !$model->getId() )
	    {
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('news')->__('This news item no longer exists.'));
		$this->_redirect('*/*/');
		return;
	    }
	}

	$this->_title( $model->getId() ? $model->getTitle() : $this->__('New Item') );

	// 3. Set entered data if was error when we do save
	$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
	if ( !empty($data) )
	{
	    $model->setData($data);
	}

	// 4. Register model to use later in blocks
	Mage::register('news_item', $model);

	// 5. Build edit form
	$this->_initAction()
	     ->_addBreadcrumb( $id ? Mage::helper('magna_news')->__('Edit Item') : Mage::helper('magna_news')->__('New Item'), $id ? Mage::helper('magna_news')->__('Edit Item') : Mage::helper('magna_news')->__('New Item'));

	$this->renderLayout();
    }
	 
    /**
     * Save Item
     *
     */
    public function saveAction()
    {
	// 1. Check if data sent
	if ( $data = $this->getRequest()->getPost() )
	{
	    $data = $this->_filterPostData($data);

	    // 2. Create model
	    $model = Mage::getModel('magna_news/news');

	    if ( $id = $this->getRequest()->getParam('news_id') )
	    {
		$model->load($id);
	    }

	    $model->setData($data);

	    // 3. Try to save data
	    try {

		$model->save();

		// 4. Display success message
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magna_news')->__('The item has been saved.'));

		// 5. Clear previously saved data from session
		Mage::getSingleton('adminhtml/session')->setFormData(false);

		// 6. Check if 'Save and Continue'
		if ( $this->getRequest()->getParam('back') )
		{
		    $this->_redirect( '*/*/edit', array( 'news_id' => $model->getId() ) );
		    return;
		}

		// 7. Go to grid
		$this->_redirect('*/*/');
		return;               

	    } 
	    catch ( Mage_Core_Exception $e )
	    {
		$this->_getSession()->addError( $e->getMessage() );
	    } 
	    catch ( Exception $e )
	    {
		$this->_getSession()->addException( $e, Mage::helper('magna_news')->__('An error occurred while saving the item.') );
	    }
	}
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        if ( $id = $this->getRequest()->getParam('news_id') )
	{
            try {
                // init model and delete
                $model = Mage::getModel('magna_news/news');
                $model->load($id);
                $model->delete();
		
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magna_news')->__('The item has been deleted.'));
                
		// go to grid
                $this->_redirect('*/*/');
                return;

            }
	    catch (Exception $e)
	    {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError( $e->getMessage() );
		
                // go back to edit form
                $this->_redirect( '*/*/edit', array('news_id' => $id) );
                return;
            }
        }
	
        // display error message
        Mage::getSingleton('adminhtml/session')->addError( Mage::helper('magna_news')->__('Unable to find a item to delete.'));
        
	// go to grid
        $this->_redirect('*/*/');
    }

     private function _initAction()
     {
	$this->loadLayout()->_setActiveMenu('magna_news')
	    ->_addBreadcrumb( Mage::helper('magna_news')->__('Magna'), Mage::helper('magna_news')->__('Magna') )
	    ->_addBreadcrumb( Mage::helper('magna_news')->__('News'), Mage::helper('magna_news')->__('News') );
	
	return $this;
     }
	 
    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param   array
     * @return  array
     */
    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('date_posted', 'date_closed'));
        return $data;
    } 
	 
    /**
     * Check permissions
     *
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('magna/news');
    } 
 }
