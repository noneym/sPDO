<?php
/**
 * @author Gürkan Biçer <gurkanbicer@yandex.com>
 *
 * @for sPDO
 */

    Class Spdo_Core
    {

        public  $_conn = false;
        private $_username = null;
        private $_password = null;
        private $_database = null;
        private $_hostname = null;
        private $_char_set = null;
        private $_engine;

        public  $_cache = false;
        public  $_cache_dir = null;
        public  $_cache_expire = null;

        function __construct($cnfg = null)
        {
            try
            {
                if (is_null($cnfg)) throw new Exception('Config information is empty.');
                if (!is_array($cnfg)) throw new Exception('Incorrect usage.');

                $this->_username = (is_string($cnfg['username']) && $cnfg['username'] != '') ? $cnfg['username'] : false;
                $this->_password = (is_string($cnfg['password'])) ? $cnfg['password'] : false;
                $this->_database = (is_string($cnfg['database']) && $cnfg['database'] != '') ? $cnfg['database'] : false;
                $this->_hostname = (is_string($cnfg['hostname']) && $cnfg['hostname'] != '') ? $cnfg['hostname'] : 'localhost';
                $this->_char_set = (is_string($cnfg['char_set']) && $cnfg['char_set'] != '') ? $cnfg['char_set'] : 'utf8';

                if (is_bool($this->_username)) throw new Exception('Username field is data type not valid.');
                if (is_bool($this->_password)) throw new Exception('Password field is data type not valid.');
                if (is_bool($this->_database)) throw new Exception('Database field is data type not valid.');

                $this->_cache        = (is_bool($cnfg['caching'])) ? $cnfg['caching'] : false;
                $this->_cache_dir    = (is_string($cnfg['cache_dir'])) ? $cnfg['cache_dir'] : null;
                $this->_cache_expire = (is_numeric($cnfg['cache_expire'])) ? $cnfg['cache_expire'] : 7200;

                $this->_engine = true;

            } catch ( Exception $errors )
            {
                echo 'Warning: ' . $errors->getMessage();
                $this->_engine = false;
            }
        }

        public function connect()
        {
            try {
                $this->_conn = new PDO('mysql:host=' . $this->_hostname . ';dbname='. $this->_database, $this->_username, $this->_password,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '$this->_char_set'"));
                $this->_conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                return $this->_conn;
            } catch ( Exception $errors )
            {
                $this->_conn = false;
                return $this->_conn;
            }
        }

        public function query($sql_query = null, $bind = null)
        {
            try
            {
                if ($this->_engine == false) throw new Exception( 'Spdo is not working. Check the config information.' );
                if (is_null($sql_query) || !is_string($sql_query) || $sql_query == '') throw new Exception( 'Sql query parameter is empty.' );
                $this->connect();
                if ( !is_null( $bind ) )
                {
                    $statement = $this->_conn->prepare( $sql_query );
                    if ( is_array($bind) )
                    {
                        if ( !$statement->execute( $bind ) )
                            throw new Exception( 'Incorrect sql query or any bug.' );
                        return $statement;
                    }
                    else
                    {
                        if ( !$statement->execute( array($bind) ) )
                            throw new Exception( 'Incorrect sql query or any bug.' );
                        return $statement;
                    }
                }
                else
                {
                    if ( $statement = $this->_conn->query( $sql_query ) )
                        return $statement;
                    else
                        throw new Exception( 'Incorrect sql query or any bug. ');
                }
                $this->disconnect();
            } catch ( Exception $errors )
            {
                return false;
            }
        }

        public function convert_query($query, $params)
        {
            if (!is_array($params))
                $params = array($params);

            $keys   = array();
            $values = $params;

            foreach ($params as $key => $value):
                if (is_string($key)) $keys[] = '/:'.$key.'/';
                else $keys[] = '/[?]/';

                if (is_string($value)) $values[$key] = "'" . $value . "'";
                if (is_array($value)) $values[$key] = implode(',', $value);
                if (is_null($value)) $values[$key] = 'NULL';
            endforeach;

            $query = preg_replace($keys, $values, $query, 1, $count);
            return $query;
        }

        public function get_cache($sql_query, $bind)
        {
            try
            {
                if ($this->_cache == false) throw new Exception( 'Caching is not activated.' );
                $raw_query = $this->convert_query($sql_query, $bind);
                $cache_filename = 'spdo_' . md5($raw_query) . '.txt';
                $cache_target   = $this->_cache_dir . '/' . $cache_filename;
                if (!file_exists($cache_target)) throw new Exception( 'Cache file is not found.' );
                if (filemtime($cache_target) > (time() - $this->_cache_expire)) throw new Exception( 'Cache file timed out.' );
                return unserialize(file_get_contents($cache_target));
            }
            catch ( Exception $errors )
            {
                return false;
            }
        }

        public function caching($sql_query, $bind, $result, $row_count)
        {
            try
            {
                if ($this->_cache == false) throw new Exception('Caching is not activated.');
                $raw_query = $this->convert_query($sql_query, $bind);
                $cache_filename = 'spdo_' . md5($raw_query) . '.txt';
                $cache_target   = $this->_cache_dir . '/' . $cache_filename;
                $data = serialize(array('results' => $result, 'rowCount' => $row_count));
                if (file_put_contents($cache_target, $data)) return true;
                else throw new Exception( 'Cache file is not created.' );
            } catch ( Exception $errors )
            {
                return false;
            }
        }

        public function disconnect()
        {
            $this->_conn = null;
            return $this->_conn;
        }

    }

?>
