<?php

/**
*
* @package phpBB Extension - Archive
* @copyright (c) 2014 o0johntam0o
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace o0johntam0o\archive\acp;

class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\o0johntam0o\archive\acp\main_module',
			'title'		=> 'ARCHIVE_TITLE_ACP',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'config_archive'	=> array(
					'title' => 'ARCHIVE_TITLE_ACP',
					'auth' => 'o0johntam0o/archive && acl_a_board',
					'cat' => array('ARCHIVE_TITLE_ACP')
				),
			),
		);
	}
}
