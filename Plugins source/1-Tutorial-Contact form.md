# Contact Form 7 in combinatie met WP-Mail-SMTP

## SMTP-server

Wanneer er geen SMTP-server aanwezig is op de machine waarop de website is geïnstalleerd, dan zal de WordPress geen mails kunnen sturen. Dat wil zeggen dat nieuwe gebruikers geen registratiemails zullen ontvangen en dat de dienst om het wachtwoord te vernieuwen, niet zal werken. Bovendien zullen ook contactformulieren niet werken.

Een oplossing is om te werken met een externe SMTP-server zoals SendGrid. In combinatie met de WP-Mail-SMTP-plugin laat die ons toe om toch mails te sturen vanuit WordPress. We configureren de WP-Mail-SMTP-plugin als volgt:

Algemeen
* From Email: e-mailadres waarvan mails afkomstig mogen zijn
* From Name: naam waaronder de mails verstuurd worden
* Mailer: selecteer 'Send all WordPress emails via SMTP
* Return Path: uitvinken

SMTP Options
* SMTP Host: smtp.sendgrid.net
* SMTP Port: 587
* Encryption: selecteer 'No encryption'
* Authentication: selecteer 'Yes: Use SMTP authentication'
* Username: gebruik username van je SendGrid-account (Groenestraat)
* Password: gebruik password van je SendGrid-account (-Password1)

Via: http://blogs.msdn.com/b/davedev/archive/2013/08/15/how_2d00_to_2d00_i_2d00_send_2d00_email_2d00_with_2d00_wordpress_2d00_hosted_2d00_on_2d00_azure_2d00_websites.aspx

## Informatie over Contact Form 7 aka Groenestraat Contact Form 7 Extension

Deze plugin is gebaseerd op Contact Form 7. Gelieve deze bij updates van het origineel dan ook mee up te daten. Enkel de volgende code dient te worden aangepast in **wp-contact-form-7.php**:

```
if ( ! defined( 'WPCF7_ADMIN_READ_CAPABILITY' ) ) {
	define( 'WPCF7_ADMIN_READ_CAPABILITY', 'edit_posts' );
}

if ( ! defined( 'WPCF7_ADMIN_READ_WRITE_CAPABILITY' ) ) {
	define( 'WPCF7_ADMIN_READ_WRITE_CAPABILITY', 'publish_pages' );
}
```

Aanpassen naar: 

```
if ( ! defined( 'WPCF7_ADMIN_READ_CAPABILITY' ) ) {
	define( 'WPCF7_ADMIN_READ_CAPABILITY', 'manage_options' );
}

if ( ! defined( 'WPCF7_ADMIN_READ_WRITE_CAPABILITY' ) ) {
	define( 'WPCF7_ADMIN_READ_WRITE_CAPABILITY', 'manage_options' );
}
```
