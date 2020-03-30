<?php
/**
*
* @package phpBB Extension - htaccess Editor
* @copyright (c) 2020 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\htaccesseditor\migrations\v10x;

class m1_acp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		$sql = 'SELECT module_id
			FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'acp'
				AND module_langname = 'ACP_HTACCESSEDITOR_TITLE'";
		$result = $this->db->sql_query($sql);
		$module_id = (int) $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);
		return $module_id;
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v330\v330'];
	}

	public function update_data()
	{
		return [
			['config.add', ['htaccesseditor_version', '1.0.0']],
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_HTACCESSEDITOR_TITLE'
			]],
			['module.add', [
				'acp',
				'ACP_HTACCESSEDITOR_TITLE',
				[
					'module_basename'	=> '\dmzx\htaccesseditor\acp\main_module',
					'modes'				=> ['manage'],
				],
			]],
		];
	}
}
