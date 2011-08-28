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
 * Adminhtml news edit form main tab
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_Block_Adminhtml_News_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        /* @var $model Magna_News_Model_News */
        $model = Mage::registry('news_item');        
        
        /* Checking if user have permissions to save information */
        if ( $this->_isAllowedAction('save') )
        {
            $isElementDisabled = false;
        }
        else
        {
            $isElementDisabled = true;
        }
        
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat( Mage_Core_Model_Locale::FORMAT_TYPE_SHORT );        
        
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('news_');
        
        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('magna_news')->__('Item Information')));
        
        if ( $model->getNewsId() )
        {
            $fieldset->addField('news_id', 'hidden', array(
                'name' => 'news_id',
            ));
        }        
        
        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('magna_news')->__('Item Title'),
            'title'     => Mage::helper('magna_news')->__('Item Title'),
            'required'  => true,
            'disabled'  => $isElementDisabled
        ));
        
        $fieldset->addField('identifier', 'text', array(
            'name'      => 'identifier',
            'label'     => Mage::helper('magna_news')->__('URL Key'),
            'title'     => Mage::helper('magna_news')->__('URL Key'),
            'required'  => true,
            'class'     => 'validate-identifier',
            'note'      => Mage::helper('magna_news')->__('Relative to Website Base URL'),
            'disabled'  => $isElementDisabled
        ));
        
        $fieldset->addField('date_posted', 'date', array(
            'name'      => 'date_posted',
            'label'     => Mage::helper('magna_news')->__('Date Posted'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => $dateFormatIso,
            'disabled'  => $isElementDisabled
        ));

        $fieldset->addField('date_closed', 'date', array(
            'name'      => 'date_closed',
            'label'     => Mage::helper('magna_news')->__('Date Closed'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => $dateFormatIso,
            'disabled'  => $isElementDisabled
        )); 
        
        $fieldset->addField('active', 'select', array(
            'name'      => 'active',
            'label'     => Mage::helper('magna_news')->__('Status'),
            'title'     => Mage::helper('magna_news')->__('Status'),
            'required'  => true,
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('magna_news')->__('Enabled'),
                ),
                array(
                    'value'     => 0,
                    'label'     => Mage::helper('magna_news')->__('Disabled'),
                ),
            )
        ));       
        
        /**
         * Check is single store mode
         */
        if ( !Mage::app()->isSingleStoreMode() )
        {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('magna_news')->__('Store View'),
                'title'     => Mage::helper('magna_news')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled'  => $isElementDisabled
            ));
        }
        else
        {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }        
        
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
        return Mage::helper('magna_news')->__('Item Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('magna_news')->__('Item Information');
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
