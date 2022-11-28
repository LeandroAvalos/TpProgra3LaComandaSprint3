<?php

class Encuesta
{
    public $id;
    public $codigoMesa;
    public $codigoComanda;
    public $puntajeMesa;
    public $puntajeRestaurante;
    public $puntajeMozo;
    public $puntajeCocinero;
    public $puntajePromedio;
    public $descripcion;

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (codigoMesa, codigoComanda, puntajeMesa, puntajeRestaurante, puntajeMozo, puntajeCocinero, puntajePromedio, descripcion) 
        VALUES (:codigoMesa, :codigoComanda, :puntajeMesa, :puntajeRestaurante, :puntajeMozo, :puntajeCocinero, :puntajePromedio, :descripcion)");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa);
        $consulta->bindValue(':codigoComanda', $this->codigoComanda, PDO::PARAM_STR);
        $consulta->bindValue(':puntajeMesa', $this->puntajeMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntajeRestaurante', $this->puntajeRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntajeMozo', $this->puntajeMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntajeCocinero', $this->puntajeCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':puntajePromedio', $this->puntajePromedio);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerMejoresComentariosEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT puntajePromedio, descripcion FROM encuestas WHERE puntajePromedio BETWEEN '6' AND '10'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
    }
}
