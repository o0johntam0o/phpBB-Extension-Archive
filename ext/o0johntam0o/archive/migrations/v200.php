<?php
/**
*
* Archive extension for the phpBB Forum Software package
*
* @copyright (c) 2014 o0johntam0o
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace o0johntam0o\archive\migrations;

class v200 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['archive_version']) && version_compare($this->config['archive_version'], '2.0.0', '>=');
	}
	
	public function update_data()
	{
		return array(
			array('config.add', array('archive_version', '2.0.0')),
			
			array('config.add', array('archive_enable', 1)),
			array('config.add', array('archive_topics_per_page', 15)),
			array('config.add', array('archive_posts_per_page', 10)),
			array('config.add', array('archive_hide_mod', 1)),
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ARCHIVE_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ARCHIVE_TITLE',
				array(
					'module_basename'   => '\o0johntam0o\archive\acp\main_module',
					'modes'             => array('archive_config'),
				),
			)),
		);
	}
}
