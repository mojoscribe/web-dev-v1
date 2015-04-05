<?php
function getPostData(){
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);

	return get_object_vars($request);
}