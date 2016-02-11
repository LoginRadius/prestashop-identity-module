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
    {if !isset($email_create)}{l s='Authentication'}{else}
        <a href="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" rel="nofollow"
           title="{l s='Authentication' mod='loginradiusadvancemodule'}">{l s='Authentication' mod='loginradiusadvancemodule'}</a>
        <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>{l s='Create your account' mod='loginradiusadvancemodule'}
    {/if}
{/capture}
<div class="loginradius_raas_forms">
<h1 class="page-heading">{if !isset($email_create)}{l s='Authentication' mod='loginradiusadvancemodule'}{else}{l s='Create an account' mod='loginradiusadvancemodule'}{/if}</h1>
{if !isset($email_create)}
    {if isset($authentification_error)}
        <div class="alert alert-danger">
            {if {$authentification_error|@count|escape:'htmlall':'UTF-8'} == 1}
                <p>{l s='There\'s at least one error' mod='loginradiusadvancemodule'} :</p>
            {else}
                <p>{l s='There are %s errors' sprintf=[$account_error|@count] mod='loginradiusadvancemodule'} :</p>
            {/if}
            <ol>
                {foreach from=$authentification_error item=v}
                    <li>{$v|escape:'html':'UTF-8'}</li>
                {/foreach}
            </ol>
        </div>
    {/if}
    <div class="col-sm-6 box" id="login_form" class="std" style="float: none;">

        <h3 class="page-subheading">{l s='Social Login' mod='loginradiusadvancemodule'}</h3>

        <div style='padding: 15px 10px;'>
            ​​<p class="title_block">{$interface_title|escape:'htmlall':'UTF-8'}</p>
            <script type="text/html" id="loginradiuscustom_tmpl">

                <div class="lr_icons_box">
                    <div style="width:100%">
					<span class="lr_providericons lr_<#=Name.toLowerCase()#>"
                          onclick="return $SL.util.openWindow('<#= Endpoint #>&is_access_token=true&callback=<#=window.location #>');"
                          title="<#= Name #>" alt="Sign in with <#=Name#>">

					</span>
                    </div>
                </div>

            </script>
            <div id="social-registration-form" class="LoginRadius_overlay" style="display: none;">
                <div id="popupouter">
                    <div id="textmatter">{$message|escape:'htmlall':'UTF-8'}</div>
                    <div class="popupinner" id="social-registration-container">

                    </div>

                    <div class="footerbox">

                    </div>
                    </form>

                </div>
            </div>
            ​
            <div class="lr_singleglider_200 interfacecontainerdiv"></div>
            ​​
            <script>
                jQuery(document).ready(function () {
                    callSocialInterface();
                });
            </script>
        </div>
    </div>
    <div class="row">


        <div class="col-xs-12 col-sm-6">
            <form action="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" method="post"
                  id="create-account_form" class="box">
                <h3 class="page-subheading">{l s='Create an account' mod='loginradiusadvancemodule'}</h3>

                <div class="form_content clearfix">
                    <p>{l s='Please enter your email address to create an account.' mod='loginradiusadvancemodule'}</p>

                    <div class="alert alert-danger" id="create_account_error" style="display:none"></div>
                    <div class="form-group">
                        <label for="email_create">{l s='Email address' mod='loginradiusadvancemodule'}</label>
                        <input type="email" class="is_required validate account_input form-control"
                               data-validate="isEmail" id="email_create" name="email_create"
                               value="{if isset($smarty.post.email_create)}{$smarty.post.email_create|stripslashes|escape:'html':'UTF-8'}{/if}"/>
                    </div>
                    <div class="submit">
                        {if isset($back)}<input type="hidden" class="hidden" name="back"
                                                value="{$back|escape:'html':'UTF-8'}" />{/if}
                        <button class="btn btn-default button button-medium exclusive" type="submit" id="SubmitCreate"
                                name="SubmitCreate">
							<span>
								<i class="icon-user left"></i>
                                {l s='Create an account' mod='loginradiusadvancemodule'}
							</span>
                        </button>
                        <input type="hidden" class="hidden" name="SubmitCreate" value="{l s='Create an account' mod='loginradiusadvancemodule'}"/>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12 col-sm-6">
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    initializeLoginRaasForm();
                    initializeSocialRegisterRaasForm();
                });</script>
            <div class="box">
                <h3 class="page-subheading lr-raas-login-form">{l s='Already registered?' mod='loginradiusadvancemodule'}</h3>

                <div id="login-container"></div>
                <div id="resetpassword-container" style="display: none"></div>
                <p class="lost_password form-group"><a href="{$link->getPageLink('password')|escape:'html':'UTF-8'}"
                                                       title="{l s='Recover your forgotten password' mod='loginradiusadvancemodule'}"
                                                       rel="nofollow">{l s='Forgot your password?' mod='loginradiusadvancemodule'}</a></p>
            </div>
        </div>
    </div>
{else}
    <script>
        jQuery(document).ready(function () {
            email_create = '{$smarty.post.email_create|escape:'html':'UTF-8'}';
            initializeRegisterRaasForm();
            initializeSocialRegisterRaasForm();
            setTimeout(show_birthdate_date_block, 1000);
        });
    </script>
    <div id="registeration-container"></div>
{/if}
    </div>

