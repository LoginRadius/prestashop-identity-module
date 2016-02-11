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
 * LR tables structure.
 */
function loginRadiuscreateDatabaseLrTable()
{
    if ($result = Db::getInstance()->getRow('SELECT * from `' . _DB_PREFIX_ . 'customer`')) {
        if (!in_array('lr_raas_uid', array_keys($result))) {
            Db::getInstance()->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'customer` ADD COLUMN `lr_raas_uid` varchar( 150 )  null');
        }
    }
    $tbl = pSQL(_DB_PREFIX_ . 'sociallogin');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
	`id_customer` int(10) unsigned NOT null COMMENT 'foreign key of customers.',
	`provider_id` varchar(100) NOT null,
	`Provider_name` varchar(100)
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_basic_profile_data');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
	`id` int( 11 ) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `social_id` varchar( 150 ) NOT null,
                `provider` varchar( 20 ) DEFAULT null,
                `prefix` varchar( 100 ) DEFAULT null,
                `first_name` varchar( 100 ) DEFAULT null,
                `middle_name` varchar( 100 ) DEFAULT null,
                `last_name` varchar( 100 ) DEFAULT null,
                `suffix` varchar( 100 ) DEFAULT null,
                `full_name` varchar( 200 ) DEFAULT null,
                `nick_name` varchar( 200 ) DEFAULT null,
                `profile_name` varchar( 100 ) DEFAULT null,
                `profile_url` varchar( 300 ) DEFAULT null,
                `birth_date` datetime DEFAULT null,
                `gender`  varchar( 20 ) DEFAULT null,
                `website` varchar( 300 ) DEFAULT null,
                `thumbnail_image_url` varchar( 300 ) DEFAULT null,
                `image_url` varchar( 300 ) DEFAULT null,
                `country_code` varchar( 10 ) DEFAULT null,
                `country_name` varchar( 100 ) DEFAULT null,
                `local_country` varchar( 50 ) DEFAULT null,
                `profile_country` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_emails');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
`id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `email_type` varchar( 15 ) DEFAULT null,
                `email` varchar( 100 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_extended_location_data');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
`id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `main_address` varchar( 500 ) DEFAULT null,
                `hometown` varchar( 100 ) DEFAULT null,
                `city` varchar( 100 ) DEFAULT null,
                `state` varchar( 100 ) DEFAULT null,
                `postal_code` varchar( 50 ) DEFAULT null,
                `country` varchar( 100 ) DEFAULT null,
                `region` varchar( 100 ) DEFAULT null,
                `local_city` varchar( 50 ) DEFAULT null,
                `profile_city` varchar( 50 ) DEFAULT null,
                `local_language` varchar( 10 ) DEFAULT null,
                `language` varchar( 15 ) DEFAULT null,
                `local_country` varchar( 50 ) DEFAULT null,
                `profile_country` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_extended_profile_data');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
`id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `website` varchar( 300 ) DEFAULT null,
                `favicon` varchar( 300 ) DEFAULT null,
                `industry` varchar( 1000 ) DEFAULT null,
                `about` varchar( 1500 ) DEFAULT null,
                `timezone` varchar( 100 ) DEFAULT null,
                `verified` varchar( 15 ) DEFAULT null,
                `last_profile_update` datetime DEFAULT null,
                `created` varchar( 100 ) DEFAULT null,
                `relationship_status` varchar( 30 ) DEFAULT null,
                `quote` varchar( 1000 ) DEFAULT null,
                `interested_in` varchar( 1000 ) DEFAULT null,
                `interests` varchar( 1000 ) DEFAULT null,
                `religion` varchar( 1000 ) DEFAULT null,
                `political_view` varchar( 1000 ) DEFAULT null,
                `https_image_url` varchar( 300 ) DEFAULT null,
                `followers_count` int( 11 ) DEFAULT null,
                `friends_count` int( 11 ) DEFAULT null,
                `is_geo_enabled` int( 11 ) DEFAULT null,
                `total_status_count` int( 11 ) DEFAULT null,
                `number_of_recommenders` int( 11 ) DEFAULT null,
                `hirable` int( 11 ) DEFAULT null,
                `repository_url` varchar( 300 ) DEFAULT null,
                `age` int( 3 ) DEFAULT null,
                `age_range_min` int(3) DEFAULT null,
                `age_range_max` int(3) DEFAULT null,
                `professional_headline` varchar( 300 ) DEFAULT null,
                `provider_access_token` varchar( 1000 ) DEFAULT null,
                `provider_token_secret` varchar( 100 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_positions');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
  `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `position` varchar( 100 ) DEFAULT null,
                `summary` varchar( 100 ) DEFAULT null,
                `start_date` varchar( 50 ) DEFAULT null,
                `end_date` varchar( 50 ) DEFAULT null,
                `is_current` int( 11 ) DEFAULT null,
                `company` int( 11 ) DEFAULT null,
                `location` varchar( 255 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);


    $tbl = pSQL(_DB_PREFIX_ . 'lr_companies');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
  `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `company_name` varchar( 100 ) DEFAULT null,
                `company_type` varchar( 50 ) DEFAULT null,
                `industry` varchar( 150 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_education');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
`id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `school` varchar( 100 ) DEFAULT null,
                `year` varchar( 50 ) DEFAULT null,
                `type` varchar( 50 ) DEFAULT null,
                `notes` varchar( 100 ) DEFAULT null,
                `activities` varchar( 100 ) DEFAULT null,
                `degree` varchar( 100 ) DEFAULT null,
                `field_of_study` varchar( 100 ) DEFAULT null,
                `start_date` varchar( 50 ) DEFAULT null,
                `end_date` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_phone_numbers');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
 `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `number_type` varchar( 20 ) DEFAULT null,
                `phone_number` varchar( 20 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_imaccounts');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
 `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `account_type` varchar( 20 ) DEFAULT null,
                `account_username` varchar( 100 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_addresses');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
   `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `type` varchar( 20 ) DEFAULT null,
                `address_line1` varchar( 100 ) DEFAULT null,
                `address_line2` varchar( 100 ) DEFAULT null,
                `city` varchar( 100 ) DEFAULT null,
                `state` varchar( 100 ) DEFAULT null,
                `postal_code` varchar( 20 ) DEFAULT null,
                `region` varchar( 100 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_sports');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
`id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `sport_id` varchar( 20 ) DEFAULT null,
                `sport` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_inspirational_people');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
   `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `social_id` varchar( 20 ) DEFAULT null,
                `name` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_skills');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
  `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `skill_id` varchar( 20 ) DEFAULT null,
                `name` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_current_status');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
    `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `status_id` varchar( 30 ) DEFAULT null,
                `status` varchar( 1500 ) DEFAULT null,
                `source` varchar( 500 ) DEFAULT null,
                `created_date` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_certifications');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
          `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `certification_id` varchar( 30 ) DEFAULT null,
                `certification_name` varchar( 50 ) DEFAULT null,
                `authority` varchar( 50 ) DEFAULT null,
                `license_number` varchar( 50 ) DEFAULT null,
                `start_date` varchar( 50 ) DEFAULT null,
                `end_date` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_courses');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
   `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `volunteer_id` varchar( 30 ) DEFAULT null,
                `role` varchar( 50 ) DEFAULT null,
                `organization` varchar( 50 ) DEFAULT null,
                `cause` varchar( 100 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_volunteer');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
    `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `status_id` varchar( 30 ) DEFAULT null,
                `status` varchar( 1500 ) DEFAULT null,
                `source` varchar( 500 ) DEFAULT null,
                `created_date` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_recommendations_received');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
  `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `recommendation_id` varchar( 30 ) DEFAULT null,
                `recommendation_type` varchar( 100 ) DEFAULT null,
                `recommendation_text` varchar( 1500 ) DEFAULT null,
                `recommender` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_languages');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
`id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `language_id` varchar( 30 ) DEFAULT null,
                `language` varchar( 30 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_patents');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
     `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `patent_id` varchar( 30 ) DEFAULT null,
                `title` varchar( 100 ) DEFAULT null,
                `date` varchar( 30 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_games');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
     `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `game_id` varchar( 30 ) DEFAULT null,
                `category` varchar( 50 ) DEFAULT null,
                `name` varchar( 100 ) DEFAULT null,
                `created_date` varchar( 30 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_television_show');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
     `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `television_id` varchar( 30 ) DEFAULT null,
                `category` varchar( 50 ) DEFAULT null,
                `name` varchar( 100 ) DEFAULT null,
                `created_date` varchar( 30 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);


    $tbl = pSQL(_DB_PREFIX_ . 'lr_movies');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
     `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `movie_id` varchar( 30 ) DEFAULT null,
                `category` varchar( 50 ) DEFAULT null,
                `name` varchar( 100 ) DEFAULT null,
                `created_date` varchar( 30 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_books');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
     `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `book_id` varchar( 30 ) DEFAULT null,
                `category` varchar( 50 ) DEFAULT null,
                `name` varchar( 100 ) DEFAULT null,
                `created_date` varchar( 30 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_favorites');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
 `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `social_id` varchar( 30 ) DEFAULT null,
                `name` varchar( 100 ) DEFAULT null,
                `type` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_facebook_likes');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
`id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `like_id` varchar( 40 ) DEFAULT null,
                `name` varchar( 300 ) DEFAULT null,
                `category` varchar( 50 ) DEFAULT null,
                `created_date` datetime DEFAULT null,
                `website` varchar( 300 ) DEFAULT null,
                `description` varchar( 1500 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_facebook_events');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
     `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `event_id` varchar( 30 ) NOT null,
                `description` varchar( 3000 ),
                `name` varchar( 500 ) NOT null,
                `start_time` datetime DEFAULT null,
                `end_time` datetime DEFAULT null,
                `privacy` varchar( 100 ),
                `rsvp_status` varchar( 50 ) DEFAULT null,
                `location` varchar( 100 ) DEFAULT null,
                `owner_id` varchar( 100 ),
                `owner_name` varchar( 300 ),
                `updated_date` datetime
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_facebook_posts');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
  `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `post_id` varchar( 50 ) NOT null,
                `name` varchar( 100 ) DEFAULT null,
                `title` varchar( 300 ) DEFAULT null,
                `start_time` datetime DEFAULT null,
                `update_time` datetime DEFAULT null,
                `message` varchar( 2000 ),
                `place` varchar( 50 ) DEFAULT null,
                `picture` varchar( 1000 ) DEFAULT null,
                `likes` int( 8 ) DEFAULT null,
                `shares` int( 8 ) DEFAULT null,
                `type` varchar( 50 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_albums');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
     `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `album_id` varchar( 40 ) DEFAULT null,
                `owner_id` varchar( 40 ) DEFAULT null,
                `title` varchar( 100 ) DEFAULT null,
                `description` varchar( 1500 ) DEFAULT null,
                `location` varchar( 100 ) DEFAULT null,
                `type` varchar( 100 ) DEFAULT null,
                `created_date` datetime DEFAULT null,
                `updated_date` datetime DEFAULT null,
                `cover_image_url` varchar( 300 ) DEFAULT null,
                `image_count` varchar( 10 ) DEFAULT null,
                `directory_url` varchar( 300 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_contacts');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
   `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `social_id` varchar( 255 ) DEFAULT null,
                `provider` varchar( 20 ) NOT null,
                `name` varchar( 100 ) DEFAULT null,
                `email` varchar( 100 ) DEFAULT null,
                `phone_number` varchar( 30 ) DEFAULT null,
                `profile_url` varchar( 1000 ) DEFAULT null,
                `image_url` varchar( 1000 ) DEFAULT null,
                `status` varchar( 1500 ) DEFAULT null,
                `industry` varchar( 50 ) DEFAULT null,
                `country` varchar( 20 ) DEFAULT null,
                `gender` varchar( 10 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_groups');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
 `id` int( 11 ) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `provider` varchar( 30 ) NOT null,
                `country` varchar( 100 ),
                `description` varchar( 1500 ),
                `email` varchar( 300 ),
                `group_id` varchar( 50 ) NOT null,
                `image` varchar ( 300 ),
                `logo` varchar ( 300 ),
                `member_count` varchar ( 10 ),
                `name` varchar( 100 ) DEFAULT null,
                `postal_code` varchar ( 50 ),
                `type` varchar ( 100 )
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_status');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
     `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `provider` varchar( 20 ) NOT null,
                `status_id` varchar( 20 ) NOT null,
                `status` varchar( 1500 ),
                `date_time` varchar( 100 ) DEFAULT null,
                `likes` int( 8 ) DEFAULT null,
                `place` varchar( 100 ) DEFAULT null,
                `source` varchar( 500 ) DEFAULT null,
                `image_url` varchar( 1000 ) DEFAULT null,
                `link_url` varchar( 1000 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_twitter_mentions');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
    `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `mention_id` varchar( 30 ) NOT null,
                `tweet` varchar( 200 ) DEFAULT null,
                `date_time` varchar( 30 ) DEFAULT null,
                `likes` int( 8 ) DEFAULT null,
                `place` varchar( 100 ) DEFAULT null,
                `source` varchar( 300 ) DEFAULT null,
                `image_url` varchar( 1000 ) DEFAULT null,
                `link_url` varchar( 1000 ) DEFAULT null,
                `mentioned_by` varchar( 100 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

    $tbl = pSQL(_DB_PREFIX_ . 'lr_linkedin_companies');
    $create_table = <<<SQLQUERY
	CREATE TABLE IF NOT EXISTS `$tbl` (
    `id` int(11) NOT null AUTO_INCREMENT PRIMARY KEY,
                `ps_user_id` int( 11 ) NOT null,
                `company_id` varchar( 20 ) NOT null,
                `company_name` varchar( 200 ) DEFAULT null
	)
SQLQUERY;
    Db::getInstance()->Execute($create_table);

}

/**
 * Check table exist and get extended profile data.
 *
 * @param $table_name
 * @param $userId
 * @param $table_key
 * @return array|bool
 */
function loginRadiusCheckExtendedProfile($table_name, $userId)
{

    if (count(Db::getInstance()->executeS('SHOW TABLES LIKE "' . _DB_PREFIX_ . $table_name . '"'))) {
        $tbl = pSQL(_DB_PREFIX_ . $table_name);
        $extendedLocationResult = Db::getInstance()->executeS("SELECT * FROM $tbl  WHERE ps_user_id = " . (int)$userId);

        if (count($extendedLocationResult) > 0) {
            return $extendedLocationResult;
        }
        return false;
    }
}

/**
 * Prepare profile data html to print
 */
function prepare_profile_data($userId)
{
    $smarty = Context::getContext()->smarty;
    $profilefield_value = unserialize(Configuration::get('lr_social_profile_selection'));
    $noProfileData = true;
    $tables = array(
        'lr_basic_profile_data',
        'lr_extended_location_data',
        'lr_extended_profile_data',
        'lr_linkedin_companies',
        'lr_facebook_events',
        'lr_status',
        'lr_facebook_posts',
        'lr_twitter_mentions',
        'lr_groups',
        'lr_contacts',
        'lr_videos',
        'lr_likes',
        'lr_photos',
        'lr_albums',
    );
    $lr_tabs = $array = array();
    $user_profile = array();

    foreach ($tables as $tables_value) {
        $table_profile_key = str_replace('lr_', '', $tables_value);
        if (isset($userId) || in_array($table_profile_key, $profilefield_value)) {
            // basic profile data
            if ($result = loginRadiusCheckExtendedProfile($tables_value, $userId)) {
                $noProfileData = false;
                $lr_tabs[$tables_value] = ucwords(str_replace(array('lr_', '_'), array('', ' '), $tables_value));
                $user_profile[$table_profile_key] = $result;
            }
        }
    }
    $smarty->assign('lr_tabs', $lr_tabs);
    $smarty->assign('noProfileData', $noProfileData);

    if (!$noProfileData) {
        if (isset($user_profile['basic_profile_data']) && count($user_profile['basic_profile_data']) > 0) {
            $data = array(
                'General' => 'lr_basic_profile_data',
                'Email' => 'lr_emails',
            );
            $array[] = lr_social_profile_data_show_data_in_same_tabs($data, $userId, $user_profile['basic_profile_data']);
        }

        if (isset($user_profile['extended_profile_data']) && count($user_profile['extended_profile_data']) > 0) {

            $data = array('lr_extended_profile_data', 'lr_positions', 'lr_education',
                'lr_phone_numbers', 'lr_imaccounts', 'lr_addresses', 'lr_sports', 'lr_inspirational_people', 'lr_skills', 'lr_current_status', 'lr_certifications', 'lr_courses', 'lr_volunteer', 'lr_recommendations_received',
                'lr_languages', 'lr_patents', 'lr_favorites', 'lr_books', 'lr_games', 'lr_television_show', 'lr_movies',
            );

            $array[] = lr_social_profile_data_show_data_in_same_tabs($data, $userId, $user_profile['extended_profile_data']);
        }
        if (isset($user_profile)) {
            foreach ($tables as $table_name) {
                if ($table_name == 'lr_extended_profile_data' || $table_name == 'lr_basic_profile_data') {
                    continue;
                }

                $profile_key = str_replace('lr_', '', $table_name);
                if (isset($user_profile[$profile_key]) && is_array($user_profile[$profile_key])) {
                    $array[][$table_name] = $user_profile[$profile_key];
                }
            }
        }
    }

    // Companies.
    $tbl = pSQL(_DB_PREFIX_ . 'lr_companies');
    $companies = Db::getInstance()->ExecuteS("SELECT * from $tbl  WHERE `ps_user_id` =" . (int)$userId);
    if (count($companies) > 0) {
        $smarty->assign('companies', $companies);
    }

    $smarty->assign('lr_tabs_data', array_filter($array));
}


/**
 * show same tab table data in proper formt.
 *
 * @param $data
 * @param $userId
 * @param $profile_data
 * @param $show
 * @return string
 */
function lr_social_profile_data_show_data_in_same_tabs($data, $userId, $profile_data)
{
    $tab_data = array();
    foreach ($data as $value) {
        if ($value == 'lr_basic_profile_data' || $value == 'lr_extended_profile_data') {
            $result = $profile_data;
        } else {
            if (count(Db::getInstance()->executeS('SHOW TABLES LIKE "' . _DB_PREFIX_ . $value . '"'))) {
                $tbl = pSQL(_DB_PREFIX_ . $value);
                $result = Db::getInstance()->ExecuteS("SELECT * from $tbl  WHERE `ps_user_id` =" . (int)$userId);
            }
        }
        if (isset($result) && count($result) > 0) {
            $tab_data[$value] = $result;
        }
    }
    return $tab_data;
}

/**
 * Data value shoud not be  array and object
 * @param array $data
 * @return array
 */
function loginRadiusCheckData($data = array())
{
    foreach ($data as $key => $value) {

        if (is_array($value) || is_object($value)) {
            $data[$key] = '';
        }
    }
    return $data;
}

/**
 * Insert and update data in tables.
 *
 * @param $table_name
 * @param $data
 * @param bool $insert
 */
function loginRadiusInsertAndUpdateValues($table_name, $data, $insert = true)
{
    foreach ($data as $key => $value) {
        $data[$key] = Db::getInstance()->escape($value);
    }
    if ($insert) {
        $sql = 'INSERT IGNORE INTO ' . _DB_PREFIX_ . $table_name . '(`' . implode("`,`", array_keys($data)) . '`) VALUES ("' . implode('","', array_values($data)) . '")';
    } else {
        $userId = $data['ps_user_id'];
        unset($data['ps_user_id']);
        $string = '';
        foreach ($data as $key => $value) {
            $value = "'$value'";
            $string .= "`$key` = $value,";
        }
        $string = rtrim($string, ",");
        $tbl = pSQL(_DB_PREFIX_ . $table_name);
        $sql = "UPDATE  $tbl  SET  $string  WHERE `ps_user_id` = " . (int)$userId;
    }
    try {
        Db::getInstance()->execute($sql);
    } catch (Exception $e) {

    }
}

/**
 * Delete values from tables.
 *
 * @param $table_name
 * @param $userId
 */

function loginRadiusDeleteValues($table_name, $userId)
{
    $tbl = pSQL(_DB_PREFIX_ . $table_name);
    $sql = "DELETE FROM  $tbl  WHERE `ps_user_id` = " . (int)$userId;
    try {
        Db::getInstance()->execute($sql);
    } catch (Exception $e) {

    }
}

/**
 * Save Extended data.
 *
 * @param $user_profile_data
 * @param $customer
 */
function loginRadiusSaveExtendedUserProfileData($user_profile_data, $customer)
{
    include_once('LoginRadiusSDK.php');
    $lr_obj = new LoginRadius();
    $context = Context::getContext();
    $cookie = $context->cookie;
    $access_token = isset($cookie->lr_token) ? $cookie->lr_token : Tools::getValue('token');
    $profilefield_value = unserialize(Configuration::get('lr_social_profile_selection'));
    $userId = $customer->id;

    if (in_array('basic_profile_data', $profilefield_value)) {
        $data = array();
        $data['ps_user_id'] = $userId;
        $data['social_id'] = isset($user_profile_data->ID) ? $user_profile_data->ID : '';
        $data['provider'] = isset($user_profile_data->Provider) ? $user_profile_data->Provider : '';
        $data['prefix'] = isset($user_profile_data->Prefix) ? $user_profile_data->Prefix : '';
        $data['first_name'] = isset($user_profile_data->FirstName) ? $user_profile_data->FirstName : '';
        $data['full_name'] = isset($user_profile_data->FullName) ? $user_profile_data->FullName : '';
        $data['middle_name'] = isset($user_profile_data->MiddleName) ? $user_profile_data->MiddleName : '';
        $data['last_name'] = isset($user_profile_data->LastName) ? $user_profile_data->LastName : '';
        $data['suffix'] = isset($user_profile_data->Suffix) ? $user_profile_data->Suffix : '';
        $data['nick_name'] = isset($user_profile_data->NickName) ? $user_profile_data->NickName : '';
        $data['profile_name'] = isset($user_profile_data->ProfileName) ? $user_profile_data->ProfileName : '';
        $data['profile_url'] = isset($user_profile_data->ProfileUrl) ? $user_profile_data->ProfileUrl : '';
        $data['birth_date'] = loginRadiusGetDateOfBirth($user_profile_data->BirthDate);
        $data['gender'] = isset($user_profile_data->Gender) && $user_profile_data->Gender != '' ? $user_profile_data->Gender : 'unknown';
        $data['website'] = isset($user_profile_data->Website) ? $user_profile_data->Website : '';
        $data['thumbnail_image_url'] = isset($user_profile_data->ThumbnailImageUrl) ? $user_profile_data->ThumbnailImageUrl : '';
        $data['image_url'] = isset($user_profile_data->ImageUrl) ? $user_profile_data->ImageUrl : '';
        $data['country_code'] = isset($user_profile_data->Country->Code) && $user_profile_data->Country->Code != 'unknown' ? $user_profile_data->Country->Code : '';
        $data['country_name'] = isset($user_profile_data->Country->Name) && $user_profile_data->Country->Name != 'unknown' ? $user_profile_data->Country->Name : '';
        $data['local_country'] = isset($user_profile_data->LocalCountry) && $user_profile_data->LocalCountry != 'unknown' ? $user_profile_data->LocalCountry : '';
        $data['profile_country'] = isset($user_profile_data->ProfileCountry) && $user_profile_data->ProfileCountry != 'unknown' ? $user_profile_data->ProfileCountry : '';

        $data = loginRadiusCheckData($data);
        $tbl = pSQL(_DB_PREFIX_ . 'lr_basic_profile_data');
        if (!Db::getInstance()->executeS("SELECT * FROM  $tbl  WHERE `ps_user_id` = " . (int)$userId)) {
            loginRadiusInsertAndUpdateValues('lr_basic_profile_data', $data);
        } else {
            loginRadiusInsertAndUpdateValues('lr_basic_profile_data', $data, false);
        }

        // Emails.
        if (isset($user_profile_data->lr_Emails) && count($user_profile_data->lr_Emails) > 0) {
            foreach ($user_profile_data->lr_Emails as $lrEmail) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['email_type'] = $lrEmail->Type;
                $data['email'] = $lrEmail->Value;
                $data = loginRadiusCheckData($data);
                $tbl = pSQL(_DB_PREFIX_ . 'lr_emails');
                if (!Db::getInstance()->executeS("SELECT * FROM $tbl WHERE `ps_user_id` = " . (int)$userId)) {
                    loginRadiusInsertAndUpdateValues('lr_emails', $data);
                } else {
                    loginRadiusInsertAndUpdateValues('lr_emails', $data, false);
                }

            }
        }
    }

    // Insert extended location data if option is selected.
    if (in_array('extended_location_data', $profilefield_value)) {
        $data = array();
        $data['ps_user_id'] = $userId;
        $data['main_address'] = isset($user_profile_data->MainAddress) ? $user_profile_data->MainAddress : '';
        $data['hometown'] = isset($user_profile_data->HomeTown) ? $user_profile_data->HomeTown : '';
        $data['city'] = isset($user_profile_data->City) ? $user_profile_data->City : '';
        $data['local_city'] = isset($user_profile_data->LocalCity) ? $user_profile_data->LocalCity : '';
        $data['profile_city'] = isset($user_profile_data->ProfileCity) ? $user_profile_data->ProfileCity : '';
        $data['state'] = isset($user_profile_data->State) ? $user_profile_data->State : '';
        $data['postal_code'] = isset($user_profile_data->PostalCode) ? $user_profile_data->PostalCode : '';
        $data['country'] = isset($user_profile_data->Country) ? $user_profile_data->Country : '';
        $data['local_country'] = isset($user_profile_data->LocalCountry) ? $user_profile_data->LocalCountry : '';
        $data['profile_country'] = isset($user_profile_data->ProfileCountry) ? $user_profile_data->ProfileCountry : '';
        $data['region'] = isset($user_profile_data->Region) ? $user_profile_data->Region : '';
        $data['local_language'] = isset($user_profile_data->LocalLanguage) ? $user_profile_data->LocalLanguage : '';
        $data['language'] = isset($user_profile_data->Language) ? $user_profile_data->Language : '';
        $data = loginRadiusCheckData($data);
        $tbl = pSQL(_DB_PREFIX_ . 'lr_extended_location_data');
        if (!Db::getInstance()->executeS("SELECT * FROM $tbl WHERE `ps_user_id` = " . (int)$userId)) {
            loginRadiusInsertAndUpdateValues('lr_extended_location_data', $data);
        } else {
            loginRadiusInsertAndUpdateValues('lr_extended_location_data', $data, false);
        }
    }

    // Insert extended profile data if option is selected.
    if (in_array('extended_profile_data', $profilefield_value)) {
        $data = array();
        $data['ps_user_id'] = $userId;
        $data['website'] = isset($user_profile_data->Website) ? $user_profile_data->Website : '';
        $data['favicon'] = isset($user_profile_data->Favicon) ? $user_profile_data->Favicon : '';
        $data['industry'] = isset($user_profile_data->Industry) ? $user_profile_data->Industry : '';
        $data['about'] = isset($user_profile_data->About) ? $user_profile_data->About : '';
        $data['timezone'] = isset($user_profile_data->TimeZone) ? $user_profile_data->TimeZone : '';
        $data['verified'] = isset($user_profile_data->Verified) ? $user_profile_data->Verified : '';
        $data['last_profile_update'] = isset($user_profile_data->UpdatedTime) ? date('Y-m-d H:i:s', strtotime($user_profile_data->UpdatedTime)) : null;
        $data['created'] = isset($user_profile_data->Created) ? $user_profile_data->Created : '';
        $data['relationship_status'] = isset($user_profile_data->RelationshipStatus) ? $user_profile_data->RelationshipStatus : '';
        $data['quote'] = isset($user_profile_data->Quote) ? $user_profile_data->Quote : '';
        $user_profile_data->InterestedIn = isset($user_profile_data->InterestedIn) ? $user_profile_data->InterestedIn : '';
        $data['interested_in'] = is_array($user_profile_data->InterestedIn) ? implode(', ', $user_profile_data->InterestedIn) : $user_profile_data->InterestedIn;

        //Age Range - Min-Max
        $data['age_range_min'] = isset($user_profile_data->Age_Range_Min) ? $user_profile_data->Age_Range_Min : '';
        $data['age_range_max'] = isset($user_profile_data->Age_Range_Max) ? $user_profile_data->Age_Range_Max : '';

        if (isset($user_profile_data->Interests) && !is_string($user_profile_data->Interests)) {
            foreach ($user_profile_data->Interests as $value) {
                $data['interests'] = $value->InterestedName;
            }
        } else {
            $data['interests'] = isset($user_profile_data->Interests) ? $user_profile_data->Interests : '';
        }

        $data['religion'] = isset($user_profile_data->Religion) ? $user_profile_data->Religion : '';
        $data['political_view'] = isset($user_profile_data->Political) ? $user_profile_data->Political : '';
        $data['https_image_url'] = isset($user_profile_data->HttpsImageUrl) ? $user_profile_data->HttpsImageUrl : '';
        $data['followers_count'] = isset($user_profile_data->FollowersCount) ? (int)$user_profile_data->FollowersCount : 0;
        $data['friends_count'] = isset($user_profile_data->FriendsCount) ? (int)$user_profile_data->FriendsCount : 0;
        $data['is_geo_enabled'] = isset($user_profile_data->IsGeoEnabled) && $user_profile_data->IsGeoEnabled == 'True' ? '1' : '0';
        $data['total_status_count'] = isset($user_profile_data->TotalStatusCount) ? (int)$user_profile_data->TotalStatusCount : 0;
        $data['number_of_recommenders'] = isset($user_profile_data->NumberOfRecommenders) ? (int)$user_profile_data->NumberOfRecommenders : 0;
        $data['hirable'] = isset($user_profile_data->Hirable) ? (int)$user_profile_data->Hirable : 0;
        $data['repository_url'] = isset($user_profile_data->RepositoryUrl) ? $user_profile_data->RepositoryUrl : '';
        $data['age'] = isset($user_profile_data->Age) ? (int)$user_profile_data->Age : 0;
        $data['professional_headline'] = isset($user_profile_data->ProfessionalHeadline) ? $user_profile_data->ProfessionalHeadline : '';
        $data['provider_access_token'] = isset($user_profile_data->ProviderAccessCredential->AccessToken) ? serialize($user_profile_data->ProviderAccessCredential->AccessToken) : '';
        $data['provider_token_secret'] = isset($user_profile_data->ProviderAccessCredential->TokenSecret) ? $user_profile_data->ProviderAccessCredential->TokenSecret : '';

        $data = loginRadiusCheckData($data);
        $tbl = pSQL(_DB_PREFIX_ . 'lr_extended_profile_data');
        if (!Db::getInstance()->executeS("SELECT * FROM $tbl WHERE `ps_user_id` = " . (int)$userId)) {
            loginRadiusInsertAndUpdateValues('lr_extended_profile_data', $data);
        } else {
            loginRadiusInsertAndUpdateValues('lr_extended_profile_data', $data, false);
        }

        // positions
        if (is_array($user_profile_data->Positions) && count($user_profile_data->Positions) > 0) {
            loginRadiusDeleteValues('lr_positions', $userId);
            foreach ($user_profile_data->Positions as $lrPosition) {
                // companies
                if (isset($lrPosition->Company)) {
                    $temp = array();
                    $temp['ps_user_id'] = $userId;
                    $temp['company_name'] = $lrPosition->Company->Name;
                    $temp['company_type'] = $lrPosition->Company->Type;
                    $temp['industry'] = $lrPosition->Company->Industry;
                    $temp = loginRadiusCheckData($temp);
                    loginRadiusInsertAndUpdateValues('lr_companies', $temp);
                    $tempId = Db::getInstance()->Insert_ID();

                }
                // positions
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['position'] = $lrPosition->Position;
                $data['summary'] = $lrPosition->Summary;
                $data['start_date'] = $lrPosition->StartDate;
                $data['end_date'] = $lrPosition->EndDate;
                $data['is_current'] = isset($lrPosition->IsCurrent) && !empty($lrPosition->IsCurrent) ? (int)$lrPosition->IsCurrent : '0';
                $data['company'] = isset($tempId) ? $tempId : null;
                $data['location'] = $lrPosition->Location;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_positions', $data);
            }
        }

        // education
        if (is_array($user_profile_data->Educations) && count($user_profile_data->Educations) > 0) {
            loginRadiusDeleteValues('lr_education', $userId);
            foreach ($user_profile_data->Educations as $education) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['school'] = $education->School;
                $data['year'] = $education->year;
                $data['type'] = $education->type;
                $data['notes'] = $education->notes;
                $data['activities'] = $education->activities;
                $data['degree'] = $education->degree;
                $data['field_of_study'] = $education->fieldofstudy;
                $data['start_date'] = $education->StartDate;
                $data['end_date'] = $education->EndDate;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_education', $data);
            }
        }

        // phone numbers
        if (is_array($user_profile_data->PhoneNumbers) && count($user_profile_data->PhoneNumbers) > 0) {
            loginRadiusDeleteValues('lr_phone_numbers', $userId);
            foreach ($user_profile_data->PhoneNumbers as $lrPhoneNumber) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['number_type'] = $lrPhoneNumber->PhoneType;
                $data['phone_number'] = $lrPhoneNumber->PhoneNumber;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_phone_numbers', $data);

            }
        }

        // IM Accounts
        if (is_array($user_profile_data->IMAccounts) && count($user_profile_data->IMAccounts) > 0) {
            loginRadiusDeleteValues('lr_imaccounts', $userId);
            foreach ($user_profile_data->IMAccounts as $lrImacc) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['account_type'] = $lrImacc->AccountType;
                $data['account_username'] = $lrImacc->AccountName;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_imaccounts', $data);
            }
        }

        // Addresses
        if (is_array($user_profile_data->Addresses) && count($user_profile_data->Addresses) > 0) {
            loginRadiusDeleteValues('lr_addresses', $userId);
            foreach ($user_profile_data->Addresses as $lraddress) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['type'] = $lraddress->Type;
                $data['address_line1'] = $lraddress->Address1;
                $data['address_line2'] = $lraddress->Address2;
                $data['city'] = $lraddress->City;
                $data['state'] = $lraddress->State;
                $data['postal_code'] = $lraddress->PostalCode;
                $data['region'] = $lraddress->Region;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_addresses', $data);
            }
        }

        // Sports
        if (is_array($user_profile_data->Sports) && count($user_profile_data->Sports) > 0) {
            loginRadiusDeleteValues('lr_sports', $userId);

            foreach ($user_profile_data->Sports as $lrSport) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['sport_id'] = $lrSport->Id;
                $data['sport'] = $lrSport->Name;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_sports', $data);
            }
        }

        // Inspirational People
        if (is_array($user_profile_data->InspirationalPeople) && count($user_profile_data->InspirationalPeople) > 0) {
            loginRadiusDeleteValues('lr_inspirational_people', $userId);
            foreach ($user_profile_data->InspirationalPeople as $lrIP) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['social_id'] = $lrIP->Id;
                $data['name'] = $lrIP->Name;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_inspirational_people', $data);

            }
        }

        // Skills
        if (is_array($user_profile_data->Skills) && count($user_profile_data->Skills) > 0) {

            loginRadiusDeleteValues('lr_skills', $userId);
            foreach ($user_profile_data->Skills as $lrSkill) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['skill_id'] = $lrSkill->Id;
                $data['name'] = $lrSkill->Name;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_skills', $data);
            }
        }

        // Current Status
        if (is_array($user_profile_data->CurrentStatus) && count($user_profile_data->CurrentStatus) > 0) {
            loginRadiusDeleteValues('lr_current_status', $userId);
            foreach ($user_profile_data->CurrentStatus as $lrCurrentStatus) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['status_id'] = $lrCurrentStatus->Id;
                $data['status'] = $lrCurrentStatus->Text;
                $data['source'] = $lrCurrentStatus->Source;
                $data['created_date'] = $lrCurrentStatus->CreatedDate;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_current_status', $data);
            }
        }

        // Certifications
        if (is_array($user_profile_data->Certifications) && count($user_profile_data->Certifications) > 0) {
            loginRadiusDeleteValues('lr_certifications', $userId);
            foreach ($user_profile_data->Certifications as $lrCertification) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['certification_id'] = $lrCertification->Id;
                $data['certification_name'] = $lrCertification->Name;
                $data['authority'] = $lrCertification->Authority;
                $data['license_number'] = $lrCertification->Number;
                $data['start_date'] = $lrCertification->StartDate;
                $data['end_date'] = $lrCertification->EndDate;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_certifications', $data);
            }
        }

        // Courses
        if (is_array($user_profile_data->Courses) && count($user_profile_data->Courses) > 0) {
            loginRadiusDeleteValues('lr_courses', $userId);
            foreach ($user_profile_data->Courses as $lrCourse) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['course_id'] = $lrCourse->Id;
                $data['course'] = $lrCourse->Name;
                $data['course_number'] = $lrCourse->Number;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_courses', $data);
            }
        }

        // Volunteer
        if (is_array($user_profile_data->Volunteer) && count($user_profile_data->Volunteer) > 0) {
            loginRadiusDeleteValues('lr_volunteer', $userId);
            foreach ($user_profile_data->Volunteer as $lrVolunteer) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['volunteer_id'] = $lrVolunteer->Id;
                $data['role'] = $lrVolunteer->Role;
                $data['organization'] = $lrVolunteer->Organization;
                $data['cause'] = $lrVolunteer->Cause;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_volunteer', $data);
            }
        }

        // Recommendations received
        if (is_array($user_profile_data->RecommendationsReceived) && count($user_profile_data->RecommendationsReceived) > 0) {
            loginRadiusDeleteValues('lr_recommendations_received', $userId);
            foreach ($user_profile_data->RecommendationsReceived as $lrRR) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['recommendation_id'] = $lrRR->Id;
                $data['recommendation_type'] = $lrRR->RecommendationType;
                $data['recommendation_text'] = $lrRR->RecommendationText;
                $data['recommender'] = $lrRR->Recommender;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_recommendations_received', $data);
            }
        }

        // Languages
        if (is_array($user_profile_data->Languages) && count($user_profile_data->Languages) > 0) {
            loginRadiusDeleteValues('lr_languages', $userId);
            foreach ($user_profile_data->Languages as $lrLanguage) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['language_id'] = $lrLanguage->Id;
                $data['language'] = $lrLanguage->Name;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_languages', $data);
            }
        }

        // Patents
        if (is_array($user_profile_data->Patents) && count($user_profile_data->Patents) > 0) {
            loginRadiusDeleteValues('lr_patents', $userId);
            foreach ($user_profile_data->Patents as $lrPatent) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['patent_id'] = $lrPatent->Id;
                $data['title'] = $lrPatent->Title;
                $data['date'] = $lrPatent->Date;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_patents', $data);
            }
        }
        // games
        if (is_array($user_profile_data->Games) && count($user_profile_data->Games) > 0) {
            loginRadiusDeleteValues('lr_games', $userId);
            foreach ($user_profile_data->Games as $lrGames) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['game_id'] = $lrGames->Id;
                $data['category'] = $lrGames->Category;
                $data['name'] = $lrGames->Name;
                $data['created_date'] = $lrGames->CreatedDate;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_games', $data);
            }
        }

        // television shows
        if (is_array($user_profile_data->TeleVisionShow) && count($user_profile_data->TeleVisionShow) > 0) {
            loginRadiusDeleteValues('lr_television_show', $userId);
            foreach ($user_profile_data->TeleVisionShow as $lrShow) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['television_id'] = $lrShow->Id;
                $data['category'] = $lrShow->Category;
                $data['name'] = $lrShow->Name;
                $data['created_date'] = $lrShow->CreatedDate;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_television_show', $data);
            }
        }

        //movies
        if (is_array($user_profile_data->Movies) && count($user_profile_data->Movies) > 0) {
            loginRadiusDeleteValues('lr_movies', $userId);
            foreach ($user_profile_data->Movies as $lrMovie) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['movie_id'] = $lrMovie->Id;
                $data['category'] = $lrMovie->Category;
                $data['name'] = $lrMovie->Name;
                $data['created_date'] = $lrMovie->CreatedDate;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_movies', $data);
            }
        }

        //books
        if (is_array($user_profile_data->Movies) && count($user_profile_data->Movies) > 0) {
            loginRadiusDeleteValues('lr_books', $userId);
            foreach ($user_profile_data->Movies as $lrBook) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['book_id'] = $lrBook->Id;
                $data['category'] = $lrBook->Category;
                $data['name'] = $lrBook->Name;
                $data['created_date'] = $lrBook->CreatedDate;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_books', $data);
            }
        }


        // Favorites
        if (is_array($user_profile_data->FavoriteThings) && count($user_profile_data->FavoriteThings) > 0) {
            loginRadiusDeleteValues('lr_favorites', $userId);
            foreach ($user_profile_data->FavoriteThings as $lrFavorite) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['social_id'] = $lrFavorite->Id;
                $data['name'] = $lrFavorite->Name;
                $data['type'] = $lrFavorite->Type;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_favorites', $data);
            }
        } //END FAVORITES
    }

    // Insert contacts if option is selected
    if (in_array($user_profile_data->Provider, array('twitter', 'facebook', 'linkedin', 'google', 'yahoo', 'foursquare', 'live', 'renren', 'vkontakte')) && in_array('contacts', $profilefield_value)) {
        try {
            $contacts = $lr_obj->loginradiusGetContacts($access_token);
        } catch (LoginRadiusException $e) {
            $contacts = null;
            //error_log( $user_profile_data->Provider . ' failed getting (contacts) ' . Tools::jsonEncode( $e->errorResponse ) );
        }

        if (isset($contacts) && is_array($contacts->Data) && count($contacts->Data) > 0) {
            loginRadiusDeleteValues('lr_contacts', $userId);
            foreach ($contacts->Data as $contact) {
                // create array to insert data
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['social_id'] = $contact->ID;
                $data['provider'] = $user_profile_data->Provider;
                $data['name'] = $contact->Name;
                $data['email'] = $contact->EmailID;
                $data['profile_url'] = $contact->ProfileUrl;
                $data['image_url'] = $contact->ImageUrl;
                $data['status'] = $contact->Status;
                $data['industry'] = $contact->Industry;
                $data['country'] = $contact->Country;
                $data['gender'] = $contact->Gender;
                $data['phone_number'] = $contact->PhoneNumber;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_contacts', $data);
            }
        }
    }

    // Insert LinkedIn Companies if option is selected
    if (in_array($user_profile_data->Provider, array('linkedin')) && in_array('linkedin_companies', $profilefield_value)) {
        try {
            $linkedInCompanies = $lr_obj->loginradiusGetFollowedCompanies($access_token);
        } catch (LoginRadiusException $e) {
            $linkedInCompanies = null;
        }

        if (isset($linkedInCompanies) && is_array($linkedInCompanies) && count($linkedInCompanies) > 0) {
            foreach ($linkedInCompanies as $company) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['company_id'] = $company->ID;
                $data['company_name'] = $company->Name;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_linkedin_companies', $data);
            }
        }
    }

    // Insert status if option is selected
    if (in_array($user_profile_data->Provider, array('twitter', 'facebook', 'linkedin', 'renren', 'vkontakte')) && in_array('status', $profilefield_value)) {
        try {
            $status = $lr_obj->loginradiusGetStatus($access_token);
        } catch (LoginRadiusException $e) {
            $status = null;
        }

        if (isset($status) && is_array($status) && count($status) > 0) {
            loginRadiusDeleteValues('lr_status', $userId);
            foreach ($status as $lrStatus) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['provider'] = $user_profile_data->Provider;
                $data['status_id'] = $lrStatus->Id;
                $data['status'] = $lrStatus->Text;
                $data['date_time'] = $lrStatus->DateTime;
                $data['likes'] = $lrStatus->Likes;
                $data['place'] = $lrStatus->Place;
                $data['source'] = $lrStatus->Source;
                $data['image_url'] = $lrStatus->ImageUrl;
                $data['link_url'] = $lrStatus->LinkUrl;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_status', $data);
            }
        }
    }

    // Insert mentions if option is selected
    if ($user_profile_data->Provider == 'twitter' && in_array('twitter_mentions', $profilefield_value)) {
        try {
            $mentions = $lr_obj->loginradiusGetMentions($access_token);
        } catch (LoginRadiusException $e) {
            $mentions = null;
        }

        if (isset($mentions) && is_array($mentions) && count($mentions) > 0) {
            foreach ($mentions as $mention) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['mention_id'] = $mention->Id;
                $data['tweet'] = $mention->Text;
                $data['date_time'] = $mention->DateTime;
                $data['likes'] = $mention->Likes;
                $data['place'] = $mention->Place;
                $data['source'] = $mention->Source;
                $data['image_url'] = $mention->ImageUrl;
                $data['link_url'] = $mention->LinkUrl;
                $data['mentioned_by'] = $mention->Name;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_twitter_mentions', $data);
            }
        }
    }

    // Insert groups if option is selected
    if (in_array($user_profile_data->Provider, array('facebook', 'vkontakte')) && in_array('groups', $profilefield_value)) {
        try {
            $groups = $lr_obj->loginradiusGetGroups($access_token);
        } catch (LoginRadiusException $e) {
            $groups = null;
        }

        if (isset($groups) && is_array($groups) && count($groups) > 0) {
            loginRadiusDeleteValues('lr_groups', $userId);
            foreach ($groups as $group) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['provider'] = $user_profile_data->Provider;
                $data['country'] = $group->Country;
                $data['description'] = $group->Description;
                $data['email'] = $group->Email;
                $data['group_id'] = $group->ID;
                $data['image'] = $group->Image;
                $data['logo'] = $group->Logo;
                $data['member_count'] = $group->MemberCount;
                $data['name'] = $group->Name;
                $data['postal_code'] = $group->PostalCode;
                $data['type'] = $group->Type;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_groups', $data);
            }
        }
    }

    // Insert Facebook Likes, if option is selected
    if ($user_profile_data->Provider == 'facebook' && in_array('likes', $profilefield_value)) {
        try {
            $fblikes = $lr_obj->loginradiusGetLikes($access_token);
        } catch (LoginRadiusException $e) {
            $fblikes = null;
        }

        if (isset($fblikes) && count($fblikes) > 0) {
            foreach ($fblikes as $like) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['like_id'] = $like->ID;
                $data['name'] = $like->Name;
                $data['category'] = $like->Category;
                $data['created_date'] = date('Y-m-d H:i:s', strtotime($like->CreatedDate));
                $data['website'] = $like->Website;
                $data['description'] = $like->Description;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_facebook_likes', $data);
            }
        }
    }

    // Insert facebook events if option is selected
    if ($user_profile_data->Provider == 'facebook' && in_array('facebook_events', $profilefield_value)) {
        try {
            $events = $lr_obj->loginradiusGetEvents($access_token);
        } catch (LoginRadiusException $e) {
            $events = null;
        }

        if (isset($events) && is_array($events) && count($events) > 0) {
            loginRadiusDeleteValues('lr_facebook_events', $userId);
            foreach ($events as $event) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['event_id'] = $event->ID;
                $data['description'] = $event->Description;
                $data['name'] = $event->Name;
                $data['start_time'] = date('Y-m-d H:i:s', strtotime($event->StartTime));
                $data['end_time'] = date('Y-m-d H:i:s', strtotime($event->EndTime));
                $data['privacy'] = $event->Privacy;
                $data['rsvp_status'] = $event->RsvpStatus;
                $data['location'] = $event->Location;
                $data['owner_id'] = $event->OwnerId;
                $data['owner_name'] = $event->OwnerName;
                $data['updated_date'] = date('Y-m-d H:i:s', strtotime($event->UpdatedDate));
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_facebook_events', $data);

            }
        }
    }

    // Insert posts if option is selected
    if ($user_profile_data->Provider == 'facebook' && in_array('facebook_posts', $profilefield_value)) {
        try {
            $posts = $lr_obj->loginradiusGetPosts($access_token);
        } catch (LoginRadiusException $e) {
            $posts = null;
        }

        if (isset($posts) && is_array($posts) && count($posts) > 0) {
            loginRadiusDeleteValues('lr_facebook_posts', $userId);
            foreach ($posts as $post) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['post_id'] = $post->ID;
                $data['name'] = $post->Name;
                $data['title'] = $post->Title;
                $data['start_time'] = date('Y-m-d H:i:s', strtotime($post->StartTime));
                $data['update_time'] = date('Y-m-d H:i:s', strtotime($post->UpdateTime));
                $data['message'] = $post->Message;
                $data['place'] = $post->Place;
                $data['picture'] = $post->Picture;
                $data['likes'] = $post->Likes;
                $data['shares'] = $post->Share;
                $data['type'] = $post->Type;
                $data = loginRadiusCheckData($data);

                loginRadiusInsertAndUpdateValues('lr_facebook_posts', $data);
            }
        }
    }

    // Album API
    if ($user_profile_data->Provider == 'facebook' && in_array('albums', $profilefield_value)) {
        try {
            $albums = $lr_obj->loginradiusGetPhotoAlbums($access_token);
        } catch (LoginRadiusException $e) {
            $albums = null;
            //error_log( $user_profile_data->Provider . ' failed getting (albums) ' . Tools::jsonEncode( $e->errorResponse ) );
        }

        if (isset($albums) && is_array($albums) && count($albums) > 0) {
            foreach ($albums as $album) {
                $data = array();
                $data['ps_user_id'] = $userId;
                $data['album_id'] = $album->ID;
                $data['owner_id'] = $album->OwnerId;
                $data['title'] = $album->Title;
                $data['description'] = $album->Description;
                $data['location'] = $album->Location;
                $data['type'] = $album->Type;
                $data['created_date'] = date('Y-m-d H:i:s', strtotime($album->CreatedDate));
                $data['updated_date'] = date('Y-m-d H:i:s', strtotime($album->UpdatedDate));
                $data['cover_image_url'] = $album->CoverImageUrl;
                $data['image_count'] = $album->ImageCount;
                $data['directory_url'] = $album->DirectoryUrl;
                $data = loginRadiusCheckData($data);
                loginRadiusInsertAndUpdateValues('lr_albums', $data);
            }
        }
    }
}
