<?php
require_once './models/Mesa.php';

class MesaController extends Mesa
{
    public function AltaMesa($request, $response, $args)
    {
        try 
        {
            $mesa = new Mesa();
            $mesa->codigoMesa = rand(10000,99999);
            $mesa->estado = "Cerrado";

            $mesa->id = $mesa->crearMesa();

            if($mesa->id > 0)
            {
                $payload = json_encode(array("mensaje" => "La mesa fue creada exitosamente"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Ocurrio un error al crear la mesa"));
            }
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodasLasMesas($request, $response, $args)
    {
        try 
        {
            $listaDeMesas = Mesa::obtenerTodasLasMesas();
            $payload = json_encode(array("listaDeMesas" => $listaDeMesas));
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CambiarEstadoMesaCerrado($request, $response, $args)
    {
        if(isset($args['codigoMesa']))
        {
            $codigoMesa = $args['codigoMesa'];

            $mesa = Mesa::obtenerMesaPorCodigo($codigoMesa);

            if($mesa)
            {
                Mesa::modificarEstadoMesaPorCodigo($codigoMesa, "Cerrado");
                $payload = json_encode(array("mensaje" => "Se cambio el estado de la mesa {$codigoMesa} a Cerrado"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Ocurrio un problema al cambiar el estado de la mesa"));
            }

        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public static function TraerMesaMasUtilizada($request, $response, $args)
    {
      $mesasUtilizadas=array_map( function($a){return $a->codigoMesa;}, Comanda::obtenerTodasLasComandas());
      $valorMesaMasUtilizada = (array_keys(array_count_values($mesasUtilizadas), max(array_count_values($mesasUtilizadas))));

      $payload = json_encode(array("Mesas mas utilizadas" => $valorMesaMasUtilizada));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }

    // public function TraerMesa($request, $response, $args)
    // {
    //     if(isset($args['id']))
    //     {
    //         $mesa = $args['id'];
    //         try 
    //         {
    //             $mesaTraida = Mesa::obtenerMesa($mesa);
    //             $payload = json_encode($mesaTraida);
    //         } 
    //         catch (Exception $e) 
    //         {
    //             $payload = json_encode(array("mensaje" => $e->getMessage()));
    //         }
    //     }

    //     $response->getBody()->write($payload);
    //     return $response
    //       ->withHeader('Content-Type', 'application/json');
    // }
}
