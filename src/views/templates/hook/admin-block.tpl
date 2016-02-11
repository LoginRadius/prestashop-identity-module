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

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#lr_tabs").tabs();
    });</script>
<div class="panel">
    <div class="panel-heading">
        <i class="icon-file"></i> {l s='LoginRadius User Profile' mod='loginradiusadvancemodule'} <span
                class="badge"></span>
    </div>
    <div id="lr_tabs">
        <ul>
            {foreach $lr_tabs as $lr_tab_key => $lr_tab_value}
                <li style="float: left; list-style: none;">
                    <a style="margin:0; font-size:12px; font-weight:bold" class="nav-tab"
                       href="#tabs-{$lr_tab_key|escape:'htmlall':'UTF-8'}"> {$lr_tab_value|escape:'htmlall':'UTF-8'}
                    </a>
                </li>
            {/foreach}
        </ul>
        {if $noProfileData}
            <strong>Profile data not found.</strong>
        {else}
            {foreach $lr_tabs_data as $lr_tabs_data_key => $lr_tabs_data_value}
                <div id="tabs-{key($lr_tabs_data_value)|escape:'htmlall':'UTF-8'}">
                    {foreach $lr_tabs_data_value as $lr_tabs_data_h2 => $lr_array}
                        {$sub_table = false}
                        {if $lr_tabs_data_h2 == 'lr_basic_profile_data' || $lr_tabs_data_h2 == 'lr_extended_profile_data'}
                            {$sub_table = true}
                            {$lr_tabs_data_h2 = 'General'}
                        {/if}
                        {$lr_tabs_data_h2 = ucwords(str_replace(array("lr_", "_"), array("", " "), {$lr_tabs_data_h2|escape:'htmlall':'UTF-8'}))}
                        <h2>{$lr_tabs_data_h2|escape:'htmlall':'UTF-8'}</h2>
                        {$count = 1}
                        {$style = ''}
                        <table id="sociallogin_userprofile_table" cellspacing="0" style="word-break: break-all;">
                            {if $sub_table}
                            <tfoot>
                            {$style = 'style="background-color:#fcfcfc"'}
                            {foreach $lr_array as $temp_key => $temp}
                                {if (($count % 2) == 0)}
                                    {$style = 'style="background-color:#fcfcfc"'}
                                {/if}
                                {foreach $temp as $key => $val}

                                    {if ($key == 'provider_access_token')}
                                        {$val = unserialize($val)}
                                    {/if}
                                    {if ($key == 'id' || $key == 'ps_user_id')}
                                        {continue}
                                    {else}
                                        <tr {$style|escape:'htmlall':'UTF-8'}>
                                            <th scope="col" class="manage-colum">{ucwords(str_replace("_", " ", $key))|escape:'htmlall':'UTF-8'}</th>

                                            {if ($key == 'picture' && !empty($val))}
                                                <th scope="col" class="manage-colum">
                                                    <img height="60" width="60" src={$val|escape:'htmlall':'UTF-8'}/></th>
                                            {else}
                                                <th scope="col" class="manage-colum">{$val|escape:'htmlall':'UTF-8'}</th>
                                            {/if}
                                        </tr>
                                    {/if}
                                {/foreach}
                                {$count = $count + 1}
                            {/foreach}
                            {else}
                            <thead>
                            <tr>
                                {foreach $lr_array as $data_key => $key }
                                    {foreach array_keys($key) as $value }
                                        {if ($value == 'ps_user_id' || $value == 'id')}
                                            {continue}
                                        {/if}
                                        <th scope="col"><strong>{ucwords(str_replace("_", " ", $value))|escape:'htmlall':'UTF-8'}</strong></th>
                                    {/foreach}
                                    {break}
                                {/foreach}
                            </tr>
                            </thead>
                            <tfoot>
                            {foreach $lr_array as $contact_key  =>$contact}
                                {if (($count % 2) == 0) }
                                    {$style = 'style="background-color:#fcfcfc"'}
                                {/if}
                                <tr {$style|escape:'htmlall':'UTF-8'}>
                                    {foreach $contact as $key => $val}
                                        {if ($key == 'ps_user_id' || $key == 'id')}
                                            {continue}
                                        {elseif ($key == 'company' && $val != null && $val != '') && $companies}

                                            <th scope="col" class="manage-colum">
                                            <table cellspacing="0" style="word-break: break-all;">
                                                <thead>
                                                <tr>
                                                    {foreach $companies as $companies_keys }
                                                        {foreach $companies_keys as $companies_key => $companies_value }
                                                            {if ($companies_key == 'ps_user_id' || $companies_key == 'id')}
                                                                {continue}
                                                            {/if}
                                                            <th scope="col"><strong>{ucwords(str_replace("_", " ", $companies_key))|escape:'htmlall':'UTF-8'}</strong></th>
                                                        {/foreach}
                                                        {break}
                                                    {/foreach}
                                                </tr>
                                                </thead>
                                                <tfoot><tr>
                                                    {foreach $companies as $companies_keys }
                                                        {foreach  $companies_keys as $companies_key => $companies_value }
                                                            {if ($companies_key == 'ps_user_id' || $companies_key == 'id')}
                                                                {continue}
                                                            {/if}
                                                            <th scope="col" class="manage-colum">{$companies_value|escape:'htmlall':'UTF-8'}</th>
                                                        {/foreach}
                                                        {break}
                                                    {/foreach}
                                                    </tr></tfoot>
                                           </table></th>
                                        {else}
                                            {if (!empty($val) && ($key == 'image_url' || $key == 'picture')) }
                                                {$val = "<img height=50 width=50 src={$val}>"}
                                            {/if}
                                            <th scope="col" class="manage-colum">{$val|escape:'htmlall':'UTF-8'}</th>
                                        {/if}
                                    {/foreach}
                                </tr>
                                {$count = $count + 1}
                            {/foreach}
                            {/if}
                            </tfoot>
                        </table>
                    {/foreach}
                </div>
            {/foreach}
        {/if}
    </div>
</div>