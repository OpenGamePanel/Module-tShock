<?php
/*
 *
 * OGP - Open Game Panel
 * Copyright (C) 2008 - 2018 The OGP Development Team
 *
 * http://www.opengamepanel.org/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
function exec_ogp_module()
{
	global $db;
	$home_cfg_ids = array();
	
	foreach($db->getGameCfgs() as $cfg)
	{
		if(preg_match('/terraria/i', $cfg['home_cfg_file']))
			$home_cfg_ids[] = $cfg['home_cfg_id'];
	}
	
	if(!empty($home_cfg_ids))
	{
		$server_homes = array();
		$isAdmin = $db->isAdmin($_SESSION['user_id']);
		foreach($home_cfg_ids as $home_cfg_id)
		{
			if($isAdmin)
				$server_homes = array_merge($server_homes, $db->getHomesFor_limit('admin', $_SESSION['user_id'], 1, 9999, $home_cfg_id,''));
			else	
				$server_homes = array_merge($server_homes, $db->getHomesFor_limit('user_and_group', $_SESSION['user_id'], 1, 9999, $home_cfg_id,''));
		}
		
		if(empty($server_homes))
			print_failure(get_lang('no_game_homes_assigned'));
		else
			create_home_selector_address($_GET['m'], 'default', $server_homes);
	}
	else
	{
		print_failure("No Terraria XML found");
	}
}