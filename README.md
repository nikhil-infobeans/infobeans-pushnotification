#Infobeans PushNotification Magento 2 Integration
##Quick instructions

###Manual Install
- Create folder structure /app/code/Infobeans/PushNotification/
- Download the .ZIP file from the marketplace
- Extract the contents of the .ZIP file to the folder you just created

###Composer Install
- Go to magento2 root directory.
- Enter command composer require infobeans/magento2-pushnotification:2.3.0

#### Run install commands:
```
php bin/magento module:enable Infobeans_PushNotification
```
php bin/magento setup:upgrade
```
- You may need to run the following command to flush the Magento cache:
```
php bin/magento cache:flush
