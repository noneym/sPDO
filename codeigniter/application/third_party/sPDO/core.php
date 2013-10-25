<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Gürkan Biçer <gurkanbicer@yandex.com>
 *
 * @for sPDO
 */

    Class sPDO_core
    {

        public  $_conn;
        private $_username;
        private $_password;
        private $_database;
        private $_hostname;
        private $_char_set;
        public  $_cache;
        private $_cacheDir;
        private $_cacheExpire;

        function __construct($info)
        {
            $this->_username    = $info['username'];
            $this->_password    = $info['password'];
            $this->_database    = $info['database'];
            $this->_hostname    = $info['hostname'];
            $this->_char_set    = $info['char_set'];
            $this->_cache       = $info['cache'];
            $this->_cacheDir    = $info['cachedir'];
            $this->_cacheExpire = $info['cacheexpire'];
        }

        public function connect()
        {
            $this->_conn = new PDO('mysql:host=' . $this->_hostname . ';dbname='. $this->_database, $this->_username, $this->_password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '$this->_char_set'"));
            $this->_conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $this->_conn;
        }

        public function query($sql_query = null, $binding = null)
        {
            if (!is_null($sql_query) and $sql_query != '')
            {
                if (!is_null($binding) and $binding != '')
                {
                    $this->connect();
                    $result = $this->_conn->prepare($sql_query);
                    if (is_array($binding))
                    {
                        if ($result->execute($binding))
                        {
                            return $result;
                            $this->disconnect();
                        }
                        else
                        {
                            return false;
                        }
                    }
                    else
                    {
                        if ($result->execute(array($binding)))
                        {
                            return $result;
                            $this->disconnect();
                        }
                        else
                        {
                            return false;
                        }
                    }
                }
                else
                {
                    $this->connect();
                    if ($result = $this->_conn->query($sql_query))
                    {
                        return $result;
                        $this->disconnect();
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
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

        public function get_cache($sql_query, $binding)
        {
            if ($this->_cache == true)
            {
                $_filename  = md5($this->convert_query($sql_query, $binding)) . '.txt';
                $_target    = $this->_cacheDir . $_filename;

                if (file_exists($_target))
                {
                    if (filemtime($_target) > time() - $this->_cacheExpire)
                    {
                        $_filecontent = unserialize(file_get_contents($_target));
                        return $_filecontent;
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }

        public function caching($sql_query, $binding, $result, $rowCount)
        {
            if ($this->_cache == true)
            {
                $_filename  = md5($this->convert_query($sql_query, $binding));
                $_target    = $this->_cacheDir . $_filename . ".txt";
                $_content   = serialize(array('results' => $result, 'rowCount' => $rowCount));

                if (is_writable($this->_cacheDir))
                {
                    $action = fopen($_target, 'w');
                    fwrite($action, $_content);
                    fclose($action);
                }
            }
        }

        public function disconnect()
        {
            $this->_conn = null;
            return $this->_conn;
        }

    }

/* End of file core.php */
/* Location: ./third_party/sPDO/core.php */
