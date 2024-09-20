<?php
require("conn.php");

$arreglo = array(
    "sucess"=>false,
    "status"=>400,
    "data"=>"",
    "message"=>"",
    "cant"=> 0

);

if($_SERVER["REQUEST_METHOD"] === "GET"){
    //EL METODO ES GET
    if(isset($_GET["type"]) && $_GET["type"] != ""){
        //SI SE ENVIO EL PARAMETRO type

        $conexion =new conexion;
        $conn = $conexion->conectar();

        $datos = $conn->query('SELECT * FROM empleado');
        $resultados = $datos->fetchAll();

        switch($_GET["type"]){
            case "json":
                result_json($resultados);
            break;
            case "xml";
                result_xml($resultados);
            break;
            default;
                echo("Por Favor, defina el tipo de resultado");
            break;
        }

    }else{
        //NO HAY VALORES PARA EL PARAMETRO type
        $arreglo = array(
        "sucess"=>false,
        "status"=>array("status_code"=>412,"status_text"=> "Precondition Failed"),
        "data"=>"",
        "message"=>"Se esperaba el parametro 'type' con el tipo de resultado.",
        "cant"=>0
        );
    }
}else{
    $arreglo = array(
        "sucess"=>false,
        "status"=>array("status_code"=>405,"status_text"=> "Method not Allowed"),
        "data"=>"",
        "message"=>"NO SE ACEPTA EL METODO",
        "cant"=> 0
    );
}

function result_jason($resultado){
    $arreglo = array(
        "sucess"=>true,
        "status"=>array("status_code"=>200,"status_text"=> "OK"),
        "data"=>$resultado,
        "message"=>"",
        "cant"=>sizeof($resultado)
    );

    header("HTTP/1.1 ".$arreglo["status"]["status_code"]." ".$arreglo["status"]["status_code"]);
    header("content.Type: Application/json");
    echo(json_encode($arreglo));
}

function result_xml($resultado){
    $xml = new SimpleXMLElement("<empleados />");
    foreach($resultado as $i => $v){
        $subnodo = $xml->addChild("empleado");
        $invert = array_flip($v);
        array_walk_recursive($invert,array($subnodo,'addChild'));
    }
    header("content-Type: text/xml");
    echo($xml->asXML());
}

?>
