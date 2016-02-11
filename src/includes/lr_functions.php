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
 * Include required files
 */
include_once(dirname(__FILE__) . '/lr_linking.php');
include_once(dirname(__FILE__) . '/lr_sharing.php');
include_once(dirname(__FILE__) . '/LoginRadiusRaasSDK.php');
include_once(dirname(__FILE__) . '/lr_sso.php');
include_once(dirname(__FILE__) . '/lr_social_profile_data.php');

/**
 * Show Error Message.
 *
 * @param type $msg message to shown on popup
 * @param type $social_id Social network ID
 */
function loginRadiusPopupVerify($msg, $social_id = '')
{
    $module = new LoginRadiusAdvanceModule();
    $context = Context::getContext();
    $context->controller->addCSS(__PS_BASE_URI__ . 'modules/loginradiusadvancemodule/views/css/lr_style.css');
    $context->smarty->assign('social_id', $social_id);
    $context->smarty->assign('message', $msg);
    return ($module->display(__PS_BASE_URI__ . 'modules/loginradiusadvancemodule', 'htmlpopup-verify.tpl'));
}

/**
 *  Save Raas uid.
 *
 * @param $cid
 * @param $uid
 */
function loginRadiusSaveRaasUId($cid, $uid)
{
    $sql = 'UPDATE ' . _DB_PREFIX_ . 'customer
      SET `lr_raas_uid` = "' . pSQL($uid) . '"
      WHERE `id_customer` = ' . (int)$cid;
    Db::getInstance()->Execute($sql);
}

/**
 * Get uid of Raas
 * @param $cid
 * @return mixed
 */
function loginRadiusGetRassUid($cid)
{

    return Db::getInstance()->getValue('SELECT lr_raas_uid FROM `' . _DB_PREFIX_ . 'customer` WHERE `id_customer` = ' . (int)$cid);

}

/**
 * Get Username from user's social network profile
 *
 * @param object $user_profile User profile object that got from social network
 * @return string Generated username
 */
function loginRadiusGetUserName($user_profile)
{
    if (!empty($user_profile->FirstName) && !empty($user_profile->LastName)) {
        $username = $user_profile->FirstName . ' ' . $user_profile->LastName;
    } elseif (!empty($user_profile->FullName)) {
        $username = $user_profile->FullName;
    } elseif (!empty($user_profile->ProfileName)) {
        $username = $user_profile->ProfileName;
    } elseif (!empty($user_profile->NickName)) {
        $username = $user_profile->NickName;
    } elseif (!empty($user_profile->Email)) {
        $user_name = explode('@', $user_profile->Email);
        $username = $user_name[0];
    } else {
        $username = $user_profile->ID;
    }

    return $username;
}

/**
 * Get proper formatted date of birth from user profile
 *
 * @param string $dob date of birth that got from user's social network profile
 * @return string get formatted date of birth
 */
function loginRadiusGetDateOfBirth($dob)
{
    if ($dob) {
        $dob_arr =  array();
        if (strpos($dob, '/') == true) {
            $dob_arr = explode('/', $dob);
        } elseif (strpos($dob, '-') == true) {
            $dob_arr = explode('-', $dob);
        }
        $dob = $dob_arr[2] . '-' . $dob_arr[0] . '-' . $dob_arr[1];

    }

    return (!empty($dob) && Validate::isBirthDate($dob) ? $dob : '');
}

/**
 * Check user exist and then save the new user.
 *
 * @param $user_profile
 * @return bool|string
 */
function loginRadiusExistUserandSave($user_profile)
{
    $module = new LoginRadiusAdvanceModule();
    //check AccountId exist
    $user_id_exist = Db::getInstance()->getRow('SELECT * FROM ' . pSQL(_DB_PREFIX_ . 'customer') . ' as c
				WHERE c.lr_raas_uid="' . pSQL($user_profile->Uid) . '"');
    if (!$user_id_exist) {
        //Check Social provider id is already exist.
        $user_id_exist = Db::getInstance()->getRow('SELECT * FROM ' . pSQL(_DB_PREFIX_ . 'sociallogin') . ' as sl INNER JOIN ' . pSQL(_DB_PREFIX_ . 'customer') . " as c
		ON c.id_customer=sl.id_customer WHERE sl.provider_id='" . pSQL($user_profile->ID) . "'");
        if (!$user_id_exist) {
            if (!empty($user_profile->Email)) {
                // check email address is exist in database if email is retrieved from Social network.
                $user_id_exist = Db::getInstance()->getRow('SELECT * FROM ' . pSQL(_DB_PREFIX_ . 'customer') . ' as c
				WHERE c.email="' . pSQL($user_profile->Email) . '"');
            }
        }
    }
    if ($user_id_exist) {
        //if user is blocked
        if ($user_id_exist['active'] === 0) {
            $msg = $module->l('User has been disabled or blocked.', 'sociallogin_user_functions');
            return loginRadiusPopupVerify($msg);
        } elseif ($user_id_exist['deleted'] == 1) {
            $msg = $module->l('Authentication failed.', 'sociallogin_user_functions');
            $script = '';
            if (Configuration::get('lr_enable_sso') == '1') {
                $script .= '
<script type="text/javascript">
					jQuery(document).ready(function () {
      if(window.LoginRadiusSSO){

     lrsso_logout();
          }
});
</script>';
            }
            return $script . loginRadiusPopupVerify($msg);
        }
        $tbl = pSQL(_DB_PREFIX_ . 'sociallogin');
        Db::getInstance()->Execute("INSERT IGNORE into $tbl (`id_customer`,`provider_id`,`Provider_name`) values ('" . pSQL($user_id_exist['id_customer']) . "','" . pSQL($user_profile->ID) . "' , '" . pSQL($user_profile->Provider) . "')");
        $customer = new Customer((int)$user_id_exist['id_customer']);
        if (Configuration::get('lr_update_user_profile') == 1) {
            loginRadiusUpdateUserProfileData((int)$user_id_exist['id_customer'], $user_profile);
            $customer = new Customer((int)$user_id_exist['id_customer']);
        }

        if (loginRadiusGetRassUid($customer->id) == '') {
            loginRadiusSaveRaasUId($customer->id, $user_profile->Uid);
        }
        //login User.
        loginRadiusUpdateContext($customer, $user_profile->ID);
    }
    //Store user data into database and provide login functionality.
    return loginRadiusStoreAndLogin($user_profile);
}

/**
 * Social Login Interface Script Code.
 *
 * @return string Get script to show interface.
 */
function loginRadiusInterfaceScript()
{
    
    $raasOptionData ='';
    
    if (Configuration::get('lr_term_condition') !='') {
         $raasOptionData  .='raasoption.termsAndConditionHtml="'. Configuration::get('lr_term_condition').'";';
    }
    if (Configuration::get('lr_email_verify_template') !='') {
         
         $raasOptionData  .='raasoption.emailVerificationTemplate="'. Configuration::get('lr_email_verify_template').'";';
    }
    if (Configuration::get('lr_forgot_pass_template') !='') {
         
         $raasOptionData  .='raasoption.forgotPasswordTemplate="'. Configuration::get('lr_forgot_pass_template').'";';
    }
    if (Configuration::get('lr_recaptcha') !='') {
         
         $raasOptionData  .='raasoption.V2RecaptchaSiteKey="'. Configuration::get('lr_recaptcha').'";';
    }

    if (Configuration::get('lr_render_form_delay') !='') {
            $raasOptionData  .='raasoption.formRenderDelay='. Configuration::get('lr_render_form_delay').';';
    }
     
            
    if (Configuration::get('lr_email_verify_mail')==0 && Configuration::get('lr_prompt_password') == 1) {
            $raasOptionData  .='raasoption.promptPasswordOnSocialLogin=true;';
           
    }if (Configuration::get('lr_email_verify_mail')==0 && Configuration::get('lr_email_verification') == 1) {
             $raasOptionData  .='raasoption.enableLoginOnEmailVerification=true;';
    } if (Configuration::get('lr_email_verify_mail')==0 && Configuration::get('lr_ask_email') == 1) {
           $raasOptionData  .='raasoption.askEmailAlwaysForUnverified=true;';
    }if (Configuration::get('lr_email_verify_mail')==0 && Configuration::get('lr_enable_username') == 1) {
           $raasOptionData  .='raasoption.enableUserName=true;';
    }
    if (Configuration::get('lr_email_verify_mail')==1 && Configuration::get('lr_email_verification') == 1) {
             $raasOptionData  .='raasoption.enableLoginOnEmailVerification=true;';
    } if (Configuration::get('lr_email_verify_mail')==1 && Configuration::get('lr_ask_email') == 1) {
           $raasOptionData  .='raasoption.askEmailAlwaysForUnverified=true;';
    }

     
    if (Configuration::get('lr_email_verify_mail') == 2) {
        $raasOptionData  .='raasoption.DisabledEmailVerification=true;';
    } else if (Configuration::get('lr_email_verify_mail') == 1) {
         $raasOptionData  .='raasoption.OptionalEmailVerification=true;';
    }
   
     
    if ((Configuration::get('lr_min_password_length')!='') && (Configuration::get('lr_max_password_length')!='')) {
         $passlen = '{min:'.Configuration::get('lr_min_password_length').',max:'.Configuration::get('lr_max_password_length').'}';
         $raasOptionData  .='raasoption.passwordlength='. $passlen.';';
    }
     
    $data = Tools::jsonDecode(Configuration::get('lr_add_raas_option'));
     
    $loginradius_apikey = trim(Configuration::get('lr_api_key'));
    Context::getContext()->controller->addJQueryUI('ui.datepicker');
    $raasScript=  '<script src="//hub.loginradius.com/include/js/LoginRadius.js"></script>
    
<link rel="stylesheet" href="' . __PS_BASE_URI__ . 'modules/loginradiusadvancemodule/views/css/lr_raas.css" />
<link rel="stylesheet" href="' . __PS_BASE_URI__ . 'modules/loginradiusadvancemodule/views/css/lr_loading.css" />
<script src="//cdn.loginradius.com/hub/prod/js/LoginRadiusRaaS.js"></script>
  <script src="' . __PS_BASE_URI__ . 'modules/loginradiusadvancemodule/views/js/LoginRadiusFrontEnd.js"></script>

<script type="text/javascript">
 //initialize raas options
    var raasoption = {};
    var LocalDomain = window.location;
raasoption.apikey = "' . $loginradius_apikey . '";
raasoption.inFormvalidationMessage = '.((Configuration::get('lr_form_validation')== '1')? "true" : "false").';
raasoption.V2Recaptcha = true;
raasoption.templatename = "loginradiuscustom_tmpl";
raasoption.hashTemplate = true;
raasoption.appName = "' . Configuration::get('lr_site_name') . '";
raasoption.emailVerificationUrl = "' . Context::getContext()->link->getPageLink('authentication', true) . '";
raasoption.forgotPasswordUrl = "' . Context::getContext()->link->getPageLink('authentication', true) . '";

'.$raasOptionData.'';
    
    if (is_object($data)) {
       
        foreach ($data as $key => $value) {
            
             $raasScript .= 'raasoption.'.$key.'=';
           
           
            if (is_object($value) || is_array($value)) {
                $raasScript .=  Tools::jsonEncode($value).';';
            } else {
                   $raasScript .= str_replace(";", "", $value).';';
            }
        }
        
    
    } elseif (Configuration::get('lr_add_raas_option')!='') {
        
        $raasScript .= str_replace(";", "", Configuration::get('lr_add_raas_option')).';';
    }
    
  
    $raasScript.= 'jQuery(document).ready(function () {
initializeResetPasswordRaasForm(raasoption);
});

</script>';
    return $raasScript;
}

/**
 * Get Redirection url after user login
 *
 * @return string get redirection url
 */
function loginRadiusRedirectAddUrl()
{
    $redirect = '';
    $loc = Configuration::get('lr_redirect');

    if ($loc == 'profile') {
        $redirect = 'my-account.php';
    } elseif ($loc == 'url') {
        $custom_url = Configuration::get('lr_redirect_add_url');
        $redirect = !empty($custom_url) ? $custom_url : 'my-account.php';
    } else {
        if (Tools::getValue('back')) {
            if (_PS_VERSION_ >= 1.6) {
                $loc = $_SERVER['REQUEST_URI'];
                $redirect_location = explode('back=', $loc);
                $redirect = urldecode($redirect_location['1']);
            } else {
                $redirect = urldecode(Tools::getValue('back'));
            }
        } elseif (empty($redirect)) {
            $http = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'Off' && !empty($_SERVER['HTTPS'])) ? 'https://' : 'http://');
            $redirect = urldecode($http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }


    return $redirect;
}

/**
 * Update the logged in user data in context.
 *
 * @param object $customer Contain logged in customer information
 * @param string $social_id Social network id through which customer logged in.
 */
function loginRadiusUpdateContext($customer, $social_id)
{
    $context = Context::getContext();
    $cookie = $context->cookie;
    $cookie->id_customer = $customer->id;
    $cookie->customer_lastname = $customer->lastname;
    $cookie->customer_firstname = $customer->firstname;
    $cookie->logged = 1;
    $cookie->passwd = $customer->passwd;
    $cookie->email = $customer->email;
    $cookie->loginradius_id = $social_id;
    $cookie->lr_login = 'true';

    if ((empty($cookie->id_cart) || Cart::getNbProducts($cookie->id_cart) == 0)) {
        $cookie->id_cart = (int)Cart::lastNoneOrderedCart($cookie->id_customer);
    }
    // OPC module compatibility
    $cart = $context->cart;
    $cart->id_address_delivery = 0;
    $cart->id_address_invoice = 0;
    $cart->update();
    $cookie->id_compare = isset($cookie->id_compare) ? $cookie->id_compare : CompareProduct::getIdCompareByIdCustomer($cookie->id_customer);
    Hook::exec('authentication');
    $redirect = loginRadiusRedirectAddUrl();
    Tools::redirectLink($redirect);
}

/**
 * Create random string.
 *
 * @return string  Random string
 */
function loginRadiusGetRandomString()
{
    $char = '';

    for ($i = 0; $i < 20; $i++) {
        $char .= rand(0, 9);
    }

    return ($char);
}

/**
 * Remove special character from name.
 *
 * @param string $field Name from which remove special charcter
 * @return string removed special character name
 */
function LoginRadiusRemoveSpecialCharacter($field)
{
    $in_str = str_replace(array('<', '>', '&', '{', '}', '*', '/', '(', '[', ']', '@', '!', ')', '&', '*', '#', '$', '%', '^', '|', '?', '+', '=',
        '"', ','), array(''), $field);
    $cur_encoding = mb_detect_encoding($in_str);

    if ($cur_encoding == 'UTF-8' && mb_check_encoding($in_str, 'UTF-8')) {
        $name = $in_str;
    } else {
        $name = utf8_encode($in_str);
    }

    if (!Validate::isName($name)) {
        $len = Tools::strlen($name);
        $return_val = '';

        for ($i = 0; $i < $len; $i++) {
            if (ctype_alpha($name[$i])) {
                $return_val .= $name[$i];
            }
        }

        $name = $return_val;

        if (empty($name)) {
            $letters = range('a', 'z');

            for ($i = 0; $i < 5; $i++) {
                $name .= $letters[rand(0, 26)];
            }
        }
    }
    $name = Tools::substr($name, 0, 32);
    return $name;
}


/**
 * Map user profule data from LoginRadius profile data according to Prestashop.
 *
 * @param object $user_profile Conatin user profile object
 * @return object filtered data that is required to prestashop database.
 */
function loginRadiusMappingProfileData($user_profile)
{
    $user_profile->lr_Emails = $user_profile->Email;
    $user_profile->Email = (count($user_profile->Email) > 0 ? $user_profile->Email[0]->Value : '');

    if (empty($user_profile->City) || $user_profile->City == 'unknown') {
        $user_profile->City = (!empty($user_profile->LocalCity) && $user_profile->LocalCity != 'unknown' ? $user_profile->LocalCity : '');
    }

    if (empty($user_profile->Country)) {
        $user_profile->Country = (!empty($user_profile->LocalCountry) ? $user_profile->LocalCountry : '');
    }
    $user_profile->PhoneNumber = (!empty($user_profile->PhoneNumbers['0']->PhoneNumber) ? $user_profile->PhoneNumbers['0']->PhoneNumber : '');
    $user_profile->Address = (!empty($user_profile->Addresses['0']->Address1) ? $user_profile->Addresses['0']->Address1 : '');
    $user_profile->Zipcode = (!empty($user_profile->Addresses['0']->PostalCode) ? $user_profile->Addresses['0']->PostalCode : '');
    return $user_profile;
}

/**
 * Connect Social login Interface and Handle loginradius token.
 * @return sring html content
 */
function loginRadiusConnect($user_profile)
{
    include_once('LoginRadiusSDK.php');
    //Get the user_profile of authenticate user.
    //If user is not logged in and user is authenticated then handle login functionality.
    if (isset($user_profile->ID) && $user_profile->ID != '' && !Context:: getContext()->customer->isLogged()) {
        $user_profile = loginRadiusMappingProfileData($user_profile);
        return loginRadiusExistUserandSave($user_profile);
    }
}

/**
 * Update the user profile data.
 *
 * @param int $user_id User ID
 * @param object $user_profile user profile data
 */
function loginRadiusUpdateUserProfileData($user_id, $user_profile)
{
    $customer = new Customer((int)$user_id);
    $user_profile->FirstName = LoginRadiusRemoveSpecialCharacter(!empty($user_profile->FirstName) ? pSQL($user_profile->FirstName) : '');
    $user_profile->LastName = LoginRadiusRemoveSpecialCharacter(!empty($user_profile->LastName) ? pSQL($user_profile->LastName) : '');
    if (isset($user_profile->FirstName) && !empty($user_profile->FirstName)) {
        $customer->firstname = pSQL($user_profile->FirstName);
    }

    if (isset($user_profile->LastName) && !empty($user_profile->LastName)) {
        $customer->lastname = pSQL($user_profile->LastName);
    }

    if (isset($user_profile->Gender) && !empty($user_profile->Gender)) {
        $gender = ((!empty($user_profile->Gender)
            && (strpos($user_profile->Gender, 'f') !== false
                || (trim($user_profile->Gender) == 'F'))) ? 2 : 1);
        $customer->id_gender = pSQL($gender);
    }

    if (!empty($user_profile->BirthDate)) {
        if (strpos($user_profile->BirthDate, '/') == true) {
            $dob_arr = explode('/', $user_profile->BirthDate);
        } elseif (strpos($user_profile->BirthDate, '-') == true) {
            $dob_arr = explode('-', $user_profile->BirthDate);
        }
        $dob = $dob_arr[2] . '-' . $dob_arr[0] . '-' . $dob_arr[1];
        $date_of_birth = (!empty($dob) && Validate::isBirthDate($dob) ? $dob : '');
        $customer->birthday = pSQL($date_of_birth);

    }
    $customer->update();
    loginRadiusSaveExtendedUserProfileData($user_profile, $customer);
}



/**
 * When user have Email address then check login functionaity
 *
 * @param object $user_profile_data user profile data
 * @param string $rand tandom number
 * @return boolean false when customer is not added into database.
 */
function loginRadiusStoreAndLogin($user_profile_data)
{
    $email = $user_profile_data->Email;
    $username = loginRadiusGetUserName($user_profile_data);
    $password = Tools::passwdGen();
    $optin = $newsletter = '0';
    $gender = ((!empty($user_profile_data->Gender)
        && (strpos($user_profile_data->Gender, 'f') !== false
            || (trim($user_profile_data->Gender) == 'F'))) ? 2 : 1);
    $required_field_check = Db::getInstance()->ExecuteS('SELECT field_name FROM  ' . pSQL(_DB_PREFIX_) . 'required_field');

    foreach ($required_field_check as $item) {
        if ($item['field_name'] == 'newsletter') {
            $newsletter = '1';
        }
        if ($item['field_name'] == 'optin') {
            $optin = '1';
        }
    }

    $customer = new Customer();
    $customer->firstname = LoginRadiusRemoveSpecialCharacter(!empty($user_profile_data->FirstName) ? pSQL($user_profile_data->FirstName) : pSQL($username));
    $customer->lastname = LoginRadiusRemoveSpecialCharacter(!empty($user_profile_data->LastName) ? pSQL($user_profile_data->LastName) : pSQL($username));
    $customer->email = $email;
    $customer->id_gender = $gender;
    $customer->birthday = isset($user_profile_data->BirthDate) && !empty($user_profile_data->BirthDate) ? loginRadiusGetDateOfBirth($user_profile_data->BirthDate) : '';
    $customer->active = true;
    $customer->deleted = false;
    $customer->is_guest = false;
    $customer->passwd = Tools::encrypt($password);
    $customer->newsletter = $newsletter;
    $customer->optin = $optin;

    if ($customer->add()) {
        loginRadiusSaveRaasUId($customer->id, $user_profile_data->Uid);
        $tbl = pSQL(_DB_PREFIX_ . 'sociallogin');
        Db::getInstance()->Execute("DELETE FROM $tbl WHERE provider_id='" . pSQL($user_profile_data->ID) . "'");
        $query = "INSERT into $tbl (`id_customer`,`provider_id`,`Provider_name`)
	values ('" . (int) $customer->id . "','" . pSQL($user_profile_data->ID) . "','" . pSQL($user_profile_data->Provider) . "') ";
        Db::getInstance()->Execute($query);
        loginRadiusSaveExtendedUserProfileData($user_profile_data, $customer);
        loginRadiusUpdateContext($customer, $user_profile_data->ID);

    }

    //error
    return false;
}



/**
 * save the user data in cookie.
 *
 * @param object $user_profile_data User  Profile data
 */
function loginRadiusStoreInCookie($user_profile_data)
{
    $context = Context::getContext();
    $cookie = $context->cookie;
    $cookie->login_radius_data = '';
    $user_profile_data = (object)array_filter((array)$user_profile_data);
    $cookie->login_radius_data = Tools::jsonEncode($user_profile_data);
}

/**
 * Delete user from Raas in Bulk
 * @param $customerBox
 */
function loginRadiusBulkDeleteRaaSCustomer($customerBox)
{
    if (is_array($customerBox)) {
        foreach ($customerBox as $key) {
            loginRadiusDeleteRaaSCustomer($key);
        }
    }
}

/**
 * Delete Raas user
 * @param $cid
 */
function loginRadiusDeleteRaaSCustomer($cid)
{
    if ($uid = loginRadiusGetRassUid($cid)) {
        $raas_sdk = new LoginradiusRaasSDK();
        try {
            $raas_sdk->raasUserDelete($uid);

        } catch (LoginRadiusException $e) {

        }
    }
}

/**
 * Update user in bulk
 *
 * @param $customerBox
 */
function loginRadiusBulkUpdateStatusRaasCustomer($customerBox)
{
    if (is_array($customerBox)) {
        foreach ($customerBox as $key) {

            loginRadiusUpdateStatusRaasCustomer($key);
        }
    }
}

/**
 * Change status of user.
 *
 * @param $key
 */
function loginRadiusUpdateStatusRaasCustomer($key)
{

    if ($uid = loginRadiusGetRassUid($key)) {
        $customer = new Customer($key);
        $raas_sdk = new LoginradiusRaasSDK();
        $action = 'unblock';
        if ($customer->active == "0") {
            $action = 'block';
        }
        if ($action == 'unblock') {
            try {
                $raas_sdk->raasBlockUser(array('isblock' => 'false'), $uid);

            } catch (LoginRadiusException $e) {

            }
        } else {
            try {
                $raas_sdk->raasBlockUser(array('isblock' => 'true'), $uid);

            } catch (LoginRadiusException $e) {

            }
        }
    }
}

/**
 * Save linked account to ps database.
 *
 * @param $id
 * @param $provider_id
 * @param $provider
 * @param string $uid
 */
function loginRadiusSaveLinkedAccount($id, $provider_id, $provider, $uid = '')
{
    $tbl = pSQL(_DB_PREFIX_ . 'sociallogin');
    $query = "INSERT into $tbl (`id_customer`,`provider_id`,`Provider_name`,`verified`,`rand`)
						values ('" . (int)$id . "','" . pSQL($provider_id) . "' , '" . pSQL($provider) . "','1','') ";
    Db::getInstance()->Execute($query);
    if (!empty($uid)) {
        loginRadiusSaveRaasUId($id, $uid);
    }
}

/**
 * Save Raas user.
 *
 * @param $customer
 */
function loginRadiusSaveRaaSCustomer($customer)
{
    $password = Tools::getValue('passwd');
    $gender = Tools::getValue('id_gender');
    $gender = (isset($gender) && $gender == 1 ? 'M' : 'F');
    if (Tools::getValue('months') && Tools::getValue('days') && Tools::getValue('years')) {
        $birthdate = Tools::getValue('days') . '-' . Tools::getValue('months') . '-' . Tools::getValue('years');
        $birthdate = date("m-d-Y", strtotime($birthdate));


    }

    $raas_sdk = new LoginradiusRaasSDK();

    if ($uid = loginRadiusGetRassUid($customer->id)) {

        $provider_id = Db::getInstance()->getValue('SELECT provider_id FROM ' . _DB_PREFIX_ . 'sociallogin  WHERE id_customer= ' . pSQL($customer->id) . ' AND Provider_name= "RAAS"');
        if (empty($provider_id)) {

            $provider_id = Db::getInstance()->getValue('SELECT provider_id FROM ' . _DB_PREFIX_ . 'sociallogin  WHERE id_customer= ' . pSQL($customer->id));
            if (empty($provider_id)) {
                $provider_id = isset(Context::getContext()->cookie->loginradius_id) ? Context::getContext()->cookie->loginradius_id : '';
            }

        }
    }

    if (empty($provider_id)) {
        if (empty($uid)) {
            $params = array(
                'emailid' => Tools::getValue('email'),
                'firstname' => Tools::getValue('firstname'),
                'lastname' => Tools::getValue('lastname'),
                'password' => (!empty($password) ? $password : loginRadiusGetRandomString()),
                'gender' => $gender
            );
            if (!empty($birthdate)) {
                $params['birthdate'] = $birthdate;
            }
            try {
                $userprofile = $raas_sdk->raasCreateUser($params);
                if (isset($userprofile->Uid)) {
                    loginRadiusSaveLinkedAccount($customer->id, $userprofile->ID, $userprofile->Provider, $userprofile->Uid);
                }

            } catch (LoginRadiusException $e) {
                if ($e->errorResponse->errorCode == 936) {
                    try {
                        $r = $raas_sdk->raasGetUserProfileByEmail(Tools::getValue('email'));
                        if (isset($r[0]->Uid)) {
                            if (loginRadiusGetRassUid($customer->id) == '') {
                                loginRadiusSaveRaasUId($customer->id, $r[0]->Uid);
                            }
                        }
                    } catch (LoginRadiusException $e) {
                    }
                }
            }
        } else {
            $params = array(
                'accountid' => $uid,
                'password' => (!empty($password) ? $password : loginRadiusGetRandomString()),
                'emailid' => Tools::getValue('email'),
                'gender' => $gender
            );
            if (!empty($birthdate)) {
                $params['birthdate'] = $birthdate;
            }
            try {
                $userprofile = $raas_sdk->createRaasProfile($params);
                if (isset($userprofile->Uid)) {
                    loginRadiusSaveLinkedAccount($customer->id, $userprofile->ID, $userprofile->Provider, $userprofile->Uid);
                }
            } catch (LoginRadiusException $e) {

            }
        }
    } else {

        if (!empty($password)) {
            try {
                $raas_sdk->raasSetPassword(array('password' => $password), $provider_id);
            } catch (LoginRadiusException $e) {

            }
        }

        $params = array(
            'firstname' => Tools::getValue('firstname'),
            'lastname' => Tools::getValue('lastname'),
            'gender' => $gender
        );

        if (!empty($birthdate)) {
            $params['birthdate'] = $birthdate;
        }

        try {
            $raas_sdk->raasUpdateUser($params, $provider_id);

        } catch (LoginRadiusException $e) {

        }
    }

    loginRadiusUpdateStatusRaasCustomer($customer->id);
}
