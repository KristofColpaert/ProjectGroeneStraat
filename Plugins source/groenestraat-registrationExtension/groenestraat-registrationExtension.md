# Uitleg groenestraat-registrationExtension

Toevoegen aan functions.php (theme):

```
    wp_enqueue_script('email', get_stylesheet_directory_uri() . '/js/registration.js', array('jquery'));
    wp_localize_script('email', 'registration', array('ajax_url' => admin_url('admin-ajax.php')));
```

Maak een file **{themenaam}/js/registration.js** met volgend script (implementatie zelf aan te passen):

```
jQuery(document).on('click', '.test-button', function()
{
    var emailAddress = jQuery('#user_email').val();
    checkValidEmail(emailAddress);
});

//Checkt op sever of email-adres al dan niet bestaat
function checkValidEmail(emailAddress)
{
    jQuery.ajax(
    {
        url : registration.ajax_url,
        type : 'post',
        data : 
        {
            action : 'check_email',
            email : emailAddress
        },
        success : function(response)
        {
            console.log(response);
            return response;
        },
        error : function(error)
        {
            return 'failed';
        }
    });
}
```

Installeer plugin **Groenestraat Registratie Uitbreiding**