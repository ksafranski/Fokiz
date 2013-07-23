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

?>
<div>
<h1><?php echo(urldecode($_GET['title'])); ?></h1>
<iframe width="100%" height="100%" style="border: 2px solid #fff; height: <?php echo($_GET['height']-115); ?>px;" frameborder="0" src="<?php echo($_GET['ext_url']); ?>"></iframe>
<button onclick="modal.hide();"><?php echo lang('Close'); ?></button>
<div class="clear"></div>
</div>
