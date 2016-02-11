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

/**
 * @file
 * User Registration SDK.
 */

// Define LoginRadius Raas domain.
define('RAAS_DOMAIN', 'https://api.loginradius.com');
define('RAAS_API_KEY', trim(Configuration::get('lr_api_key')));
define('RAAS_SECRET_KEY', trim(Configuration::get('lr_api_secret')));

include_once('LoginRadiusSDK.php');
/**
 * Class for RaaS SDK.
 *
 */
class LoginradiusRaasSDK
{
    /**
     * Create User.
     *
     * @param $params
     * @return mixed
     */
    public function raasCreateUser($params)
    {
        $url = RAAS_DOMAIN . "/raas/v1/user?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY;
        return $this->raasGetResponseFromRaas($url, Tools::jsonEncode($params), 'application/json');
    }

    /**
     * Update User.
     *
     * @param $params
     * @param $uid
     * @return mixed
     */
    public function raasUpdateUser($params, $uid)
    {
        $url = RAAS_DOMAIN . "/raas/v1/user?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY . "&userid=" . $uid;
        return $this->raasGetResponseFromRaas($url, Tools::jsonEncode($params), 'application/json');
    }

    /**
     * Block User.
     *
     * @param $params
     * @param $uid
     * @return mixed
     */
    public function raasBlockUser($params, $uid)
    {
        $url = RAAS_DOMAIN . "/raas/v1/user/status?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY . "&uid=" . $uid;
        return $this->raasGetResponseFromRaas($url, $params);
    }

    /**
     * Update password.
     *
     * @param $params
     * @param $uid
     * @return mixed
     */
    public function raasUpdatePassword($params, $uid)
    {
        $url = RAAS_DOMAIN . "/raas/v1/account/password?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY . "&accountid=" . $uid;
        return $this->raasGetResponseFromRaas($url, $params);
    }

    /**
     * created Raas Profile.
     *
     * @param $params
     * @return mixed
     */
    public function createRaasProfile($params)
    {
        $url = RAAS_DOMAIN . "/raas/v1/account/profile?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY;
        return $this->raasGetResponseFromRaas($url, $params);
    }

    /**
     * Set Password.
     *
     * @param $params
     * @param $uid
     * @return mixed
     */
    public function raasSetPassword($params, $uid)
    {
        $url = RAAS_DOMAIN . "/raas/v1/user/password?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY . "&userid=" . $uid . "&action=set";
        return $this->raasGetResponseFromRaas($url, $params);
    }

    /**
     * Set Account Password.
     *
     * @param $params
     * @param $uid
     * @return mixed
     */
    public function raasSetAccountPassword($params, $accountid)
    {
         $url = RAAS_DOMAIN . "/raas/v1/account/password?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY . "&accountid=" . $accountid . "&action=set";
       
        return $this->raasGetResponseFromRaas($url, $params);
    }

    /**
     * Link account.
     *
     * @param $uid
     * @param $provider
     * @param $providerid
     * @return mixed
     */
    public function raasLinkAccount($uid, $provider, $providerid)
    {
        $url = RAAS_DOMAIN . "/raas/v1/account/link?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY;
        $params = array(
            'accountid' => $uid,
            'provider' => $provider,
            'providerid' => $providerid,
        );
        return $this->raasGetResponseFromRaas($url, $params);
    }

    /**
     * Unlink Account.
     *
     * @param $uid
     * @param $provider
     * @param $providerid
     * @return mixed
     */
    public function raasUnlinkAccount($uid, $provider, $providerid)
    {
        $url = RAAS_DOMAIN . "/raas/v1/account/unlink?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY;

        $params = array(
            'accountid' => $uid,
            'provider' => $provider,
            'providerid' => $providerid,
        );
        return $this->raasGetResponseFromRaas($url, $params);
    }

    /**
     * Delete User.
     *
     * @param $uid
     * @return mixed
     */
    public function raasUserDelete($uid)
    {
        $url = RAAS_DOMAIN . "/raas/v1/user/delete?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY . "&uid=" . $uid;
        return $this->raasGetResponseFromRaas($url);
    }

    /**
     * Get Custom field of raas.
     *
     * @return string
     */
    public function raasGetUserProfileByEmail($email)
    {
        $url = RAAS_DOMAIN . "/raas/v1/user?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY . "&emailid=" . $email;
        return $this->raasGetResponseFromRaas($url);

    }

    /**
     * Get Custom field of raas.
     *
     * @return string
     */
    public function raasGetCustomFields()
    {
        $url = RAAS_DOMAIN . "/api/v2/userprofile/fields?apikey=" . RAAS_API_KEY . "&apisecret=" . RAAS_SECRET_KEY;
        $response = $this->raasGetResponseFromRaas($url);
        return isset($response->CustomFields) ? $response->CustomFields : '';
    }

    /**
     * Get only Raas Profile Data.
     *
     * @param $uid
     * @return mixed
     */
    public function raasGetRaasProfile($uid)
    {
        $url = RAAS_DOMAIN . "/raas/v1/account?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY . "&accountid=" . $uid;
        $user_profile = $this->raasGetResponseFromRaas($url);
        foreach ($user_profile as $provider_profile) {
            if (isset($provider_profile->Provider) && Tools::strtolower($provider_profile->Provider) == 'raas') {
                return $provider_profile;
            }
        }
    }

    /**
     * Get all linked account.
     *
     * @param $uid
     * @return mixed
     */
    public function raasGetLinkedAccount($uid)
    {
        $url = RAAS_DOMAIN . "/raas/v1/account?appkey=" . RAAS_API_KEY . "&appsecret=" . RAAS_SECRET_KEY . "&accountid=" . $uid;
        return $this->raasGetResponseFromRaas($url);
    }

    /**
     * Get Response from API client.
     *
     * @param $validate_url
     * @param string $post
     * @param string $content_type
     * @return mixed
     */
    public function raasGetResponseFromRaas($validate_url, $post = '', $content_type = 'application/x-www-form-urlencoded')
    {

        $obj = new LoginRadius();
        return Tools::jsonDecode($obj->loginRadiusApiClient($validate_url, '', $post, $content_type));
    }
}
