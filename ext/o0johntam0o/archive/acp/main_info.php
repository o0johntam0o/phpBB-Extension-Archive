<?php
/**
*
* Archive extension for the phpBB Forum Software package
*
* @copyright (c) 2014 o0johntam0o
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace o0johntam0o\archive\acp;

class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\o0johntam0o\archive\acp\main_module',
			'title'		=> 'ARCHIVE_TITLE',
			'modes'		=> array(
				'archive_config'	=> array(
					'title' => 'ARCHIVE_TITLE_SETTINGS',
					'auth' => 'ext_o0johntam0o/archive && acl_a_board',
					'cat' => array('ARCHIVE_TITLE_SETTINGS')
				),
			),
		);
	}
}
