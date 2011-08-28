<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Mysql4 Init
 *
 * @category    Magna
 * @package     Magna_News
 * @author      Svetli Nikolov - http://svetli.name
 */

/**
 * Mysql4 Insatller
 * 
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;
$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('magna_news')};
CREATE TABLE {$this->getTable('magna_news')} (
  `news_id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `content` text,
  `identifier` varchar(100) NOT NULL default '',
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_closed` datetime default NULL,
  `update_time` datetime default NULL,
  `active` tinyint(1) NOT NULL default '1',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,  
  PRIMARY KEY  (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('magna_news_store')};
CREATE TABLE {$this->getTable('magna_news_store')} (
  `news_id` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`news_id`,`store_id`),
  CONSTRAINT `FK_NEWS_STORE` FOREIGN KEY (`news_id`) REFERENCES {$this->getTable('magna_news')} (`news_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

"); // end installer->run

$installer->endSetup();
