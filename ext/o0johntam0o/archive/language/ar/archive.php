<?php
/**
*
* Archive extension for the phpBB Forum Software package [Arabic]
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
	'ARCHIVE_VIEW_FULL_STORY_EXPLAIN'	=> 'انقر لمُشاهدة الموضوع الأصلي لـ ',
	'ARCHIVE_BACK'						=> 'الرجوع إلى المنتدى الأب',
	'ARCHIVE_BACK_MAIN'					=> 'الرجوع إلى الأرشيف',
	'ARCHIVE_FORUM_PASSWORD'			=> 'نرجوا تسجيل الدخول للوصول إلى هذا المنتدى',
	'ARCHIVE_NO_FORUM_OR_NO_FORUMS'		=> 'هذه الصفحة غير موجودة. قد يكون السبب :<br /><br />- لا يوجد أقسام في هذا المنتدى.<br /><br />- طلبك غير صحيح.<br /><br />- هذا المنتدى هو مجرد رابط تحويل.<br /><br />- الأرشيف معطل بواسطة إدارة الموقع.',
	'ARCHIVE_MOD'						=> 'الأرشيف',
	'ARCHIVE_PAGE'						=> 'الصفحة ',
	'ARCHIVE_JUMP'						=> 'الإنتقال إلى الأرشيف',
	'ARCHIVE_NOT_VISIBLE'				=> '<b>[غير مرئي]</b>',
	'ARCHIVE_POST_GLOBAL'				=> '<b>[إعلان عام]</b>',
	'ARCHIVE_POST_ANNOUNCE'				=> '<b>[إعلان]</b>',
	'ARCHIVE_POST_STICKY'				=> '<b>[مُثبت]</b>',
	
	'ARCHIVE_TITLE'						=> 'الأرشيف',
	'ARCHIVE_TITLE_SETTINGS'			=> 'الإعدادات',
	'ARCHIVE_ENABLE'					=> 'تفعيل ',
	'ARCHIVE_ENABLE_EXPLAIN'			=> 'هل تريد استخدام هذه الإضافة الآن ؟',
	'ARCHIVE_TOPICS_PER_PAGE'			=> 'المواضيع في كل صفحة ',
	'ARCHIVE_TOPICS_PER_PAGE_EXPLAIN'	=> 'عدد المواضيع التي سيتم عرضها في كل صفحة',
	'ARCHIVE_POSTS_PER_PAGE'			=> 'المشاركات في كل صفحة ',
	'ARCHIVE_POSTS_PER_PAGE_EXPLAIN'	=> 'دد المشاركات التي سيتم عرضها في كل صفحة',
	'ARCHIVE_HIDE_MOD'					=> 'اخفاء/ مخفي ( BBCode ) ',
	'ARCHIVE_HIDE_MOD_EXPLAIN'			=> 'هل تستخدم كود البي بي [Hide] أو [Hidden] لإخفاء محتوى المشاركات ؟',
	'ARCHIVE_SAVED'						=> 'تم تحديث الإعدادات بنجاح',
	'ARCHIVE_LOG_MSG'					=> '<strong>تحديث إعدادات الإضافة : الأرشيف</strong>',
));
