<?php
class ControladorOrden
{


    public function index()
    {
        $usuario = ModeloUsuario::index("usuario");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($usuario as $key => $valueUsuario) {
                if (
                    "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
                    "Basic " . base64_encode($valueUsuario["llave"] . ":" . $valueUsuario["codigo"])
                ) {
                    $orden = ModeloOrden::index("orden");
                    $json = array(
                        "status" => 200,
                        "total_registro" => count($orden),
                        "detalle" => $orden
                    );
                    echo json_encode($json, true);
                }
            }
        }
    }

    public function create($datos)
    {

        if ((isset($datos['orden_codigo']) && isset($datos['id_proceso']) && isset($datos['id_estado_orden'])) && (!is_numeric($datos['orden_codigo']) || !is_numeric($datos['id_proceso']) || !is_numeric($datos['id_estado_orden']) )) {
            $json = array(

                "status" => 404,
                "detalle" => "Error"
            );

            echo json_encode($json, true);

            return;
        }

        $buscarOrden = ModeloOrden::index("orden");
        foreach($buscarOrden as $key => $valueOrden){
            if($valueOrden['orden_codigo'] == $datos['orden_codigo']){
                $json = array(

                    "status" => 404,
                    "detalle" => "Error"
                );
    
                echo json_encode($json, true);
    
                return;
            }
        }

        $datos = array(
            "orden_codigo" => $datos['orden_codigo'],
            "id_proceso" => $datos['id_proceso'],
            "id_estado_orden" => $datos['id_estado_orden']
        );

        $usuario = ModeloUsuario::index("usuario");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($usuario as $key => $valueUsuario) {
                if (
                    "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
                    "Basic " . base64_encode($valueUsuario["llave"] . ":" . $valueUsuario["codigo"])
                ) {
                    $create = ModeloOrden::create("orden", $datos);

                    if ($create == 'ok') {
                        $json = array(

                            "status" => 200,
                            "detalle" => "Registro exitoso de orden"
                        );

                        echo json_encode($json, true);

                        return;
                    }
                }
            }
        }
    }
}
