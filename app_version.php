<?php
header("Access-Control-Allow-Origin: *");
$app_version = array('app_version' => 3);
echo json_encode($app_version);
