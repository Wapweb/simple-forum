<?php
header('Content-type: application/json');
require_once ROOT."/application/libs/bbcode/Parser.php";

$parser = new JBBCode\Parser();
//$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

$builder = new JBBCode\CodeDefinitionBuilder('quote', '<blockquote>{param}</blockquote>');
$parser->addCodeDefinition($builder->build());

$builder = new JBBCode\CodeDefinitionBuilder('code', '<pre>{param}</pre>');
// $builder->setParseContent(false);
$parser->addCodeDefinition($builder->build());
$text = nl2br($this->message_data['message_text']);
$parser->parse($text);
$out = $parser->getAsHtml();

//sleep(1);
$message = "<blockquote><a href='".HOME."/user/profile/".$this->message_data['user_login']."'>".$this->message_data['user_login']."</a> в <a href='".HOME."/message/find/".$this->message_data['message_id']."'>".date("H:i d.m.y",$this->message_data['message_create_date'])."</a> пишет:<br>$out</blockquote>";
print json_encode(array('out'=>$message));
?>