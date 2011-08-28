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
 * Adminhtml news edit form content tab
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_Block_Adminhtml_News_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        if ( Mage::getSingleton('magna_news/wysiwyg_config')->isEnabled() )
        {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
    
    protected function _prepareForm()
    {
        /** @var $model Magna_News_Model_News */
        $model = Mage::registry('news_item');

        /*
         * Checking if user have permissions to save information
         */
        if ( $this->_isAllowedAction('save') )
        {
            $isElementDisabled = false;
        }
        else
        {
            $isElementDisabled = true;
        }

        /* init form */
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('news_');

        $fieldset = $form->addFieldset('content_fieldset', array('legend'=>Mage::helper('magna_news')->__('Content'),'class'=>'fieldset-wide'));

        $wysiwygConfig = Mage::getSingleton('magna_news/wysiwyg_config')->getConfig( array('tab_id' => $this->getTabId()) );

        $contentField = $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'style'     => 'height:36em;',
            'required'  => true,
            'disabled'  => $isElementDisabled,
            'config'    => $wysiwygConfig
        ));

        // Setting custom renderer for content field to remove label column
        $renderer = $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset_element')
                    ->setTemplate('magna_news/edit/form/renderer/content.phtml');
        $contentField->setRenderer($renderer);

        $form->setValues($model->getData());
        $this->setForm($form);

        Mage::dispatchEvent('magna_news/adminhtml_news_edit_tab_content_prepare_form', array('form' => $form));

        return parent::_prepareForm();
    }    
    
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('magna_news')->__('Content');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('magna_news')->__('Content');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('magna_news/' . $action);
    }    
}
