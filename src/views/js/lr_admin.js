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

var checkedhorizontal = document.getElementsByName('lr_chooseshare');
var checkedvertical = document.getElementsByName('lr_chooseverticalshare');

document.write("<script type='text/javascript'>var islrsharing = true; var islrsocialcounter = true;</script>");
document.write("<script src='//share.loginradius.com/Content/js/LoginRadius.js' type='text/javascript'></script>");

$(document).ready(function () {
    $("input[name='lr_chooseshare']").click(function () {
        var val = $(this).val();
        if ($.inArray(val, ['0', '1', '10']) != -1) {

            toggle_loginradius_horizontal_sharing(true);
        }
        else if ($.inArray(val, ['2', '3']) != -1) {


            toggle_loginradius_horizontal_sharing(false);
        }
        else {


            toggle_loginradius_horizontal_sharing(true, true)
        }
    });
    $("input[name='lr_chooseverticalshare']").click(function () {
        var val = $(this).val();
        if ($.inArray(val, ['4', '5']) != -1) {

            loginradius_toggle_vertical_sharing(true);
        }

        else {

            loginradius_toggle_vertical_sharing(false)
        }
    });
    $("input[name='lr_redirect']").click(function () {
        hideRedirectUrlTextBox();
    });
    $(function () {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        $("#verticalsortable").sortable();
        $("#verticalsortable").disableSelection();
    });

});
function sharingproviderlist() {
    var sharing = $SS.Providers.More;
    var div = $('#shareprovider');
    var rowitem = $("<tr class='row_white shareprovider'>");
    var div_vertical = $('#verticalshareprovider');
    var veritcalrowitem = $("<tr class='row_white verticalshareprovider'>");
    if (div && div_vertical) {
        for (var i = 0; i < sharing.length; i++) {
            var listItem = $("<td><label><input type='checkbox' id='" + sharing[i].toLowerCase() + "' onChange='loginRadiusSharingLimit(this),loginRadiusRearrangeProviderList(this)' name=lr_socialshare_show_providers_list['" + sharing[i].toLowerCase() + "'] value='" + sharing[i].toLowerCase() + "' />" + sharing[i] + "</label></td>");
            rowitem.append(listItem);
            div.append(rowitem);
            var listItem = $("<td><label><input type='checkbox' id='vertical-" + sharing[i].toLowerCase() + "' onChange='loginRadiusverticalSharingLimit(this),loginRadiusverticalRearrangeProviderList(this)' name=lr_socialverticalshare_providers_list['" + sharing[i].toLowerCase() + "'] value='" + sharing[i].toLowerCase() + "' />" + sharing[i] + "</label></td>");
            veritcalrowitem.append(listItem);
            div_vertical.append(veritcalrowitem);
        }

        $('input[name^="lr_rearrange_settings[]"]').each(function () {
            var elem = $(this);
            if (!elem.checked) {
                $('#' + elem.val()).attr('checked', 'checked');
            }
        });
        $('input[name^="lr_vertical_rearrange_settings[]"]').each(function () {
            var elem = $(this);
            if (!elem.checked) {
                $('#vertical-' + elem.val()).attr('checked', 'checked');
            }
        });
    }
}
function counterproviderlist(HorizontalProvider, verticalProvider) {
    var counter = $SC.Providers.All;
    var div = $('#shareprovider');
    var rowitem = $("<tr class='row_white counterprovider'>");
    var div_vertical = $('#verticalshareprovider');
    var verticalrowitem = $("<tr class='row_white verticalcounterprovider'>");
    if (div && div_vertical) {
        for (var i = 0; i < counter.length; i++) {
            var value = counter[i].split(' ').join('');
            value = value.replace("++", "plusplus");
            value = value.replace("+", "plus");
            var listItem = $("<td><label><input type='checkbox' id='edit-counter-" + value + "' name='lr_socialshare_show_counter_list[]' value='" + counter[i] + "' />" + counter[i] + "</label></td>");
            rowitem.append(listItem);
            div.append(rowitem);
            var listItem = $("<td><label><input type='checkbox' id='edit-vertical-counter-" + value + "' name='lr_socialshare_counter_list[]' value='" + counter[i] + "' class='form-checkbox' />" + counter[i] + "</label></td>");
            verticalrowitem.append(listItem);
            div_vertical.append(verticalrowitem);
        }
        if (HorizontalProvider.length > 0) {
            for (var i = 0; i < HorizontalProvider.length; i++) {
                if (!HorizontalProvider[i].checked) {
                    value = HorizontalProvider[i].split(' ').join('');
                    value = value.replace("++", "plusplus");
                    value = value.replace("+", "plus");
                    $('#edit-counter-' + value).attr('checked', 'checked');
                }
            }
        }
        if (verticalProvider.length > 0) {
            for (var i = 0; i < verticalProvider.length; i++) {
                if (!verticalProvider[i].checked) {
                    value = verticalProvider[i].split(' ').join('');
                    value = value.replace("++", "plusplus");
                    value = value.replace("+", "plus");
                    $('#edit-vertical-counter-' + value).attr('checked', 'checked');
                }
            }
        }
    }
}
// prepare rearrange provider list
function loginRadiusverticalRearrangeProviderList(elem) {
    var ul = $('#verticalsortable');
    if (elem.checked) {
        var provider = $("<li id='loginRadiusLIvertical" + elem.value + "' title='" + elem.value + "' class='lrshare_iconsprite32 lrshare_" + elem.value.toLowerCase() + "'><input type='hidden' value='" + elem.value + "' name='lr_vertical_rearrange_settings[]'></li>");
        ul.append(provider);
    } else {
        if ($('#loginRadiusLIvertical' + elem.value)) {
            $('#loginRadiusLIvertical' + elem.value).remove();
        }
    }
}
// check provider more then 9 select
function loginRadiusverticalSharingLimit(elem) {
    loginRadiusSubmitSharingLimit(elem, $('input[name^="lr_vertical_rearrange_settings[]"]'), jQuery("#loginRadiusverticalSharingLimit"));
}
function loginRadiusSubmitSharingLimit(elem, providers, div) {
    var checkCount = providers.length;
    if (elem.checked) {
        // count checked providers
        checkCount++;
        if (checkCount >= 10) {
            elem.checked = false;
            div.show('slow');
            setTimeout(function () {
                div.hide('slow');
            }, 2000);
            return;
        }
    }
}
// prepare rearrange provider list
function loginRadiusRearrangeProviderList(elem) {
    var ul = $('#sortable');
    if (elem.checked) {
        var provider = $("<li id='loginRadiusLI" + elem.id + "' title='" + elem.id + "' class='lrshare_iconsprite32 lrshare_" + elem.value.toLowerCase() + "'><input type='hidden' value='" + elem.id + "' name='lr_rearrange_settings[]'></li>");
        ul.append(provider);
    } else {
        if ($('#loginRadiusLI' + elem.value)) {
            $('#loginRadiusLI' + elem.value).remove();
        }
    }
}
// check provider more then 9 select
function loginRadiusSharingLimit(elem) {
    loginRadiusSubmitSharingLimit(elem, $('input[name^="lr_rearrange_settings[]"]'), jQuery("#loginRadiusSharingLimit"));
}
function loginradius_toggle_vertical_sharing(is_social_share) {
    var sharing_network = is_social_share ? "show" : "hide";
    var counter_network = is_social_share ? "hide" : "show";
    var color = is_social_share ? "#EBEBEB" : "#FFFFFF";
    $('.vertical_location').css({"background": color});
    
    $("#verticalshareprovider").closest('div[class="form-group"]').show();
    $('.row_white.verticalcounterprovider')[counter_network]();
    $('.row_white.verticalshareprovider')[sharing_network]();
    
    $('#verticalsortable').closest('div[class="form-group"]')[sharing_network]();
}
function toggle_loginradius_horizontal_sharing(is_social_share, is_social_counter) {
    var display_sharing_network = is_social_share ? "show" : "hide";
    var display_simple_network = "hide";
    var color = '#FFFFFF';
    
    $("#shareprovider").closest('div[class="form-group"]')[display_sharing_network]();
    
    if (is_social_counter) {
        display_sharing_network = 'hide';
        display_simple_network = 'show';
        color = '#EBEBEB';
    }
    $('.horizontal_location').css({"background": color});
    $('#sortable').closest('div[class="form-group"]')[display_sharing_network]();
    $('.row_white.counterprovider')[display_simple_network]();
    $('.row_white.shareprovider')[display_sharing_network]();
}
function makeVisibletoWidget(val) {
    if (val == 'vr_widget') {
        Makevertivisible();
    }
    else {
        Makehorivisible();
    }
    hideRedirectUrlTextBox();
}
function hideRedirectUrlTextBox() {
    var show = $("input[name='lr_redirect']:checked").val();
    if (show == 'url') {
        $('#lr_redirect_add_url').show();
    }
    else {
        $('#lr_redirect_add_url').hide();
    }
}
function Makevertivisible() {
   
    for (var i = 0; i < checkedvertical.length; i++) {
        if (checkedvertical[i].checked) {
            if (checkedvertical[i].value == 6 || checkedvertical[i].value == 7)
                loginradius_toggle_vertical_sharing(false);
            else if (checkedvertical[i].value == 4 || checkedvertical[i].value == 5)
                loginradius_toggle_vertical_sharing(true);
        }
    }
    
    $('#shareprovider').closest('div[class="form-group"]').hide();
    $('#sortable').closest('div[class="form-group"]').hide();
    $("input[name='lr_enable_mobile_friendly']").closest('div[class="form-group"]').hide();
    
    
    toggle_widget_position(false);
}
function Makehorivisible() {
    for (var i = 0; i < checkedhorizontal.length; i++) {
        if (checkedhorizontal[i].checked) {
            if (checkedhorizontal[i].value == 2 || checkedhorizontal[i].value == 3)
                toggle_loginradius_horizontal_sharing(false);
            else if (checkedhorizontal[i].value == 8 || checkedhorizontal[i].value == 9)
                toggle_loginradius_horizontal_sharing(true, true);
            else if (checkedhorizontal[i].value == 0 || checkedhorizontal[i].value == 1 || checkedhorizontal[i].value == 10)
                toggle_loginradius_horizontal_sharing(true);
        }
    }
   
    $('#verticalsortable').closest('div[class="form-group"]').hide();
     $("input[name='lr_enable_mobile_friendly']").closest('div[class="form-group"]').show();
    $("#verticalshareprovider").closest('div[class="form-group"]').hide();
    toggle_widget_position(true);
}
function toggle_widget_position(is_position) {
    var horizontal_position = is_position ? 'show' : 'hide';
    var vertical_position = is_position ? 'hide' : 'show';
    var display = is_position ? 'none' : 'block';
    var left = is_position ? '-142px' : '-50px';
    var anchor_color_1 = is_position ? 'rgb(0, 204, 255)' : 'rgb(0, 0, 0)';
    var anchor_color_2 = is_position ? 'rgb(0, 0, 0)' : 'rgb(0, 204, 255)';
    $('.vertical_sharing_position').closest('div[class="form-group"]').css({'display': display});
    $('.sharing_block').css({"background": "#EBEBEB"});
    $('.sharevertical').closest('div[class="form-group"]')[vertical_position]();
    $('.sharehorizontal').closest('div[class="form-group"]')[horizontal_position]();
    $('.horizontal_location').closest('div[class="form-group"]')[horizontal_position]();
    $('.vertical_location').closest('div[class="form-group"]')[vertical_position]();
    $("input[name='lr_enable_social_horizontal_sharing']").closest('div[class="form-group"]')[horizontal_position]();
    $("input[name='lr_enable_social_vertical_sharing']").closest('div[class="form-group"]')[vertical_position]();
    
}
function show_profilefield(elem) {
    if (elem == 1) {
        $('#profilefield_display').css({"display": "block"});
    }
    else {
        $('#profilefield_display').css({"display": "none"});
    }
}

$(document).ready(function(){
    
    lrCheckValidJson();
      promptPasswordDiv();
    $("input[name=lr_email_verify_mail]:radio").click(function () {
        promptPasswordDiv();
       
    });

});

// Function to hide/show prompt password div from admin based on email verification options 
function promptPasswordDiv(){
    var promptPassword = $("input[name='lr_prompt_password']").closest('div[class="form-group"]');
    var askEmail = $("input[name='lr_ask_email']").closest('div[class="form-group"]');
    var loginEmailVerification = $("input[name='lr_email_verification']").closest('div[class="form-group"]');
    var enableLoginUserName = $("input[name='lr_enable_username']").closest('div[class="form-group"]');
     if ($('input[name=lr_email_verify_mail]:checked').val() == "1") {
            promptPassword.hide();
            askEmail.show();
            loginEmailVerification.show();
            enableLoginUserName.hide();
          

        } else if ($('input[name=lr_email_verify_mail]:checked').val() == "2") {
            
            promptPassword.hide();
            askEmail.hide();
            loginEmailVerification.hide();
            enableLoginUserName.hide();

        }else{
            promptPassword.show();
            askEmail.show();
            loginEmailVerification.show();
            enableLoginUserName.show();
        }
}
   
// Function to validate raas option json format
 function lrCheckValidJson() {


    var addRaasOption = $('textarea[name=lr_add_raas_option]');
       addRaasOption.blur(function(){
       var profile = addRaasOption.val();
       var response = '';
       try
       {
           response = $.parseJSON(profile);
           
           if(response != true && response != false){
               var validjson = JSON.stringify(response, null, '\t').replace(/</g, '&lt;');
               if(validjson != 'null'){
                   addRaasOption.val(validjson);
                   addRaasOption.css("border","1px solid green");
               }else{
                   addRaasOption.css("border","1px solid red");
               }
           }
           else{
               addRaasOption.css("border","1px solid green");
           }
       } catch (e)
       {
           addRaasOption.css("border","1px solid green");
       }
   });
}