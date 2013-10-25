<?php
/**
 * @name sPDO
 *
 * @description sPDO bir veritabanı sınıfıdır. PDO aracılığıyla MySQL'de SQL sorgusu
 * çalıştırarak; dönen sonucu anlamlandırıp, olası hataların önüne geçmek, sonucu
 * yazacağımız kod satırları arasında daha rahat kullanabilmek; ayrıca kod satırlarını
 * en aza indirmek için hazırlanmıştır.
 *
 * @version 1.1.0
 * @author Gürkan Biçer <gurkanbicer@yandex.com>
 * @link https://github.com/gurkanbicer/sPDO
 *
 * @time 23.10.2013
 */

    require_once 'spdo_core.php';

    Class sPDO Extends sPDO_core
    {

        public  $num_rows = 0;

        function __construct($cnf_array)
        {
            parent::__construct( $cnf_array );
        }

        /**
         * Sorgu sonucunda birden fazla satırı, belirlenen ayrıştırma türü ile döndürür.
         *
         * @param string        $sql_query  SQL sorgusu
         * @param string|array  $binding    PDO binding veri temizleme özelliği
         * @param string        $parser     Ayrıştırma türü
         * @return array
         */

        public function get_results($sql_query = null, $binding = null, $parser = null)
        {
            if ($this->_cache == true)
            {
                $cache = $this->get_cache($sql_query, $binding);
                if ($cache == false)
                {
                    if ($results = $this->query($sql_query, $binding))
                    {
                        $this->num_rows = $results->rowCount();
                        if (!is_null($parser) and $parser != '')
                        {
                            switch ($parser):
                                case 'object':
                                    $return_data = $results->fetchAll(PDO::FETCH_OBJ);
                                    $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                                    return $return_data;
                                    break;

                                case 'assoc':
                                    $return_data = $results->fetchAll(PDO::FETCH_ASSOC);
                                    $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                                    return $return_data;
                                    break;

                                default:
                                    $return_data = $results->fetchAll();
                                    $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                                    return $return_data;

                            endswitch;
                        }
                        else
                        {
                            $return_data = $results->fetchAll();
                            $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                            return $return_data;
                        }
                    }
                    else
                    {
                        $this->num_rows = 0;
                        return false;
                    }
                }
                else
                {
                    if (!is_null($parser))
                    {
                        switch ($parser):
                            case 'assoc':
                                $this->num_rows = $cache['rowCount'];
                                $return_assoc = array();
                                if ($cache['rowCount'] > 0)
                                    foreach ($cache['results'] as $cache_row) $return_assoc[] = (array) $cache_row;
                                return $return_assoc;
                                break;

                            case 'object':
                                $this->num_rows = $cache['rowCount'];
                                $return_assoc = array();
                                if ($cache['rowCount'] > 0)
                                    foreach ($cache['results'] as $cache_row) $return_assoc[] = (object) $cache_row;
                                return $return_assoc;
                                break;

                            default:
                                $this->num_rows = $cache['rowCount'];
                                $return_assoc = array();
                                if ($cache['rowCount'] > 0)
                                    foreach ($cache['results'] as $cache_row) $return_assoc[] = (object) $cache_row;
                                return $return_assoc;
                                break;

                        endswitch;
                    }
                    else
                    {
                        $this->num_rows = $cache['rowCount'];
                        $return_assoc = array();
                        if ($cache['rowCount'] > 0)
                            foreach ($cache['results'] as $cache_row) $return_assoc[] = (object) $cache_row;
                        return $return_assoc;
                    }
                }
            }
            else
            {
                if ($results = $this->query($sql_query, $binding))
                {
                    $this->num_rows = $results->rowCount();
                    if (!is_null($parser) and $parser != '')
                    {
                        switch ($parser):
                            case 'object':
                                $return_data = $results->fetchAll(PDO::FETCH_OBJ);
                                $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                                return $return_data;
                                break;
                            case 'assoc':
                                $return_data = $results->fetchAll(PDO::FETCH_ASSOC);
                                $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                                return $return_data;
                                break;
                            default:
                                $return_data = $results->fetchAll();
                                $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                                return $return_data;
                        endswitch;
                    }
                    else
                    {
                        $return_data = $results->fetchAll();
                        $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                        return $return_data;
                    }
                }
                else
                {
                    $this->num_rows = 0;
                    return false;
                }
            }
        }

        /**
         * Sorgu sonucunda ilk satırı, belirlenen ayrıştırma türü ile döndürür.
         *
         * @param string        $sql_query
         * @param string|array  $binding
         * @param string        $parser
         * @return array
         */

        public function get_row($sql_query = null, $binding = null, $parser = null)
        {
            if ($this->_cache == true)
            {
                $cache = $this->get_cache($sql_query, $binding);
                if ($cache == false)
                {
                    if ($results = $this->query($sql_query, $binding))
                    {
                        $this->num_rows = $results->rowCount();
                        if (!is_null($parser) and $parser != '')
                        {
                            switch ($parser):
                                case 'object':
                                    $return_data = $results->fetchAll(PDO::FETCH_OBJ);
                                    $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                                    if ($this->num_rows == 0)
                                        return $return_data;
                                    else
                                        return $return_data[0];
                                    break;

                                case 'assoc':
                                    $return_data = $results->fetchAll(PDO::FETCH_ASSOC);
                                    $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                                    if ($this->num_rows == 0)
                                        return $return_data;
                                    else
                                        return $return_data[0];
                                    break;

                                default:
                                    $return_data = $results->fetchAll();
                                    $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                                    if ($this->num_rows == 0)
                                        return $return_data;
                                    else
                                        return $return_data[0];

                            endswitch;
                        }
                        else
                        {
                            $return_data = $results->fetchAll();
                            $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                            if ($this->num_rows == 0)
                                return $return_data;
                            else
                                return $return_data[0];
                        }
                    }
                    else
                    {
                        $this->num_rows = 0;
                        return false;
                    }
                }
                else
                {
                    if (!is_null($parser))
                    {
                        switch ($parser):
                            case 'assoc':
                                $this->num_rows = $cache['rowCount'];
                                $return_assoc = array();
                                if ($cache['rowCount'] > 0)
                                {
                                    foreach ($cache['results'] as $cache_row) $return_assoc[] = (array) $cache_row;
                                    return $return_assoc[0];
                                }
                                else
                                {
                                    return $return_assoc;
                                }
                                break;
                            case 'object':
                                $this->num_rows = $cache['rowCount'];
                                $return_obj = array();
                                if ($cache['rowCount'] > 0)
                                {
                                    foreach ($cache['results'] as $cache_row) $return_obj[] = (object) $cache_row;
                                    return $return_obj[0];
                                }
                                else
                                {
                                    return $return_obj;
                                }
                                break;
                            default:
                                $this->num_rows = $cache['rowCount'];
                                $return_obj = array();
                                if ($cache['rowCount'] > 0)
                                {
                                    foreach ($cache['results'] as $cache_row) $return_obj[] = (object) $cache_row;
                                    return $return_obj[0];
                                }
                                else
                                {
                                    return $return_obj;
                                }
                                break;
                        endswitch;
                    }
                    else
                    {
                        $this->num_rows = $cache['rowCount'];
                        $return_obj = array();
                        if ($cache['rowCount'] > 0)
                        {
                            foreach ($cache['results'] as $cache_row) $return_obj[] = (object) $cache_row;
                            return $return_obj[0];
                        }
                        else
                        {
                            return $return_obj;
                        }
                    }
                }
            }
            else
            {
                if ($results = $this->query($sql_query, $binding))
                {
                    $this->num_rows = $results->rowCount();
                    if (!is_null($parser) and $parser != '')
                    {
                        switch ($parser):
                            case 'object':
                                $return_data = $results->fetch(PDO::FETCH_OBJ);
                                return $return_data;
                                break;
                            case 'assoc':
                                $return_data = $results->fetch(PDO::FETCH_ASSOC);
                                return $return_data;
                                break;
                            default:
                                $return_data = $results->fetch();
                                return $return_data;
                        endswitch;
                    }
                    else
                    {
                        $return_data = $results->fetch();
                        return $return_data;
                    }
                }
                else
                {
                    $this->num_rows = 0;
                    return false;
                }
            }
        }

        /**
         * Sorgu sonucunda tek bir kolonun değerini döndürür.
         *
         * @param string        $sql_query
         * @param string|array  $binding
         * @return string
         */

        public function get_var($sql_query = null, $binding = null)
        {
            if ($this->_cache == true)
            {
                $cache = $this->get_cache($sql_query, $binding);
                if ($cache == false)
                {
                    if ($results = $this->query($sql_query, $binding))
                    {
                        $this->num_rows = $results->rowCount();
                        $return_data = $results->fetchColumn();
                        $this->caching($sql_query, $binding, $return_data, $this->num_rows);
                        return $return_data;
                    }
                    else
                    {
                        $this->num_rows = 0;
                        return false;
                    }
                }
                else
                {
                    $this->num_rows = $cache['rowCount'];
                    return $cache['results'];
                }
            }
            else
            {
                if ($results = $this->query($sql_query, $binding))
                {
                    $this->num_rows = $results->rowCount();
                    $return_data = $results->fetchColumn();
                    return $return_data;
                }
                else
                {
                    $this->num_rows = 0;
                    return false;
                }
            }
        }

        /**
         * Veritabanına kayıt işlemini gerçekleştirir. Eklenen satırın birincil anahtar değeri geri döner.
         *
         * @param string        $sql_query
         * @param string|array  $binding
         * @return integer
         */

        public function insert($sql_query = null, $binding = null)
        {
            if ($results = $this->query($sql_query, $binding))
                return $this->_conn->lastInsertId();
            else
                return false;
        }

        /**
         * Veritabanında güncelleme işlemini gerçekleştirir. Etkilenen satır sayısını döndürür.
         *
         * @param string        $sql_query
         * @param string|array  $binding
         * @return integer
         */

        public function update($sql_query = null, $binding = null)
        {
            if ($results = $this->query($sql_query, $binding))
                return $results->rowCount();
            else
                return false;
        }

        /**
         * Veritabanında silme işlemini gerçekleştirir. Etkilenen satır sayısını döndürür.
         *
         * @param string        $sql_query
         * @param string|array  $binding
         * @return integer
         */

        public function delete($sql_query = null, $binding = null)
        {
            if ($results = $this->query($sql_query, $binding))
                return $results->rowCount();
            else
                return false;
        }

    }

/* End of file spdo.php */
/* Location spdo.php */
