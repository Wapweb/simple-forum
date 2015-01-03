<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 13.04.14
 * Time: 19:02
 */

class UploadFile {
    // 10mb
    const MAXFILESIZE = 10485760;
    const MAXUPLOADFILES = 10;

    private $_uploadDir;

    /** @var  PDO $_db */
    private $_db;

    private $_files;
    private $_countFiles;

    private $_user_id;

    private $_errors = "";
    private $_result = array();
    private $_allowTypes = array(
        "image/jpeg"=> true,
        "image/pjpeg"=>true,
        "image/gif"=> true,
        "image/png"=>true,
        "image/webp"=>true,
        "application/zip"=>true,
        "application/x-gzip"=>true,
        "text/plain" => true,
        "application/pdf"=>true,
        "application/x-rar-compressed" => true,
        "application/x-7z-compressed" => true,
        "image/vnd.djvu"=>true,
        "application/vnd.oasis.opendocument.text"=> true,
        "application/vnd.oasis.opendocument.spreadsheet"=> true,
        "application/vnd.oasis.opendocument.presentation" => true,
        "application/vnd.oasis.opendocument.graphics" => true,
        "application/vnd.ms-excel" => true,
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => true,
        "application/vnd.ms-powerpoint" => true,
        "application/vnd.openxmlformats-officedocument.presentationml.presentation" => true,
        "application/msword" => true,
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => true
    );
    public function __construct($files, UserModel $user){
        $this->_uploadDir = PUBLIC_ROOT."/assets/files/";
        $this->_files = $files;
        $this->_countFiles = count($files);
        $this->_db = Registry::get("db");
        $this->_user_id = $user->getId();
    }

    /**
     * @return array
     */
    public function upload() {

        // первичная проверка
        if($this->_countFiles == 0) {
            $this->_errors.= "Не выбраны файлы";
        } else {
            if($this->_countFiles > self::MAXUPLOADFILES) {
                $this->_errors.= "Одновременно можно загружать не более 10 файлов";
            }
        }

        if($this->_errors != "") {
            $this->_result["errors"] = $this->_errors;
            return $this->_result;
        }

        //
        $result = array();
        foreach($this->_files as $file) {
            $fres = array();
            $fres["error"] = "";
            $fres["error"] = $this->checkFileError($file["error"]);
            $fres["error"].= $this->checkFileType($file["type"]);
            $fres["error"].= $this->checkFileSize($file["size"]);
            if($fres["error"]) {
                $fres["file"] = $file["name"];
                $result[] = $fres;
                continue;
            }

            $extension = pathinfo($file["name"],PATHINFO_EXTENSION);
            $fileName = pathinfo($file["name"],PATHINFO_BASENAME);
            $newFileName = $fileName."_".time().".".$extension;

            $fres["file"] = $newFileName;

            //
            $uploadFile = move_uploaded_file($file["tmp_name"],$this->_uploadDir.$newFileName);
            if($uploadFile) {

                $sth =$this->_db->prepare("
                        INSERT INTO files SET
                            user_id = :user_id,
                            file_name = :file_name,
                            file_path = :file_path,
                            file_date_create = :file_date_create
                        ");
                $sth->bindParam(":user_id",$this->_user_id,PDO::PARAM_INT);
                $sth->bindParam(":file_name",$newFileName,PDO::PARAM_STR);
                $sth->bindParam(":file_path",$this->_uploadDir,PDO::PARAM_STR);
                $time = time();
                $sth->bindParam(":file_date_create",$time,PDO::PARAM_INT);
                $res = $sth->execute();
                if($res === false) {
                    $fres["error"] = "Ошибка загрузки файла в базу данных";
                }
                $fres["id"] = $this->_db->lastInsertId();

            } else {
                $fres["error"] = "Ошибка загрузки файла на сервер";
            }

            $result[] = $fres;

        }
        $this->_result["errors"] = "";
        $this->_result["response"] = $result;

        return $this->_result;
    }

    /**
     * @param $errorCode
     * @return string
     */
    private function checkFileError($errorCode) {
        $res = "";
        switch($errorCode) {
            case 1:
                $res = "Размер файла превысел максимально допустимый размер файла, который задан на сервере";
                break;
            case 2:
                $res = "Размер файла превысел максимально допустимый размер файла";
                break;
            case 3:
                $res = "Файл не удалось загрузить полностью";
                break;
            case 4:
                $res = "Файл не удалось загрузить";
                break;
            case 6:
                $res = "Системная ошибка";
                break;
            case 7:
                $res = "Системная ошибка";
                break;
            default:
        }
        return $res;
    }

    /**
     * @param $mimeType
     * @return string
     */
    private function checkFileType($mimeType) {
        $res = "";
        if(!isset($this->_allowTypes[$mimeType])) {
            $res = "Запрещенный формат";
        }
        return $res;
    }

    private function checkFileSize($filesize) {
        $res = "";
        if($filesize > self::MAXFILESIZE) {
            $res = "Максимальнвй размер файла 10мб!";
        }
        return $res;
    }
} 