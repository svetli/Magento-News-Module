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
 * Adminhtml News Block Grid
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

class Magna_News_Block_Adminhtml_News_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('newsGrid');
        $this->setDefaultSort('news_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * Prepare grid columns
     *
     * @return  Mage_Adminhtml_Block_Widget_Grid
     * @access  protected
     */
    protected function _prepareColumns()
    {
        $this->addColumn('news_id', array(
            'header'    => Mage::helper('magna_news')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'news_id'
        ));
                                            
        $this->addColumn('identifier', array(
            'header'    => Mage::helper('magna_news')->__('Identifier'),
            'align'     => 'left',
            'index'     => 'identifier'
        ));
        
        $this->addColumn('title', array(
            'header'    => Mage::helper('magna_news')->__('Title'),
            'align'     => 'left',
            'index'     => 'title'
        ));
        
        $this->addColumn('content', array(
            'header'    => Mage::helper('magna_news')->__('Content'),
            'align'     => 'left',
            'index'     => 'content',
            'renderer'  => 'adminhtml/widget_grid_column_renderer_longtext',
            'string_limit' => '255'
        ));
        
        $this->addColumn('date_posted', array(
            'header'    => Mage::helper('magna_news')->__('Date Posted'),
            'align'     => 'left',
            'width'     => '100px',
            'type'      => 'date',
            'index'     => 'date_posted'
        ));

        $this->addColumn('date_closed', array(
            'header'    => Mage::helper('magna_news')->__('Date Closed'),
            'align'     => 'left',
            'width'     => '100px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'date_closed'
        ));
        
        if ( !Mage::app()->isSingleStoreMode() )
        {
            $this->addColumn('visible_in', array(
                'header'    => Mage::helper('magna_news')->__('Visible In'),
                'index'     => 'stores',
                'type'      => 'store',
                'store_view'=> true,
                'sortable'  => false,
            ));
        }
        
        $this->addColumn('active', array(
            'header'    => Mage::helper('magna_news')->__('Active'),
            'align'     => 'center',
            'width'     => '80px',
            'index'     => 'active',
            'type'      => 'options',
            'options'   => array(
                1 => Mage::helper('magna_news')->__('Enabled'),
                0 => Mage::helper('magna_news')->__('Disabled')
            )
        ));        
        
        return parent::_prepareColumns();
    }
    
    /**
     * Prepare News Collection
     *
     * @return Magna_News_Block_Adminhtml_News_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('magna_news/news')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
        
        if ( !Mage::app()->isSingleStoreMode() )
        {
            $this->getCollection()->addStoreData();
        }
        
        return $this;
    }
    
    /**
     * Row Url
     *
     * @access  public
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('news_id' => $row->getId()));
    }    
}
