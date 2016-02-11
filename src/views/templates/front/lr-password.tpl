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

{capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}">
        {l s='My account' mod='loginradiusadvancemodule'}</a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>{l s='Password' mod='loginradiusadvancemodule'}
{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
<div class="loginradius_raas_forms">
{if $socialloginlrpasswordmessage}
    <p class="warning alert alert-warning">{$socialloginlrpasswordmessage|escape:'htmlall':'UTF-8'}</p>
{/if}
<div id="favoriteproducts_block_account">
    <h2 id="lr_password_title">{l s='Password' mod='loginradiusadvancemodule'}</h2>

    <div>
        <div class="favoriteproduct clearfix">
            <script>
                jQuery(document).ready(function () {
                    initializeChangePasswordRaasForms();

                });
            </script>
            <div id="changepasswordbox"></div>
            <div id="setpasswordbox"></div>
        </div>
    </div>
    <ul class="footer_links clearfix">
        <li>
            <a class="btn btn-default button button-small"
               href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
				<span>
					<i class="icon-chevron-left"></i>{l s='Back to Your Account' mod='loginradiusadvancemodule'}
				</span>
            </a>
        </li>
        <li class="f_right">
            <a class="btn btn-default button button-small" href="{$base_dir|escape:'html':'UTF-8'}">
				<span>
					<i class="icon-chevron-right"></i>{l s='Home' mod='loginradiusadvancemodule'}
				</span>
            </a>
        </li>
    </ul>
</div>
    </div>