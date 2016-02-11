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
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>{l s='Social Account Linking' mod='loginradiusadvancemodule'}
{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
{if $socialloginlrmessage}
    <p class="warning alert alert-warning">{$socialloginlrmessage|escape:'htmlall':'UTF-8'}</p>
{/if}
<div id="favoriteproducts_block_account">
    <h2>{l s='Social Account Linking' mod='loginradiusadvancemodule'}</h2>

    <div>
        <div class="favoriteproduct clearfix">
            <script type="text/html" id="loginradiuscustom_tmpl">

                <# if(isLinked) { #>

                    <div class="lr-linked">
                        <div style="width:100%">
        <span title="<#= Name #>" alt="Linked with <#=Name#>">
<img style="margin-right: 5px;"
     src="{$base_dir|escape:'htmlall':'UTF-8'}modules/loginradiusadvancemodule/views/img/<#= Name.toLowerCase() #>.png">
              
			   <# if("{$cookie->loginradius_id|escape:'html':'UTF-8'}" == providerId) { #>
      <label style="color:green;"> Currently connected with </label>
                                <label>
                                    <#= Name #>
                                </label>
  <# } else { #>
  <label> Connected with <#= Name #></label>
             <# }  #>
 </span>
       <span> <a style="margin-left:10px;cursor: pointer"
                 onclick='return unLinkAccount(\"<#= Name.toLowerCase() #>\",\"<#= providerId #>\")'>Unlink</a></span>
                        </div>
                    </div>
                    </div>
                    <# } else { #>
                        <div class="lr-unlinked">
                            <div class="lr_icons_box">
                                <div style="width:100%">
                    <span class="lr_providericons lr_<#=Name.toLowerCase()#>"

                          onclick="return $SL.util.openWindow('<#= Endpoint #>&is_access_token=true&ac_linking=1&callback=<#=window.location #>');"

                          title="<#= Name #>" alt="Link with <#=Name#>">
                    </span>
                                </div>
                            </div>
                        </div>
                        <# } #>
            </script>
            <div class="lr_account_linking">
                <div id="interfacecontainerdiv" class="lr_singleglider_200 interfacecontainerdiv"></div>
                <div style="clear:both"></div>
            </div>
            <div class="lr-unlinked-data lr_singleglider_200"></div>
            <div style="clear:both"></div>
            <div class="lr-linked-data lr_singleglider_200"></div>
            <div style="clear:both"></div>

            <script>
                jQuery(document).ready(function () {
                    initializeAccountLinkingRaasForms();

                });
            </script>

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