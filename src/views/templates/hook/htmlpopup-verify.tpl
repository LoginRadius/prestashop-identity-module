{*
* NOTICE OF LICENSE
*
* @package   loginradiusadvancemodule Add User Registration in your Pretashop module
* @author    LoginRadius Team
* @copyright Copyright 2014 www.loginradius.com - All rights reserved.
* @license   GNU GENERAL PUBLIC LICENSE Version 2, June 1991

* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along
* with this program; if not, write to the Free Software Foundation, Inc.,
* 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*}

<!-- Block Popup Verification -->
<div id="fade" class="LoginRadius_overlay">
    <div id="popupouter" class="verify">
        <div id="textmatter"></div>
        <div id="popupinner">
            <div>{$message|escape:'htmlall':'UTF-8'}</div>
        </div>

        <div class="footerbox">
            <form method="post" name="validform_verify" action="">
                <input type="button" value="{l s='Ok' mod='loginradiusadvancemodule'}"
                       onclick="window.location.href=window.location.href;" class="inputbutton"/>
        </div>
        </form>

    </div>
</div>
<!-- /Block Popup Verification -->


