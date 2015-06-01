<?php
/**
*
* Archive extension for the phpBB Forum Software package
*
* @copyright (c) 2014 o0johntam0o
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace o0johntam0o\archive\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\controller\helper */
	protected $helper;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\auth\auth */
	protected $auth;
	/** @var \phpbb\config\config */
	protected $config;
	/** @var \phpbb\request\request */
	protected $request;
	/** @var string */
	protected $php_ext;
	
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\request\request $request, $php_ext)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->config = $config;
		$this->request = $request;
		$this->php_ext = $php_ext;
	}
	
	static public function getSubscribedEvents()
    {
        return array(
            'core.user_setup'		=> 'load_language_on_setup',
            'core.page_header'		=> 'assign_archive_link',
            'core.viewonline_overwrite_location' => 'get_location_in_archive',
            'core.search_modify_tpl_ary'               => 'append_link_to_archive',
        );
    }
	
    public function load_language_on_setup($event)
    {
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = array(
            'ext_name' => 'o0johntam0o/archive',
            'lang_set' => 'archive',
        );
        $event['lang_set_ext'] = $lang_set_ext;
    }

    public function assign_archive_link($event)
    {
		if ($this->user->page['page_dir'] != '' || str_replace('.' . $this->php_ext, '', $this->user->page['page_name']) == 'archive')
		{
			return;
		}
		
		$this->template->assign_var('U_ARCHIVE_AVAILABLE', true);
		
		if ($this->request->variable('f', 0) > 0)
		{
			if ($this->request->variable('t', 0) > 0)
			{
				$this->template->assign_var('U_ARCHIVE_PAGE', $this->helper->route('o0johntam0o_archive_viewtopic_controller', array('f' => $this->request->variable('f', 0), 't' => $this->request->variable('t', 0))));
			}
			else
			{
				$this->template->assign_var('U_ARCHIVE_PAGE', $this->helper->route('o0johntam0o_archive_viewforum_controller', array('f' => $this->request->variable('f', 0))));
			}
		}
		else
		{
			$this->template->assign_var('U_ARCHIVE_PAGE', $this->helper->route('o0johntam0o_archive_base_controller'));
		}
    }

    public function append_link_to_archive($event)
    {
		$event['tpl_ary'] = array_merge($event['tpl_ary'], array(
			'U_VIEW_ARCHIVE'	=> (!empty($event['row']['post_id'])) ? $this->helper->route('o0johntam0o_archive_viewtopic_controller', array('f' => $event['row']['forum_id'], 't' => $event['row']['topic_id'])) : '',
		));
    }

    public function get_location_in_archive($event)
    {
		if ($event['on_page'][1] == 'app')
		{
			if ($this->check_ext_page($event['row']['session_page']))
			{
				$forum_id = $event['row']['session_forum_id'];
				if ($forum_id > 0 && $this->auth->acl_get('f_list', $forum_id))
				{
					$event['location'] = sprintf($this->user->lang['READING_FORUM'], $event['forum_data'][$forum_id]['forum_name']);
					$event['location_url'] = $this->helper->route('o0johntam0o_archive_viewforum_controller', array('f' => $forum_id));
				}
				else
				{
					$event['location'] = $this->user->lang['ARCHIVE_MOD'];
					$event['location_url'] = $this->helper->route('o0johntam0o_archive_base_controller');
				}
			}
		}
    }
	
	protected function check_ext_page($session_page)
	{
		if (substr($session_page, 8, 7) === 'archive')
		{
			if (empty(substr($session_page, 15 , 1)) || substr($session_page, 15 , 1) == '?' || substr($session_page, 15 , 1) == '/')
			{
				return 1;
			}
		}
		
		return 0;
	}
}
