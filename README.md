<p align="center">
<img src="https://cloud.githubusercontent.com/assets/5717025/15674295/335fa446-273c-11e6-9db1-76c1b89d153d.jpg" alt="1">
</p>

# payu_easyplus_opencart_2.x
PayU's updated plugin for OpenCart v2.x

These instructions are for a manual installation using FTP, cPanel or other web hosting Control Panel.

Before proceeding with the installation of the plugin, you must have installed OpenCart shopping cart.

This plugin was tested with OpenCart version 2.2 only. It is NOT RECOMMENDED to be installed on OpenCart version 1.x.

Beyond having PHP5 SOAP extension enabled on your Web server there are no specific requirements for installing and configuring the plugin.

- Linux Install -

1. Upload all of the files and folders to your server from the "upload" folder, place them in your web root. The web root is different on some servers, cPanel it should be public_html/ and on Plesk it should be httpdocs/.
2. Visit the store admin e.g. http://www.example.com/admin or http://www.example.com/store/admin if OpenCart was installed in a subfolder of the web root.
3. Login with your admin credentials.
![image](https://cloud.githubusercontent.com/assets/5717025/17997343/48cf1d86-6b6e-11e6-8dbb-183a70ace0f0.png)
4. Navigate to Extensions -> Payments, Find PayU secure payments and click “Install” to install the extension.
![image](https://cloud.githubusercontent.com/assets/5717025/17997359/5ec95354-6b6e-11e6-9290-88adbf75d458.png)
5. Install “PayU Easy Plus secure payments” extension
![image](https://cloud.githubusercontent.com/assets/5717025/17997387/81236f66-6b6e-11e6-968f-7accddbf0473.png)
6. Click “Edit” icon to configure extension.
![image](https://cloud.githubusercontent.com/assets/5717025/17997417/a9bf7aa0-6b6e-11e6-898e-79916fbec38f.png)
7. The default values are used for testing purposes. Replace the default values with your credentials provided by PayU when you opened an account. This includes the safe key, API username, and API password. Select the Payment Methods activated on your PayU account. Only use debug or extended debug logging during testing. Also remember to Enable the plugin. Switch to live mode before accepting real transactions and select the desired payment currency. Finally click “Save” to save your changes.
![image](https://cloud.githubusercontent.com/assets/5717025/17997424/c87b9b4a-6b6e-11e6-80f0-071dcaaf3a3b.png)
8. PayU payment method should now be available as a payment method upon checkout.
![image](https://cloud.githubusercontent.com/assets/5717025/17997432/dc51d95e-6b6e-11e6-9a6b-e47aef0622a0.png)
![image](https://cloud.githubusercontent.com/assets/5717025/17997453/f2562214-6b6e-11e6-90be-8d2e48a128e0.png)
9. Redirection to PayU to complete payment after a customer confirms order.
![image](https://cloud.githubusercontent.com/assets/5717025/17997462/02094f1a-6b6f-11e6-8c1d-1b8da7fe2189.png)

- Windows Install -
1. Upload all the files and folders to your server from the "upload" folder. This can be to anywhere of your choice. e.g. /wwwroot/store or /wwwroot
2. Follow the instructions of step 2 till the end of Linux install instructions.

