<?php
if($this->result['error_msg']) {
    header("location: ".$this->redirect_url);
    exit;
} else {
    header("location: ".HOME."/topic/show/".$this->topic_url."/?page=".$this->page);
    exit;
}