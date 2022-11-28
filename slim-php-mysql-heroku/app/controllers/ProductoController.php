<?php
require_once './models/Producto.php';

class ProductoController extends Producto
{
    public function AltaProducto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['idMenu']) && isset($parametros['codigoComanda']))
        {
            $idMenu = $parametros['idMenu'];
            $codigoComanda = $parametros['codigoComanda'];
            $menu = Menu::obtenerMenuPorId($idMenu);
            $comanda = Comanda::obtenerComandaPorCodigo($codigoComanda);

            if($menu != null && $comanda != null)
            {
                try 
                {
                    $producto = new Producto();
                    $producto->idMenu = $idMenu;
                    $producto->codigoComanda = $codigoComanda;
                    $producto->estado = "Pendiente";
    
                    $producto->id = $producto->crearProducto();
    
                    if($producto->id > 0)
                    {
                        $payload = json_encode(array("mensaje" => "El producto fue creado exitosamente"));
                    }
                    else
                    {
                        $payload = json_encode(array("mensaje" => "Ocurrio un error al crear el producto"));
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
            $payload = json_encode(array("mensaje" => "Hubo un problema con los parametros enviados para crear el producto"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerProductosPorSectorEnPendiente($request, $response, $args)
    {
        $header = $request->getHeaderLine('authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $codigoComanda = $_GET["codigoComanda"];
        $comanda = Comanda::obtenerComandaPorCodigo($codigoComanda);

        if($comanda != null)
        {
            $productos = Producto::obtenerProductosPendientesPorCodigoComanda($data->perfil, $codigoComanda);           
            $payload = json_encode(array("Listado de productos de {$data->perfil}" => $productos));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerProductosPorSectorEnPreparacion($request, $response, $args)
    {
        $header = $request->getHeaderLine('authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $codigoComanda = $_GET["codigoComanda"];
        $comanda = Comanda::obtenerComandaPorCodigo($codigoComanda);

        if($comanda != null)
        {
            $productos = Producto::obtenerProductosEnPreparacionPorCodigoComanda($data->perfil, $codigoComanda);           
            $payload = json_encode(array("Listado de productos de {$data->perfil}" => $productos));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CambiarEstadoEnPreparacionPorSector($request, $response, $args)
    {
        $header = $request->getHeaderLine('authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $codigoComanda = $args["codigoComanda"];
        $comanda = Comanda::obtenerComandaPorCodigo($codigoComanda);

        if($comanda != null)
        {
            if($data->perfil == "cocina")
            {
                Producto::modificarProductoEnPreparacionPorSector($data->perfil, $codigoComanda, rand(20,40));
            }
            else
            {
                Producto::modificarProductoEnPreparacionPorSector($data->perfil, $codigoComanda, rand(1,5));
            }

            $tiempoMaximo = Comanda::obtenerMaximoTiempoEstimado($codigoComanda);
            Comanda::modificarTiempoEstimadoComanda($codigoComanda, $tiempoMaximo);

            $precioMaximo = Comanda::obtenerPrecioTotal($codigoComanda);
            Comanda::modificarPrecioTotalComanda($codigoComanda, $precioMaximo);

            $payload = json_encode(array("mensaje" => "Los productos se modificaron correctamente"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CambiarEstadoListoParaServirPorSector($request, $response, $args)
    {
        $header = $request->getHeaderLine('authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $codigoComanda = $args["codigoComanda"];
        $comanda = Comanda::obtenerComandaPorCodigo($codigoComanda);

        if($comanda != null)
        {
            Producto::modificarProductoListoParaServirPorSector($data->perfil, $codigoComanda);

            $payload = json_encode(array("mensaje" => "El producto se modificaron correctamente"));
        }

        if(Producto::comprobarProductosListoParaServir($codigoComanda) == true)
        {
            Comanda::modificarEstadoComanda($codigoComanda, "Listo para servir");
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosLosProductos($request, $response, $args)
    {
        try 
        {
            $listaDeProductos = Producto::obtenerTodosLosProductos();
            $payload = json_encode(array("listaDeProductos" => $listaDeProductos));
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
