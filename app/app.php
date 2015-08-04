<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Car.php";

    $app = new Silex\Application();

    $app->get("/", function(){
      return "
      <!DOCTYPE html>
      <html>
        <head>
          <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css'>
          <title>Car Dealership!!</title>
        </head>
        <body>
          <div class='container'>
            <h1>Car Search</h1>
            <p>Enter your maximum price and mileage</p>
            <form action='/car_search'>
              <div class='form-group'>
                <label for='price'>Enter your maximum price</label>
                <input id='price' name='price' class='form-control' type='number'>
                <label for='mileage'>Enter your maximum mileage</label>
                <input id='mileage' name='mileage' class='form-control' type='number'>
              </div>
              <button type='submit' class='btn-success'>Submit</button>
            </form>
        </body>
      </html>
      ";
    });

    $app->get("/car_search", function(){
      $porsche = new Car("2014 Porsche 911", 114991, 7864, "img/porsche.jpg");
      $ford = new Car("2011 Ford F450", 55995, 14241, "img/ford.jpg");
      $lexus = new Car("2013 Lexus RX 350", 44700, 20000, "img/lexus.jpg");
      $mercedes = new Car("Mercedes Benz CLS550", 39900, 37979, "img/mercedes.jpg");
      $random = new Car();
      $cars = array($porsche, $ford, $lexus, $mercedes, $random);

      $cars_matching_search = array();
      foreach ($cars as $car) {
        if ( ($car->getPrice() <= $_GET["price"]) && ($car->getMiles() <= $_GET["mileage"]) ) {
          array_push($cars_matching_search, $car);
        }
      }

      $output = "";
      foreach ($cars_matching_search as $car){
        $output = $output . "<div class='row'>
          <div class='col-md-6'>
            <img src=" . $car->getImage() . ">
          </div>
          <div class='col-md-6'>
            <p>" . $car->getModel(). "</p>
            <p>$" . $car->getPrice() . "</p>
            <p>Miles: " . $car->getMiles() . "</p>
          </div>
        </div>
        ";
       }
       return "
       <!DOCTYPE html>
       <html>
          <head>
              <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css'>
              <title>Search Results</title>
          </head>
          <body>
          " . $output . "
          </body>
      </html>";
    });

    return $app;
?>
