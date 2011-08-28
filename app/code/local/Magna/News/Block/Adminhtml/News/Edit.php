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
 * Adminhtml news edit block
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_Block_Adminhtml_News_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'news_id';
        $this->_controller = 'adminhtml_news';
        $this->_blockGroup = 'magna_news';
        
        parent::__construct();        
        
        /* check for save permission */
        if ( $this->_isAllowedAction('save') )
        {
            $this->_updateButton('save', 'label', Mage::helper('magna_news')->__('Save Item'));
            $this->_addButton('saveandcontinue', array(
                'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save',
            ), -100);
        }
        else
        {
            $this->_removeButton('save');
        }
        
        /* check for delete permission */
        if ( $this->_isAllowedAction('delete') )
        {
            $this->_updateButton('delete', 'label', Mage::helper('magna_news')->__('Delete Item'));
        }
        else
        {
            $this->_removeButton('delete');
        }

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    
    public function getHeaderText()
    {
        if( Mage::registry('news_data') && Mage::registry('news_data')->getId() )
        {
            return Mage::helper('magna_news')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('news_data')->getTitle()));
        }
        else
        {
            return Mage::helper('magna_news')->__('New Item');
        }
    }
    
    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('magna_news/edit/' . $action);
    }    
}
