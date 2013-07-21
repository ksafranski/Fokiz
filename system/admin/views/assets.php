<?php

/*
 * This file is part of the Fokiz Content Management System
 * <http://www.fokiz.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once('../../../config.php');
permitUser(User::ADMIN, User::EDITOR);
checkToken(); // Check Authentication Token

?>

<h1><?php echo lang('Assets'); ?></h1>

<iframe style="margin-top: -10px;" src="<?php echo(BASE_URL); ?>system/admin/editor/filemgr/index.php" width="100%" height="495" frameborder="0" scrolling="no"></iframe>

<button onclick="modal.hide();"><?php echo lang('Close'); ?></button>