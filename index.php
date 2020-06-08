<?php
  function conversion($total) {
	//ESTABLEZCO ZONA HORARIA
    date_default_timezone_set('America/Caracas');
	//VERIFICO SI HAY CONEXIÓN A INTERNET
	$internet = @fsockopen('www.google.com', 80);
    //CREO UNA VARIABLE CON EL NOMBRE DEL ARCHIVO
	$archivo = 'data.json';
    //OBTENGO LOS DATOS DESDE LA API DE DOLARTODAY
	$url = 'https://s3.amazonaws.com/dolartoday/data.json';
    //SI EXISTE LA CONEXIÓN A INTERNET
	if($internet) {  
	  //VERIFICO SI EL ARCHIVO EXISTE
      if (file_exists($archivo)) {
		//OBTENGO LA FECHA DE CREACIÓN DEL ARCHIVO
        $fechaArchivo = new DateTime(date("Y-m-d", filemtime($archivo)));
        //OBTENGO LA FECHA ACTUAL
		$fechaActual = new DateTime('NOW');
        //VERIFICO QUE TAN ANTIGUO ES EL ARCHIVO
		$antiguedad = $fechaArchivo->diff($fechaActual);
        //SI LA ANTIGUEDAD DEL ARCHIVO ES MAYOR A 1 DÍA
		if ($antiguedad->format('%d') >= 1 ) {
          //OBTENGO LOS DATOS ACTUALIZADOS DEL API DE DOLARTODAY
		  $contenido = file_get_contents($url);
		  //ESCRIBO LOS RESULTADOS EN EL ARCHIVO Y LO GUARDO
          $resultado = file_put_contents($archivo, $contenido);
        }
	  //SI EL ARCHIVO NO EXISTE
      } else {
		//OBTENGO LOS DATOS ACTUALIZADOS DEL API DE DOLARTODAY
        $contenido = utf8_encode(file_get_contents($url));
		//ESCRIBO LOS RESULTADOS EN EL ARCHIVO Y LO GUARDO
        $resultado = file_put_contents($archivo, $contenido);
      }
    }
	//LEO LOS DATOS DEL ARCHIVO
    $jsonArchivo = file_get_contents($archivo);
    //TRANSFORMO LOS DATOS JSON A UN ARREGLO
	$objDecodificado = json_decode($jsonArchivo, true);
    //OBTENGO EL PRECIO DEL DÍA
	$valor = ($objDecodificado['USD']['transferencia']);
    //REGRESO EL VALOR CON 2 DECIMALES
	return round(($total * $valor),'2');
  }
  
  $total = 1;
  echo "$".$total." son ".conversion($total);

?>