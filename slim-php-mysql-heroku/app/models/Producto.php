<?php

class Producto
{
    public $id;
    public $idMenu;
    public $codigoComanda;
    public $estado;
    public $tiempoEstimadoProducto;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (idMenu, codigoComanda, estado) VALUES 
        (:idMenu, :codigoComanda, :estado)");
        $consulta->bindValue(':idMenu', $this->idMenu, PDO::PARAM_INT);
        $consulta->bindValue(':codigoComanda', $this->codigoComanda, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodosLosProductos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProductosPendientesPorCodigoComanda($cargoEmpleado,$codigoComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos JOIN menus ON productos.idMenu = menus.id WHERE codigoComanda = :codigoComanda AND menus.sectorProducto = :cargoEmpleado AND productos.estado = 'pendiente'");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->bindValue(':cargoEmpleado', $cargoEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProductosEnPreparacionPorCodigoComanda($cargoEmpleado,$codigoComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos JOIN menus ON productos.idMenu = menus.id WHERE codigoComanda = :codigoComanda AND menus.sectorProducto = :cargoEmpleado AND productos.estado = 'En preparacion'");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->bindValue(':cargoEmpleado', $cargoEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function modificarProductoEnPreparacionPorSector($cargoEmpleado,$codigoComanda, $tiempoEstimado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos JOIN menus ON productos.idMenu = menus.id SET estado = 'En preparacion', tiempoEstimadoProducto = :tiempoEstimado WHERE codigoComanda = :codigoComanda AND menus.sectorProducto = :cargoEmpleado AND productos.estado = 'pendiente' LIMIT 1");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->bindValue(':cargoEmpleado', $cargoEmpleado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function modificarProductoListoParaServirPorSector($cargoEmpleado,$codigoComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos JOIN menus ON productos.idMenu = menus.id SET estado = 'Listo para servir' WHERE codigoComanda = :codigoComanda AND menus.sectorProducto = :cargoEmpleado AND productos.estado = 'En preparacion' LIMIT 1");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->bindValue(':cargoEmpleado', $cargoEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function modificarEstadoProductoEntregado($codigoComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos SET estado = 'Entregado' WHERE codigoComanda = :codigoComanda");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function obtenerProductosPorCodigo($codigoComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE codigoComanda = :codigoComanda");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function comprobarProductosListoParaServir($codigoComanda)
    {
        $productos = Producto::obtenerProductosPorCodigo($codigoComanda);
        $estaListo = false;

        foreach ($productos as $unProducto) 
        {
            if($unProducto->estado == "Listo para servir")
            {
                $estaListo = true;
            }
            else
            {
                return false;
            }
        }
        return $estaListo;
    }
}
