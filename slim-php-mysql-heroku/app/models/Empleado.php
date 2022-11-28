<?php

class Empleado
{
    public $id;
    public $nombreEmpleado;
    public $usuario;
    public $clave;
    public $cargoEmpleado;
    public $fechaAlta;
    public $fechaBaja;

    public function crearEmpleado()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO empleados (nombreEmpleado, usuario, clave, cargoEmpleado, fechaAlta) 
        VALUES (:nombreEmpleado, :usuario, :clave, :cargoEmpleado, :fechaAlta)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombreEmpleado', $this->nombreEmpleado, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':cargoEmpleado', $this->cargoEmpleado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodosLosEmpleados()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombreEmpleado, usuario, cargoEmpleado, fechaAlta FROM empleados");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function obtenerEmpleadoPorId($empleadoID)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombreEmpleado, usuario, cargoEmpleado, fechaAlta FROM empleados WHERE id = :id");
        $consulta->bindValue(':id', $empleadoID, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Empleado');
    }

    public static function obtenerEmpleadoPorUsuario($empleadoUsuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM empleados WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $empleadoUsuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Empleado');
    }
}
