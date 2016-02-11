Prestashop User Registration Addon installation instructions:
=============

For any help feel free to contact at hello@loginradius.com

Step by steps instructions:  

1. Download Social Login module for Prestashop v1.6 by LoginRadius

2. Go to your PrestaShop’s back end (admin panel) and log in.

3. Now click on Modules tab.

4. Click on Add a module from my computer, then browse zip file and click on Upload this module.

5. Click on Other Modules and it will expand up. Now click on Install button .

6. After that click on Configure.

7. Under Module settings, enter LoginRadius Site Name, API Key and API Secret that you get from your LoginRadius account. (Please see note below on ‘How to get LoginRadius Key & Secret’). Few ID providers do not provide user's Email ID so select Yes in Email Required if you want users to enter their email ID (you need to have SMTP account to send email at server) or select No if you don't. Social login will auto generate a virtual email ID for those users). Finally, hit Save button.

8. With this you are done with the LoginRadius setup for PrestaShop Add on.

> **Note**: the full functionality of this plugin requires a LoginRadius Site Name, LoginRadius API Key and a LoginRadius API Secret. If you do not have this data only the functionality of the Social Sharing component can be utilized. Please find further documentation on how you can obtain this data [here](http://ish.re/INI1).

== Changelog ==
= 1.0.1 =

* Added email verification options during registration i.e Required, Optional, Disable.
* Added login with username.
* Ask Email For Unverified Account
* Enable login upon email verification.
* Prompt for password after registration with social provider.
* Google reCaptcha public key.
* Template name for email verification email
* Template name for forgot password email
* Password Length (min, max)
* Delay time to generate authentication pages
* Text for Terms and Condition
* Display Validation Message
* Custom customer registration options

= 1.0.0 =

* Social sharing.
* Extended profile data.
* Social Linking option.
* Language translation
* Update user profile
* Replace Traditional login/signup page.
