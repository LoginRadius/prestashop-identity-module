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
<div class="loginradius_raas_forms">
{capture name=path}<a href="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}"
                      title="{l s='Authentication' mod='loginradiusadvancemodule'}"
                      rel="nofollow">{l s='Authentication' mod='loginradiusadvancemodule'}</a><span
        class="navigation-pipe">{$navigationPipe}</span>{l s='Forgot your password' mod='loginradiusadvancemodule'}{/capture}
<div class="box">
    <h1 class="page-subheading">{l s='Forgot your password?' mod='loginradiusadvancemodule'}</h1>


    <script>
        jQuery(document).ready(function () {
            initializeForgotPasswordRaasForms();
        });
    </script>
    <div id="forgotpassword-container"></div>
</div>
<ul class="clearfix footer_links">
    <li><a class="btn btn-default button button-small"
           href="{$link->getPageLink('authentication')|escape:'html':'UTF-8'}"
           title="{l s='Back to Login' mod='loginradiusadvancemodule'}" rel="nofollow"><span><i
                        class="icon-chevron-left"></i>{l s='Back to Login' mod='loginradiusadvancemodule'}</span></a>
    </li>
</ul>
    </div>
