<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 12.04.14
 * Time: 20:09
 */

class FileController implements IController {
    /** @var  View $_view */
    private $_view;
    function __construct() {
        $this->_view = new View();
        if($this->_view->user->level == 0) {
            throw new Exception("Доступ запрещен");
        }
    }

    function uploadAction() {
        $files = Utility::getNormalizeFiles();
		if(isset($files["fileNames"])) {
		
			$Upload = new UploadFile($files["fileNames"],$this->_view->user);
			$res = $Upload->upload();
			$response = "";
			if($res["errors"] != "") {
				$response = "<div class='alert alert-warning'>".$res["errors"]."</div>";
			} else {
				foreach($res["response"] as $result) {
					if($result["error"] != "") {
						$response.= "<div class='text-danger'>".htmlspecialchars($result["file"])." - ".$result["error"]."</div>";
					} else {
						$response.= "
						<div class='file' id='".$result['id']."'>
						<a href='".HOME."/assets/files/".$result["file"]."'><span class='glyphicon glyphicon-file'></span> ".htmlspecialchars($result["file"])."</a> <a href='".HOME."' class='delete_link' id='".$result["id"]."'><span class='glyphicon glyphicon-remove'></span></a></div>
						";
					}
				}
			}
		}

        echo $response;

        //echo '<pre>';
        //print_r($_FILES["fileNames"]);
        //echo '</pre>';
       // sleep(5);
        //echo "<br> Файлы загружены";
    }

    function deleteAction() {
        $fc = FrontController::getInstance();
        $params = $fc->GetSimpleParams();
        $file_id = isset($params[0]) ? (int)$params[0] : 0;

        /** @var FileModel $File */
        $File = FileModel::findById($file_id);
        if($this->_view->user->getId() != $File->user_id) {
            throw new Exception("Нельзя удалять чужие файлы!");
        }


        $delete = $File->delete();
        $result = array("success"=>true);
        echo json_encode($result);

    }
} 