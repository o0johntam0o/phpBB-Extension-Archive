<?php

/**
*
* @package phpBB Extension - Archive
* @copyright (c) 2014 o0johntam0o
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace o0johntam0o\archive\acp;

class main_module
{
	var $u_action;
	
	function main($id, $mode)
	{
		global $user, $template;
		global $config, $request, $phpbb_log;

		$this->tpl_name = 'acp_archive';
		$this->page_title = $user->lang['ARCHIVE_TITLE_ACP'];
		add_form_key('o0johntam0o/acp_archive');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('o0johntam0o/acp_archive'))
			{
				trigger_error('FORM_INVALID');
			}
			
			$config->set('archive_enable', $request->variable('archive_enable', 0));
			$config->set('archive_topics_per_page', $request->variable('archive_topics_per_page', 15));
			$config->set('archive_posts_per_page', $request->variable('archive_posts_per_page', 10));
			$config->set('archive_hide_mod', $request->variable('archive_hide_mod', 1));
			
			$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'ARCHIVE_LOG_MSG');
			trigger_error($user->lang['ARCHIVE_SAVED'] . adm_back_link($this->u_action));
		}
		
		$template->assign_vars(array(
			'U_ACTION'						=> $this->u_action,
			'S_ARCHIVE_VERSION'				=> isset($config['archive_version']) ? $config['archive_version'] : false,
			'S_ARCHIVE_ENABLE'				=> isset($config['archive_enable']) ? $config['archive_enable'] : 0,
			'S_ARCHIVE_TOPICS_PER_PAGE'		=> isset($config['archive_topics_per_page']) ? $config['archive_topics_per_page'] : 15,
			'S_ARCHIVE_POSTS_PER_PAGE'		=> isset($config['archive_posts_per_page']) ? $config['archive_posts_per_page'] : 10,
			'S_ARCHIVE_HIDE_MOD'			=> isset($config['archive_hide_mod']) ? $config['archive_hide_mod'] : 1,
		));
	}
}
