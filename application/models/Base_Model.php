<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author caleb - 2018
 *
 * Class used for getting, inserting, and updating the database 
 */
class Base_Model extends CI_Model {
    
    private $table_alias_map;
    private $req_tables;
    private $foreign_id_columns = array("facility" => "facilitycode");
    
    /**
     * @param [] $params - Associative array of parameters to search by (column => value...)
     * @return [][] - Associative Array of all rows that correspond to all params organized by table alias (table_alias => (column => value...))
     */
    public function get_tables_by_params($params) 
    {
        $results = array();
        foreach ($this->req_tables as $table_alias) {
            $filtered_params = $this->filter_params($this->table_alias_map[$table_alias], $params);
            $sql = $this->db_util->create_select_by_param_sql($this->table_alias_map[$table_alias], $filtered_params, $this->foreign_id_columns);
            $query = $this->db->query($sql, array_values($filtered_params));
            $db_error = $this->db->error();
            if (isset($db_error['code']) && $db_error['code'] != 0) {
                throw new Exception('Database Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
            }
            $results[$table_alias] = $query->result_array();
        }
        return $results;
    }
    
    /**
     * @param String $id - Id to search for 
     * @return [][] - Associative Array of all rows that correspond to id organized by table alias (table_alias => (column => value...))
     */
    public function get_tables_by_id($id)
    {
        $results = array();
        foreach ($this->req_tables as $table_alias) {
            $sql = $this->db_util->create_select_sql($this->table_alias_map[$table_alias], $id);
            $query = $this->db->query($sql, array($id));
            $db_error = $this->db->error();
            if (isset($db_error['code']) && $db_error['code'] != 0) {
                throw new Exception('Database Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
            }
            $results[$table_alias] = $query->result_array();
        }
        return $results;
    }
    
    /**
     * @return [][] - Associative Array of all rows organized by table alias (table_alias => (column => value...))
     */
    public function get_tables()
    {
        $results = array();
        foreach ($this->req_tables as $table_alias) {
            $sql = $this->db_util->create_select_sql($this->table_alias_map[$table_alias]);
            $query = $this->db->query($sql);
            $db_error = $this->db->error();
            if (isset($db_error['code']) && $db_error['code'] != 0) {
                throw new Exception('Database Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
            }
            $results[$table_alias] = $query->result_array();
        }
        return $results;
    }
    
    /**
     * @param [] $params - Associative array of values to insert into the tables
     * @return [][] - Associative array of all successful insert ids (table_alias => ('id' => value))
     */
    public function insert_params($params) 
    {
        $results = array();
        foreach ($this->req_tables as $table_alias) {
            $filtered_params = $this->filter_params($this->table_alias_map[$table_alias], $params);
            $sql = $this->db_util->create_insert_sql($this->table_alias_map[$table_alias], $filtered_params, $this->foreign_id_columns);
            $query = $this->db->query($sql, array_values($filtered_params));
            $db_error = $this->db->error();
            if (isset($db_error['code']) && $db_error['code'] != 0) {
                throw new Exception('Database Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
            }
            $results[$table_alias] = array("id" => $this->db->insert_id());
        }
        return $results;
    }
    
    /**
     * @param [] $params - Associative array of values to insert into the tables
     * @param String $id - ID to update
     * @return [][] - Associative array of all successful update ids (table_alias => ('id' => value))
     */
    public function update_with_params($params, $id)
    {
        $results = array();
        foreach ($this->req_tables as $table_alias) {
            $filtered_params = $this->filter_params($this->table_alias_map[$table_alias], $params);
            $sql = $this->db_util->create_update_sql($this->table_alias_map[$table_alias], $filtered_params, $this->foreign_id_columns);
            $values = array_merge(array_values($filtered_params), array($id));
            $query = $this->db->query($sql, $values);
            $db_error = $this->db->error();
            if (isset($db_error['code']) && $db_error['code'] != 0) {
                throw new Exception('Database Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
            }
            $results[$table_alias] = array("id" => $id);
        }
        return $results;
    }
    
    /**
     * @param String $facility_id - facilityid of a facility to get associated object names
     * @return [] - Associative array of all names for all object types that are "superclass" of facility
     */
    public function get_missing_object_names($facility_id)
    {
        $sql = $this->db_util->create_get_name_by_facility_code_sql();
        $query = $this->db->query($sql, array($facility_id));
        $db_error = $this->db->error();
        if (isset($db_error['code']) && $db_error['code'] != 0) {
            throw new Exception('Database Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
        }
        $result = $query->result_array();
        if (sizeof($result) == 0) {
            return array();
        }
        return $result[0];
    }
    
    /**
     * @param String $table - The table to filter from (must be previously sql injection safe if coming from user)
     * @param [] $params - Associative array of parameters to filter
     * @return [] - Associative array of all $params that's key is a column name in $table
     */
    private function filter_params($table, $params)
    {
        $table_columns = $this->db_util->get_column_names($table);
        foreach ($params as $column => $value) {
            if (!in_array($column, $table_columns)) {
                unset($params[$column]);
            }
        }
        return $params;
    }
    
    public function get_aliases()
    {
        return $this->table_alias_map;
    }
    public function set_aliases($table_alias_map)
    {
        $this->table_alias_map = $table_alias_map;
    }
    
    public function get_req_tables()
    {
        return $this->req_tables;
    }
    public function set_req_tables($req_tables)
    {
        $this->req_tables = $req_tables;
    }
    
    public function get_foreign_id_columns() 
    {
        return $this->foreign_id_columns;
    }
    public function set_foreign_id_columns($foreign_id_columns)
    {
        $this->foreign_id_columns = $foreign_id_columns;
    }
}