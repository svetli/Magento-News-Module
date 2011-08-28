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
 * Wysiwyg config
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */
class Magna_News_Model_Wysiwyg_Config extends Mage_Cms_Model_Wysiwyg_Config
{
    /**
     * Return Wysiwyg config as Varien_Object
     *
     * Config options description:
     *
     * enabled:                 Enabled Visual Editor or not
     * hidden:                  Show Visual Editor on page load or not
     * use_container:           Wrap Editor contents into div or not
     * no_display:              Hide Editor container or not (related to use_container)
     * translator:              Helper to translate phrases in lib
     * files_browser_*:         Files Browser (media, images) settings
     * encode_directives:       Encode template directives with JS or not
     *
     * @param $data Varien_Object constructor params to override default config values
     * @return Varien_Object
     */
    public function getConfig($data = array())
    {
        $config = new Varien_Object();

        $config->setData(array(
            'enabled'                       => $this->isEnabled(),
            'hidden'                        => $this->isHidden(),
            'use_container'                 => true,
            'add_variables'                 => true,
            'add_widgets'                   => false,
            'no_display'                    => false,
            'translator'                    => Mage::helper('magna_news'),
            // fix url: */cms_wysiwyg_images/index to: adminhtml/cms_wysiwyg_images/index
            'files_browser_window_url'      => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
            'files_browser_window_width'    => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width'),
            'files_browser_window_height'   => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height'),
            'encode_directives'             => true,
            // fix url: */cms_wysiwyg/directive/index to: adminhtml/cms_wysiwyg/directive
            'directives_url'                => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
            'popup_css'                     => Mage::getBaseUrl('js').'mage/adminhtml/wysiwyg/tiny_mce/themes/advanced/skins/default/dialog.css',
            'content_css'                   => Mage::getBaseUrl('js').'mage/adminhtml/wysiwyg/tiny_mce/themes/advanced/skins/default/content.css',
            'width'                         => '100%',
            'plugins'                       => array()
        ));

        $config->setData('directives_url_quoted', preg_quote($config->getData('directives_url')));

        if ( is_array($data) )
        {
            $config->addData($data);
        }

        Mage::dispatchEvent('magna_news_wysiwyg_config_prepare', array('config' => $config));

        return $config;
    }    
}
