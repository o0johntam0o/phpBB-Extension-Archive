<?php

/**
*
* @package phpBB Extension - Archive
* @copyright (c) 2014 o0johntam0o
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace o0johntam0o\archive\controller;

class main
{
	protected $helper, $template, $user, $config, $auth, $request, $db, $passwords_manager, $root_path, $php_ext;
	protected $pageview_t, $pageview_f, $pageview_page, $archive_enable, $topics_per_page, $posts_per_page, $hide_mod;

	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \phpbb\config\config $config, \phpbb\auth\auth $auth, \phpbb\request\request $request, \phpbb\db\driver\driver_interface $db, \phpbb\passwords\manager $passwords_manager, $root_path, $php_ext)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->config = $config;
		$this->auth = $auth;
		$this->request = $request;
		$this->db = $db;
		$this->passwords_manager = $passwords_manager;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		
		
		$this->user->session_begin();
		$this->auth->acl($this->user->data);

		$this->archive_enable = isset($this->config['archive_enable']) ? $this->config['archive_enable'] : 0;
		$this->topics_per_page = isset($this->config['archive_topics_per_page']) ? $this->config['archive_topics_per_page'] : 15;
		$this->posts_per_page = isset($this->config['archive_posts_per_page']) ? $this->config['archive_posts_per_page'] : 10;
		$this->hide_mod = isset($this->config['archive_hide_mod']) ? $this->config['archive_hide_mod'] : 1;
	}

	/**
	*	INPUT
	*		$id = forum_id
	*	
	*	RETURN
	*		return login state
	*/
	protected function check_forum_login($id = 0, $hash = '')
	{
		if ($id > 0)
		{
			$id = (int) $id;
			
			$pass_input = utf8_normalize_nfc($this->request->variable('password', '', true));

			$sql = 'SELECT forum_id
				FROM ' . FORUMS_ACCESS_TABLE . '
				WHERE forum_id = ' . $id . '
					AND user_id = ' . $this->user->data['user_id'] . "
					AND session_id = '" . $this->db->sql_escape($this->user->session_id) . "'";
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ($row)
			{
				return true;
			}

			if ($pass_input)
			{
				// Remove expired authorised sessions
				$sql = 'SELECT f.session_id
					FROM ' . FORUMS_ACCESS_TABLE . ' f
					LEFT JOIN ' . SESSIONS_TABLE . ' s ON (f.session_id = s.session_id)
					WHERE s.session_id IS NULL';
				$result = $this->db->sql_query($sql);

				if ($row = $this->db->sql_fetchrow($result))
				{
					$sql_in = array();
					do
					{
						$sql_in[] = (string) $row['session_id'];
					}
					while ($row = $this->db->sql_fetchrow($result));

					// Remove expired sessions
					$sql = 'DELETE FROM ' . FORUMS_ACCESS_TABLE . '
						WHERE ' . $this->db->sql_in_set('session_id', $sql_in);
					$this->db->sql_query($sql);
				}
				$this->db->sql_freeresult($result);

				if ($this->passwords_manager->check($pass_input, $hash))
				{
					$sql_ary = array(
						'forum_id'		=> $id,
						'user_id'		=> (int) $this->user->data['user_id'],
						'session_id'	=> (string) $this->user->session_id,
					);

					$this->db->sql_query('INSERT INTO ' . FORUMS_ACCESS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));

					return true;
				}

				$this->template->assign_var('ARCHIVE_LOGIN_ERROR', $this->user->lang['WRONG_PASSWORD']);
			}

			$this->template->assign_vars(array(
				'S_ARCHIVE_LOGIN_ACTION'		=> build_url($this->helper->route('archive_viewtopic_controller')),
				'S_ARCHIVE_HIDDEN_FIELDS'		=> build_hidden_fields(array('f' => $id)))
			);
			return false;
		}
		return false;
	}

	/**
	*	INPUT
	*		$id = forum_id
	*	
	*	RETURN
	*		return protection state
	*/
	protected function check_protected_forum($id = 0)
	{
		if ($id > 0)
		{
			$id = (int)$id;
			$result = $this->db->sql_query('SELECT f.forum_password
									FROM ' . FORUMS_TABLE . ' f' . "
									WHERE f.forum_id = $id");
			$check_pass = $this->db->sql_fetchrow($result);
			$check_pass = $check_pass['forum_password'];
			$this->db->sql_freeresult($result);
			
			if (isset($check_pass) && $check_pass != '' && !$this->check_forum_login($id, $check_pass))
			{
				$this->template->assign_var('ARCHIVE_FORUM_PROTECTED', true);
				return 1;
			}
			else
			{
				return 0;
			}
		}
		return 0;
	}

	/**
	*	INPUT
	*		$id = parent_id
	*	
	*	RETURN (All forums)
	*		If ($id > 0)	return array(forum_id => forum_name)
	*		Else			return array(forum_id => array(forum_name => parent_id))
	*/
	protected function fetch_forum_list($id = 0)
	{
		$id = (int)$id;
		
		// Check if forum is protected
		if ($id > 0)
		{
			if ($this->check_protected_forum($id))
			{
				return;
			}
		}
		
		if ($id > 0)
		{
			$archive_sql = 'SELECT forum_id, forum_name FROM ' . FORUMS_TABLE . ' WHERE parent_id=' . $id . ' ORDER BY left_id ASC';
		}
		else
		{
			$archive_sql = 'SELECT forum_id, parent_id, forum_name FROM ' . FORUMS_TABLE . ' ORDER BY left_id ASC';
		}
		
		$result = $this->db->sql_query($archive_sql);
		
		while ($row = $this->db->sql_fetchrow($result))
		{
			if ($id > 0)
			{
				$_fetch_forum_list[$row['forum_id']] = $row['forum_name'];
			}
			else
			{
				$_fetch_forum_list[$row['forum_id']][$row['forum_name']] = $row['parent_id'];
			}
		}
		
		$this->db->sql_freeresult($result);
		
		if (isset($_fetch_forum_list))
		{
			return $_fetch_forum_list;
		}
	}

	/**
	*	INPUT
	*		$id		= forum_id
	*		$limit	= Limitation of rows
	*		$start	= Position of row
	*	
	*	RETURN
	*		If ($id > 0)	return array(topic_id => topic_title)
	*/
	protected function fetch_topic_list($id = 0, $limit = 0, $start = 0)
	{
		if ($id > 0)
		{
			$id = (int)$id;
			$limit = (int)$limit;
			$start = (int)$start;
		
			if ($this->check_protected_forum($id))
			{
				return;
			}
			
			if ($limit == 0 && $start == 0)
			{
				// For count
				$tmp_sql = 'SELECT forum_topics_approved, forum_topics_unapproved, forum_topics_softdeleted FROM ' . FORUMS_TABLE . ' WHERE forum_id = ' . $id;
				$result = $this->db->sql_query($tmp_sql);
				$row = $this->db->sql_fetchrow($result);
				
				if ($this->auth->acl_get('m_approve', $id))
				{
					$topic_count = $row['forum_topics_approved'] + $row['forum_topics_unapproved'] + $row['forum_topics_softdeleted'];
				}
				else
				{
					$topic_count = $row['forum_topics_approved'];
				}
				
				$this->db->sql_freeresult($result);
				
				return $topic_count;
			}

			$archive_sql = array(
				'SELECT'	=> 't.topic_id, t.topic_title, t.topic_visibility',
				'FROM'		=> array(TOPICS_TABLE => 't')
				);
			// Fetch Global Announcements
			$archive_sql['WHERE']		= 't.topic_type = ' . POST_GLOBAL;
			$archive_sql['WHERE']		.= ($this->auth->acl_get('m_approve', $id)) ? '' : ' AND t.topic_visibility = 1';
			$archive_sql['ORDER_BY']	= 't.topic_last_post_time DESC';
			$result = $this->db->sql_query($this->db->sql_build_query('SELECT', $archive_sql));
			
			while ($row = $this->db->sql_fetchrow($result))
			{
				$_fetch_topic_list[$row['topic_id']] = $row['topic_title'] . " " . $this->user->lang['ARCHIVE_POST_GLOBAL'];
			}
			
			$this->db->sql_freeresult($result);
			// Fetch Announcements And Sticky
			$archive_sql['SELECT'] .= ', t.topic_type';
			$archive_sql['WHERE'] = "t.forum_id = $id AND " . $this->db->sql_in_set('t.topic_type', array(POST_STICKY, POST_ANNOUNCE));
			$archive_sql['WHERE'] .= ($this->auth->acl_get('m_approve', $id)) ? '' : ' AND t.topic_visibility = 1';
			$archive_sql['ORDER_BY'] = 't.topic_type DESC';
			$result = $this->db->sql_query($this->db->sql_build_query('SELECT', $archive_sql));
			
			while ($row = $this->db->sql_fetchrow($result))
			{
				if ($row['topic_type'] == POST_ANNOUNCE)
				{
					$_fetch_topic_list[$row['topic_id']] = $row['topic_title'] . " " . $this->user->lang['ARCHIVE_POST_ANNOUNCE'];
				}
				else
				{
					$_fetch_topic_list[$row['topic_id']] = $row['topic_title'] . " " . $this->user->lang['ARCHIVE_POST_STICKY'];
				}
			}
			
			$this->db->sql_freeresult($result);
			// Fetch Normal Topic
			$archive_sql['WHERE'] = "t.forum_id = $id AND t.topic_type = " . POST_NORMAL;
			$archive_sql['WHERE'] .= ($this->auth->acl_get('m_approve', $id)) ? '' : ' AND t.topic_visibility = 1';
			$archive_sql['ORDER_BY'] = 't.topic_last_post_time DESC';
			
			if ($limit > 0 && $start == 0)
			{
				// For first page
				$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $archive_sql), $limit);
			}
			else if ($limit > 0 && $start > 0)
			{
				// For after pages
				$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $archive_sql), $limit, $start);
			}
			else
			{
				// For other pages - Load default
				$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $archive_sql), $this->topics_per_page, $start);
			}
			
			while ($row = $this->db->sql_fetchrow($result))
			{
				if ($row['topic_visibility'] == 1)
				{
					$_fetch_topic_list[$row['topic_id']] = $row['topic_title'];
				}
				else
				{
					$_fetch_topic_list[$row['topic_id']] = $row['topic_title'] . " " . $this->user->lang['ARCHIVE_NOT_VISIBLE'];
				}
			}
			
			$this->db->sql_freeresult($result);
			
			if (isset($_fetch_topic_list))
			{
				return $_fetch_topic_list;
			}
		}
	}

	/**
	*	INPUT
	*		$id			= topic_id
	*		$limit		= Limitation of rows
	*		$start		= Position of row
	*		$forum_id	= forum_id
	*	
	*	RETURN
	*		If ($id > 0 && $forum_id > 0)	return array(post_time => array(poster_id => array(post_subject => post_text)))
	*/
	protected function fetch_post_list($id = 0, $limit = 0, $start = 0, $forum_id = 0)
	{
		if ($id > 0 && $forum_id > 0)
		{
			$id = (int)$id;
			$limit = (int)$limit;
			$start = (int)$start;
			$forum_id = (int)$forum_id;
		
			if ($this->check_protected_forum($forum_id))
			{
				return;
			}
			
			if ($limit == 0 && $start == 0)
			{
				// For count
				$tmp_sql = 'SELECT topic_posts_approved, topic_posts_unapproved, topic_posts_softdeleted FROM ' . TOPICS_TABLE . ' WHERE topic_id = ' . $id;
				$result = $this->db->sql_query($tmp_sql);
				$row = $this->db->sql_fetchrow($result);
				
				if ($this->auth->acl_get('m_approve', $id))
				{
					$post_count = $row['topic_posts_approved'] + $row['topic_posts_unapproved'] + $row['topic_posts_softdeleted'];
				}
				else
				{
					$post_count = $row['topic_posts_approved'];
				}
				
				$this->db->sql_freeresult($result);
				return $post_count;
			}
			
			$archive_sql = array(
				'SELECT'	=> 'p.post_subject, p.post_text, p.poster_id, p.post_time, p.post_visibility',
				'FROM'		=> array(POSTS_TABLE => 'p')
				);
			$archive_sql['WHERE'] = "p.topic_id = $id";
			$archive_sql['WHERE'] .= ($this->auth->acl_get('m_approve', $forum_id)) ? '' : ' AND p.post_visibility = 1';
			$archive_sql['ORDER_BY'] = 'p.post_time ASC';
			if ($limit > 0 && $start == 0)
			{
				// For first page
				$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $archive_sql), $limit);
			}
			else if ($limit > 0 && $start > 0)
			{
				// For after pages
				$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $archive_sql), $limit, $start);
			}
			else
			{
				// For other pages - Load default
				$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $archive_sql), $this->posts_per_page, $start);
			}
			
			while ($row = $this->db->sql_fetchrow($result))
			{
				if ($row['post_visibility'] == 1)
				{
					$_fetch_post_list[$row['post_time']][$row['poster_id']] = array($row['post_subject'] => $row['post_text']);
				}
				else
				{
					$_fetch_post_list[$row['post_time']][$row['poster_id']] = array($row['post_subject'] . " " . $this->user->lang['ARCHIVE_NOT_VISIBLE'] => $row['post_text']);
				}
			}
			$this->db->sql_freeresult($result);
			
			if (isset($_fetch_post_list))
			{
				// Update topic view
				if (isset($this->user->data['session_page']) && !$this->user->data['is_bot'] && (strpos($this->user->data['session_page'], '&t=' . $id) === false || isset($this->user->data['session_created'])))
				{
					$sql = 'UPDATE ' . TOPICS_TABLE . '
						SET topic_views = topic_views + 1, topic_last_view_time = ' . time() . "
						WHERE topic_id = $id";
					$this->db->sql_query($sql);
				}
				
				return $_fetch_post_list;
			}
		}
	}

	/**
	*	INPUT
	*		$id = user_id
	*	
	*	RETURN
	*		If ($id > 0)	return username
	*/
	protected function fetch_username($id)
	{
		if ($id > 0)
		{
			$id = (int)$id;
			$archive_sql = array(
				'SELECT'	=> 'u.username',
				'FROM'		=> array(USERS_TABLE => 'u'),
				'WHERE'		=> "u.user_id = $id"
				);
			$result = $this->db->sql_query($this->db->sql_build_query('SELECT', $archive_sql));
			while ($row = $this->db->sql_fetchrow($result))
			{
				$_fetch_username = $row['username'];
			}
			$this->db->sql_freeresult($result);
			if (isset($_fetch_username))
			{
				return $_fetch_username;
			}
		}
	}	

	/**
	*	INPUT
	*		$id = topic_id
	*	
	*	RETURN
	*		If ($id > 0)	return topic_title
	*/
	protected function fetch_topic_title($id)
	{
		if ($id > 0)
		{
			$id = (int)$id;
			$archive_sql = array(
				'SELECT'	=> 't.topic_title',
				'FROM'		=> array(TOPICS_TABLE => 't'),
				'WHERE'		=> "t.topic_id = $id"
				);
			$result = $this->db->sql_query($this->db->sql_build_query('SELECT', $archive_sql));
			while ($row = $this->db->sql_fetchrow($result))
			{
				$_fetch_topic_title = $row['topic_title'];
			}
			$this->db->sql_freeresult($result);
			if (isset($_fetch_topic_title))
			{
				return $_fetch_topic_title;
			}
		}
	}

	/**
	*	INPUT
	*		$id = forum_id
	*	
	*	RETURN
	*		If ($id > 0)	return forum_name
	*/
	protected function fetch_forum_name($id)
	{
		if ($id > 0)
		{
			$id = (int)$id;
			$archive_sql = array(
				'SELECT'	=> 'f.forum_name',
				'FROM'		=> array(FORUMS_TABLE => 'f'),
				'WHERE'		=> "f.forum_id = $id"
				);
			$result = $this->db->sql_query($this->db->sql_build_query('SELECT', $archive_sql));
			while ($row = $this->db->sql_fetchrow($result))
			{
				$_fetch_forum_name = $row['forum_name'];
			}
			$this->db->sql_freeresult($result);
			if (isset($_fetch_forum_name))
			{
				return $_fetch_forum_name;
			}
		}
	}

	/**
	*	INPUT
	*		$id			= forum_id
	*		$arr		= array(forum_id => array(forum_name => parent_id))
	*	
	*	RETURN
	*		If (is_array($arr))		return array()		(Branches)
	*/
	protected function count_sub_level($id, $arr)
	{
		if (is_array($arr))
		{
			$id = (int)$id;
			$count[1] = $id;
			while ($id != 0)
			{
				foreach ($arr as $key => $value)
				{
					if ($key == $id)
					{
						$id = array_shift($value);
						$count[] = $id;
						break;
					}
				}
			}
			return $count;
		}
	}

	public function base($f = 0, $t = 0, $page = 0)
	{
		global $phpbb_path_helper;
		
		$this->pageview_f = $f;
		$this->pageview_t = $t;
		$this->pageview_page = $page;

		$this->template->assign_vars(array(
			'ARCHIVE_STYLE'				=> $phpbb_path_helper->update_web_root_path($this->root_path . 'ext/o0johntam0o/archive/styles/prosilver/theme/archive.css'),
			'ARCHIVE_LINK_HOME'			=> $this->helper->route('archive_base_controller'),
			'ARCHIVE_LINK_HOME_FULL'	=> $phpbb_path_helper->update_web_root_path($this->root_path . 'index.' . $this->php_ext),
			));
		
		if (!$this->archive_enable)
		{
			return $this->helper->render('archive.html');
		}
		
		if ($this->pageview_f == 0)
		{
			// ------ MAKE MAIN FORUMS OR CATEGORIES
			$_fetch_forum_list = $this->fetch_forum_list();
			if (is_array($_fetch_forum_list))
			{
				$this->template->assign_var('ARCHIVE_AVAILABLE', true);
				foreach ($_fetch_forum_list as $id => $arr)
				{
					if ($this->auth->acl_get('f_list', $id))
					{
						foreach ($arr as $name => $parent)
						{
							$this->template->assign_block_vars('archive_row', array(
								'ARCHIVE_ROW_FORUM'		=> true,
								'ARCHIVE_ROW_LEVEL'		=> sizeof($this->count_sub_level($parent, $_fetch_forum_list)),
								'ARCHIVE_FORUMS_NAME'	=> $name,
								'ARCHIVE_FORUMS_LINK'	=> $this->helper->route('archive_viewforum_controller', array('f' => $id)),
								));
						}
					}
				}
			}
			unset($_fetch_forum_list);
			$this->template->assign_vars(array(
				'U_MCP'		=> ($this->auth->acl_get('m_') || $this->auth->acl_getf_global('m_')) ? append_sid("{$this->root_path}mcp.$this->php_ext", 'i=main&amp;mode=front') : '',
				));
		}
		else if ($this->pageview_f > 0)
		{
			// Correct the forum id in sessions table
			$archive_sql = 'UPDATE ' . SESSIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', array('session_forum_id' => $this->pageview_f)) . ' WHERE session_id="' . (string) $this->user->session_id . '"';
			$this->db->sql_query($archive_sql);
		
			$this->template->assign_var('ARCHIVE_TITLE_FORUM', $this->fetch_forum_name($this->pageview_f));
			// ------ MAKE THE PARENTS MENU
			$_fetch_forum_list = $this->fetch_forum_list();
			
			if (is_array($_fetch_forum_list))
			{
				foreach ($_fetch_forum_list as $id => $arr)
				{
					foreach ($arr as $name => $parent)
					{
						if ($id == $this->pageview_f)
						{
							$parent_link = $this->count_sub_level($id, $_fetch_forum_list);
							if (sizeof($parent_link) > 1)
							{
								$this->template->assign_var('ARCHIVE_ROW_PARENT', true);
								for ($i = sizeof($parent_link) - 1; $i > 0; $i--)
								{
									if ($this->auth->acl_get('f_list', $parent_link[$i]))
									{
										$this->template->assign_block_vars('archive_row_parent', array(
											'ARCHIVE_ROW_PARENT_NAME'		=> $this->fetch_forum_name($parent_link[$i]),
											'ARCHIVE_ROW_PARENT_LINK'		=> $this->helper->route('archive_viewforum_controller', array('f' => $parent_link[$i])),
										));
									}
								}
							}
							unset($parent_link);
						}
					}
				}
			}
			unset($_fetch_forum_list);
			if ($this->pageview_t == 0)
			{
				// ------ MAKE PAGE NUMBER FOR TOPICS
				if ($this->auth->acl_get('f_read', $this->pageview_f))
				{
					$count_topics = $this->fetch_topic_list($this->pageview_f);
					if ($count_topics > $this->topics_per_page)
					{
						$count_pages = $count_topics / $this->topics_per_page;
						if ($count_pages > (int)$count_pages)
						{
							$count_pages = (int)$count_pages + 1;
						}
						for ($i = 1; $i <= $count_pages; $i++)
						{
							$this->template->assign_var('ARCHIVE_PAGE_COUNT', true);
							if ($this->pageview_page == $i || ($this->pageview_page == 0 && $i == 1))
							{
								$this->template->assign_var('ARCHIVE_TOPIC_PAGE', $i);
								$this->template->assign_block_vars('archive_page', array(
									'ARCHIVE_PAGE_NUM'	=> $i,
									'ARCHIVE_PAGE_LINK'	=> 0,
									));
							}
							else
							{
								$this->template->assign_block_vars('archive_page', array(
									'ARCHIVE_PAGE_NUM'	=> $i,
									'ARCHIVE_PAGE_LINK'	=> $this->helper->route('archive_viewforum_controller', array('f' => $this->pageview_f, 'page' => $i)),
									));
							}
						}
					}
					unset($count_pages);
					unset($count_topics);
				}
				
				// ------ MAKE FORUM LIST IN THIS FORUM OR CATEGORY
				$_fetch_forum_list = $this->fetch_forum_list($this->pageview_f);
				if (is_array($_fetch_forum_list))
				{
					$this->template->assign_var('ARCHIVE_AVAILABLE', true);
					foreach ($_fetch_forum_list as $id => $name)
					{
						if ($this->auth->acl_get('f_list', $id))
						{
							$this->template->assign_block_vars('archive_row', array(
								'ARCHIVE_ROW_FORUM'		=> true,
								'ARCHIVE_ROW_LEVEL'		=> 1,
								'ARCHIVE_FORUMS_NAME'	=> $name,
								'ARCHIVE_FORUMS_LINK'	=> $this->helper->route('archive_viewforum_controller', array('f' => $id)),
								));
						}
					}
				}
				unset($_fetch_forum_list);
				
				// ------ MAKE TOPIC LIST IN THIS FORUM OR CATEGORY
				if ($this->auth->acl_get('f_read', $this->pageview_f))
				{
					if ($this->pageview_page > 1)
					{
						$_fetch_topic_list = $this->fetch_topic_list($this->pageview_f, $this->topics_per_page, ($this->pageview_page - 1) * $this->topics_per_page);
					}
					else
					{
						$_fetch_topic_list = $this->fetch_topic_list($this->pageview_f, $this->topics_per_page);
					}
					if (is_array($_fetch_topic_list))
					{
						$this->template->assign_var('ARCHIVE_AVAILABLE', true);
						foreach ($_fetch_topic_list as $id => $title)
						{
							$this->template->assign_block_vars('archive_row', array(
								'ARCHIVE_ROW_FORUM'		=> false,
								'ARCHIVE_TOPICS_NAME'	=> $title,
								'ARCHIVE_TOPICS_LINK'	=> $this->helper->route('archive_viewtopic_controller', array('f' => $this->pageview_f, 't' => $id)),
								));
						}
						$this->template->assign_vars(array(
							'U_MCP'		=> ($this->auth->acl_get('m_', $this->pageview_f)) ? append_sid("{$this->root_path}mcp.$this->php_ext", "i=main&amp;mode=forum_view&amp;f=$this->pageview_f") : '',
							));
					}
					unset($_fetch_topic_list);
				}
			}
			else if ($this->pageview_t > 0 && $this->auth->acl_get('f_read', $this->pageview_f))	// FETCH TOPIC BODY
			{
				// ------ MAKE PAGE NUMBER FOR POSTS
				$count_posts = $this->fetch_post_list($this->pageview_t, 0, 0, $this->pageview_f);
				if ($count_posts > $this->posts_per_page)
				{
					$count_pages = $count_posts / $this->posts_per_page;
					if ($count_pages > (int)$count_pages)
					{
						$count_pages = (int)$count_pages + 1;
					}
					for ($i = 1; $i <= $count_pages; $i++)
					{
						$this->template->assign_var('ARCHIVE_PAGE_COUNT', true);
						if ($this->pageview_page == $i || ($this->pageview_page == 0 && $i == 1))
						{
							$this->template->assign_var('ARCHIVE_POST_PAGE', $i);
							$this->template->assign_block_vars('archive_page', array(
								'ARCHIVE_PAGE_NUM'	=> $i,
								'ARCHIVE_PAGE_LINK'	=> 0,
								));
						}
						else
						{
							$this->template->assign_block_vars('archive_page', array(
								'ARCHIVE_PAGE_NUM'	=> $i,
								'ARCHIVE_PAGE_LINK'	=> $this->helper->route('archive_viewtopic_controller', array('f' => $this->pageview_f, 't' => $this->pageview_t, 'page' => $i)),
								));
						}
					}
				}
				unset($count_pages);
				unset($count_posts);
				
				// ------ MAKE POST LIST IN THIS FORUM
				$this->template->assign_var('ARCHIVE_TITLE_TOPIC', $this->fetch_topic_title($this->pageview_t));
				if ($this->pageview_page > 1)
				{
					$_fetch_post_list = $this->fetch_post_list($this->pageview_t, $this->posts_per_page, ($this->pageview_page - 1) * $this->posts_per_page, $this->pageview_f);
				}
				else
				{
					$_fetch_post_list = $this->fetch_post_list($this->pageview_t, $this->posts_per_page, 0, $this->pageview_f);
				}
				if (is_array($_fetch_post_list))
				{
					$this->template->assign_var('ARCHIVE_AVAILABLE', true);
					$this->template->assign_vars(array(
						'ARCHIVE_POST_AVAILABLE'	=> true,
						'ARCHIVE_TOPIC_LINK_FULL'	=> $phpbb_path_helper->update_web_root_path($this->root_path . 'viewtopic.' . $this->php_ext . '?f=' . $this->pageview_f . '&amp;t=' . $this->pageview_t),
						'ARCHIVE_TOPIC_NAME'		=> $this->fetch_topic_title($this->pageview_t),
						));
					foreach($_fetch_post_list as $time => $this->author_subject_text)
					{
						foreach ($this->author_subject_text as $this->author => $subject_text)
						{
							foreach ($subject_text as $subject => $text)
							{
								if ($this->user->data['user_id'] == ANONYMOUS || $this->user->data['is_bot'])
								{
									if ($this->hide_mod)
									{
										// For BBCode [Hidden] from guest
										$text = preg_replace('#\[Hidden:.{8}\].*\[/Hidden:.{8}\]#i', '', $text);
										// For BBCode [Hide] from guest
										$text = preg_replace('#\[Hide:.{8}\].*\[/Hide:.{8}\]#i', '', $text);
									}
								}
								
								strip_bbcode($text);
								$text = bbcode_nl2br($text);
								$this->template->assign_block_vars('archive_post', array(
										'ARCHIVE_POST_AUTHOR'		=> $this->fetch_username($this->author),
										'ARCHIVE_POST_TIME'			=> $this->user->format_date($time, false, true),
										'ARCHIVE_POST_SUBJECT'		=> $subject,
										'ARCHIVE_POST_TEXT'			=> $text,
									));
							}
						}
					}
					$this->template->assign_vars(array(
						'U_MCP' 	=> ($this->auth->acl_get('m_', $this->pageview_f)) ? append_sid("{$this->root_path}mcp.$this->php_ext", "i=main&amp;mode=topic_view&amp;f=$this->pageview_f&amp;t=$this->pageview_t") : '',
						));
				}
			}
		}

		return $this->helper->render('archive.html');
	}
}
