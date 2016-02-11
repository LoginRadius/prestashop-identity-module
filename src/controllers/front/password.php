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

class LoginRadiusAdvanceModulePasswordModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    /**
     * @see FrontController::init()
     */
    public function init()
    {
        parent::init();

        require_once($this->module->getLocalPath() . 'loginradiusadvancemodule.php');
        require_once($this->module->getLocalPath() . 'includes/lr_functions.php');
        require_once($this->module->getLocalPath() . 'includes/LoginRadiusRaasSDK.php');
    }

    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();

        if (!Context::getContext()->customer->isLogged()) {
            Tools::redirect('index.php?controller=authentication&redirect=module&module=loginradiusadvancemodule&action=password');
        }
        $raas_sdk = new LoginRadiusRaasSDK();
        if (Context::getContext()->customer->id) {
            $raas_uid = loginRadiusGetRassUid(Context::getContext()->customer->id);
            if (Tools::getValue('emailid') && Tools::getValue('password') && !empty($raas_uid)) {


                $params = array(
                    'accountid' => $raas_uid,
                    'password' => Tools::getValue('password'),
                    'emailid' => Tools::getValue('emailid')
                );
                try {
                    $result = $raas_sdk->createRaasProfile($params);
                    if (isset($result->isPosted) && $result->isPosted) {
                        $message = $this->module->l('Password set successfully.');
                    }
                } catch (LoginRadiusException $e) {
                    $retrieved_msg = $e->getMessage();
                    $msg = isset($retrieved_msg) ? $retrieved_msg : 'Password is not set';

                    $message = $this->module->l($msg);
                }
            } else if (Tools::getValue('newpassword') && !empty($raas_uid)) {

                try {
                    $result = $raas_sdk->raasUpdatePassword(array(
                        'newpassword' => Tools::getValue('newpassword'),
                        'oldpassword' => Tools::getValue('oldpassword')
                    ), $raas_uid);
                    if (isset($result->isPosted) && $result->isPosted) {
                        $message = $this->module->l('Password changed successfully.');
                    }
                } catch (LoginRadiusException $e) {
                    $retrieved_msg = $e->getErrorResponse()->description;
                    $msg = isset($retrieved_msg) ? $retrieved_msg : 'Password is not changed';

                    $message = $this->module->l($msg);
                }
            }
            if (isset($message) && $message != '') {
                $this->context->smarty->assign('socialloginlrpasswordmessage', $message);
                $this->context->cookie->lrmessage = '';
            } else {
                $this->context->smarty->assign('socialloginlrpasswordmessage', '');
            }
            $this->setTemplate('lr-password.tpl');
        }
    }
}
