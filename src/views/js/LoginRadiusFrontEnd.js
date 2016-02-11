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
$(document).ready(function () {
    
    $("#fade, #lr-loading").click(function () {
        $('#fade, #lr-loading').hide();
    });


});

function show_birthdate_date_block() {
    var maxYear = new Date().getFullYear();
    var minYear = maxYear - 100;
    if ($('body').on) {
        $('body').on('focus', '.loginradius-raas-birthdate', function () {
                $('.loginradius-raas-birthdate').datepicker({
                    dateFormat: 'mm-dd-yy',
                    maxDate: new Date(),
                    minDate: "-100y",
                    changeMonth: true,
                    changeYear: true,
                    yearRange: (minYear + ":" + maxYear)
                });
            }
        );
    } else {
        $(".loginradius-raas-birthdate").live("focus", function () {
            $('.loginradius-raas-birthdate').datepicker({
                dateFormat: 'mm-dd-yy',
                maxDate: new Date(),
                minDate: "-100y",
                changeMonth: true,
                changeYear: true,
                yearRange: (minYear + ":" + maxYear)
            });
        });
    }

}
function unLinkAccount(name, id) {
    handleResponse(true, "");
    if (confirm('Are you sure you want to unlink!')) {
        $('#fade').show();
        var array = {};
        array['value'] = 'accountUnLink';
        array['provider'] = name;
        array['providerId'] = id;
        var form = document.createElement('form');
        var key;
        form.action = '';
        form.method = 'POST';
        for (key in array) {
            var hiddenToken = document.createElement('input');
            hiddenToken.type = 'hidden';
            hiddenToken.value = array[key];
            hiddenToken.name = key;
            form.appendChild(hiddenToken);
        }
        document.body.appendChild(form);
        form.submit();
    }
    else {
        $('#fade').hide();
    }
}


function handleResponse(isSuccess, message, show, status) {
    status = status ? status : "status";
    if (status == "error" && window.LoginRadiusSSO) {
        LoginRadiusSSO.init(raasoption.appName);
        LoginRadiusSSO.logout(window.location);
    }
    if (typeof show != 'undefined' && !show) {
        $('#fade').show();
    }
    if (message != null && message != "") {
	if (isSuccess) {
            $('form').each(function () {
                this.reset();
            });
        }
        displaymessage(message);

    }
}
function displaymessage(message) {
    if (message && message !== '') {
        if ($('.topmainslid').length) {
            $('.topmainslid').remove();
        }
        $('body').append('<div class="topmainslid">' + message + '<span class="top-mans-close"></span></div>');

        $(".top-mans-close").click(function () {
            $(".topmainslid").animate({ 'top': '-100%' });
        });
        $(".topmainslid").animate({ 'top': 0 });
        setTimeout(function () {
            $(".topmainslid").fadeOut("slow");
        }, 5000);
        setTimeout(function () {
            $(".wrapper").animate({ 'top': 0 });
        }, 5200);
        $("#lr-loading").hide();
        return false;
    } else {
        return false;
    }
}
function linking() {
    $(".lr-linked-data, .lr-unlinked-data").html('');
    $(".lr-linked").each(function () {
        $(".lr-linked-data").append($(this).html());
    });
    $(".lr-unlinked").each(function () {
        $(".lr-unlinked-data").append($(this).html());
    });
    var linked_val = $('.lr-linked-data').html();
    var unlinked_val = $('.lr-unlinked-data').html();
    if (linked_val != '') {

        $(".lr-linked-data").prepend('Connected Account<br>');
    }
    if (unlinked_val != '') {
        $(".lr-unlinked-data").prepend('Choose Social Account to connect<br>');
    }
    $('#interfacecontainerdiv').hide();
}
LoginRadiusRaaS.$hooks.setProcessHook(function () {

    $('#lr-loading').show();
}, function () {
    if ($('.lr_account_linking') && $('#interfacecontainerdiv').text() != '') {
        linking();

        $("#lr-loading").hide();
    }
    if ($('#loginradius-raas-registration-emailid') && typeof email_create != 'undefined' && email_create != '') {

        $('#loginradius-raas-registration-emailid').val(email_create);
        email_create = '';

    }
    if ($('.content-loginradius-raas-g-recaptcha-response').length > 0 || $('.loginradius-raas-confirmnewpassword').length > 0) {

        $("#lr-loading").hide();
    }

    if($('form[name="loginradius-raas-registration"]').length > 0 ){
        $('form[name="loginradius-raas-registration"]').addClass('std box');
    }


    if($('.loginradius-raas--form-element-content').length > 0) {
        $('.loginradius-raas--form-element-content').addClass('form-group');
        $('.loginradius-raas--form-element-content input').addClass('form-control');
        $('.loginradius-raas--form-element-content select').addClass('form-control');
        $('.loginradius-raas-submit').addClass('btn btn-default button button-medium');
    }

    
});
LoginRadiusRaaS.$hooks.socialLogin.onFormRender = function () {
    $('#lr-loading').hide();
    $('#social-registration-form').show();
    show_birthdate_date_block();
    
};
function callSocialInterface() {
    LoginRadiusRaaS.CustomInterface(".interfacecontainerdiv", raasoption);
    $('#lr-loading').hide();
}
function initializeLoginRaasForm() {
//initialize Login form
    LoginRadiusRaaS.init(raasoption, 'login', function (response) {
        handleResponse(true, "");
        raasRedirect(response.access_token);
    }, function (response) {
        if (response[0].description != null) {
            handleResponse(false, response[0].description, "", "error");
        }
    }, "login-container");
    $('#lr-loading').hide();

}
function initializeRegisterRaasForm() {
    LoginRadiusRaaS.init(raasoption, 'registration', function (response, data) {
       if(response.access_token != null && response.access_token != ""){ 
           handleResponse(true, ""); raasRedirect(response.access_token); 
       }else{
           handleResponse(true, "An email has been sent to " + $("#loginradius-raas-registration-emailid").val() + ".Please verify your email address."); 
       }
    }, function (response) {
        if (response[0].description != null) {
            handleResponse(false, response[0].description, "", "error");
        }
    }, "registeration-container");
    $('#lr-loading').hide();
}
function initializeResetPasswordRaasForm(raasoption) {
    //initialize reset password form and handel email verifaction
    var vtype = $SL.util.getQueryParameterByName("vtype");
    if (vtype != null && vtype != "") {
        LoginRadiusRaaS.init(raasoption, 'resetpassword', function (response) {
            handleResponse(true, "Password reset successfully");
            window.location = raasoption.emailVerificationUrl;
        }, function (response) {
            handleResponse(false, response[0].description, "", "error");
        }, "resetpassword-container");

        if (vtype == "reset") {
            LoginRadiusRaaS.init(raasoption, 'emailverification', function (response) {
            handleResponse(true, "");
                $('#resetpassword-container').show();
                $('#login-container').hide();
                $('#login_form').hide();
                $('#lr-loading').hide();
                $('#create-account_form').parent().hide();
                $('.lr-raas-login-form').text('Reset Password');

                
            }, function (response) {
                handleResponse(false, response[0].description, "", "error");
            });
        } else {
            LoginRadiusRaaS.init(raasoption, 'emailverification', function (response) {
              //  console.log(response);
          if (response.access_token != null && response.access_token != "" ) {
          handleResponse(true, "", true);
            raasRedirect(response.access_token);
     } else {
         handleResponse(true, "Your email has been verified successfully");
     }
                //On Success this callback will call
              
                //   ShowformbyId("login_form");
            }, function (response) {
                // on failure this function will call ‘errors’ is an array of error with message.
                handleResponse(false, response[0].description, "", "error");
            });
        }
    }
    $('#lr-loading').hide();
}
function initializeSocialRegisterRaasForm() {
    //initialize social Login form
    LoginRadiusRaaS.init(raasoption, 'sociallogin', function (response) {
        if (response.isPosted) {
            handleResponse(true, "An email has been sent to " + $("#loginradius-raas-social-registration-emailid").val() + ".Please verify your email address.");
            $('#social-registration-form').hide();
        } else {
            handleResponse(true, "", true);
            raasRedirect(response);
        }
    }, function (response) {
        if (response[0].description != null) {
            handleResponse(false, response[0].description, "", "error");
            $('#social-registration-form').hide();
        }
    }, "social-registration-container");

    $('#lr-loading').hide();

}

function initializeForgotPasswordRaasForms() {
    //initialize forgot password form
    LoginRadiusRaaS.init(raasoption, 'forgotpassword', function (response) {

        handleResponse(true, "An email has been sent to " + $("#loginradius-raas-forgotpassword-emailid").val() + " with reset Password link.");
    }, function (response) {
        if (response[0].description != null) {
            handleResponse(false, response[0].description, "", "error");
        }
    }, "forgotpassword-container");
    $('#lr-loading').hide();
}
function initializeAccountLinkingRaasForms() {
    LoginRadiusRaaS.init(raasoption, "accountlinking", function (response) {
        handleResponse(true, "");
        raasRedirect(response);
    }, function (response) {
        $('#fade').hide();
        if (response[0].description != null) {
            handleResponse(false, response[0].description, "", "error");
        }
    }, "interfacecontainerdiv");
    $('#lr-loading').hide();
}
function initializeChangePasswordRaasForms() {
   
    LoginRadiusRaaS.passwordHandleForms("setpasswordbox", "changepasswordbox", function (israas) {

        var password_div = $('#lr_password_title');
        if (israas) {
            password_div.html('Change Password');
            $("#changepasswordbox").show();
        } else {
            password_div.html('Set Password');
            $("#setpasswordbox").show();
        }
    }, function () {
        document.forms['setpassword'].action = window.location;
        document.forms['setpassword'].submit();
    }, function () {

    }, function () {
        document.forms['changepassword'].action = window.location;
        document.forms['changepassword'].submit();
    }, function () {

    }, raasoption);
    $('#lr-loading').hide();
}
function raasRedirect(token, name) {
    if (window.redirect) {
        redirect(token, name);
    }
    else {
        var token_name = name ? name : 'token';
        var source = typeof lr_source != 'undefined' && lr_source ? lr_source : '';

        $('#fade').show();
        var form = document.createElement('form');
        form.action = LocalDomain;
        form.method = 'POST';

        var hiddenToken = document.createElement('input');
        hiddenToken.type = 'hidden';
        hiddenToken.value = token;
        hiddenToken.name = token_name;
        form.appendChild(hiddenToken);
        if (source == 'wall_post' || source == 'friend_invite') {
            var hiddenToken = document.createElement('input');
            hiddenToken.type = 'hidden';
            hiddenToken.value = lr_source;
            hiddenToken.name = 'lr_source';
            form.appendChild(hiddenToken);
        }
        document.body.appendChild(form);
        form.submit();
    }
}
