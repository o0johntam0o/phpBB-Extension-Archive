<?php

/**
*
* @package phpBB Extension - Archive [Vietnamese]
* @copyright (c) 2014 o0johntam0o
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ARCHIVE_VIEW_FULL_STORY_EXPLAIN'	=> 'Nhấn để xem bản đầy đủ của ',
	'ARCHIVE_BACK'						=> 'Trở về chuyên mục cha',
	'ARCHIVE_BACK_MAIN'					=> 'Trở về bản lưu trữ chính',
	'ARCHIVE_FORUM_PASSWORD'			=> 'Vui lòng nhập mật khẩu để truy cập chuyên mục này',
	'ARCHIVE_NO_FORUM_OR_NO_FORUMS'		=> 'Trang này không tồn tại. Lỗi này có thể xảy ra do:<br />- Diễn đàn này không có chuyên mục nào<br />- Yêu cầu của bạn vừa chọn không hợp lệ<br />- Đây là một chuyên mục liên kết<br />- Archive Extension đã được tắt bởi người quản trị',
	'ARCHIVE_MOD'						=> 'Bản lưu trữ',
	'ARCHIVE_PAGE'						=> 'Trang ',
	'ARCHIVE_JUMP'						=> 'Chuyển đến trang lưu trữ',
	'ARCHIVE_NOT_VISIBLE'				=> '<b>[Không Hiện]</b>',
	'ARCHIVE_POST_GLOBAL'				=> '<b>[Thông Báo Chung]</b>',
	'ARCHIVE_POST_ANNOUNCE'				=> '<b>[Thông Báo]</b>',
	'ARCHIVE_POST_STICKY'				=> '<b>[Chú Ý]</b>',
	
	'ARCHIVE_TITLE'						=> 'Archive Extension',
	'ARCHIVE_TITLE_ACP'					=> 'Thiết lập Archive Extension',
	'ARCHIVE_ENABLE'					=> 'Kích hoạt Archive Extension',
	'ARCHIVE_ENABLE_EXPLAIN'			=> 'Bạn có muốn sử dụng tiện ích này ngay bây giờ không?',
	'ARCHIVE_TOPICS_PER_PAGE'			=> 'Số chủ đề mỗi trang',
	'ARCHIVE_TOPICS_PER_PAGE_EXPLAIN'	=> 'Số chủ đề hiển thị ở mỗi trang',
	'ARCHIVE_POSTS_PER_PAGE'			=> 'Số bài viết mỗi trang',
	'ARCHIVE_POSTS_PER_PAGE_EXPLAIN'	=> 'Số bài viết hiển thị ở mỗi trang',
	'ARCHIVE_HIDE_MOD'					=> 'Hide/Hidden MOD',
	'ARCHIVE_HIDE_MOD_EXPLAIN'			=> 'Bạn có đang sử dụng BBCode [Hide] hoặc [Hidden] để ẩn nội dung bài viết không',
	'ARCHIVE_SAVED'						=> 'Các thiết lập cho Archive Extension đã được cập nhật',
	'ARCHIVE_LOG_MSG'					=> '<strong>Đã thay đổi các thiết lập của Archive Extension</strong>',
));
