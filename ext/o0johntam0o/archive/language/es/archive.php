<?php
/**
*
* Archive extension for the phpBB Forum Software package [Spanish]
* Spanish translation by phpbb-es (http://www.phpbb-es.com)
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
	'ARCHIVE_VIEW_FULL_STORY_EXPLAIN'	=> 'Clic para ver la historia completa de ',
	'ARCHIVE_BACK'						=> 'Volver al foro padre',
	'ARCHIVE_BACK_MAIN'					=> 'Volver al archivo principal',
	'ARCHIVE_FORUM_PASSWORD'			=> 'Por favor, debe iniciar sesión para acceder a este foro',
	'ARCHIVE_NO_FORUM_OR_NO_FORUMS'		=> 'Esta página no esta disponible. Este error se debe a:<br />- Este foro no tiene foros<br />- Su solicitud es incorrecta<br />- Esto es un foro enlace<br />- La extensión Archivo ha sido deshabilitada por el Administrador',
	'ARCHIVE_MOD'						=> 'Archivo',
	'ARCHIVE_PAGE'						=> 'Página ',
	'ARCHIVE_JUMP'						=> 'Ir al Archivo',
	'ARCHIVE_NOT_VISIBLE'				=> '<b>[No Visible]</b>',
	'ARCHIVE_POST_GLOBAL'				=> '<b>[Anuncio Global]</b>',
	'ARCHIVE_POST_ANNOUNCE'				=> '<b>[Anuncio]</b>',
	'ARCHIVE_POST_STICKY'				=> '<b>[Nota]</b>',
	
	'ARCHIVE_TITLE'						=> 'Extensión Archivo',
	'ARCHIVE_TITLE_SETTINGS'			=> 'Ajustes',
	'ARCHIVE_ENABLE'					=> 'Habilitar la extensión Archivo',
	'ARCHIVE_ENABLE_EXPLAIN'			=> '¿Quiere utilizar esta extensión ahora?',
	'ARCHIVE_TOPICS_PER_PAGE'			=> 'Temas por página',
	'ARCHIVE_TOPICS_PER_PAGE_EXPLAIN'	=> 'Número de temas a mostrar en cada página',
	'ARCHIVE_POSTS_PER_PAGE'			=> 'Mensajes por página',
	'ARCHIVE_POSTS_PER_PAGE_EXPLAIN'	=> 'Número de mensajes a mostrar en cada página',
	'ARCHIVE_HIDE_MOD'					=> 'Hide/Hidden MOD',
	'ARCHIVE_HIDE_MOD_EXPLAIN'			=> '¿Esta utilizando el BBCode [Hide] o [Hidden] para ocultar contenido de sus mensajes?',
	'ARCHIVE_SAVED'						=> 'Ajustes de la extensión Archivo actualizados',
	'ARCHIVE_LOG_MSG'					=> '<strong>Ajustes de la extensión Archivo alterados</strong>',
));
