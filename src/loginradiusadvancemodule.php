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

include_once(dirname(__FILE__) . '/includes/lr_functions.php');

class LoginRadiusAdvanceModule extends Module
{
    /**
     * Add required variables that are used to define module
     *
     * Constructor
     **/
    public function __construct()
    {
        $this->name = 'loginradiusadvancemodule';
        $this->tab = 'others';
        $this->version = '1.0.1';
        $this->author = 'LoginRadius';
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->module_key = '192fbb8d683dbcd8a98ec70f4faeefa8'; //Product Key //Don't change.
        parent::__construct();
        $this->displayName = $this->l('Advanced Customer Registration and Management');
        $this->description = $this->l('This module enables customer registration and management for your prestashop website.');
    }

    /**
     * Show complete extended profile data block.
     *
     * @return mixed
     */
    public function hookDisplayAdminCustomers()
    {
        Context::getContext()->controller->addCSS(__PS_BASE_URI__ . 'modules/loginradiusadvancemodule/views/css/lr-social-profile-data-settings.css');
        Context::getContext()->controller->addJS(__PS_BASE_URI__ . 'modules/loginradiusadvancemodule/views/js/social-profile-data-admin.js');
        Context::getContext()->controller->addJQueryUI('ui.tabs');

        prepare_profile_data(Tools::getValue('id_customer'));

        return $this->display(__FILE__, 'admin-block.tpl');
    }

    /**
     * Add loading image tpl file.
     *
     * @param $params
     * @return mixed
     */
    public function hookFooter($params)
    {
        Context::getContext()->controller->addCSS(__PS_BASE_URI__ . 'modules/loginradiusadvancemodule/views/css/lr_style.css');
        return $this->display(__FILE__, 'loading.tpl');
    }

    /**
     * Header hook that add script [Social share script, Social counter script, Social Interface script] at head .
     *
     * @param array $params Parameters
     * @return string LoginRadius login and sharing script
     */
    public function hookHeader($params)
    {

        $script = '';

        $loginradius_api_key = trim(Configuration::get('lr_api_key'));
        $loginradius_api_secret = trim(Configuration::get('lr_api_secret'));

        if (!empty($loginradius_api_key) && !empty($loginradius_api_secret)) {
            $script .= loginRadiusInterfaceScript();
        }

        if (Configuration::get('lr_enable_sso') == '1') {
            $script .= loginRadiusSSOScript();
            $script .= loginRadiusHookTopSSO();
        }

        if (Configuration::get('lr_enable_social_horizontal_sharing') == '1') {
            $script .= loginRadiusHorizontalShareScript();
        }

        if (Configuration::get('lr_enable_social_vertical_sharing') == '1') {
            $script .= loginRadiusVerticalShareScript();
        }

        return $script;
    }

    /**
     * home hook that showing share and counter widget at home page.
     *
     * @param array $params Parameters
     * @return string sharing and counter div
     */
    public function hookHome($params)
    {
        return loginRadiusGetSharingDiv('lr_social_hr_share_location_home', 'lr_social_vr_share_location_home');
    }

    /**
     *  Invoice hook that showing share and counter widget at Invoice page.
     *
     * @param array $params Parameters
     * @return string sharing and counter div
     */
    public function hookInvoice($params)
    {
        return loginRadiusGetSharingDiv('lr_social_hr_share_location_product', 'lr_social_vr_share_location_product');
    }

    /**
     * Cart hook that showing share and counter widget at Cart page.
     *
     * @param array $params Parameters
     * @return string sharing and counter div
     */
    public function hookShoppingCart($params)
    {
        return loginRadiusGetSharingDiv('lr_social_hr_share_location_cart', 'lr_social_vr_share_location_cart');
    }

    /**
     * Show share and counter widget at right column of product page
     *
     * @param array $params Parameters
     * @return string sharing and counter div
     */
    public function hookDisplayRightColumnProduct($params)
    {
        if (_PS_VERSION_ >= 1.6) {
            return loginRadiusGetSharingDiv('lr_social_hr_share_location_product', 'lr_social_vr_share_location_product');
        }
    }

    /**
     * Show share and counter widget at compare page
     * @param array $params Parameters
     * @return string sharing and counter div
     */
    public function hookDisplayCompareExtraInformation($params)
    {
        return loginRadiusGetSharingDiv('lr_social_hr_share_location_product', 'lr_social_vr_share_location_product');
    }

    /**
     *  Product footer hook that showing share and counter widget at product footer page.
     *
     * @param array $params Parameters
     * @return string sharing and counter div
     */
    public function hookProductFooter($params)
    {
        if (_PS_VERSION_ < 1.6) {
            $context = Context::getContext();
            $cookie = $context->cookie;
            /* Product informations */
            $product = new Product((int)Tools::getValue('id_product'), false, (int)$cookie->id_lang);
            $this->currentproduct = $product;
            return loginRadiusGetSharingDiv('lr_social_hr_share_location_product', 'lr_social_vr_share_location_product');
        }
    }

    /*
    *  Top hook that Handle login functionality.
    */
    public function hookTop()
    {

        $context = Context::getContext();
        $cookie = $context->cookie;

        include_once('includes/LoginRadiusSDK.php');
        $lr_obj = new LoginRadius();
        if (Tools::getValue('token') && !Tools::getValue('email_create')) {
            $check_curl = function_exists('curl_version');
            $loginradius_api_secret = trim(Configuration::get('lr_api_secret'));
            try {
                $access_token = $lr_obj->loginRadiusFetchAccessToken($loginradius_api_secret, $check_curl);
            } catch (LoginRadiusException $e) {

            }

            if (empty($access_token)) {
                return false;
            }
            $cookie->lr_token = $access_token;
            //Get the user profile data.
            $userprofile = $lr_obj->loginRadiusGetUserProfileData($access_token, $check_curl);
           
            if (isset($userprofile->EmailVerified)) {
                 $cookie->emailVerified = $userprofile->EmailVerified;
            }
            if (isset($userprofile->Provider)) {
                $cookie->provider = $userprofile->Provider;
            }
        }
        //check user is already logged in.
        if (Context::getContext()->customer->isLogged()) {
            //Provide account linking when uer is already logged in.
            if (isset($userprofile->ID) && !empty($userprofile->ID)) {
                loginRadiusAccountLinking($cookie, $userprofile);
            }

            //Remove account linking when user click on remove button.
            if (Tools::getValue('value') && Tools::getValue('value') == 'accountUnLink') {
                loginRadiusRemoveLinking($cookie);
            }

        } elseif (!Context::getContext()->customer->isLogged() && !Tools::getValue('email_create') && isset($userprofile->ID)) {
            //user is not logged in.
            //Retrieve token and provide login functionality.
            return loginRadiusConnect($userprofile);
        }
    }

    /**
     * customer account hook that show tpl for Social linking.
     *
     * @param array $params Parameters
     * @return string Social Linking widget
     */
    public function hookDisplayCustomerAccount($params)
    {
        $context = Context::getContext();
        $cookie = $context->cookie;
             
        $this->smarty->assign('emailVerified', $cookie->emailVerified);
        $this->smarty->assign('provider', $cookie->provider);
        
        $this->smarty->assign('in_footer', false);
        return $this->display(__FILE__, 'my-account.tpl');
    }

    /**
     * my account hook that show tpl for Social linking.
     *
     * @param array $params Parameters
     * @return string Social Linking widget
     */
    public function hookMyAccountBlock($params)
    {
        $this->smarty->assign('in_footer', true);
        return $this->display(__FILE__, 'my-account.tpl');
    }

    /**
     * Save customer when action submit by admin.
     *
     * @param $value
     * @param $return
     */
    public function hookActionAdminSaveAfter($value, $return)
    {

        $customer = new Customer((int)Tools::getValue('id_customer'));

        loginRadiusSaveRaaSCustomer($customer);
    }

    /**
     * Delete customer.
     *
     * @param $value
     */
    public function hookActionAdminDeleteBefore($value)
    {

        loginRadiusDeleteRaaSCustomer(Tools::getValue('id_customer'));
    }

    /**
     * Bulk customer delete.
     *
     * @param $value
     */
    public function hookActionAdminBulKDeleteBefore($value)
    {

        loginRadiusBulkDeleteRaaSCustomer(Tools::getValue('customerBox'));
    }

    /**
     * Update status in bulk.
     *
     * @param $value
     */
    public function hookActionAdminBulkEnableSelectionAfter($value)
    {
        loginRadiusBulkUpdateStatusRaasCustomer(Tools::getValue('customerBox'));
    }

    /**
     * Change status of customer.
     *
     * @param $value
     * @param $return
     */
    public function hookActionAdminStatusAfter($value, $return)
    {
        loginRadiusUpdateStatusRaasCustomer((int)Tools::getValue('id_customer'));

    }

    /**
     * Modify page of customer identity.
     *
     * @param string $value
     * @return string
     */
    public function hookDisplayCustomerIdentityForm($value = '')
    {
        $context = Context::getContext();
        $cookie = $context->cookie;
        $smarty = $context->smarty;

        if (count($smarty->tpl_vars['errors']->value) == 0 && Tools::isSubmit('submitIdentity')) {

            $customer = new Customer($cookie->id_customer);

            loginRadiusSaveRaaSCustomer($customer);
        }
        $passwd = loginRadiusGetRandomString();
        $cookie->passwd = Tools::encrypt($passwd);

        return "<script type=text/javascript>
         $('#old_passwd').val('" . $passwd . "');
         $('#old_passwd').parent().hide();
         $('#passwd').parent().hide();
         $('#email').attr('readonly', true);
         $('#confirmation').parent().hide();
		</script>";
    }

    /**
     * Install hook that  register hook which used by social Login.
     *
     * @return boolean true when module hooks and tables created successfully.
     */
    public function install()
    {

        if (!parent::install()
            || !$this->registerHook('DisplayOverrideTemplate')
            || !$this->registerHook('displayAdminCustomers')
            || !$this->registerHook('actionAdminDeleteBefore')
            || !$this->registerHook('displayCustomerIdentityForm')
            || !$this->registerHook('actionAdminBulKDeleteBefore')
            || !$this->registerHook('actionAdminBulkEnableSelectionAfter')
            || !$this->registerHook('actionAdminStatusAfter')
            || !$this->registerHook('actionAdminSaveAfter')
            || !$this->registerHook('top')
            || !$this->registerHook('Header')
            || !$this->registerHook('Footer')
            || !$this->registerHook('Home')
            || !$this->registerHook('Invoice')
            || !$this->registerHook('ShoppingCart')
            || !$this->registerHook('productfooter')
            || !$this->registerHook('displaycustomerAccount')
            || !$this->registerHook('displayRightColumnProduct')
            || !$this->registerHook('displayCompareExtraInformation')
            || !$this->registerHook('myAccountBlock')
        ) {
            return false;
        }
        //create the social Login table.
        loginRadiuscreateDatabaseLrTable();
        return true;
    }

    /**
     * Add own tpl files.
     *
     * @param $params
     * @return bool|string
     */
    public function hookDisplayOverrideTemplate($params)
    {
        $loginradius_api_key = trim(Configuration::get('lr_api_key'));
        $loginradius_api_secret = trim(Configuration::get('lr_api_secret'));

        if (!empty($loginradius_api_key) && !empty($loginradius_api_secret)) {
            $controllerName = get_class($params['controller']);
            $tplname = $controllerName;
            if ($controllerName == 'AuthController') {
                $tplname = 'authentication';
                $smarty = Context::getContext()->smarty;
                $smarty->assign('message', trim(Configuration::get('lr_popup_title')));
                $smarty->assign('interface_title', trim(Configuration::get('lr_title')));
            } elseif ($controllerName == 'PasswordController') {
                $tplname = 'password';
            }
            $tpl = $this->local_path . 'views/templates/hook/' . $tplname . '.tpl';

            if (file_exists($tpl)) {

                return $tpl;
            }

            return false;
        }
    }

    /**
     * Login Radius Admin UI.
     *
     * @return string Admin UI Content
     */
    public function getContent()
    {
        include_once(dirname(__FILE__) . '/includes/lr_admin.php');
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $output .= loginRadiusSaveModuleSettings();
        }
        return $output . loginRadiusGetAdminContent();
    }

    /**
     * delete social login table form database.
     *
     * @return boolean true when module uninstall successfully.
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            Db::getInstance()
                ->Execute('DELETE FROM `' . _DB_PREFIX_ . 'sociallogin`');
        }
        parent::uninstall();
        return true;
    }

    /**
     * Override the traditional template.
     *
     * @return mixed
     */
    public function getOverrideTemplate()
    {
        return Hook::exec('DisplayOverrideTemplate', array('controller' => $this));
    }
}
