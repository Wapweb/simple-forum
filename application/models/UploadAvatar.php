<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 01.09.13
 * Time: 22:26
 * To change this template use File | Settings | File Templates.
 */

class UploadAvatar {
    /** @var PDO $DB */
    private $DB;
    public function __construct() {
        $this->DB = Registry::get('db');
    }

    public function save(UserModel $user) {
        $data = Utility::getNormalizeFiles();

        if(!count($data)) {
            return array('error'=>'Вы не выбрали файл');
        }

        $avatar = $data['avatar'][0];

        if(empty($avatar['name']) || $avatar['size'] == 0) {
            return array('error'=>'Пустое имя');
        }
        if($avatar['error'] > 0) {
            return array('error'=>'Ошибка загрузки аватара');
        }

        $ext = strtolower(Utility::getFileExt($avatar['name']));

        $allow = array('jpg','jpeg','gif','png');
        if(!in_array($ext,$allow)) {
            return array('error'=>'Запрещенный формат аватара');
        }
        $info = getimagesize($avatar['tmp_name']);
        if(!$info) {
            return array('error'=>'Неверный тип изображения');
        }
        $name = str_replace(".$ext","",$avatar['name']);
        $name = Utility::UrlTranslit($name);
        $new_name = $name."_".time().".".$ext;

        if(copy($avatar['tmp_name'],PUBLIC_ROOT."/assets/images/avatars/$new_name")) {
            $old_avatar = $user->avatar;
            if(!($old_avatar)) {
                if(is_file(PUBLIC_ROOT."/assets/images/avatars/$old_avatar")) {
                    unlink(PUBLIC_ROOT."/assets/images/avatars/$old_avatar");
                }
                if(is_file(PUBLIC_ROOT."/assets/images/avatars/small_$old_avatar")) {
                    unlink(PUBLIC_ROOT."/assets/images/avatars/small_$old_avatar");
                }
            }

            $q = "update users set user_avatar = ".$this->DB->quote($new_name)." where user_id = ".$this->DB->quote($user->getId())."";
            $query = $this->DB->Query($q);

            if($query !== false) {

                $resizeObj = new ResizeImage(PUBLIC_ROOT."/assets/images/avatars/$new_name");

                // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
                $resizeObj -> resizeImage(192, 192, 'auto');

                // *** 3) Save image
                $resizeObj -> saveImage(PUBLIC_ROOT."/assets/images/avatars/small_$new_name", 100);

                return array("error"=>'',"msg"=>'Аватар успешно обновлен!','user_avatar'=>$new_name);
            }
        }


    }
}