<?php
/**
*
* Archive extension for the phpBB Forum Software package [British English]
*
* @copyright (c) 2014 o0johntam0o
* Dutch translation by Dutch Translators (https://github.com/dutch-translators)
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
	'ARCHIVE_VIEW_FULL_STORY_EXPLAIN'	=> 'Klik om de geschiedenis te bekijken van ',
	'ARCHIVE_BACK'						=> 'Terug naar bovenliggend forum',
	'ARCHIVE_BACK_MAIN'					=> 'Terug naar archief',
	'ARCHIVE_FORUM_PASSWORD'			=> 'Je moet inloggen om toegang te krijgen tot deze pagina',
	'ARCHIVE_NO_FORUM_OR_NO_FORUMS'		=> 'Deze pagina is niet beschikbaar. Deze fout kan optreden als gevolg van:<br />- Er zijn geen forums<br />- Jouw verzoek is niet geldig<br />- Dit is een forumlink<br />- Archive extensie is uitgeschakeld door de administrator',
	'ARCHIVE_MOD'						=> 'Archief',
	'ARCHIVE_PAGE'						=> 'Pagina ',
	'ARCHIVE_JUMP'						=> 'Spring naar Archief',
	'ARCHIVE_NOT_VISIBLE'				=> '<b>[Niet zichtbaar]</b>',
	'ARCHIVE_POST_GLOBAL'				=> '<b>[Globale mededeling]</b>',
	'ARCHIVE_POST_ANNOUNCE'				=> '<b>[Mededeling]</b>',
	'ARCHIVE_POST_STICKY'				=> '<b>[Sticky]</b>',
	
	'ARCHIVE_TITLE'						=> 'Archief Extensie',
	'ARCHIVE_TITLE_SETTINGS'			=> 'Algemene instellingen',
	'ARCHIVE_ENABLE'					=> 'Archief extensie inschakelen',
	'ARCHIVE_ENABLE_EXPLAIN'			=> 'Wil je deze extensie nu gebruiken?',
	'ARCHIVE_TOPICS_PER_PAGE'			=> 'Onderwerpen per pagina',
	'ARCHIVE_TOPICS_PER_PAGE_EXPLAIN'	=> 'Het aantal onderwerpen dat per pagina getoond wordt',
	'ARCHIVE_POSTS_PER_PAGE'			=> 'Berichten per pagina',
	'ARCHIVE_POSTS_PER_PAGE_EXPLAIN'	=> 'Het aantal berichten dat per pagina getoond wordt',
	'ARCHIVE_HIDE_MOD'					=> 'Verberg/Verborgen MOD',
	'ARCHIVE_HIDE_MOD_EXPLAIN'			=> 'Gebruik je de BBCode [Verberg] of [Verborgen] om inhoud van berichten te verbergen?',
	'ARCHIVE_SAVED'						=> 'Archief extensie geüpdatet',
	'ARCHIVE_LOG_MSG'					=> '<strong>Instellingen Archief extensie gewijzigd</strong>',
));
