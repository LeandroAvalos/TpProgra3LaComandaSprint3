<?php
require_once './models/Encuesta.php';

class EncuestaController extends Encuesta
{
    public function AltaEncuesta($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = "";
        if(isset($parametros["codigoMesa"]) && isset($parametros["codigoComanda"]) && isset($parametros["puntajeMesa"]) && isset($parametros["puntajeRestaurante"]) 
        && isset($parametros["puntajeMozo"]) && isset($parametros["puntajeCocinero"]) && isset($parametros["descripcion"]))
        {
            $codigoMesa = $parametros['codigoMesa'];
            $codigoComanda = $parametros['codigoComanda'];
            $puntajeMesa = $parametros['puntajeMesa'];
            $puntajeRestaurante = $parametros['puntajeRestaurante'];
            $puntajeMozo = $parametros['puntajeMozo'];
            $puntajeCocinero = $parametros['puntajeCocinero'];
            $descripcion = $parametros['descripcion'];

            $mesa = Mesa::obtenerMesaPorCodigo($codigoMesa);
            $comanda = Comanda::obtenerComandaPorCodigo($codigoComanda);

            if($mesa && $comanda)
            {
                if($comanda->codigoMesa == $mesa->codigoMesa)
                {
                    if($puntajeMesa > 0 && $puntajeMesa <= 10 
                    && $puntajeRestaurante > 0 && $puntajeRestaurante <= 10
                    && $puntajeMozo > 0 && $puntajeMozo <= 10
                    && $puntajeCocinero > 0 && $puntajeCocinero <= 10
                    && strlen($descripcion) > 10 && strlen($descripcion) <= 66)
                    {
                        try 
                        {
                            $encuesta = new Encuesta();
                            $encuesta->codigoMesa = $codigoMesa;
                            $encuesta->codigoComanda = $codigoComanda;
                            $encuesta->puntajeMesa = $puntajeMesa;
                            $encuesta->puntajeRestaurante = $puntajeRestaurante;
                            $encuesta->puntajeMozo = $puntajeMozo;
                            $encuesta->puntajeCocinero = $puntajeCocinero;
                            $encuesta->puntajePromedio = ($puntajeMesa + $puntajeRestaurante + $puntajeMozo + $puntajeCocinero) / 4;
                            $encuesta->descripcion = $descripcion;
                
                            $encuesta->id = $encuesta->crearEncuesta();
                
                            if($encuesta->id > 0)
                            {
                                $payload = json_encode(array("mensaje" => "Se creo exitosamente la encuesta"));
                            }
                            else
                            {
                                $payload = json_encode(array("mensaje" => "Ocurrio un error al crear la encuesta"));
                            }
                        } 
                        catch (Exception $e) 
                        {
                            $payload = json_encode(array("mensaje" => $e->getMessage()));
                        }
                    }
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "Los codigos de mesa no son iguales"));
                }
            }
            else
            {
                $payload = json_encode(array("mensaje" => "La mesa o la comanda no se encontraron"));
            }
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Datos invalidos."));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerMejoresComentariosEncuesta($request, $response, $args)
    {
        try 
        {
            $encuestasMejorPuntuadas = Encuesta::obtenerMejoresComentariosEncuesta();
            $payload = json_encode(array("Encuestas mejor puntuadas" => $encuestasMejorPuntuadas));
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
