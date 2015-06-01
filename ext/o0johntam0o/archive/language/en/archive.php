<?php
/**
*
* Archive extension for the phpBB Forum Software package [British English]
*
* @copyright (c) 2014 o0johntam0o
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ARCHIVE_VIEW_FULL_STORY_EXPLAIN'	=> 'Click to view full story of ',
	'ARCHIVE_BACK'						=> 'Back to parent forum',
	'ARCHIVE_BACK_MAIN'					=> 'Back to main archive',
	'ARCHIVE_FORUM_PASSWORD'			=> 'Please login in order to access this forum',
	'ARCHIVE_NO_FORUM_OR_NO_FORUMS'		=> 'This page is not available. This error occurred due to:<br />- This board has no forums<br />- Your request is incorrect<br />- This is a forum link<br />- Archive Extension was disabled by the administrator',
	'ARCHIVE_MOD'						=> 'Archive',
	'ARCHIVE_PAGE'						=> 'Page ',
	'ARCHIVE_JUMP'						=> 'Jump to Archive',
	'ARCHIVE_NOT_VISIBLE'				=> '<b>[Not Visible]</b>',
	'ARCHIVE_POST_GLOBAL'				=> '<b>[Global Announcement]</b>',
	'ARCHIVE_POST_ANNOUNCE'				=> '<b>[Announcement]</b>',
	'ARCHIVE_POST_STICKY'				=> '<b>[Sticky]</b>',
	
	'ARCHIVE_TITLE'						=> 'Archive Extension',
	'ARCHIVE_TITLE_SETTINGS'			=> 'Settings',
	'ARCHIVE_TOPICS_PER_PAGE'			=> 'Topics per page',
	'ARCHIVE_TOPICS_PER_PAGE_EXPLAIN'	=> 'Number of topics shown in each page',
	'ARCHIVE_POSTS_PER_PAGE'			=> 'Posts per page',
	'ARCHIVE_POSTS_PER_PAGE_EXPLAIN'	=> 'Number of posts shown in each page',
	'ARCHIVE_HIDE_MOD'					=> 'Hide/Hidden MOD',
	'ARCHIVE_HIDE_MOD_EXPLAIN'			=> 'Are you using the BBCode [Hide] or [Hidden] to hide the posts contents',
	'ARCHIVE_SAVED'						=> 'Archive Extension settings updated',
	'ARCHIVE_LOG_MSG'					=> '<strong>Altered Archive Extension settings</strong>',
));
