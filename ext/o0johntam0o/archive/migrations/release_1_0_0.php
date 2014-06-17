<?php

/**
*
* @package phpBB Extension - Archive
* @copyright (c) 2014 o0johntam0o
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace o0johntam0o\archive\migrations;

class release_1_0_0 extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['archive_version']) && version_compare($this->config['archive_version'], '1.0.0', '>=');
    }

    static public function depends_on()
    {
        return array('\phpbb\db\migration\data\v310\dev');
    }

    public function update_data()
    {
        return array(
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
                    'modes'             => array('config_archive'),
                ),
            )),

            array('config.add', array('archive_version', '1.0.0')),
        );
    }
}
