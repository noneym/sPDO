<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Gürkan Biçer <gurkanbicer@yandex.com>
 *
 * @for sPDO
 */

$config['sPDO'] = array(

    /**
     * MySQL kullanıcı bilgileri
     *
     */
    'username'      => '',
    'password'      => '',

    /**
     * MySQL veritabanı ve host adı
     *
     */
    'database'      => '',
    'hostname'      => 'localhost',

    /**
     * MySQL bağlantısı için karakter seti
     *
     */
    'char_set'      => 'utf8',

    /**
     * SQL sorgu sonuçlarının önbelleklenmesi
     *
     */
    'cache'         => true,
    'cachedir'      => APPPATH . 'third_party/sPDO/cache/',
    'cacheexpire'   => 900,

);

/* End of file sPDO.php */
/* Location: ./config/sPDO.php */
