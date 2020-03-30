<?php
/**
*
* @package phpBB Extension - htaccess Editor
* @copyright (c) 2020 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\htaccesseditor\controller;

use phpbb\config\config;
use phpbb\template\template;
use phpbb\log\log_interface;
use phpbb\user;
use phpbb\request\request_interface;
use phpbb\language\language;
use phpbb\extension\manager;
use Symfony\Component\DependencyInjection\Container;

class admin_controller
{
	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var log_interface */
	protected $log;

	/** @var \phpbb\user */
	protected $user;

	/** @var request_interface */
	protected $request;

	/** @var language */
	protected $language;

	/** @var manager */
	protected $ext_manager;

	/** @var Container */
	protected $phpbb_container;

	/** @var string */
	protected $root_path;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor
	 *
	 * @param config				$config
	 * @param template				$template
	 * @param log_interface			$log
	 * @param user					$user
	 * @param request_interface		$request
	 * @param language				$language
	 * @param manager				$ext_manager
	 * @param Container	 			$phpbb_container
	 * @param string 				$root_path
	 */
	public function __construct(
		config $config,
		template $template,
		log_interface $log,
		user $user,
		request_interface $request,
		language $language,
		manager $ext_manager,
		Container $phpbb_container,
		$root_path
	)
	{
		$this->config 					= $config;
		$this->template 				= $template;
		$this->log 						= $log;
		$this->user 					= $user;
		$this->request 					= $request;
		$this->language					= $language;
		$this->ext_manager	 			= $ext_manager;
		$this->phpbb_container 			= $phpbb_container;
		$this->root_path 				= $root_path;
	}

	public function display_htaccesseditor()
	{
		add_form_key('acp_htaccesseditor');

		$this->htaccess = $this->root_path . '.htaccess';

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('acp_htaccesseditor'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action));
			}

			$this->put_htaccess(htmlspecialchars_decode($this->request->variable('htaccesseditor', '', true)));

			$this->config->increment('assets_version', 1);

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_HTACCESSEDITOR_SETTINGS');

			trigger_error($this->language->lang('ACP_HTACCESSEDITOR_SETTINGS_SAVED') . adm_back_link($this->u_action));

		}

		if ($this->ext_manager->is_enabled('marttiphpbb/codemirror'))
		{
			$load = $this->phpbb_container->get('marttiphpbb.codemirror.load');
			$load->set_mode('nginx');
		}

		$this->template->assign_vars([
			'U_ACTION'			=> $this->u_action,
			'HTACCESSEDITOR'	=> $this->get_htaccess(),
		]);
	}

	protected function get_htaccess()
	{
		return file_get_contents($this->htaccess);
	}

	protected function put_htaccess($content)
	{
		@file_put_contents($this->htaccess, $content);
	}

	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
