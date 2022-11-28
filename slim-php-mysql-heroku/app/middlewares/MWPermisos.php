<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWPermisos
{
    public function esSocio($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->perfil == "socio") 
            {
                $response = $handler->handle($request);
            } 
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "Esta accion necesita permisos de Socio.")));
            }
        } 
        else 
        {
            $response->getBody()->write(json_encode(array("error" => "Necesita loguearse como Socio.")));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function esMozo($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->perfil == "mozo") 
            {
                $response = $handler->handle($request);
            } 
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "Esta accion necesita permisos de Mozo.")));
            }
        } 
        else 
        {
            $response->getBody()->write(json_encode(array("error" => "Necesita loguearse como Mozo.")));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function esCocinero($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->perfil == "cocina") 
            {
                $response = $handler->handle($request);
            } 
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "Esta accion necesita permisos de Cocinero.")));
            }
        } 
        else 
        {
            $response->getBody()->write(json_encode(array("error" => "Necesita loguearse como Cocinero.")));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function esBartender($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->perfil == "bartender") 
            {
                $response = $handler->handle($request);
            } 
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "Esta accion necesita permisos de Bartender.")));
            }
        } 
        else 
        {
            $response->getBody()->write(json_encode(array("error" => "Necesita loguearse como Bartender.")));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function esCervecero($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->perfil == "cervecero") 
            {
                $response = $handler->handle($request);
            } 
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "Esta accion necesita permisos de Cervecero.")));
            }
        } 
        else 
        {
            $response->getBody()->write(json_encode(array("error" => "Necesita loguearse como Cervecero.")));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function esCerveceroOBartenderOCocinero($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->perfil == "cervecero" || $data->perfil == "bartender" || $data->perfil == "cocina") 
            {
                $response = $handler->handle($request);
            } 
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "Esta accion necesita permisos de Cervecero o Bartender o Cocinero.")));
            }
        } 
        else 
        {
            $response->getBody()->write(json_encode(array("error" => "Necesita loguearse como Cervecero o Bartender o Cocinero.")));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
