<?php
require_once './models/Menu.php';
require_once './models/Archivos.php';

class MenuController extends Menu
{
    public function AltaMenu($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        if(isset($parametros["nombreProducto"]) && isset($parametros["precio"]) && isset($parametros["sectorProducto"]))
        {
            $nombreProducto = $parametros['nombreProducto'];
            $precio = $parametros['precio'];
            $sectorProducto = $parametros['sectorProducto'];

            try 
            {
                $menu = new Menu();
                $menu->nombreProducto = $nombreProducto;
                $menu->precio = $precio;
                $menu->sectorProducto = $sectorProducto;
    
                $menu->id = $menu->crearMenu();
    
                if($menu->id > 0)
                {
                    $payload = json_encode(array("mensaje" => "El menu fue creado exitosamente"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "Ocurrio un error al crear el menu"));
                }
            } 
            catch (Exception $e) 
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerMenuPorId($request, $response, $args)
    {
        if(isset($args['id']))
        {
            $menu = $args['id'];
            try 
            {
                $menuTraido = Menu::obtenerMenuPorId($menu);
                $payload = json_encode($menuTraido);
            } 
            catch (Exception $e) 
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CrearArchivoCSVMenu($request, $response, $args)
    {
        if(Archivos::crearArchivoCSVConDatosDelMenu())
        {
            $payload = json_encode(array("mensaje" => "Se cargo el csv con datos exitosamente!"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error, verifique la informacion ingresada"));
        }
        $response->getBody()->write($payload);
   
        return $response->withHeader('Content-Type', 'application/json');;
    }

    public function ImportarDatosMenuTabla($request, $response, $args)
    {
        try
        {
          $archivo = $_FILES["menuCSV"]["tmp_name"];
          $todoOk = Archivos::importarDatosDeUnCSVALaTabla($archivo);
          if($todoOk)
          {
            readfile($archivo);
            $respuesta = $response->withHeader('Content-Type', 'text/csv');
          }
          else
          {
            $respuesta = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode(array("mensaje" => "Error al cargar los datos")));
          }
        }
        catch(Exception  $e)
        {
          echo($e->getMessage());
        }
        finally
        {
            return $respuesta;
        }    
    }
}
