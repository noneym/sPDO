<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name sPDO
 *
 * @description sPDO bir veritabanı sınıfıdır. PDO aracılığıyla MySQL'de SQL sorgusu
 * çalıştırarak; dönen sonucu anlamlandırıp, olası hataların önüne geçmek, sonucu
 * yazacağımız kod satırları arasında daha rahat kullanabilmek; ayrıca kod satırlarını
 * en aza indirmek için hazırlanmıştır.
 *
 * @version 1.2.0
 * @author Gürkan Biçer <gurkanbicer@yandex.com>
 * @link https://github.com/gurkanbicer/sPDO
 *
 * @time 23.11.2013
 */

    require_once APPPATH . 'third_party/sPDO/core.php';

    Class Spdo Extends Spdo_Core
    {

        private $_ci;
        public  $num_rows = 0;

        function __construct()
        {
            $this->_ci = & get_instance();
            $this->_ci->config->load('spdo');

            parent::__construct( $this->_ci->config->item('sPDO') );
        }

        public function get_results($sql_query, $bind = null, $parser = 'object')
        {
            try
            {
                if ($this->_cache == false) throw new Exception( 'Cache is not activated.' );
                $cache = $this->get_cache($sql_query, $bind);
                if ($cache == false)
                {
                    $results = $this->query($sql_query, $bind);
                    if ($results != false)
                    {
                        $this->num_rows = $results->rowCount();
                        switch($parser):
                            case 'object':
                                $return_data = $results->fetchAll(PDO::FETCH_OBJ);
                                $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                                return $return_data;
                                break;

                            case 'assoc':
                                $return_data = $results->fetchAll(PDO::FETCH_ASSOC);
                                $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                                return $return_data;
                                break;

                            default:
                                $return_data = $results->fetchAll();
                                $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                                return $return_data;
                        endswitch;
                    }
                    else
                    {
                        $this->num_rows = 0;
                        return false;
                    }
                }
                else
                {
                    switch($parser):
                        case 'object':
                            $this->num_rows = $cache['rowCount'];
                            $return_obj = array();
                            if ($cache['rowCount'] > 0)
                                foreach ($cache['results'] as $cache_row) $return_obj[] = (object) $cache_row;
                            return $return_obj;
                            break;

                        case 'assoc':
                            $this->num_rows = $cache['rowCount'];
                            $return_assoc = array();
                            if ($cache['rowCount'] > 0)
                                foreach ($cache['results'] as $cache_row) $return_assoc[] = (array) $cache_row;
                            return $return_assoc;
                            break;

                        default:
                            $this->num_rows = $cache['rowCount'];
                            $return_obj = array();
                            if ($cache['rowCount'] > 0)
                                foreach ($cache['results'] as $cache_row) $return_obj[] = (object) $cache_row;
                            return $return_obj;
                    endswitch;
                }
            }
            catch (Exception $errors)
            {
                $results = $this->query($sql_query, $bind);
                if ($results != false)
                {
                    $this->num_rows = $results->rowCount();
                    switch ($parser):
                        case 'object':
                            $return_data = $results->fetchAll(PDO::FETCH_OBJ);
                            $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                            return $return_data;
                            break;

                        case 'assoc':
                            $return_data = $results->fetchAll(PDO::FETCH_ASSOC);
                            $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                            return $return_data;
                            break;

                        default:
                            $return_data = $results->fetchAll();
                            $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                            return $return_data;
                    endswitch;
                }
                else
                {
                    $this->num_rows = 0;
                    return false;
                }
            }
        }

        public function get_row($sql_query, $bind = null, $parser = 'object')
        {
            try
            {
                if ($this->_cache == false) throw new Exception( 'Cache is not activated.' );
                $cache = $this->get_cache($sql_query, $bind);
                if ($cache == false)
                {
                    $results = $this->query($sql_query, $bind);
                    if ($results != false)
                    {
                        $this->num_rows = $results->rowCount();
                        switch($parser):
                            case 'object':
                                $return_data = $results->fetchAll(PDO::FETCH_OBJ);
                                $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                                if ($this->num_rows == 0)
                                    return $return_data;
                                else
                                    return $return_data[0];
                                break;

                            case 'assoc':
                                $return_data = $results->fetchAll(PDO::FETCH_ASSOC);
                                $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                                if ($this->num_rows == 0)
                                    return $return_data;
                                else
                                    return $return_data[0];
                                break;

                            default:
                                $return_data = $results->fetchAll();
                                $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                                if ($this->num_rows == 0)
                                    return $return_data;
                                else
                                    return $return_data[0];
                        endswitch;
                    }
                    else
                    {
                        $this->num_rows = 0;
                        return false;
                    }
                }
                else
                {
                    switch($parser):
                        case 'object':
                            $this->num_rows = $cache['rowCount'];
                            $return_obj = array();
                            if ($cache['rowCount'] > 0)
                                foreach ($cache['results'] as $cache_row) $return_obj[] = (object) $cache_row;
                            return $return_obj[0];
                            break;

                        case 'assoc':
                            $this->num_rows = $cache['rowCount'];
                            $return_assoc = array();
                            if ($cache['rowCount'] > 0)
                                foreach ($cache['results'] as $cache_row) $return_assoc[] = (array) $cache_row;
                            return $return_assoc[0];
                            break;

                        default:
                            $this->num_rows = $cache['rowCount'];
                            $return_obj = array();
                            if ($cache['rowCount'] > 0)
                                foreach ($cache['results'] as $cache_row) $return_obj[] = (object) $cache_row;
                            return $return_obj[0];
                    endswitch;
                }
            }
            catch (Exception $errors)
            {
                $results = $this->query($sql_query, $bind);
                if ($results != false)
                {
                    $this->num_rows = $results->rowCount();
                    switch ($parser):
                        case 'object':
                            $return_data = $results->fetch(PDO::FETCH_OBJ);
                            $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                            return $return_data;
                            break;

                        case 'assoc':
                            $return_data = $results->fetch(PDO::FETCH_ASSOC);
                            $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                            return $return_data;
                            break;

                        default:
                            $return_data = $results->fetch();
                            $this->caching($sql_query, $bind, $return_data, $this->num_rows);
                            return $return_data;
                    endswitch;
                }
                else
                {
                    $this->num_rows = 0;
                    return false;
                }
            }
        }

        public function get_var($sql_query, $bind = null)
        {
            try
            {
                if ($this->_cache == false) throw new Exception( 'Cache is not activated.' );
                $cache = $this->get_cache($sql_query, $bind);
                if ($cache == false)
                {
                    $results = $this->query($sql_query, $bind);
                    if ($results != false)
                    {
                        $this->num_rows = $results->rowCount();
                        $return_data = $results->fetchColumn();
                        $this->caching($sql_query, $bind, $return_data, $this->num_rows);
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
            catch (Exception $errors)
            {
                $results = $this->query($sql_query, $bind);
                if ($results != false)
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

        public function execute($sql_query, $bind = null)
        {
            $results = $this->query($sql_query, $bind);
            if ($results != false)
            {
                if (stristr($sql_query, 'insert')) return $this->_conn->lastInsertId();
                else return $results->rowCount();
            }
            else
            {
                return false;
            }
        }

        public function select($col, $tbl)
        {
            try
            {
                if (is_null($col) || !is_string($col) || $col == '') throw new Exception('Column parameter is empty.');
                if (is_null($tbl) || !is_string($tbl) || $tbl == '') throw new Exception('Table parameter is empty.');
                $this->sql_query = "SELECT $col FROM $tbl";
                $this->sql_bind = null;
                return $this;
            } catch ( Exception $errors )
            {
                $this->sql_query = false;
                $this->sql_bind = null;
                return $this;
            }
        }

        public function where($body, $bind = null)
        {
            try
            {
                if ($this->sql_query == false) throw new Exception('Incorrect usage.');
                if (!is_null($body))
                {
                    if (is_array($body))
                    {
                        $where_body = implode(' AND ', $body);
                        $this->sql_query .= " WHERE $where_body";
                        if (!is_null($bind))
                            $this->sql_bind = (is_array($bind)) ? $bind : array($bind);
                        else
                            $this->sql_bind = null;
                        return $this;
                    }
                    elseif (is_string($body))
                    {
                        $this->sql_query .= " WHERE $body";
                        if (!is_null($bind))
                            $this->sql_bind = (is_array($bind)) ? $bind : array($bind);
                        else
                            $this->sql_bind = null;
                        return $this;
                    }
                    else
                    {
                        return $this;
                    }
                }
                else
                {
                    return $this;
                }
            } catch (Exception $errors)
            {
                return $this;
            }
        }

        public function group($col)
        {
            try
            {
                if ($this->sql_query == false) throw new Exception('Incorrect usage.');
                if (!is_null($col))
                {
                    $this->sql_query .= " GROUP BY $col";
                    return $this;
                }
                else
                {
                    return $this;
                }
            } catch ( Exception $errors )
            {
                return $this;
            }
        }

        public function order($order_by, $sort = null)
        {
            try
            {
                if ($this->sql_query == false) throw new Exception('Incorrect usage.');
                if (!is_null($order_by))
                {
                    $this->sql_query .= " ORDER BY $order_by";
                    if (!is_null($sort) && is_int($sort))
                    {
                        switch ($sort):
                            case 1:
                                $this->sql_query .= " ASC";
                                break;

                            case -1:
                                $this->sql_query .= " DESC";
                                break;

                            default:
                                $this->sql_query .= " ASC";
                        endswitch;
                    }
                    return $this;
                }
                else
                {
                    return $this;
                }
            } catch ( Exception $errors )
            {
                return $this;
            }
        }

        public function limit($start, $show = null)
        {
            try
            {
                if ($this->sql_query == false) throw new Exception('Incorrect usage.');
                if (!is_null($start) && is_numeric($start))
                {
                    $this->sql_query .= " LIMIT $start";
                    if (!is_null($show) && is_numeric($show)) $this->sql_query .= ",$show";
                    return $this;
                }
                else
                {
                    return $this;
                }
            } catch (Exception $errors)
            {
                return $this;
            }
        }

        public function result($parse = 'object')
        {
            try
            {
                if ($this->sql_query == false) throw new Exception('x001');
                if ($this->_cache == true)
                {
                    $cache = $this->get_cache($this->sql_query, $this->sql_bind);
                    if ($cache == false)
                    {
                        $results = $this->query($this->sql_query, $this->sql_bind);
                        if ($results != false)
                        {
                            $this->num_rows = $results->rowCount();
                            switch($parse):
                                case 'object':
                                    $return_data = $results->fetchAll(PDO::FETCH_OBJ);
                                    $this->caching($this->sql_query, $this->sql_bind, $return_data, $this->num_rows);
                                    return $return_data;
                                    break;

                                case 'assoc':
                                    $return_data = $results->fetchAll(PDO::FETCH_ASSOC);
                                    $this->caching($this->sql_query, $this->sql_bind, $return_data, $this->num_rows);
                                    return $return_data;
                                    break;

                                default:
                                    $return_data = $results->fetchAll();
                                    $this->caching($this->sql_query, $this->sql_bind, $return_data, $this->num_rows);
                                    return $return_data;
                            endswitch;
                        }
                        else
                        {
                            $this->num_rows = 0;
                            return false;
                        }
                    }
                    else
                    {
                        switch($parse):
                            case 'object':
                                $this->num_rows = $cache['rowCount'];
                                $return_obj = array();
                                if ($cache['rowCount'] > 0)
                                    foreach ($cache['results'] as $cache_row) $return_obj[] = (object) $cache_row;
                                return $return_obj;
                                break;

                            case 'assoc':
                                $this->num_rows = $cache['rowCount'];
                                $return_assoc = array();
                                if ($cache['rowCount'] > 0)
                                    foreach ($cache['results'] as $cache_row) $return_assoc[] = (array) $cache_row;
                                return $return_assoc;
                                break;

                            default:
                                $this->num_rows = $cache['rowCount'];
                                $return_obj = array();
                                if ($cache['rowCount'] > 0)
                                    foreach ($cache['results'] as $cache_row) $return_obj[] = (object) $cache_row;
                                return $return_obj;
                        endswitch;
                    }
                }
                else
                {
                    $results = $this->query($this->sql_query, $this->sql_bind);
                    if ($results != false)
                    {
                        $this->num_rows = $results->rowCount();
                        switch ($parse):
                            case 'object':
                                $return_data = $results->fetchAll(PDO::FETCH_OBJ);
                                return $return_data;
                                break;
                            case 'assoc':
                                $return_data = $results->fetchAll(PDO::FETCH_ASSOC);
                                return $return_data;
                                break;

                            default:
                                $return_data = $results->fetchAll(PDO::FETCH_OBJ);
                                return $return_data;
                        endswitch;
                    }
                    else
                    {
                        throw new Exception('x002');
                    }
                }
            } catch (Exception $errors)
            {
                switch ($errors->getMessage()):
                    case 'x001':
                        return false;
                        break;

                    case 'x002':
                        return null;
                        break;

                    default:
                        return false;
                endswitch;
            }
        }

        public function insert($tbl, $data)
        {
            try
            {
                if (is_null($tbl) || !is_string($tbl) || $tbl == '') throw new Exception('Table parameter is empty.');
                if (is_null($data) || !is_array($data) || count($data) == 0) throw new Exception('Data parameter is empty.');
                $sql_query = "INSERT INTO $tbl SET";
                $keys = implode(' = ?, ', array_keys($data)) . " = ?";
                $vals = array_values($data);
                $sql_query .= " $keys";
                $result = $this->query($sql_query, $vals);
                if ($result != false) return $this->_conn->lastInsertId();
                else return false;
            } catch ( Exception $errors )
            {
                return false;
            }
        }

        public function update($tbl, $data, $where = null, $where_bind = null)
        {
            try
            {
                if (is_null($tbl) || !is_string($tbl) || $tbl == '') throw new Exception('Table parameter is empty.');
                if (is_null($data) || !is_array($data) || count($data) == 0) throw new Exception('Data parameter is empty.');
                $sql_query = "UPDATE $tbl SET";
                $keys = implode(' = ?, ', array_keys($data)) . " = ?";
                $sql_query .= " $keys";
                $bind = array_values($data);
                if (!is_null($where))
                {
                    if (is_string($where) && $where != '') $sql_query .= " WHERE $where";
                    elseif (is_array($where) && count($where) > 0) $sql_query .= " WHERE " . implode(' AND ',$where);
                    if (!is_null($where_bind))
                    {
                        if (is_array($where_bind)) foreach ($where_bind as $bind_data) $bind[] = $bind_data;
                        else $bind[] = $where_bind;
                    }
                }
                $result = $this->query($sql_query, $bind);
                if ($result != false) return $result->rowCount();
                else return false;
            } catch (Exception $errors)
            {
                return false;
            }
        }

        public function delete($tbl, $where = null, $where_bind = null)
        {
            try
            {
                if (is_null($tbl) || !is_string($tbl) || $tbl == '') throw new Exception('Table parameter is empty.');
                $sql_query = "DELETE FROM $tbl";
                $bind = null;
                if (!is_null($where))
                {
                    if (is_string($where) && $where != '') $sql_query .= " WHERE $where";
                    elseif (is_array($where) && count($where) > 0) $sql_query .= " WHERE " . implode(' AND ',$where);

                    if (!is_null($where_bind))
                    {
                        if (is_array($where_bind)) foreach ($where_bind as $bind_data) $bind[] = $bind_data;
                        else $bind[] = $where_bind;
                    }
                }
                $result = $this->query($sql_query, $bind);
                if ($result != false) return $result->rowCount();
                else return false;
            } catch (Exception $errors)
            {
                return false;
            }
        }

    }

/* End of file Spdo.php */
/* Location ./libraries/Spdo.php */
