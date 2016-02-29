<?php
/**
 * NOTICE OF LICENSE
 *
 * @package   loginradiusadvancemodule Add Customer Registration in your Pretashop module
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

include_once(dirname(__FILE__) . '/lr_sharing.php');

/**
 * Get content of configuration page.
 *
 * @return string
 */
function loginRadiusGetAdminContent()
{
    $module = new LoginRadiusAdvanceModule();
    $context = Context::getContext();
    $context->controller->addCSS(__PS_BASE_URI__ . 'modules/loginradiusadvancemodule/views/css/lr_admin.css');
    $context->controller->addJS(__PS_BASE_URI__ . 'modules/loginradiusadvancemodule/views/js/lr_admin.js');
    $context->controller->addJQueryUI('ui.sortable');

    // Get default language
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

    // Init Fields form array
    $fields_form = $options = array();
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $module->l('Customer Registration'),
            'icon' => 'icon-cogs'
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $module->l('Site Name'),
                'required' => true,
                'name' => 'lr_site_name',
            ),
            array(
                'type' => 'text',
                'label' => $module->l('API Key'),
                'name' => 'lr_api_key',
                'required' => true,
                'desc' => $module->l('To activate the module, insert LoginRadius API Key')
            ),
            array(
                'type' => 'text',
                'label' => $module->l('API Secret'),
                'required' => true,
                'name' => 'lr_api_secret',
                'desc' => $module->l('To activate the module, insert LoginRadius API Secret')
            ),
            array(
                'type' => 'radio',
                'br' => true,
                'label' => $module->l('Where do you want to redirect your users after successfully log in?'),
                'name' => 'lr_redirect',
                'values' => array(
                    array(
                        'id' => 'lr_redirect_backpage',
                        'value' => 'backpage',
                        'label' => $module->l('Redirect to Same page (Same as traditional login)')
                    ),
                    array(
                        'id' => 'lr_redirect_profile',
                        'value' => 'profile',
                        'label' => $module->l('Redirect to the profile')
                    ),
                    array(
                        'id' => 'lr_redirect_url',
                        'value' => 'url',
                        'label' => $module->l('Redirect to the following url')
                    )
                ),
            ),
            array(
                'type' => 'text',
                'label' => '',
                'name' => 'lr_redirect_add_url'
            ),
        ),
        'submit' => array(
            'title' => $module->l('Save'),

        )
    );
    $li = '';
    $lr_rearrange_settings = unserialize(Configuration::get('lr_rearrange_settings'));

    if (empty($lr_rearrange_settings)) {
        $lr_rearrange_settings = loginRadiusGetSharingDefaultNetworks('sharing');
    }

    foreach ($lr_rearrange_settings as $provider) {
        $li .= '<li title="' . $provider . '" id="loginRadiusLI' . $provider . '" class="lrshare_iconsprite32 lrshare_' . $provider . '">
		<input type="hidden" name="lr_rearrange_settings[]" value="' . $provider . '" />
		</li>';
    }
    $vertical_li = '';
    $lr_vertical_rearrange_settings = unserialize(Configuration::get('lr_vertical_rearrange_settings'));

    if (empty($lr_vertical_rearrange_settings)) {
        $lr_vertical_rearrange_settings = loginRadiusGetSharingDefaultNetworks('sharing');
    }

    foreach ($lr_vertical_rearrange_settings as $provider) {
        $vertical_li .= '<li title="' . $provider . '" id="loginRadiusLIvertical' . $provider . '" class="lrshare_iconsprite32 lrshare_' . $provider . '">
		<input type="hidden" name="lr_vertical_rearrange_settings[]" value="' . $provider . '" />
		</li>';
    }
    $fields_form[1]['form'] = array(
        'legend' => array(
            'title' => $module->l('Social Share'),
            'icon' => 'icon-cogs'
        ),
        'input' => array(
            array(
                'type' => 'select',
                'lang' => true,
                'label' => $module->l('Widget'),
                'name' => 'lr_widget',
                'desc' => $module->l('Please select the social sharing widget, horizontal and vertical widgets can be enabled simultaneously.'),
                $options = array(
                    array(
                        'id_option' => 'hr_widget',
                        'name' => 'Horizontal Widget'
                    ),
                    array(
                        'id_option' => 'vr_widget',
                        'name' => 'Vertical Widget'
                    ),
                ),
                'options' => array(
                    'query' => $options,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
                'onchange' => 'makeVisibletoWidget($(this).val());',


            ),
            array(
                'type' => 'switch',
                'label' => $module->l('Do you want to enable horizontal social sharing for your website?'),
                'name' => 'lr_enable_social_horizontal_sharing',

                'values' => array(
                    array(
                        'id' => 'lr_enable_social_horizontal_sharing',
                        'value' => 1,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_enable_social_horizontal_sharing',
                        'value' => 0,
                        'label' => $module->l('No')
                    )
                ),
            ),
            array(
                'type' => 'switch',
                'label' => $module->l('Do you want to enable mobile Friendly social sharing for your website?'),
                'name' => 'lr_enable_mobile_friendly',

                'values' => array(
                    array(
                        'id' => 'lr_enable_mobile_friendly',
                        'value' => true,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_enable_mobile_friendly',
                        'value' => false,
                        'label' => $module->l('No')
                    )
                ),
            ),
            array(
                'type' => 'switch',
                'label' => $module->l('Do you want to enable vertical social sharing for your website?'),
                'name' => 'lr_enable_social_vertical_sharing',

                'values' => array(
                    array(
                        'id' => 'lr_enable_social_vertical_sharing',
                        'value' => 1,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_enable_social_vertical_sharing',
                        'value' => 0,
                        'label' => $module->l('No')
                    )
                ),
            ),
            array(
                'type' => 'radio',
                'br' => true,
                'label' => $module->l('Select your social sharing widget'),
                'name' => 'lr_chooseshare',
                'class' => 'sharehorizontal',
                'values' => array(
                    array(
                        'id' => 'hori32',
                        'value' => 0,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/horizonSharing32.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'
                    ),
                    array(
                        'id' => 'lr_chooseshare',
                        'value' => 1,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/horizonSharing16.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'

                    ),

                    array(
                        'id' => 'lr_chooseshare',
                        'value' => 10,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/responsive-icons.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'

                    ),
                    array(
                        'id' => 'horithemelarge',
                        'value' => 2,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/single-image-theme-large.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'

                    ),
                    array(
                        'id' => 'horithemesmall',
                        'value' => 3,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/single-image-theme-small.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'

                    ),
                    array(
                        'id' => 'hybrid-horizontal-horizontal',
                        'value' => 9,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/hybrid-horizontal-horizontal.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'

                    ),
                    array(
                        'id' => 'hybrid-horizontal-vertical',
                        'value' => 8,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/hybrid-horizontal-vertical.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'

                    ),
                ),
            ),
            array(
                'type' => 'radio',
                'br' => true,
                'label' => $module->l('Select your social sharing widget'),
                'name' => 'lr_chooseverticalshare',
                'class' => 'sharevertical',
                'values' => array(
                    array(
                        'id' => 'vertibox32',
                        'value' => 4,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/32VerticlewithBox.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'
                    ),
                    array(
                        'id' => 'vertibox16',
                        'value' => 5,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/16VerticlewithBox.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'

                    ),

                    array(
                        'id' => 'lr_chooseverticalshare',
                        'value' => 6,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/hybrid-verticle-vertical.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'

                    ),
                    array(
                        'id' => 'hybrid-verticle-horizontal',
                        'value' => 7,
                        'label' => '<img src="../modules/loginradiusadvancemodule/views/img/hybrid-verticle-horizontal.png" alt="' . $module->l('Horizontal Sharing') . '" title="' . $module->l('Horizontal Sharing image') . '" />'

                    ),

                ),
            ),
            array(
                'type' => 'radio',
                'br' => true,
                'label' => $module->l('Select the position of social sharing widget'),
                'name' => 'lr_choosesharepos',
                'class' => 'vertical_sharing_position',
                'values' => array(
                    array(
                        'id' => 'topleft',
                        'value' => 0,
                        'label' => $module->l('Top Left')
                    ),
                    array(
                        'id' => 'topright',
                        'value' => 1,
                        'label' => $module->l('Top Right')
                    ),
                    array(
                        'id' => 'bottomleft',
                        'value' => 2,
                        'label' => $module->l('Bottom Left')
                    ),
                    array(
                        'id' => 'bottomright',
                        'value' => 3,
                        'label' => $module->l('Bottom Right')
                    )
                ),
            ),
            array(
                'type' => 'html',
                'name' => 'lr_socialshare_show_providers_list[]',
                'label' => $module->l('What sharing networks do you want to show in the sharing widget? (All other
	sharing networks will be shown as part of LoginRadius sharing icon)'),
                'html_content' => '<div id="loginRadiusSharingLimit"
	style="color: red; display: none; margin-bottom: 5px;">' . $module->l('You can select only 9 providers.', 'lr_admin') . '</div>
  <table class="form-table lr_table" id="shareprovider">
	</table>'
            ),
            array(
                'type' => 'html',
                'name' => 'lr_rearrange_settings[]',
                'label' => $module->l('What sharing network order do you prefer for your sharing widget?'),
                'html_content' => '<ul id="sortable" style="height:35px;">' . $li . '</ul>',
            ),
            array(
                'type' => 'html',
                'name' => 'lr_socialverticalshare_providers_list[]',
                'label' => $module->l('What sharing networks do you want to show in the sharing widget? (All other
	sharing networks will be shown as part of LoginRadius sharing icon)'),
                'html_content' => '<div id="loginRadiusverticalSharingLimit"
	style="color: red; display: none; margin-bottom: 5px;">' . $module->l('You can select only 9 providers.', 'lr_admin') . '</div>
  <table class="form-table lr_table" id="verticalshareprovider">
	</table>'
            ),
            array(
                'type' => 'html',
                'name' => 'lr_vertical_rearrange_settings[]',
                'label' => $module->l('What sharing network order do you prefer for your sharing widget?'),
                'html_content' => '<ul id="verticalsortable" style="height:35px;">' . $vertical_li . '</ul>',
            ),
            array(
                'type' => 'checkbox',
                'lang' => true,
                'class' => 'horizontal_location',
                'label' => $module->l('What area(s) do you want to show the social sharing widget?'),
                'name' => 'lr_social_hr_share_location',
                $options = array(
                    array(
                        'id_option' => 'home',
                        'name' => 'Show on Home page'
                    ),
                    array(
                        'id_option' => 'cart',
                        'name' => 'Show on Cart page'
                    ),
                    array(
                        'id_option' => 'product',
                        'name' => 'Show on Product Page'
                    ),
                ),
                'values' => array(
                    'query' => $options,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            ),
            
            array(
                'type' => 'checkbox',
                'lang' => true,
                'label' => $module->l('What area(s) do you want to show the social sharing widget?'),
                'name' => 'lr_social_vr_share_location',
                'class' => 'vertical_location',
                $options = array(
                    array(
                        'id_option' => 'home',
                        'name' => 'Show on Home page'
                    ),
                    array(
                        'id_option' => 'cart',
                        'name' => 'Show on Cart page'
                    ),
                    array(
                        'id_option' => 'product',
                        'name' => 'Show on Product Page'
                    ),
                ),
                'values' => array(
                    'query' => $options,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            ),
        ),
        'submit' => array(
            'title' => $module->l('Save'),

        )
    );
    $fields_form[2]['form'] = array(
        'legend' => array(
            'title' => $module->l('Advance Settings'),
            'icon' => 'icon-cogs'
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $module->l('Please enter the title to be shown on social login interface'),
                'name' => 'lr_title',
                'desc' => $module->l('Leave empty for no text')
            ),
            array(
                'type' => 'text',
                'label' => $module->l('Please enter the message to be displayed to the user in the pop-up'),
                'name' => 'lr_popup_title',
            ),
            array(
                'type' => 'switch',
                'label' => $module->l('Do you want to update the user profile data in your database everytime a user logs into your website?'),
                'name' => 'lr_update_user_profile',

                'values' => array(
                    array(
                        'id' => 'lr_update_user_profile',
                        'value' => 1,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_update_user_profile',
                        'value' => 0,
                        'label' => $module->l('No')
                    )
                ),
            ),
            array(
                'type' => 'switch',
                'label' => $module->l('Do you want to display form validation message on authentication pages?').'<a title="Form validation includes checking for username and password lengths, password complexity, etc."> (<span>?</span>)</a>',
                'name' => 'lr_form_validation',

                'values' => array(
                    array(
                        'id' => 'lr_form_validation',
                        'value' => 1,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_form_validation',
                        'value' => 0,
                        'label' => $module->l('No')
                    )
                ),
            ),
            array(
                'type' => 'textarea',
                'label' => $module->l('Enter text to be displayed under the Terms and Condition on the registration page.'),
                'name' => 'lr_term_condition',
                'cols' => 50,
                'rows' => 4
            ),
            array(
                'type' => 'text',
                'label' => $module->l('Enter delay time to generate authentication pages'). '<a title=" Recommended for content heavy sites where page loading time is longer due to lots of images, videos, etc. on the page."> (<span>?</span>)</a>',
                'name' => 'lr_render_form_delay',
            ),
            array(
                'type' => 'text',
                'label' => $module->l('Enter desired minimum length for password'),
                'name' => 'lr_min_password_length',
            ),
            array(
                'type' => 'text',
                'label' => $module->l('Enter desired maximum length for password'),
                'name' => 'lr_max_password_length',
            ),
            
           
            array(
                'type' => 'text',
                'label' => $module->l('Enter template name for forgot password email'),
                'name' => 'lr_forgot_pass_template',
            ),
            array(
                'type' => 'text',
                'label' => $module->l('Enter template name for email verification email'),
                'name' => 'lr_email_verify_template',
            ),
             
            
             array(
                'type' => 'text',
                'label' => $module->l('Enter Google reCaptcha public key'),
                'name' => 'lr_recaptcha',
            ),
            array(
                'type' => 'textarea',
                'label' => $module->l('Please enter custom customer registration options for LoginRadius interface'),
                'name' => 'lr_add_raas_option',
                'cols' => 50,
                'rows' => 4
            ),
             array(
                'type' => 'radio',
                'br' => true,
                'label' => $module->l('Select your desired email verification option during the registration process.'),
                'name' => 'lr_email_verify_mail',
                'values' => array(
                    array(
                        'id' => 'required',
                        'value' => 0,
                        'label' => $module->l('Required')
                    ),
                    array(
                        'id' => 'optional',
                        'value' => 1,
                        'label' => $module->l('Optional')
                    ),
                    array(
                        'id' => 'disable',
                        'value' => 2,
                        'label' => $module->l('Disable')
                    )
                    
                ),
            ),
            array(
                'type' => 'switch',
                'label' => $module->l('Do you want to prompt for password after registration with social provider?'),
                'name' => 'lr_prompt_password',

                'values' => array(
                    array(
                        'id' => 'lr_prompt_password',
                        'value' => 1,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_prompt_password',
                        'value' => 0,
                        'label' => $module->l('No')
                    )
                ),
            ),
            array(
                'type' => 'switch',
                'label' => $module->l('Do you want to enable login upon email verification'),
                'name' => 'lr_email_verification',

                'values' => array(
                    array(
                        'id' => 'lr_email_verification',
                        'value' => 1,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_email_verification',
                        'value' => 0,
                        'label' => $module->l('No')
                    )
                ),
            ),
            array(
                'type' => 'switch',
                'label' => $module->l('Always Ask Email For Unverified Account'),
                'name' => 'lr_ask_email',

                'values' => array(
                    array(
                        'id' => 'lr_ask_email',
                        'value' => 1,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_ask_email',
                        'value' => 0,
                        'label' => $module->l('No')
                    )
                ),
            ),
             array(
                'type' => 'switch',
                'label' => $module->l('Do you want to enable login with username?'),
                'name' => 'lr_enable_username',

                'values' => array(
                    array(
                        'id' => 'lr_enable_username',
                        'value' => 1,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_enable_username',
                        'value' => 0,
                        'label' => $module->l('No')
                    )
                ),
            ),
        ),
        'submit' => array(
            'title' => $module->l('Save'),

        )
    );
    $fields_form[3]['form'] = array(
        'legend' => array(
            'title' => $module->l('Social Profile Data'),
            'icon' => 'icon-cogs'
        ),
        'input' => array(
            array(
                'type' => 'checkbox',
                'name' => 'lr_social_profile_selection',
                'label' => $module->l('Please select the user profile data fields you would like to save in your database'),
                'values' => array(
                    'query' => array(
                        array(
                            'id' => 'basic_profile_data',
                            'name' => $module->l('Basic Profile Data') . '<a title=" Social ID, Social ID Provider, Prefix, First Name, Middle Name, Last Name, Suffix, Full Name, Nick Name, Profile Name, Birthdate, Gender, Country Code, Country Name, Thumbnail Image Url, Image Url, Local Country, Profile Country"> (<span>?</span>)</a>',
                            'val' => 'basic_profile_data',

                        ),
                        array(
                            'id' => 'extended_location_data',
                            'name' => $module->l('Extended Location Data') . '<a title=" Main Address, Hometown, State, City, Local City, Profile City, Profile Url, Local Language, Language"> (<span>?</span>)</a>',
                            'val' => 'extended_location_data'
                        ),

                        array(
                            'id' => 'extended_profile_data',
                            'name' => $module->l('Extended Profile Data') . '<a title=" Website, Favicon, Industry, About, Timezone, Verified, Last Profile Update, Created, Relationship Status, Favorite Quote, Interested In, Interests, Religion, Political View, HTTPS Image Url, Followers Count, Friends Count, Is Geo Enabled, Total Status Count, Number of Recommenders, Honors, Associations, Hirable, Repository Url, Age, Professional Headline, Provider Access Token, Provider Token Secret, Positions, Companies, Education, Phone Numbers, IM Accounts, Addresses, Sports, Inspirational People, Skills, Current Status, Certifications, Courses, Volunteer, Recommendations Received, Languages, Patents, Favorites"> (<span>?</span>)</a>',
                            'val' => 'extended_profile_data'
                        ),
                        array(
                            'id' => 'linkedin_companies',
                            'name' => $module->l('Linkedin Companies') . '<a title="A list of all the companies this user follows on LinkedIn."> (<span>?</span>)</a>',
                            'val' => 'linkedin_companies'
                        ),
                        array(
                            'id' => 'facebook_events',
                            'name' => $module->l('Facebook Events') . '<a title="A list of events (birthdays, invitation, etc.) on the Facebook profile of user"> (<span>?</span>)</a>',
                            'val' => 'facebook_events'
                        ),
                        array(
                            'id' => 'status',
                            'name' => $module->l('Status') . '<a title="Facebook wall activity, Twitter tweets and LinkedIn status of the user, including links"> (<span>?</span>)</a>',
                            'val' => 'status'
                        ),
                        array(
                            'id' => 'facebook_posts',
                            'name' => $module->l('Facebook Posts') . '<a title="Facebook posts of the user, including links"> (<span>?</span>)</a>',
                            'val' => 'facebook_posts'
                        ),
                        array(
                            'id' => 'twitter_mentions',
                            'name' => $module->l('Twitter Mentions') . '<a title="A list of tweets that the user is mentioned in."> (<span>?</span>)</a>',
                            'val' => 'twitter_mentions'
                        ),
                        array(
                            'id' => 'groups',
                            'name' => $module->l('Groups') . '<a title="A list of the Facebook groups of user."> (<span>?</span>)</a>',
                            'val' => 'groups'
                        ),
                        array(
                            'id' => 'contacts',
                            'name' => $module->l('Contacts') . '<a title="For email providers (Google and Yahoo), a list of the contacts of user in his/her address book. For social networks (Facebook, Twitter, and LinkedIn), a list of the people in the network of user."> (<span>?</span>)</a>',
                            'val' => 'contacts'
                        ),

                    ),
                    'id' => 'id',
                    'name' => 'name',

                )
            ),
        ),
        'submit' => array(
            'title' => $module->l('Save'),

        )
    );
    $fields_form[4]['form'] = array(
        'legend' => array(
            'title' => $module->l('Single Sign On'),
            'icon' => 'icon-cogs'
        ),
        'input' => array(

            array(
                'type' => 'switch',
                'label' => $module->l('Do you want to enable Single Sign On? '),
                'name' => 'lr_enable_sso',

                'values' => array(
                    array(
                        'id' => 'lr_enable_sso',
                        'value' => 1,
                        'label' => $module->l('Yes')
                    ),
                    array(
                        'id' => 'lr_enable_sso',
                        'value' => 0,
                        'label' => $module->l('No')
                    )
                ),
            ),
        ),
        'submit' => array(
            'title' => $module->l('Save'),

        )
    );

    $helper = new HelperForm();
    // Module, token and currentIndex
    $helper->module = $module;
    $helper->name_controller = $module->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $module->name;

    // Language
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;

    // Title and toolbar
    $helper->title = $module->displayName;
    $helper->show_toolbar = false; // false -> remove toolbar
    $helper->toolbar_scroll = true; // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit' . $module->name;
    $helper->tpl_vars = array(
        'fields_value' => getConfigFieldsValues(),
        'languages' => $context->controller->getLanguages(),
        'id_language' => $context->language->id
    );
    $helper->toolbar_btn = array(
        'save' =>
            array(
                'desc' => $module->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $module->name . '&save' . $module->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
        'back' => array(
            'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            'desc' => $module->l('Back to list')
        )
    );

    $html = headerHtml();
    $html .= $helper->generateForm($fields_form);
    return $html;

}

/**
 * Get script required for admin page.
 *
 * @return string
 */
function headerHtml()
{
    $countericons = unserialize(Configuration::get('lr_socialshare_show_counter_list'));

    if (empty($countericons)) {
        $countericons = loginRadiusGetSharingDefaultNetworks('counter');
    }

    $verticalcountericons = unserialize(Configuration::get('lr_socialshare_counter_list'));

    if (empty($verticalcountericons)) {
        $verticalcountericons = loginRadiusGetSharingDefaultNetworks('counter');
    }
    return '<script type="text/javascript">
      window.onload = function() {
          sharingproviderlist();

        counterproviderlist(' . Tools::jsonEncode($countericons) . ', ' . Tools::jsonEncode($verticalcountericons) . ');
        makeVisibletoWidget($( "#lr_widget option:selected" ).val());
    }
    </script>';
}

/**
 * All config fields keys.
 *
 * @return array
 */
function getConfigFieldsKeys()
{
    $result = array('lr_widget', 'lr_site_name', 'lr_api_key', 'lr_api_secret', 'lr_redirect', 'lr_enable_social_horizontal_sharing', 'lr_enable_social_vertical_sharing',
        'lr_enable_mobile_friendly', 'lr_chooseshare', 'lr_chooseverticalshare', 'lr_choosesharepos', 'lr_social_hr_share_location_home', 'lr_social_hr_share_location_product', 'lr_social_hr_share_location_cart', 'lr_social_vr_share_location_home', 'lr_social_vr_share_location_product', 'lr_social_vr_share_location_cart'
    , 'lr_title', 'lr_popup_title', 'lr_update_user_profile', 'lr_enable_sso', 'lr_redirect_add_url', 'lr_socialshare_show_counter_list', 'lr_socialshare_counter_list', 'lr_rearrange_settings', 'lr_vertical_rearrange_settings',
    'lr_form_validation', 'lr_term_condition', 'lr_render_form_delay', 'lr_min_password_length', 'lr_max_password_length', 'lr_email_verification', 'lr_enable_username', 'lr_forgot_pass_template', 'lr_email_verify_template', 'lr_prompt_password','lr_ask_email', 'lr_recaptcha', 'lr_add_raas_option', 'lr_email_verify_mail');
    $result = array_combine($result, $result);

    $result_checkboxes = array('lr_social_profile_selection' => array('basic_profile_data',
        'extended_location_data', 'extended_profile_data', 'linkedin_companies',
        'facebook_events', 'status', 'facebook_posts', 'twitter_mentions',
        'groups', 'contacts'));

    return array_merge($result, $result_checkboxes);

}

/**
 * Get config field values.
 *
 * @return array
 */
function getConfigFieldsValues()
{
    $module = new LoginRadiusAdvanceModule();
    $settings_array = getConfigFieldsKeys();
    $values = array();
    if (Configuration::get('lr_social_hr_share_location_home') != 'on' && Configuration::get('lr_social_hr_share_location_product') != 'on' && Configuration::get('lr_social_hr_share_location_cart') != 'on') {
        Configuration::updateValue('lr_social_hr_share_location_home', 'on');
    }
    if (Configuration::get('lr_social_vr_share_location_home') != 'on' && Configuration::get('lr_social_vr_share_location_product') != 'on' && Configuration::get('lr_social_vr_share_location_cart') != 'on') {
        Configuration::updateValue('lr_social_vr_share_location_home', 'on');
    }
    foreach ($settings_array as $settings_array_key => $settings_array_keys_val) {
        if ($settings_array_key == 'lr_redirect' && !Configuration::get('lr_redirect')) {
            $values['lr_redirect'] = 'backpage';
        } elseif ($settings_array_key == 'lr_chooseverticalshare' && !Configuration::get('lr_chooseverticalshare')) {
            $values['lr_chooseverticalshare'] = '4';

        } elseif ($settings_array_key == 'lr_popup_title' && !Configuration::get('lr_chooseverticalshare')) {
            $values['lr_popup_title'] = $module->l('Please fill the following details to complete the registration');
        } elseif ($settings_array_key == 'lr_social_profile_selection') {

            foreach ($settings_array_keys_val as $settings_array_value) {
                $selection = unserialize(Configuration::get('lr_social_profile_selection'));
                if (is_array($selection)) {
                    $values[$settings_array_key . '_' . $settings_array_value] = in_array($settings_array_value, $selection) ? $settings_array_value : '';
                }
            }
        } else {
            $values[$settings_array_key] = Configuration::get($settings_array_key);
        }
    }
    return $values;
}



/**
 * Validate the api credentials and module settings
 *
 * @return string if settings is not validated then return error message
 */
function loginRadiusModuleSettingsValidate()
{
    $module = new LoginRadiusAdvanceModule();
    include_once(dirname(__FILE__) . '/LoginRadiusSDK.php');
    $obj = new LoginRadius();
    $loginradius_api_key = trim(Tools::getValue('lr_api_key'));
    $loginradius_api_secret = trim(Tools::getValue('lr_api_secret'));
    $loginradius_app_name = trim(Tools::getValue('lr_site_name'));
    $empty_api_credentials = $module->l('LoginRadius API Key or Secret is invalid. Get your LoginRadius API key from ', 'sociallogin_settings_validation') . "<a href='http://www.loginradius.com' target='_blank'>LoginRadius</a>";
    $empty_app_name = $module->l('Please Enter LoginRadius App name');
    if (empty($loginradius_api_key) || empty($loginradius_api_secret)) {
        return $empty_api_credentials;
    } elseif(empty($loginradius_app_name)) {
        return $empty_app_name;
    }

    $validateurl = 'https://' . LR_DOMAIN . '/api/v2/app/validate?apikey=' . rawurlencode($loginradius_api_key) . '&apisecret=' . rawurlencode($loginradius_api_secret);
    try {
        $json_result = $obj->loginRadiusApiClient($validateurl);
        $result = Tools::jsonDecode($json_result);
        //var_dump($result);die;
        if (empty($result)) {
            return $module->l('please check your php.ini settings to enable CURL or FSOCKOPEN', 'sociallogin_settings_validation');
        }
        if (isset($result->Status) && !$result->Status) {
            $error = array(
                'API_KEY_NOT_FORMATED' => $module->l('LoginRadius API key is invalid. Get your LoginRadius API key from ', 'sociallogin_settings_validation') . "<a href='http://www.loginradius.com' target='_blank'>LoginRadius</a>",
                'API_SECRET_NOT_VALID' => $module->l('LoginRadius API Secret is invalid. Get your LoginRadius API Secret from ', 'sociallogin_settings_validation') . "<a href='http://www.loginradius.com' target='_blank'>LoginRadius</a>",
                'API_KEY_NOT_VALID' => $module->l('LoginRadius API Key is not formatted correctly', 'sociallogin_settings_validation'),
                'API_SECRET_NOT_FORMATED' => $module->l('LoginRadius API Secret is not formatted correctly', 'sociallogin_settings_validation'),
            );

            foreach ($result->Messages as $value) {
               
                return $error[$value];
            }
        }
    } catch (LoginRadiusException $e) {
        
        return $e;
    }
}

/**
 * Save module settings into database
 *
 * @return string message that display to admin.
 */
function loginRadiusSaveModuleSettings()
{
    $module = new LoginRadiusAdvanceModule();
    $html = '';
    $settings_array = getConfigFieldsKeys();
    $settings = array();
    foreach ($settings_array as $settings_array_key => $settings_array_keys_val) {
        if ($settings_array_key == 'lr_rearrange_settings') {
            $settings['lr_rearrange_settings'] = count(Tools::getValue('lr_rearrange_settings')) > 0 ? serialize(Tools::getValue('lr_rearrange_settings')) : '';

        } elseif ($settings_array_key == 'lr_vertical_rearrange_settings') {
            $settings['lr_vertical_rearrange_settings'] = count(Tools::getValue('lr_vertical_rearrange_settings')) > 0 ? serialize(Tools::getValue('lr_vertical_rearrange_settings')) : '';

        } elseif ($settings_array_key == 'lr_socialshare_counter_list') {
            $settings['lr_socialshare_counter_list'] = count(Tools::getValue('lr_socialshare_counter_list')) > 0 ? serialize(Tools::getValue('lr_socialshare_counter_list')) : '';

        } elseif ($settings_array_key == 'lr_socialshare_show_counter_list') {
            $settings['lr_socialshare_show_counter_list'] = count(Tools::getValue('lr_socialshare_show_counter_list')) > 0 ? serialize(Tools::getValue('lr_socialshare_show_counter_list')) : '';
        } elseif ($settings_array_key == 'lr_social_profile_selection') {
            $settings['lr_social_profile_selection'] = array();
            foreach ($settings_array_keys_val as $settings_array_value) {
                if (Tools::getValue($settings_array_key . '_' . $settings_array_value) == $settings_array_value) {
                    $settings['lr_social_profile_selection'][] = $settings_array_value;
                }

            }

        } else {
            $settings[$settings_array_key] = trim(Tools::getValue($settings_array_key));
        }
    }
    $result = loginRadiusModuleSettingsValidate();
    
    if ($result) {
        
        return $module->displayError($result);
    } else {
        Configuration::updateValue('lr_api_key', trim(Tools::getValue('lr_api_key')));
        Configuration::updateValue('lr_api_secret', trim(Tools::getValue('lr_api_secret')));
        loginRadiusUpdateModuleSettings($settings);
        $html .= $module->displayConfirmation($module->l('Settings updated.', 'lr_admin'));
        
        return $html;
    }
}

/**
 * Update module settings inot database
 *
 * @param array $settings Conatin module settings information.
 */
function loginRadiusUpdateModuleSettings($settings)
{
    foreach ($settings as $key => $value) {
        if (is_array($value)) {
            $value = serialize($value);
        }
        Configuration::updateValue($key, $value);
    }
}
