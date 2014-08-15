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
class search_listener implements EventSubscriberInterface
{
	protected $helper, $template;
	
    public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template)
    {
        $this->helper = $helper;
        $this->template = $template;
    }
	
	static public function getSubscribedEvents()
    {
        return array(
            'core.search_modify_tpl_ary'               => 'append_link_to_archive',
        );
    }

    public function append_link_to_archive($event)
    {
		$event['tpl_ary'] = array_merge($event['tpl_ary'], array(
			'U_VIEW_ARCHIVE'	=> (!empty($event['row']['post_id'])) ? $this->helper->route('archive_viewtopic_controller', array('f' => $event['row']['forum_id'], 't' => $event['row']['topic_id'])) : '',
		));
    }
}
