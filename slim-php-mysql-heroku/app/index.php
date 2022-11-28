<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './controllers/MesaController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/ComandaController.php';
require_once './controllers/MenuController.php';
require_once './controllers/EncuestaController.php';
require_once './middlewares/AutenticadorJWT.php';
require_once './middlewares/MWPermisos.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('/crearMesa', \MesaController::class . ':AltaMesa');
    $group->get('/traerMesas', \MesaController::class . ':TraerTodasLasMesas')->add(\MWPermisos::class . ':esSocio');
    $group->get('/{id}', \MesaController::class . ':TraerMesa');
    $group->get('/masUtilizada/traerMesa', \MesaController::class . ':TraerMesaMasUtilizada')->add(\MWPermisos::class . ':esSocio');
    $group->put('/cerrarMesa/{codigoMesa}', \MesaController::class . ':CambiarEstadoMesaCerrado')->add(\MWPermisos::class . ':esSocio');
  });

$app->group('/empleados', function (RouteCollectorProxy $group) {
    $group->post('/crearEmpleado', \EmpleadoController::class . ':AltaEmpleado');
    $group->get('[/]', \EmpleadoController::class . ':TraerTodosLosEmpleados');
    $group->post('/login', \EmpleadoController::class . ':Login');
  });

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->post('/crearProducto', \ProductoController::class . ':AltaProducto');
    $group->get('[/]', \ProductoController::class . ':TraerTodosLosProductos');
    $group->get('/productoPorSectorPendiente', \ProductoController::class . ':TraerProductosPorSectorEnPendiente')->add(\MWPermisos::class . ':esCerveceroOBartenderOCocinero');
    $group->get('/productoPorSectorEnPreparacion', \ProductoController::class . ':TraerProductosPorSectorEnPreparacion')->add(\MWPermisos::class . ':esCerveceroOBartenderOCocinero');
    $group->put('/modificarEstadoEnPreparacion/{codigoComanda}', \ProductoController::class . ':CambiarEstadoEnPreparacionPorSector')->add(\MWPermisos::class . ':esCerveceroOBartenderOCocinero');
    $group->put('/modificarEstadoListoParaServir/{codigoComanda}', \ProductoController::class . ':CambiarEstadoListoParaServirPorSector')->add(\MWPermisos::class . ':esCerveceroOBartenderOCocinero');
  });

$app->group('/comandas', function (RouteCollectorProxy $group) {
    $group->post('/crearComanda', \ComandaController::class . ':AltaComanda')->add(\MWPermisos::class . ':esMozo');
    $group->get('/traerComandas', \ComandaController::class . ':TraerTodasLasComandas')->add(\MWPermisos::class . ':esSocio');
    $group->get('/traerComandasListas', \ComandaController::class . ':TraerTodasLasComandasListasParaServir')->add(\MWPermisos::class . ':esMozo');
    $group->put('/cambiarEstadoComandaAEntregado/{codigoComanda}', \ComandaController::class . ':CambiarEstadoPedidoAEntregado')->add(\MWPermisos::class . ':esMozo');
    $group->put('/cobrarCuenta/{codigoComanda}', \ComandaController::class . ':CobrarCuentaACliente')->add(\MWPermisos::class . ':esMozo');
  });

$app->group('/menus', function (RouteCollectorProxy $group) {
    $group->post('/crearMenu', \MenuController::class . ':AltaMenu');
    $group->get('/crear/archivocsv', \MenuController::class . ':CrearArchivoCSVMenu');
    $group->post('/cargar/archivocsv', \MenuController::class . ':ImportarDatosMenuTabla');
  });

$app->group('/clientes', function (RouteCollectorProxy $group) {
    $group->get('/traerTiempoEstimado/{codigoMesa}/{codigoComanda}', \ComandaController::class . ':TraerTiempoDemoraComanda');
    $group->post('/crearEncuesta', \EncuestaController::class . ':AltaEncuesta');
  });

$app->group('/encuestas', function (RouteCollectorProxy $group) {
    $group->get('/encuestasMejorPuntuadas', \EncuestaController::class . ':TraerMejoresComentariosEncuesta')->add(\MWPermisos::class . ':esSocio');
  });

$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
