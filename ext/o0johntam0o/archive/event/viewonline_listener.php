<?php

/**
*
* @package phpBB Extension - Archive
* @copyright (c) 2014 o0johntam0o
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace o0johntam0o\archive\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class viewonline_listener implements EventSubscriberInterface
{
	protected $helper, $user, $auth, $php_ext;

    public function __construct(\phpbb\controller\helper $helper, \phpbb\user $user, \phpbb\auth\auth $auth, $php_ext)
    {
        $this->helper = $helper;
        $this->user = $user;
		$this->auth = $auth;
		$this->php_ext = $php_ext;
    }
	
	static public function getSubscribedEvents()
    {
        return array(
            'core.viewonline_overwrite_location' => 'get_location_in_archive',
        );
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
					$event['location_url'] = $this->helper->route('archive_viewforum_controller', array('f' => $forum_id));
				}
				else
				{
					$event['location'] = $this->user->lang['ARCHIVE_MOD'];
					$event['location_url'] = $this->helper->route('archive_base_controller');
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
