<?php
header('Content-type: application/json');
//sleep(1);
print json_encode(array('user_login'=>'<a href="'.HOME.'/message/find/'.$this->message_data['message_id'].'">#'.$this->message_data['message_id'].' '.$this->message_data['user_login'].'</a>'));
?>