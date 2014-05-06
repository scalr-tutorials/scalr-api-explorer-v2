<?php

if ($argc !== 2)
  die("Invalid number of arguments\n");

define("SRCPATH", $argv[1]);
define("LAST_API", "ScalrAPI_2_3_0");
require SRCPATH . "/autoload.inc.php";

$apiClass = new ReflectionClass(LAST_API);

$apiMethods = array();

foreach($apiClass->getMethods() as $methIndex => $method) {
  if (!$method->isPublic() || $method->isConstructor())
    continue;



  $methodName = $method->getName();


  // One method we'll ignore
  if ($methodName === "BuildRestServer")
    continue;

  $methodObject = array("name" => $methodName);
  $methodParameters = array();

  foreach($method->getParameters() as $paramIndex => $parameter) {
    array_push($methodParameters, array(
      "name"=>$parameter->getName(),
      "required"=>!$parameter->isOptional(),
      "array"=>$parameter->isArray()
    ));
  }

  $methodObject["params"] = $methodParameters;
  array_push($apiMethods, $methodObject);
}

print(json_encode($apiMethods, JSON_PRETTY_PRINT));

?>
