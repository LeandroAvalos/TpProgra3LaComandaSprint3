<?php

class Comanda
{
    public $id;
    public $codigoMesa;
    public $codigoComanda;
    public $nombreCliente;
    public $pathFoto;
    public $estadoComanda;
    public $precioFinalComanda;
    public $tiempoEstimado;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO comandas (codigoMesa, codigoComanda, nombreCliente, pathFoto, estadoComanda) VALUES 
        (:codigoMesa, :codigoComanda, :nombreCliente, :pathFoto, :estadoComanda)");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':codigoComanda', $this->codigoComanda, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':pathFoto', $this->pathFoto, PDO::PARAM_STR);
        $consulta->bindValue(':estadoComanda', $this->estadoComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodasLasComandas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comandas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
    }

    public static function obtenerComandaPorCodigo($codigoComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comandas WHERE codigoComanda = :codigoComanda");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Comanda');
    }

    public static function obtenerComandasListasParaServir()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comandas WHERE estadoComanda = 'Listo para servir'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
    }

    public static function modificarEstadoComanda($codigoComanda, $estadoComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE comandas SET estadoComanda = :estadoComanda WHERE codigoComanda = :codigoComanda");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->bindValue(':estadoComanda', $estadoComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function modificarTiempoEstimadoComanda($codigoComanda, $tiempoEstimado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE comandas SET tiempoEstimado = :tiempoEstimado WHERE codigoComanda = :codigoComanda");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function modificarPrecioTotalComanda($codigoComanda, $precioTotal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE comandas SET precioFinalComanda = :precioFinalComanda WHERE codigoComanda = :codigoComanda");
        $consulta->bindValue(':codigoComanda', $codigoComanda, PDO::PARAM_STR);
        $consulta->bindValue(':precioFinalComanda', $precioTotal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function obtenerMaximoTiempoEstimado($codigoComanda)
    {
        $productos = Producto::obtenerProductosPorCodigo($codigoComanda);
        $maximo = 0;

        foreach ($productos as $unProducto)
        {
            if($unProducto->tiempoEstimadoProducto > $maximo)
            {
                $maximo = $unProducto->tiempoEstimadoProducto;
            }
        }

        return $maximo;
    }

    public static function obtenerPrecioTotal($codigoComanda)
    {
        $productos = Producto::obtenerProductosPorCodigo($codigoComanda);
        $precioTotal = 0;

        foreach ($productos as $unProducto)
        {
            if($unProducto->estado == "En preparacion")
            {
                $menu = Menu::obtenerMenuPorId($unProducto->idMenu);
                $precioTotal += $menu->precio;
            }
        }

        return $precioTotal;
    }

    public static function generarCodigoAlfanumerico()
    {
        return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
    }

    public static function obtenerPathDeLaFoto($request, $nombreCliente)
    {
        $archivoSubido = $request->getUploadedFiles()['foto'];
        if ($archivoSubido->getError() === UPLOAD_ERR_OK) 
        {
          $nombreDelArchivo = $nombreCliente.'.jpg';
          if (!file_exists('../app/fotos/pedidos/'))
          {
              mkdir('../app/fotos/pedidos/', 0777, true);
          }
          $ruta = "../app/fotos/pedidos/".$nombreDelArchivo;
          $archivoSubido->moveTo($ruta);
        }
        else
        {
          $ruta = "";
        }
  
        return $ruta;
    }
   
}
