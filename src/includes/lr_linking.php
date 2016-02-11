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


include_once('LoginRadiusRaasSDK.php');
/**
 * Provide Social linking.
 *
 * @param object $arrdata Contain cookie information
 * @param object $user_profile User profile data that got from social network
 */
function loginRadiusAccountLinking($cookie, $user_profile)
{
    $module = new LoginRadiusAdvanceModule();
    $cookie->lrmessage = '';

    //check User is authenticate and user data is not empty.
    if (!empty($user_profile) && isset($user_profile->ID) && $user_profile->ID != '') {
        //Check Social ID and  provider is in database.
        $tbl = pSQL(_DB_PREFIX_ . 'sociallogin');

        $raas_uid = loginRadiusGetRassUid($cookie->id_customer);
        $raas_sdk = new LoginradiusRaasSDK();
        if (!empty($raas_uid)) {
            $exist_account = Db::getInstance()->getValue('SELECT c.id_customer FROM ' . pSQL(_DB_PREFIX_ . 'customer') . ' AS c INNER JOIN ' . $tbl . '
			AS sl ON sl.id_customer=c.id_customer WHERE sl.provider_id= "' . pSQL($user_profile->ID) . '"');
            if (!$exist_account) {
                try {
                    $result = $raas_sdk->raasLinkAccount($raas_uid, $user_profile->Provider, $user_profile->ID);
                    if (isset($result->isPosted) && $result->isPosted) {
                        $query = "INSERT into $tbl (`id_customer`,`provider_id`,`Provider_name`)
				values ('" . pSQL($cookie->id_customer) . "','" . pSQL($user_profile->ID) . "' , '" . pSQL($user_profile->Provider) . "') ";
                        Db::getInstance()->Execute($query);
                        $cookie->lrmessage = $module->l("Your account successfully mapped with this account.", 'lr_linking');
                    }
                } catch (LoginRadiusException $e) {
                    $retrieved_msg = $e->getMessage();
                    $msg = isset($retrieved_msg) ? $retrieved_msg : 'You cannot link this account as it is already linked with another account';

                    $cookie->lrmessage = $module->l($msg, 'lr_linking');
                }
            } else {
                $cookie->lrmessage = $module->l('You cannot link this account as it is already linked with another account', 'lr_linking');

            }
        }
    }
}

/**
 * Remove Social Linking
 *
 * @param object $cookie Contain cookie information
 * @param string $value Social network ID
 */
function loginRadiusRemoveLinking($cookie)
{

    $provider = Tools::getValue('provider');
    $providerId = Tools::getValue('providerId');
    $module = new LoginRadiusAdvanceModule();
    $raas_uid = loginRadiusGetRassUid($cookie->id_customer);
    $raas_sdk = new LoginradiusRaasSDK();
    if (!empty($raas_uid) && !empty($provider) && !empty($providerId)) {
        try {
            $result = $raas_sdk->raasUnlinkAccount($raas_uid, $provider, $providerId);
            if (isset($result->isPosted) && $result->isPosted) {
                $deletequery = 'delete from ' . pSQL(_DB_PREFIX_ . 'sociallogin') . " where provider_id ='" . pSQL($providerId) . "'";
                Db::getInstance()->Execute($deletequery);
                $cookie->lrmessage = $module->l('Account Unlinked Successfully', 'lr_linking');
            }
        } catch (LoginRadiusException $e) {
            $retrieved_msg = $e->getMessage();
            $msg = isset($retrieved_msg) ? $retrieved_msg : 'Account has not unlinked';

            $cookie->lrmessage = $module->l($msg, 'lr_linking');
        }


    }
}

/**
 * Add Linked account to customer account
 *
 * @param type $num Customer account ID
 * @param type $id Social network ID
 * @param type $provider Social Network
 */
function loginRadiusAddLinkedAccount($num, $id, $provider)
{
    $module = new LoginRadiusAdvanceModule();
    $context = Context::getContext();
    $tbl = pSQL(_DB_PREFIX_ . 'sociallogin');
//check only social id is in database.
    $check_user_id = Db::getInstance()->ExecuteS('SELECT c.id_customer FROM ' . pSQL(_DB_PREFIX_ . 'customer') . ' AS c INNER JOIN ' . $tbl . '
			AS sl ON sl.id_customer=c.id_customer WHERE sl.provider_id= "' . pSQL($id) . '"');

    if (empty($check_user_id['0']['id_customer'])) {
        Db::getInstance()->Execute('DELETE FROM ' . $tbl . "  WHERE provider_id='" . pSQL($id) . "'");
    }

    $lr_id = Db::getInstance()->ExecuteS('SELECT provider_id FROM ' . $tbl . "  WHERE provider_id= '" . pSQL($id) . "'");

//Present then show warning message.
    if (!empty($lr_id['0']['provider_id'])) {
        $context->cookie->lrmessage = $module->l('Account cannot be mapped as it already exists in database', 'sociallogin_linking');
    } else {
        $query = "INSERT into $tbl (`id_customer`,`provider_id`,`Provider_name`)
				values ('".pSQL($num)."','" . pSQL($id) . "' , '" . pSQL($provider) . "') ";
        Db::getInstance()->Execute($query);
        $context->cookie->lrmessage = $module->l('Your account is successfully mapped', 'sociallogin_linking');
    }
}
