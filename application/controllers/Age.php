<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author caleb - 2018
 *
 */
class Age extends MY_Controller {
    
    private $table_alias_map = array("county" => "vl_county_age", "national" => "vl_national_age",
        "partner" => "vl_partner_age", "facility" => "vl_site_age", "subcounty" => "vl_subcounty_age");
    
    
    //mapping: base_url/gender
    //mapping: GET base_url/ages
    public function index()
    {
        $this->load->view('instructions');
    }
    
    //get based on type and id
    //mapping: GET base_url/ages/{type}
    //mapping: GET base_url/ages/{type}/{id}
    public function select($type = "all", $id = null)
    {
        $requested_tables = array();
        switch ($type) {
            case "all":
                array_push($requested_tables, "county", "national", "partner", "facility", "subcounty");
                break;
            case "county":
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
        
        if ($_GET) {
            parse_str($_SERVER['QUERY_STRING'], $params);
            $results = $this->base_model->get_tables_by_params($params);
        } else if (isset($id)) {
            $results = $this->base_model->get_tables_by_id($id);
        } else {
            $results = $this->base_model->get_tables();
        }
        echo json_encode($results);
    }
    
    //insert new resource by type
    //mapping: POST base_url/ages/{type}
    public function insert($type = "all")
    {
        $requested_tables = array();
        switch ($type) {
            case "all":
                array_push($requested_tables, "county", "national", "partner", "facility", "subcounty");
                break;
            case "county":
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
        $results = $this->base_model->insert_params($params);
        echo json_encode($results);
    }
    
    //update a resource by type and id
    //mapping: PUT base_url/age/{type}/{id}
    public function update($type, $id)
    {
        $requested_tables = array();
        switch ($type) {
            case "county":
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
        $results = $this->base_model->update_with_params($params, $id);
        echo json_encode($results);
    }
    
    //delete a resource based on type and id
    //mapping: DELETE base_url/age/{type}/{id}
    public function delete($type, $id)
    {
        echo "DELETE is unimplemented";
    }    
    
}