<?php

class Menu
{
    public $id;
    public $nombreProducto;
    public $precio;
    public $sectorProducto;

    public function crearMenu()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO menus (nombreProducto, precio, sectorProducto) VALUES (:nombreProducto, :precio, :sectorProducto)");
        $consulta->bindValue(':nombreProducto', $this->nombreProducto, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':sectorProducto', $this->sectorProducto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerMenuPorId($menuID)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM menus WHERE id = :id");
        $consulta->bindValue(':id', $menuID, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Menu');
    }

    public static function obtenerTodosLosMenu()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM menus");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Menu');
    }

    public static function borrarTodosLosMenu()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("TRUNCATE TABLE menus");
        $consulta->execute();
        return $consulta->rowCount();
    }
}
