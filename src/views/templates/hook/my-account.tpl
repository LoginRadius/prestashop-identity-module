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
{if $emailVerified }
<li class="favoriteproducts">
    <a href="{$link->getModuleLink('loginradiusadvancemodule', 'account')|escape:'htmlall':'UTF-8'}"
       title="{l s='Social Account Linking' mod='loginradiusadvancemodule'}">
        {if !$in_footer}<i> <img
                    {if isset($mobile_hook)}src="{$module_template_dir|escape:'htmlall':'UTF-8'}views/img/socialinking.jpg"
                    class="ui-li-icon ui-li-thumb"
                    {else}src="{$module_template_dir|escape:'htmlall':'UTF-8'}views/img/socialinking.jpg"
                    class="icon"{/if}
                    alt="{l s='Social Account Linking' mod='sociallogin'}"/></i>{/if}
        <span>{l s='Social Account Linking' mod='loginradiusadvancemodule'}</span>
    </a>
</li>
{/if}

{if $emailVerified || $provider == 'RAAS'}
<li class="favoriteproducts">
    <a href="{$link->getModuleLink('loginradiusadvancemodule', 'password')|escape:'htmlall':'UTF-8'}"
       title="{l s='Password' mod='loginradiusadvancemodule'}">
        {if !$in_footer}<i> <img
                    {if isset($mobile_hook)}src="{$module_template_dir|escape:'htmlall':'UTF-8'}views/img/socialinking.jpg"
                    class="ui-li-icon ui-li-thumb"
                    {else}src="{$module_template_dir|escape:'htmlall':'UTF-8'}views/img/socialinking.jpg"
                    class="icon"{/if}
                    alt="{l s='Change Password' mod='sociallogin'}"/></i>{/if}
        <span>{l s='Password' mod='loginradiusadvancemodule'}</span>
    </a>
</li>
{/if}