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

class main_module
{
	/** @var ContainerInterface */
	protected $phpbb_container;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;
	
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var string */
	public $u_action;
	
	function main($id, $mode)
	{
		global $phpbb_container, $user, $template, $config, $request;
		
		$this->phpbb_container = $phpbb_container;
		
		$this->user = $user;
		$this->template = $template;
		$this->config = $config;
		$this->request = $request;
		$this->log = $this->phpbb_container->get('log');

		$this->tpl_name = 'acp_archive';
		$this->page_title = $this->user->lang('ARCHIVE_TITLE');
		add_form_key('o0johntam0o/acp_archive');

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('o0johntam0o/acp_archive'))
			{
				trigger_error('FORM_INVALID');
			}
			
			$this->config->set('archive_topics_per_page', $this->request->variable('archive_topics_per_page', 15));
			$this->config->set('archive_posts_per_page', $this->request->variable('archive_posts_per_page', 10));
			$this->config->set('archive_hide_mod', $this->request->variable('archive_hide_mod', 1));
			
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'ARCHIVE_LOG_MSG');
			trigger_error($this->user->lang('ARCHIVE_SAVED') . adm_back_link($this->u_action));
		}
		
		$this->template->assign_vars(array(
			'U_ACTION'						=> $this->u_action,
			'S_ARCHIVE_VERSION'				=> isset($this->config['archive_version']) ? $this->config['archive_version'] : false,
			'S_ARCHIVE_TOPICS_PER_PAGE'		=> isset($this->config['archive_topics_per_page']) ? $this->config['archive_topics_per_page'] : 15,
			'S_ARCHIVE_POSTS_PER_PAGE'		=> isset($this->config['archive_posts_per_page']) ? $this->config['archive_posts_per_page'] : 10,
			'S_ARCHIVE_HIDE_MOD'			=> isset($this->config['archive_hide_mod']) ? $this->config['archive_hide_mod'] : 1,
		));
	}
}
