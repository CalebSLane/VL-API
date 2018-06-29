<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author caleb - 2018
 *
 * Class used for extracting metadata from the database and creating sql to be run against the database
 */
class DB_Util {
    
    /**
     * @param String $table - The table to select from (must be previously sql injection safe if coming from user)
     * @param String $id - The id to select from the table
     * @return String - The unbound sql to be run
     */
    public function create_select_sql($table, $id = null)
    {
        $sql = "SELECT * FROM $table";
        if ($id != null) {
            $sql .= " WHERE id = ?";
        }
        return $sql;
    }
    
    /**
     * @param String $table - The table to select from (must be previously sql injection safe if coming from user)
     * @param [] $params - Associative array to select entries where column-value equals key-value in array for all entries in array
     * @return String - The unbound sql to be run
     */
    public function create_select_by_param_sql($table, $params, $foreign_id_columns = array())
    {
        $sql = "SELECT * FROM $table WHERE ";
        $prepend_value = "";
        $foreign_columns_info = $this->get_foreign_columns_info($table);
        foreach ($params as $column => $value) {
            if (array_key_exists($column, $foreign_columns_info)) {
                if (array_key_exists($column, $foreign_id_columns)) {
                    $sql .= $prepend_value . $column . " = (SELECT ID FROM " . $foreign_columns_info[$column] . " WHERE " . $foreign_id_columns[$column] . " = ?) ";
                } else {
                    $sql .= $prepend_value . $column . " = (SELECT ID FROM " . $foreign_columns_info[$column] . " WHERE name = ?) ";
                }
            } else {
                $sql .= $prepend_value . $column . " = ? ";
            }
            $prepend_value = "AND ";
        }
        return $sql;
    }
    
    
    /**
     * @param String $table - The table to insert into (must be previously sql injection safe if coming from user)
     * @param [] $params - Associative array to select entries where column-value equals key-value in array for all entries in array
     * @return String - The unbound sql to be run
     */
    public function create_insert_sql($table, $params, $foreign_id_columns = array())
    {        
        $sql = "INSERT INTO $table (";
        $columns_sql = "";
        $values_sql = "";
        $prepend_value = "";
        $foreign_columns_info = $this->get_foreign_columns_info($table);
        foreach ($params as $column => $value) {
            if (array_key_exists($column, $foreign_columns_info)) {
                $columns_sql .= $prepend_value . $column;
                if (array_key_exists($column, $foreign_id_columns)) {
                    $values_sql .= $prepend_value . "(SELECT ID FROM " . $foreign_columns_info[$column] . " WHERE " . $foreign_id_columns[$column] . " = ?)";
                } else {
                    $values_sql .= $prepend_value . "(SELECT ID FROM " . $foreign_columns_info[$column] . " WHERE name = ?)";
                }
            } else {
                $columns_sql .= $prepend_value . $column;
                $values_sql .= $prepend_value . " ? ";
            }
            $prepend_value = ", ";
        }
        $sql .= $columns_sql . ") VALUES (" . $values_sql . ")";
        return $sql;
    }
    
    /**
     * @param String $table - The table to update (must be previously sql injection safe if coming from user)
     * @param [] $params - Associative array to select entries where column-value equals key-value in array for all entries in array
     * @return String - The unbound sql to be run
     */
    public function create_update_sql($table, $params, $foreign_id_columns = array())
    {
        $sql = "UPDATE $table SET ";
        $prepend_value = "";
        $foreign_columns_info = $this->get_foreign_columns_info($table);
        foreach ($params as $column => $value) {
            if (array_key_exists($column, $foreign_columns_info)) {
                if (array_key_exists($column, $foreign_id_columns)) {
                    $sql .= $prepend_value . $column . " = (SELECT ID FROM " . $foreign_columns_info[$column] . " WHERE " . $foreign_id_columns[$column] . " = ?)";
                } else {
                    $sql .= $prepend_value . $column . " = (SELECT ID FROM " . $foreign_columns_info[$column] . " WHERE name = ?)";
                }
            } else {
                $sql .= $prepend_value . $column . " = ? ";
            }
            $prepend_value = ", ";
        }
        $sql .= " WHERE id = ?";
        return $sql;
    }
    
    /**
     * @param String $table - The table to get column names from (must be previously sql injection safe if coming from user)
     * @return [] - Array of all column names in the given table
     */
    public function get_column_names($table)
    {
        $result = array();
        $sql = "SELECT COLUMN_NAME
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA='apidb'
                    AND TABLE_NAME='$table'";
        $CI =   &get_instance();
        $query = $CI->db->query($sql);
        $results_array = $query->result_array();
        foreach ($results_array as $row) {
            $result[] = $row['COLUMN_NAME'];
        }
        return $result;
    }
    
    /**
     * @param String $table - The table to get info from (must be previously sql injection safe if coming from user)
     * @return [] - Associative array of all foreign keys where key is local column name and value is the name of the foreign table it is linked to
     */
    public function get_foreign_columns_info($table)
    {
        $result = array();
        $sql = "SELECT COLUMN_NAME, REFERENCED_TABLE_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE CONSTRAINT_SCHEMA = SCHEMA()
                    AND TABLE_NAME = '$table'
                    AND REFERENCED_COLUMN_NAME IS NOT NULL;";
        $CI =   &get_instance();
        $query = $CI->db->query($sql);
        $results_array = $query->result_array();
        foreach ($results_array as $row) {
            $result[$row['COLUMN_NAME']] = $row['REFERENCED_TABLE_NAME'];
        }
        return $result;
    }
    
    public function create_get_name_by_facility_code_sql()
    {
        $sql = "SELECT fac.name as facility,
                    lab.name as lab,
                    par.name as partner,
                    dis.name as subcounty,
                    cou.name as county,
                    pro.name as province
                FROM facilitys fac
                JOIN labs lab 
                    ON lab.ID = fac.lab
                JOIN partners par
                    ON par.ID = fac.partner
                JOIN districts dis
                    ON dis.ID = fac.district
                JOIN countys cou
                    ON cou.ID = dis.county
                JOIN provinces pro
                    ON pro.ID = dis.province
                WHERE fac.facilitycode = ?";
        return $sql;
    }
    
}