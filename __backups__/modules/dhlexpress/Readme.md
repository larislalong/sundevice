# DHL EXPRESS ®

## INSTALL

Module compatible with PrestaShop **1.6.1.x, 1.7.x**

To install, please insure to run a version listed above on your back office (written in top of screen besides the prestashop logo or in Menu / Advanced parameters / informations)

Make sure that your hosting provide the **cURL** library. No further need required to install.

To install,
Upload your file via the Menu / Modules => Add a new module & Upload the zip version (follow Prestashop recommandations)
Once the zip file is uploaded onto your hostings, click on Install, Configure => you will need a DHL account to go further. 
(To get a DHL account, go to : [dhl.com](http://dhl.com))

## USAGE & REQUIREMENTS

**/!\ IMPORTANT** : the module will create the necessary carriers "**NEEDED**" to use the DHL API onto your shop. Please do not remove them.
Four carriers will be created for the DHL services provided (domestic, international...). Please do not remove or disable them, otherwise your DHL carriers won't appear on your front office page.

**/!\ IMPORTANT** : customer phone field on address regstration should be mandatory. Phone number is mandatory for DHL label generation.

Make sure that the option "phone number mandatory" is enabled : 
  * for Version 1.6 - go to PREFERENCES / CLIENTS (a switch button is available for option "Phone number is mandatory" = Yes)
  * for Version 1.7 - go to SALES / CLIENTS / ADDRESSES (a button at the bottom of the page is available to "configure the required field for this section")

To run your DHL module on Front & back office, please insure to fulfill at least one sender address & one default package size during module configuration.
Address of sender & parcel default size are mandatory to get a quotation. These informations must be fulfilled while configuring the module.
