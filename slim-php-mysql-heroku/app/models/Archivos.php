<?php

class Archivos
{
    public static function crearArchivoCSVConDatosDelMenu()
    {
        $menus = Menu::obtenerTodosLosMenu();
        $ruta = "./datosBackUp/menu.csv";
        
        $archivo = fopen($ruta, "w+");
        foreach($menus as $unMenu)
        {
            if($archivo)
            {
                fwrite($archivo, implode(",", (array)$unMenu).PHP_EOL); 
            }                           
        }
        fclose($archivo);  

        if(filesize($ruta) > 0)
        {
            return true;
        }
        return false;
    }

    public static function importarDatosDeUnCSVALaTabla($archivo)
    {
        $todoOk=false;
        if(file_exists($archivo))
        {
            Menu::borrarTodosLosMenu();
            $archivo = fopen($archivo, "r");
            try
            {
                while(!feof($archivo))
                {
                    $atributosDelMenu = fgets($archivo);                        
                    if(!empty($atributosDelMenu))
                    {         
                        $menu=new Menu();

                        $propiedadesMenu=explode(",", $atributosDelMenu);
                        $id=$propiedadesMenu[0];
                        $menu->nombreProducto=$propiedadesMenu[1];
                        $menu->precio=$propiedadesMenu[2];
                        $menu->sectorProducto=$propiedadesMenu[3];
                        $todoOk=$menu->crearMenu();                       
                    }
                }
            }
            catch(\Throwable $e)
            {
                echo($e);
            }
            finally
            {
                fclose($archivo);
                return $todoOk;
            }
        }
    }
}
