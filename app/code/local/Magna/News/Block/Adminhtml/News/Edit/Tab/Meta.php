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
 * Adminhtml news edit form meta tab
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_Block_Adminhtml_News_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareForm()
    {
        /* Checking if user have permissions to save information */
        if ( $this->_isAllowedAction('save') )
        {
            $isElementDisabled = false;
        }
        else
        {
            $isElementDisabled = true;
        }

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('news_');
        $model = Mage::registry('news_item');

        $fieldset = $form->addFieldset('meta_fieldset', array('legend' => Mage::helper('magna_news')->__('Meta Data'), 'class' => 'fieldset-wide'));

        $fieldset->addField('meta_keywords', 'textarea', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('magna_news')->__('Keywords'),
            'title' => Mage::helper('magna_news')->__('Meta Keywords'),
            'disabled'  => $isElementDisabled
        ));

        $fieldset->addField('meta_description', 'textarea', array(
            'name' => 'meta_description',
            'label' => Mage::helper('magna_news')->__('Description'),
            'title' => Mage::helper('magna_news')->__('Meta Description'),
            'disabled'  => $isElementDisabled
        ));

        Mage::dispatchEvent('magna_news_adminhtml_news_edit_tab_meta_prepare_form', array('form' => $form));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
 

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('magna_news')->__('Meta Data');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('magna_news')->__('Meta Data');
    }

    /**
     * Returns status flag about this tab can be showen or not
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
