<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Car.php";

    session_start();

    if(empty($_SESSION['list_of_cars'])){
      $_SESSION['list_of_cars'] = array();
    }

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
      'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
      return $app['twig']->render('car-search.html.twig');
    });

    $app->get("/car_search", function() use ($app) {
      $porsche = new Car("2014 Porsche 911", 114991, 7864, "img/porsche.jpg");
      array_push($_SESSION['list_of_cars'], $porsche);
      $ford = new Car("2011 Ford F450", 55995, 14241, "img/ford.jpg");
      array_push($_SESSION['list_of_cars'], $ford);
      $lexus = new Car("2013 Lexus RX 350", 44700, 20000, "img/lexus.jpg");
      array_push($_SESSION['list_of_cars'], $lexus);
      $mercedes = new Car("Mercedes Benz CLS550", 39900, 37979, "img/mercedes.jpg");
      array_push($_SESSION['list_of_cars'], $mercedes);
      $random = new Car();
      array_push($_SESSION['list_of_cars'], $random);

      $cars_matching_search = array();
      foreach ($_SESSION['list_of_cars'] as $car) {
        if ( ($car->getPrice() <= $_GET["price"]) && ($car->getMiles() <= $_GET["mileage"]) ) {
          array_push($cars_matching_search, $car);
        }
      }

      return $app['twig']->render('matching-search.html.twig', array('matches' => $cars_matching_search));
    });

    $app->post("/new_car", function() use ($app) {
      $new_car = new Car($_POST['add_make_model'], $_POST['add_price'], $_POST['add_miles'], $_POST['add_image']);
      $new_car->save();
      return $app['twig']->render('car-added.html.twig', array('newcar' => $new_car));
    });

    return $app;
?>
