<?php
/**
*
* @package phpBB Extension - htaccess Editor
* @copyright (c) 2020 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\htaccesseditor\acp;

class main_info
{
	public function module()
	{
		return [
			'filename'	=> '\dmzx\htaccesseditor\acp\main_module',
			'title'		=> 'ACP_HTACCESSEDITOR_TITLE',
			'modes'		=> [
				'manage'	=> [
					'title'	=> 'ACP_HTACCESSEDITOR_SETTINGS',
					'auth'	=> 'ext_dmzx/htaccesseditor && acl_a_board',
					'cat'	=> ['ACP_HTACCESSEDITOR_TITLE']
				],
			],
		];
	}
}
