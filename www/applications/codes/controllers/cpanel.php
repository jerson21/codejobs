<?php
/**
 * Access from index.php:
 */
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class CPanel_Controller extends ZP_Load
{
	private $vars = array();
	
	public function __construct()
	{
		$this->app("cpanel");
        $this->config("cpanel", "codes");
		$this->application = whichApplication();
		$this->CPanel = $this->classes("cpanel", "CPanel", null, "cpanel");
		$this->isAdmin = $this->CPanel->load();
		$this->vars = $this->CPanel->notifications();
		$this->CPanel_Model = $this->model("CPanel_Model");
		$this->Templates = $this->core("Templates");
		$this->Templates->theme("cpanel");
	}
	
	public function index()
	{
		if ($this->isAdmin) {
			redirect("cpanel");
		} else {
			$this->login();
		}
	}
        
    public function login()
    {
		$this->title("Login");
		$this->CSS("login", "users");
		
		if (POST("connect")) {	
			$this->Users_Controller = $this->controller("Users_Controller");
			$this->Users_Controller->login("cpanel");
		} else {
			$this->vars["URL"] = getURL();
			$this->vars["view"] = $this->view("login", true, "cpanel");
		}
		
		$this->render("include", $this->vars);
		$this->rendering("header", "footer");
	}

	public function check()
	{
		if (POST("trash") and is_array(POST("records"))) { 
			foreach (POST("records") as $record) {
				$this->trash($record, true); 
			}

			redirect("$this->application/cpanel/results");
		} elseif (POST("restore") and is_array(POST("records"))) {
			foreach (POST("records") as $record) {
				$this->restore($record, true); 
			}

			redirect("$this->application/cpanel/results");
		} elseif (POST("delete") and is_array(POST("records"))) {
			foreach (POST("records") as $record) {
				$this->delete($record, true); 
			}

			if (segment(2, isLang()) === "languages") {
				redirect("$this->application/cpanel/languages");
			} else {
				redirect("$this->application/cpanel/results");
			}
		}

		return false;
	}

	public function delete($ID = 0, $return = false)
	{
		if (!$this->isAdmin) {
			$this->login();
		}
		
		if ($this->CPanel_Model->delete($ID)) {
			if ($return) {
				return true;
			}

			redirect("$this->application/cpanel/results/trash");
		} else {
			if ($return) {
				return false;
			}

			redirect("$this->application/cpanel/results");
		}	
	}

	public function restore($ID = 0, $return = false)
	{ 
		if (!$this->isAdmin) {
			$this->login();
		}
		
		if ($this->CPanel_Model->restore($ID)) {
			if ($return) {
				return true;
			}

			redirect("$this->application/cpanel/results/trash");
		} else {
			if ($return) {
				return false;
			}

			redirect("$this->application/cpanel/results");
		}
	}

	public function trash($ID = 0, $return = false)
	{
		if (!$this->isAdmin) {
			$this->login();
		}
		
		if ($this->CPanel_Model->trash($ID)) {		
			if ($return) {
				return true;
			}	

			redirect("$this->application/cpanel/results");
		} else {
			if ($return) {
				return false;
			}

			redirect("$this->application/cpanel/add");
		}
	}
	
	public function add()
	{
		if (!$this->isAdmin) {
			$this->login();
		}
		
		$this->config("cpanel", "codes");
		$this->title("Add");
		$this->CSS("forms", "cpanel");
        $this->helper(array("forms", "html"));
        $this->helper("codes", $this->application);
                
		$Model = ucfirst($this->application) ."_Model";
		$this->$Model = $this->model($Model);
		
		if (POST("save")) {
			$save = $this->$Model->cpanel("save");
			$this->vars["alert"] = $save;
		} elseif (POST("cancel")) {
			redirect("cpanel");
		}

		$this->CSS("add", $this->application);
		$this->js("mode", "codes");
		$this->vars["view"] = $this->view("add", true, $this->application);
		$this->render("content", $this->vars);
	}
	
	public function edit($ID = 0)
	{
		if (!$this->isAdmin) {
			$this->login();
		}
        
        if ((int) $ID === 0 AND !POST("edit", "clean")) { 
			redirect($this->application ."/cpanel/results");
		}

		$this->config("cpanel", "codes");
		$this->title("Edit");
		$this->CSS("forms", "cpanel");
		$Model = ucfirst($this->application) ."_Model";
		$Model_Files = ucfirst($this->application . "Files_Model");
		$this->$Model = $this->model($Model);
		$this->$Model_Files = $this->model($Model_Files);
                
		if (POST("edit")) {
			$this->vars["alert"] = $this->$Model->cpanel("edit");
		} elseif (POST("cancel")) {
			redirect("cpanel");
		} 
		
        $data = $this->$Model->getByID(isset($this->$Model->id) ? $this->$Model->id : $ID);
        
		if ($data) {
            $data[0]["Files"] = $this->$Model_Files->getByCode(isset($this->$Model->id) ? $this->$Model->id : $ID);
            
            $this->helper(array("forms", "html"));
            $this->helper("codes", $this->application);
            $this->CSS("add", $this->application);
            $this->js("mode", "codes");
            $this->vars["data"] = $data;
			$this->vars["view"] = $this->view("add", true, $this->application);
			$this->render("content", $this->vars);
		} else {
			redirect($this->application ."/cpanel/results");
		}
	}
	
	public function results()
	{
		if (!$this->isAdmin) {
			$this->login();
		}

		$this->check();
		$this->title("Manage ". $this->application);
		$this->CSS("results", "cpanel");
		$this->CSS("pagination");
		$this->js("checkbox");
		$trash = (segment(3, isLang()) === "trash") ? true : false;
		
		$this->vars["total"] = $this->CPanel_Model->total($trash);
		$this->vars["tFoot"] = $this->CPanel_Model->records($trash);
		$this->vars["message"] = (!$this->vars["tFoot"]) ? "Error" : null;
		$this->vars["pagination"] = $this->CPanel_Model->getPagination($trash);
		$this->vars["trash"] = $trash;	
		$this->vars["search"] = getSearch(); 			
		$this->vars["view"] = $this->view("results", true, $this->application);
		
		$this->render("content", $this->vars);
	}

	public function languages()
	{
		if (!$this->isAdmin) {
			$this->login();
		}

		$this->check();
		$this->helper(array("html", "forms"));
		$this->title(__("Manage languages"));
		$this->CSS("results", "cpanel");

		$this->vars["total"] = $this->CPanel_Model->totalLanguages();
		$this->vars["tFoot"] = $this->CPanel_Model->records();
		$this->vars["message"] = (!$this->vars["tFoot"]) ? "Error" : null;
		$this->vars["view"]	= $this->view("languages", true, $this->application);

		$this->render("content", $this->vars);
	}

	public function language()
	{
		if (!$this->isAdmin) {
			$this->login();
		}

		$this->helper(array("html", "forms"));
		$this->CSS("forms", "cpanel");
		$edit = (POST("edit") ? true : false);
		$this->title(__($edit ? "Edit language" : "Add language"));
		$Model = ucfirst($this->application) ."_Model";
		$this->$Model = $this->model($Model);

		if (POST("save")) {
			$save = $this->$Model->cpanel("saveLanguage");
			$this->vars["alert"] = $save;
		} elseif (POST("cancel")) {
			redirect("codes/cpanel/languages");
		} elseif (POST("edit")) {
			if (POST("records")) {
				$record = POST("records");
				$data = $this->$Model->getLanguage(array_shift($record));
			} else {
				redirect("codes/cpanel/languages");
			}
		} elseif (POST("editLanguage")) {
			$edit = $this->$Model->cpanel("editLanguage");
			$this->vars["alert"] = $edit;
		}

		$this->vars["data"] = isset($data) ? $data : null;
		$this->vars["edit"]	= $edit;
		$this->vars["view"] = $this->view("language", true, $this->application);

		$this->render("content", $this->vars);
	}

	public function activate($ID = 0, $ID_User = 0, $author = null)
	{
		if ($ID > 0 and $ID_User > 0 and !is_null($author)) {
			$this->Users_Model = $this->model("Users_Model");
			$edit = $this->Users_Model->isAllow("edit");

			if ($edit) {
				$Model = ucfirst($this->application) ."_Model";
				$this->$Model = $this->model($Model);

				if ($this->$Model->activate($ID)) {
					$vars["data"] = 1;

					$this->Users_Model->updateCredits($ID_User, "codes");

					$this->Cache = $this->core("Cache");	
					$this->Cache->removeAll("codes");
					$this->Cache->remove("profile-$author", "users");
				} else {
					$vars["data"] = 0;
				}

				$this->view("activate", $vars, $this->application);
			}
		}
	}
}