<?php
require_once './models/Empleado.php';

class EmpleadoController extends Empleado
{
    public function AltaEmpleado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['nombreEmpleado']) && isset($parametros['usuario']) && isset($parametros['clave']) && isset($parametros['cargoEmpleado']))
        {
            $nombreEmpleado = $parametros['nombreEmpleado'];
            $usuario = $parametros['usuario'];
            $clave = $parametros['clave'];
            $cargoEmpleado = $parametros['cargoEmpleado'];
            $fechaAlta = date('Y-m-d H:i:s');

            try 
            {
                $empleado = new Empleado();
                $empleado->nombreEmpleado = $nombreEmpleado;
                $empleado->usuario = $usuario;
                $empleado->clave = $clave;
                $empleado->cargoEmpleado = $cargoEmpleado;
                $empleado->fechaAlta = $fechaAlta;

                $empleado->id = $empleado->crearEmpleado();

                if($empleado->id > 0)
                {
                    $payload = json_encode(array("mensaje" => "El perfil del empleado fue creado exitosamente"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "Ocurrio un error al crear el perfil del empleado"));
                }
            } 
            catch (Exception $e) 
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Hubo un problema con los parametros enviados para crear el empleado"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerEmpleadoPorID($request, $response, $args)
    {
        if(isset($args['id']))
        {
            $empleado = $args['id'];
            try 
            {
                $empleadoTraido = Empleado::obtenerEmpleadoPorID($empleado);
                $payload = json_encode($empleadoTraido);
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

    public function TraerTodosLosEmpleados($request, $response, $args)
    {
        try 
        {
            $listaDeEmpleados = Empleado::obtenerTodosLosEmpleados();
            $payload = json_encode(array("listaDeEmpleados" => $listaDeEmpleados));
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Login($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $usuario = $parametros['usuario'];
      $clave = $parametros['clave'];

      $empleado = Empleado::obtenerEmpleadoPorUsuario($usuario);

      if($empleado)
      {
        if(password_verify($clave, $empleado->clave))
        {
          $datos = array('Usuario' => $empleado->usuario, 'Nombre del Empleado' => $empleado->nombreEmpleado, 'perfil' => $empleado->cargoEmpleado);
      
          $token = AutentificadorJWT::CrearToken($datos);
          $payload = json_encode(array('jwt' => $token, 'Sector' => $empleado->cargoEmpleado));
        }
        else
        {
          $payload = json_encode(array("mensaje" => "Contraseña invalida"));
        }
      }
      else
      {
        $payload = json_encode(array("mensaje" => "El empleado con el mail {$usuario} no se encuentra registrado"));
      }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}
