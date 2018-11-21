<?php
date_default_timezone_set('America/Bogota');
require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->get('/guardar/{conteo}', 
	function($conteo) use($app){
	$dbconexion=pg_connect( "host=ec2-23-23-80-20.compute-1.amazonaws.com port=5432 dbname=d7l7vav8kt18d9 user=oabpbkgthojhbk password=090bf4ff331f1181a615fdaa44038b861a20f8532c2612444b432002add70ebf");
	$registro=array (
		"FECHA"=>date('Y-m-d H:i:s'),
		"BOTELLASLLENAS"=>$conteo);
	$resultado=pg_insert ($dbconexion,'VARIABLES',$registro);
	return date('Y-m-d H:i:s');
	});

$app->get('/consultarConteo', 
	function() use($app){
	$dbconexion=pg_connect( "host=ec2-23-23-80-20.compute-1.amazonaws.com port=5432 dbname=d7l7vav8kt18d9 user=oabpbkgthojhbk password=090bf4ff331f1181a615fdaa44038b861a20f8532c2612444b432002add70ebf");
	$resultado=pg_query ($dbconexion,'SELECT "BOTELLASLLENAS" FROM public."VARIABLES" ORDER BY "FECHA" DESC LIMIT 1');
	return pg_fetch_all($resultado);
	});

$app->run();
