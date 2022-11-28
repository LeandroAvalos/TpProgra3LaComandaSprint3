<?php
require_once './models/Comanda.php';

class ComandaController extends Comanda
{
    public function AltaComanda($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['codigoMesa']) && isset($parametros['nombreCliente']))
        {
            $nombreCliente = $parametros['nombreCliente'];
            $codigoMesa = $parametros['codigoMesa'];
            $mesa = Mesa::obtenerMesaPorCodigo($codigoMesa);

            if($mesa != null)
            {
                try 
                {
                    $comanda = new Comanda();
                    $comanda->codigoMesa = $codigoMesa;
                    $comanda->codigoComanda = Comanda::generarCodigoAlfanumerico();
                    $comanda->nombreCliente = $nombreCliente;
                    $comanda->foto = Comanda::obtenerPathDeLaFoto($request, $nombreCliente);
                    $comanda->estadoComanda = "Pendiente";
    
                    $comanda->id = $comanda->crearPedido();
    
                    if($comanda->id > 0)
                    {
                        $payload = json_encode(array("mensaje" => "La comanda fue creada exitosamente"));
                        $mesa->modificarEstadoMesa("Con cliente esperando pedido");
                    }
                    else
                    {
                        $payload = json_encode(array("mensaje" => "Ocurrio un error al crear la comanda"));
                    }
                } 
                catch (Exception $e) 
                {
                    $payload = json_encode(array("mensaje" => $e->getMessage()));
                }
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Codigo de mesa invalido"));
            }
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Hubo un problema con los parametros enviados para crear la comanda"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodasLasComandas($request, $response, $args)
    {
        try 
        {
            $listaDeComandas = Comanda::obtenerTodasLasComandas();
            $payload = json_encode(array("listaDeComandas" => $listaDeComandas));
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CambiarEstadoPedidoAEntregado($request, $response, $args)
    {
        if(isset($args['codigoComanda']))
        {
            $codigoComanda = $args['codigoComanda'];
            $comanda = Comanda::obtenerComandaPorCodigo($codigoComanda);

            if($comanda)
            {
                if($comanda->estadoComanda == "Listo para servir")
                {
                    Comanda::modificarEstadoComanda($codigoComanda, "Entregado");
                    Producto::modificarEstadoProductoEntregado($codigoComanda);
                    Mesa::modificarEstadoMesaPorCodigo($comanda->codigoMesa, "Con cliente comiendo");
                    $payload = json_encode(array("mensaje" => "Comanda entregada exitosamente"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "Ocurrio un problema al entregar la comanda"));
                }
            }

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }

    public function CobrarCuentaACliente($request, $response, $args)
    {
        if(isset($args['codigoComanda']))
        {
            $codigoComanda = $args['codigoComanda'];
            $comanda = Comanda::obtenerComandaPorCodigo($codigoComanda);

            if($comanda)
            {
                if($comanda->estadoComanda == "Entregado")
                {
                    Mesa::modificarEstadoMesaPorCodigo($comanda->codigoMesa, "Con cliente pagando");
                    $payload = json_encode(array("El total de la cuenta es: " => $comanda->precioFinalComanda,"mensaje" => "Cuenta cobrada exitosamente"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "Ocurrio un problema al cobrar la cuenta"));
                }
            }

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }

    public function TraerTodasLasComandasListasParaServir($request, $response, $args)
    {
        try 
        {
            $listaDeComandasListasParaServir = Comanda::obtenerComandasListasParaServir();
            $payload = json_encode(array("listaDeComandas" => $listaDeComandasListasParaServir));
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTiempoDemoraComanda($request, $response, $args)
    {
        if(isset($args['codigoMesa']) && isset($args['codigoComanda']))
        {
            $codigoMesa = $args['codigoMesa'];
            $codigoComanda = $args['codigoComanda'];
            $tiempoDemora = 0;

            $mesa = Mesa::obtenerMesaPorCodigo($codigoMesa);
            $comanda = Comanda::obtenerComandaPorCodigo($codigoComanda);

            if($mesa != null && $comanda != null)
            {
                $tiempoDemora = $comanda->tiempoEstimado;
                $payload = json_encode(array("El tiempo de demora del pedido es: " => $tiempoDemora));
            }
            
            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
        }
    }

}
