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
    
    /**
     * @param [] $params - Associative array of parameters to search by (column => value...)
     * @return [][] - Associative Array of all rows that correspond to all params organized by table alias (table_alias => (column => value...))
     */
    public function get_tables_by_params($params) 
    {
        $results = array();
        foreach ($this->req_tables as $table_alias) {
            $filtered_params = $this->filter_params($this->table_alias_map[$table_alias], $params);
            $sql = $this->db_util->create_select_by_param_sql($this->table_alias_map[$table_alias], $filtered_params);
            $query = $this->db->query($sql, array_values($filtered_params));
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
            $sql = $this->db_util->create_insert_sql($this->table_alias_map[$table_alias], $filtered_params);
            $query = $this->db->query($sql, array_values($filtered_params));
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
            $sql = $this->db_util->create_update_sql($this->table_alias_map[$table_alias], $filtered_params);
            $values = array_merge(array_values($filtered_params), array($id));
            $query = $this->db->query($sql, $values);
            $results[$table_alias] = array("id" => $id);
        }
        return $results;
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
    
}