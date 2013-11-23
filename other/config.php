<?php
$spdo_config = array(
    'username'      => 'MYSQL KULLANICI ADI',
    'password'      => 'MYSQL KULLANICI PAROLASI',
    'database'      => 'MYSQL VERITABANI ADI',

    # Sunucu ortamınızda özel bir yapılandırma yoksa, hostname anahtarının alacağı değer localhost kalmalı.
    'hostname'      => 'localhost',

    # Sorgulama işleminde kullanılan karakter seti. Varsayılan değer utf8'dir.
    'char_set'      => 'utf8',

    # Önbellekleme özelliği, kaynak kullanımını en aza indirmeyi amaçlar.
    # SQL sorguları tekrar sorgulandığında, belirlediğiniz süreden eski değilse diskten veriyi okur.
    # Etkinleştirmek için true, pasifleştirmek için false değerini atamalısınız.
    'caching'       => false,

    # Önbellekleme özelliği etkin ise cache_dir anahtarına atanan değer önbellek dosyalarının tutulacağı dizin olarak
    # benimsenir. Bu dizin yazılabilir olmalıdır.
    'cache_dir'     => 'cache',

    # Önbellekleme özelliği etkin ise belirlediğiniz saniye cinsinden süre boyunca diskteki sonuç dönecektir.
    'cache_expire'  => 7200,
);
?>
