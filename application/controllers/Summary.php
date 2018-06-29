<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author caleb - 2018
 *
 */
class Summary extends MY_Controller {
    
    private $table_alias_map = array("county" => "vl_county_summary", "lab" => "vl_lab_summary", "national" => "vl_national_summary",
        "partner" => "vl_partner_summary", "facility" => "vl_site_summary", "subcounty" => "vl_subcounty_summary");

    
    //mapping: base_url/summary
    //mapping: GET base_url/summaries
    public function index()
    {
        $this->load->view('instructions');
        
    }
    
    //get based on type and id
    //mapping: GET base_url/summaries/{type}
    //mapping: GET base_url/summary/{type}/{id}
    public function select($type = "all", $id = null)
    {
        $requested_tables = array();
        switch ($type) {
            case "all":
                array_push($requested_tables, "county", "lab", "national", "partner", "facility", "subcounty");
                break;
            case "county":
            case "lab":
            case "national":
            case "partner":
            case "facility":
            case "subcounty":
                $requested_tables[] = $type;
                break;
            default:
                echo json_encode(array("error" => "could not select provided type"));
                return;
        }
        $this->load->model('base_model');
        $this->base_model->set_aliases($this->table_alias_map);
        $this->base_model->set_req_tables($requested_tables);
        
        try {
            if ($_GET) {
                parse_str($_SERVER['QUERY_STRING'], $params);
                if (array_key_exists("facility", $params)) {
                    $params = array_merge($this->base_model->get_missing_object_names($params["facility"]), $params);
                }
                $results = $this->base_model->get_tables_by_params($params);
            } else if (isset($id)) {
                $results = $this->base_model->get_tables_by_id($id);
            } else {
                $results = $this->base_model->get_tables();
            }
        } catch (Exception $e) {
            $results = array("error" => $e->getMessage());
        }
        echo json_encode($results);
    }
    
    //insert new resource by type
    //mapping: POST base_url/summaries/{type}
    public function insert($type = "all")
    {
        $requested_tables = array();
        switch ($type) {
            case "all":
                array_push($requested_tables, "county", "lab", "national", "partner", "facility", "subcounty");
                break;
            case "county":
            case "lab":
            case "national":
            case "partner":
            case "facility":
            case "subcounty":
                $requested_tables[] = $type;
                break;
            default:
                echo json_encode(array("error" => "could not insert provided type"));
                return;
        }
        $this->load->model('base_model');
        $this->base_model->set_aliases($this->table_alias_map);
        $this->base_model->set_req_tables($requested_tables);
        
        $params = json_decode(file_get_contents('php://input'), true);
        try {
            if (array_key_exists("facility", $params)) {
                $params = array_merge($this->base_model->get_missing_object_names($params["facility"]), $params);
            }
            $results = $this->base_model->insert_params($params);
        } catch (Exception $e) {
            $results = array("error" => $e->getMessage());
        }
        echo json_encode($results);
    }
    
    //update a resource by type and id
    //mapping: PUT base_url/summary/{type}/{id}
    public function update($type, $id) 
    {
        $requested_tables = array();
        switch ($type) {
            case "county":
            case "lab":
            case "national":
            case "partner":
            case "facility":
            case "subcounty":
                $requested_tables[] = $type;
                break;
            default:
                echo json_encode(array("error" => "could not update provided type"));
                return;
        }
        $this->load->model('base_model');
        $this->base_model->set_aliases($this->table_alias_map);
        $this->base_model->set_req_tables($requested_tables);
        
        $params = json_decode(file_get_contents('php://input'), true);
        try {
            $results = $this->base_model->update_with_params($params, $id);
        } catch (Exception $e) {
            $results = array("error" => $e->getMessage());
        }
        echo json_encode($results);
    }
    
    //delete a resource based on type and id
    //mapping: DELETE base_url/summary/{type}/{id}
    public function delete($type, $id) 
    {
        echo "DELETE is unimplemented";
    }
        
}