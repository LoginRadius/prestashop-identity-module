<?php
/**
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
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
/**
 * Add loginradius SSO script.
 *
 * @return string
 */
function loginRadiusSSOScript()
{
    $path = parse_url(Context::getContext()->link->getPageLink('index'));
    $sso_path = $path['path'];
    return '<script src="//cdn.loginradius.com/hub/prod/js/LoginRadiusSSO.js"></script>
<script type="text/javascript">if (window.LoginRadiusSSO) {
         var tokencookie = "lr-user--token";
         LoginRadiusSSO.setToken = function (token) {
        LoginRadiusSSO.Cookie.setItem(tokencookie, token, "' . $sso_path . '");}
      }  
	</script>';
}

/**
 * Add functions to handling SSO.
 * @return string
 */
function loginRadiusHookTopSSO()
{
    $path = parse_url(Context::getContext()->link->getPageLink('index'));
    $sso_path = $path['path'];
    $site_name = Configuration::get('lr_site_name');
    $login = Context::getContext()->link->getPageLink('authentication', true);
    $logout_url = Context::getContext()->link->getPageLink('index') . '?mylogout';
    $logout = $logout_url . '&notlogged=1';
    $script = '  <script type="text/javascript">
    function lrsso_logout(){
    LoginRadiusSSO.init("' . trim($site_name) . '", "' . $sso_path . '");
            LoginRadiusSSO.logout("' . Context::getContext()->link->getPageLink('index') . '")
        
}</script>';

    $script .= '  <script type="text/javascript">
jQuery(document).ready(function () {
      if(window.LoginRadiusSSO){
               $(".logout").click(function(){
                var str = window.location.href;
                if(str.indexOf("notlogged") == -1){
     lrsso_logout();
   }
});
          }
});
</script>';

    if (!Context::getContext()->customer->isLogged()) {

        $script .= '<script type="text/javascript">
jQuery(document).ready(function () {
      if (window.LoginRadiusSSO) {
      var apidomain = "https://api.loginradius.com";
//      initializeSocialRegisterRaasForm();
            LoginRadiusSSO.init("' . trim($site_name) . '", "' . $sso_path . '");
             var str = window.location.href;
            if(str.indexOf("' . $login . '") == -1) {
             LoginRadius_SocialLogin.util.jsonpCall("//' . trim($site_name) . '.hub.loginradius.com/ssologin/login", function (data) {
              jQuery("#lr-loading").hide();
              if ( data.isauthenticated ) {
                window.location = "' . Context::getContext()->link->getPageLink('authentication', true) . '";
              }
          });
      }
      if(str.indexOf("' . $login . '") > -1) {
            LoginRadiusSSO.login("' . Context::getContext()->link->getPageLink('index') . '");
          }
        }
        });

			</script>';
    }
    if (Context::getContext()->customer->isLogged()) {
        $script .= '<script type="text/javascript">
    jQuery(document).ready(function () {
      if (window.LoginRadiusSSO) {
            LoginRadiusSSO.init("' . trim($site_name) . '", "' . $sso_path . '");
           LoginRadiusSSO.isNotLoginThenLogout("' . $logout . '");
          }
        });</script>';
    }
    return $script;
}
