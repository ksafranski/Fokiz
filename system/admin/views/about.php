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

require_once('../controllers/about.php');

?>
<h1><?php echo lang('Fokiz Content Management System'); ?> - v. <?php echo(VERSION); ?></h1>

<textarea style="height: 350px;"><?php echo lang('FOKIZ_LICENSE'); ?></textarea>

<button class="btn_left"><a href="http://www.fokiz.com" target="_blank"><?php echo lang('Open Fokiz Website'); ?></a></button><button onclick="modal.hide();" class="btn_right"><?php echo lang('Close'); ?></button>