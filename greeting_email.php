<?php
const GREETING_EMAIL = <<<'EOT'
Kedves Érdeklődő!

Üdvözöllek az Elektori Parlamentben!

Mivel az Elektori Parlament Anonim Digitális Azonositót használ,
ezért a következő véletlenszerű nevet kaptad: %s

Ha szeretnél valamilyen más néven megjelenni, a profilodon állitsd át
a keresztnevedet és vezetéknevedet, vagy a becenevedet, és válaszd ki
a nyilvánosan megjelenő neved.
Ezt itt tudod megtenni: https://elektoriparlament.hu/wp-admin/profile.php

Ahhoz, hogy az Elektori Parlament teljes jogú tagja legyél, el kell fogadnod
az Alapitó okiratot. Arról, hogy ez hogyan megy, az alábbi oldalon olvashatsz:
https://elektoriparlament.hu/2017/12/25/regisztracio/

Ha bármi problémád van az oldal működésével, keresd a rendszer adminisztrátorát
a következő emailcimen: %s .
EOT;

//if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $message  = sprintf(GREETING_EMAIL, $user_login, get_option('admin_email')) ;
        wp_mail($user_email, 'Üdvözöl az Elektori Parlament', $message);

    }
//}
?>
