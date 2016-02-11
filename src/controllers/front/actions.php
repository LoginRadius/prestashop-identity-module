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

class LoginRadiusAdvanceModuleActionsModuleFrontController extends ModuleFrontController
{
    /**
     * @see FsontController::init()
     */
    public function init()
    {
        parent::init();

        include_once(__PS_BASE_URI__ . 'modules/loginradiusadvancemodule/includes/lr_user_functions.php');

        if (Tools::getValue('token')) {
            include_once(__PS_BASE_URI__ . 'modules/loginradiusadvancemodule/includes/LoginRadiusSDK.php');

            $lr_obj = new LoginRadius();
            $check_curl = function_exists('curl_version');
            $loginradius_api_secret = trim(Configuration::get('lr_api_secret'));
            $access_token = $lr_obj->loginRadiusFetchAccessToken($loginradius_api_secret, $check_curl);

            if (empty($access_token)) {
                return false;
            }
            $userprofile = $lr_obj->loginRadiusGetUserProfileData($access_token, $check_curl);

            loginRadiusConnect($userprofile);
        }
    }
}
