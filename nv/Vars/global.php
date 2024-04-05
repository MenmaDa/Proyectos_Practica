<?php
try{
    require '../conexion/setting.php'; //este script define los parameros de conexion a la BD de la master
    include '../Controller/log_disco.php'; //este script controla el log a disco

    $Object = new DateTime();  
    $DateAndTime = $Object->format("d-m-Y h:i:s a");  

        if (isset($_POST['CheckColumns'], $_POST['tbls'], $_POST['bds'])) {

            $selectedCL = $_POST['CheckColumns'];
            $selectedTB =  $_POST['tbls'];
            $selectDB = $_POST['bds'];
            $UTF = "UTF-8";         
            
            if ($selectedCL!="" && $selectedCL!=null){


            //Conexion dinamica a la base de datos seleccionada.
            $nParamConn = array("Database" => $selectDB, "UID" => USER, "PWD" => PASSWORD, "CharacterSet" => $UTF);
            $nConnection = sqlsrv_connect(SERVER,$nParamConn);
            //Revisa si hay conexion exitosa.
            if( $nConnection === false) {
                die( print_r( sqlsrv_errors(), true));
                echo 'error al conectarse';
            }

            // if (!isset($selectedCL)) {
            //     $selectedCL = array(); // o proporciona un valor predeterminado según tus necesidades
            //     echo  "Array vacio" + $selectedCL;
            // }
            
            //Log a disco para pruebas de escritorio de la funcion
            log_a_disco ( $DateAndTime  ." -->Database:" . $selectDB);
            log_a_disco ( $DateAndTime ." -->Tabla:" .$selectedTB);
            log_a_disco ( $DateAndTime ." -->Columnas:" .json_encode($selectedCL));
            log_a_disco ( $DateAndTime ." -->Conexion DB dinamica: SERVER" .SERVER ." -->Usuario DB: " .USER ." -->Clave DB: " .PASSWORD);

//             $_VERIFICAR_FUN_NUMEROS="SELECT * FROM sys.objects 
//             WHERE RIGHT(type_desc, 8) = '[dbo].[FUN_CAMBIAR_NUMERO]'";

//             $query_verificar_num= sqlsrv_query($nConnection, $_VERIFICAR_FUN_NUMEROS);
//             $_CREATE_FUN_NUMEROS ="FUNCTION [dbo].[FUN_CAMBIAR_NUMERO] (@numero NVARCHAR(MAX))
//              RETURNS NVARCHAR(MAX)
//             AS
//             BEGIN
//                 -- Declare the return variable here
//                 DECLARE @numero1 bigint
//                 DECLARE @numero2 bigint
//                 DECLARE @numero3 bigint
            
//                 --BEGIN TRY
//                 --PRINT (@numero);
//                     IF LEN(@numero) >= 5
//                     BEGIN
//                         SELECT @numero1 = CAST(SUBSTRING(@numero, 1, 3) AS bigint)
//                         SELECT @numero2 = STUFF('0000000',1,0,@numero1)
//                         SELECT @numero3 = RIGHT(@numero2,10)
//                     END
//                     ELSE
//                     BEGIN
//                         SET @numero3 = @numero
//                     END
//                     RETURN (@numero3)
//                 /**END TRY
//                 BEGIN CATCH
//                     RETURN '';
//                 END CATCH
//                 **/
//             END
//             ";

//             if($query_verificar_num){
//                 $existente = 0;
//                 while ($row = sqlsrv_fetch_array($query_verificar_num, SQLSRV_FETCH_ASSOC)) {
//                     //echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
//                     $existente += 1;
//                 }

//                 $_CREATE_FUN_NUMEROS = "USE [".$selectDB."] 
//                 SET ANSI_NULLS ON 
//                 SET QUOTED_IDENTIFIER ON 
//                 ". ($existente>0 ? "ALTER" : "CREATE") ."  ".$_CREATE_FUN_NUMEROS;                
//             }else{
//                 die(print_r(sqlsrv_errors(), true));
//             }
//              print_r($_CREATE_FUN_NUMEROS);
//             $query_fun_num= sqlsrv_query($nConnection, $_CREATE_FUN_NUMEROS);

//             $_VERIFICAR_FUN_TEXTOS="SELECT * FROM sys.objects 
//             WHERE RIGHT(type_desc, 8) = '[dbo].[FUN_CAMBIAR_TEXTOA]'";

//             $query_verificar_text = sqlsrv_query($nConnection, $_VERIFICAR_FUN_TEXTOS);

//             $_CREATE_FUN_TEXTO="FUNCTION [dbo].[FUN_CAMBIAR_TEXTOA] (@cadena NVARCHAR(MAX))
//             RETURNS VARCHAR(100)
//             AS
//             BEGIN
            
//                 DECLARE @posicion INT
//                 DECLARE @subcadena1 VARCHAR(100)
//                 DECLARE @subcadena2 VARCHAR(100)
            
//                  -- Elimina espacios en blanco al principio y al final de la cadena
//                 SET @cadena = LTRIM(RTRIM(@cadena))
            
//                 IF @cadena IS NULL
//                 BEGIN 	
//                     SET @subcadena1 = ''
//                     SET @subcadena2 = ''
//                 END
//                 ELSE
//                 BEGIN
//                     SET @posicion = CHARINDEX(' ', @cadena, 1)
//                     IF @posicion = 0
//                     BEGIN 	
//                         SET @subcadena1 = @cadena
//                         SET @subcadena2 = ''
//                     END
//                     ELSE
//                     BEGIN 	
//                         SET @subcadena1 = SUBSTRING(@cadena, 1, @posicion)
//                         SET @subcadena2 = SUBSTRING(@cadena, @posicion + 1, LEN(@cadena))
//                     END
//                 END
            
//                 SET @subcadena1 = STUFF(@subcadena1, 3, LEN(@subcadena1) - 2, 'XXXXXX')
//                 SET @subcadena2 = STUFF(@subcadena2, 3, LEN(@subcadena2) - 2, 'XXXXXX')
            
//                 RETURN CONCAT(@subcadena1, @subcadena2)
//             END
//             ";
    
//             if($query_verificar_text){
//                 $existente = 0;

//                 while ($row = sqlsrv_fetch_array($query_verificar_text, SQLSRV_FETCH_ASSOC)) {
//                     //echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
//                     $existente += 1;
//                 }
//                 $_CREATE_FUN_TEXTO = "USE [".$selectDB."] 
//                 SET ANSI_NULLS ON 
//                 SET QUOTED_IDENTIFIER ON 
//                 ". ($existente>0 ? "ALTER" : "CREATE") ."  ".$_CREATE_FUN_TEXTO;
//             }else{
//                 die(print_r(sqlsrv_errors(), true));
//             }
//              print_r($_CREATE_FUN_TEXTO);
//             $query_fun_text= sqlsrv_query($nConnection, $_CREATE_FUN_TEXTO);
            

//             $_VERIFICAR_SP= "SELECT * FROM ". "[".$selectDB."]".".INFORMATION_SCHEMA.ROUTINES
//             WHERE ROUTINE_TYPE = '[dbo].[SPR_Prueba_ENMASCARAR_DATOS_PHP]'";

//             $query_verificar_sp = sqlsrv_query($nConnection, $_VERIFICAR_SP);
           
//             $_CREATE_SP="PROCEDURE [dbo].[SPR_Prueba_ENMASCARAR_DATOS_PHP]  
//             -- Add the parameters for the stored procedure here
//             @PI_NOMBRE_TABLA AS NVARCHAR(100),
//             @PI_COLUMNAS NVARCHAR(MAX)
//         AS
//         BEGIN
//             --DECLARE @ENMASCARA_VARCHAR NVARCHAR(10) = 'DXXXXXO'
//             --DECLARE @ENMASCARA_INT INT = 123456
//             DECLARE @ENMASCARA_BOOLEAN BIT = 0
//             DECLARE @SQLSENTENCIA NVARCHAR(MAX)
//             --PRINT('STEP 1');
//             -- Construir la sentencia SQL dinámicamente
//             SET @SQLSENTENCIA = 'UPDATE dbo.' + @PI_NOMBRE_TABLA + ' SET ';
        
//             -- Loop a través de las columnas y aplicar enmascaramiento según el tipo
//             DECLARE @ColumnaActual NVARCHAR(100)
//             DECLARE @PosicionInicio INT = 1
//             DECLARE @PosicionComa INT = CHARINDEX(',', @PI_COLUMNAS, @PosicionInicio)
//             DECLARE @CantidadCols INT
//             SELECT @CantidadCols = LEN(@PI_COLUMNAS) - LEN(REPLACE(@PI_COLUMNAS,',',''))
//             DECLARE @VR_FNC_RESULT VARCHAR(MAX) = '';
//             --PRINT('STEP 2');
        
//             WHILE @CantidadCols >= 0
//             BEGIN
//                 --PRINT('EVALUANDO LA COLUMNA ' + CAST(@CantidadCols AS VARCHAR))
//                 SET @ColumnaActual = SUBSTRING(@PI_COLUMNAS, @PosicionInicio, @PosicionComa - @PosicionInicio)
//                 /**
//                 --PRINT('STEP 3');
//                 -- Añadir lógica de enmascaramiento según el tipo de columna
//                 PRINT(@ColumnaActual);
//                 --SET  @VR_FNC_RESULT =;
//                 SET @SQLSENTENCIA = @SQLSENTENCIA +
//                     @ColumnaActual + ' = CASE ' +
//                     'WHEN ISNULL(' + @ColumnaActual + ', '''') <> '''' THEN ' +
//                     'CASE WHEN ISNUMERIC(' + @ColumnaActual + ') = 1 THEN  dbo.FUN_CAMBIAR_NUMERO(CAST (' + @ColumnaActual + ' AS VARCHAR))' +
//                     ' WHEN UPPER(' + @ColumnaActual + ') IN (''TRUE'', ''FALSE'') THEN ''' + CAST(@ENMASCARA_BOOLEAN AS NVARCHAR) + '''' +
//                     ' ELSE ' + 'dbo.FUN_CAMBIAR_TEXTOA(' + @ColumnaActual + ')' + ' END ELSE NULL END,'
//                     --PRINT @SQLSENTENCIA;
//                 -- Avanzar la posición de inicio para la siguiente iteración		        
//                 **/
//                 IF ISNULL(@ColumnaActual,'')<>''
//                 BEGIN
//                     SET @SQLSENTENCIA = @SQLSENTENCIA +
//                     /**@ColumnaActual + ' = CASE ' +
//                     'WHEN ISNULL(' + @ColumnaActual + ', '''') <> '''' THEN ' +
//                     'CASE WHEN ISNUMERIC(' + @ColumnaActual + ') = 1 THEN  dbo.FUN_CAMBIAR_NUMERO(CAST (' + @ColumnaActual + ' AS VARCHAR))' +
//                     ' WHEN UPPER(' + @ColumnaActual + ') IN (''TRUE'', ''FALSE'') THEN ''' + CAST(@ENMASCARA_BOOLEAN AS NVARCHAR) + '''' +
//                     ' ELSE ' + 'dbo.FUN_CAMBIAR_TEXTOA(' + @ColumnaActual + ')' + ' END ELSE NULL END';**/
        
//                     @ColumnaActual + ' = ' +
//                     'CASE WHEN ISNUMERIC(' + @ColumnaActual + ') = 1 THEN  dbo.FUN_CAMBIAR_NUMERO(CAST (' + @ColumnaActual + ' AS VARCHAR))' +
//                     ' WHEN UPPER(' + @ColumnaActual + ') IN (''TRUE'', ''FALSE'') THEN ''' + CAST(@ENMASCARA_BOOLEAN AS NVARCHAR) + '''' +
//                     ' ELSE ' + 'dbo.FUN_CAMBIAR_TEXTOA(' + @ColumnaActual + ')' + ' END';
        
//                     -- Imprimir la sentencia SQL
//                     PRINT @SQLSENTENCIA;
//                     EXEC sp_executesql @SQLSENTENCIA
//                 END
        
//                 SET @SQLSENTENCIA = 'UPDATE dbo.' + @PI_NOMBRE_TABLA + ' SET ';
//                 SET @PosicionInicio = @PosicionComa + 1;
//                 SET @PosicionComa = CHARINDEX(',', @PI_COLUMNAS, @PosicionInicio);
//                 SET @PosicionComa = CASE WHEN @PosicionComa <= 0 THEN (LEN(@PI_COLUMNAS)+1) ELSE @PosicionComa END;
//                 SET @CantidadCols = @CantidadCols -1;
//             END
        
            
        
//             -- Añadir la última columna después del bucle
//             --SET @ColumnaActual = SUBSTRING(@PI_COLUMNAS, @PosicionInicio, LEN(@PI_COLUMNAS))
//             --SET  @VR_FNC_RESULT = dbo.FUN_CAMBIAR_NUMERO(@ColumnaActual);
            
            
        
            
        
//             -- Ejecutar la sentencia SQL dinámicamente
//             --EXEC SPR_Prueba_ENMASCARAR_DATOS_PHP '[TBL_TNOVEDADES_TELEVENTAS]', '[CIE_CCELULAR],[BAS_CDONANTE],[BAS_NCOD_CLIENTE],[BAS_NIDENTIFICACION_CLIENTE],[BAS_CESTADOPORT],[BAS_NPORTIN_MSISDN]'
//         END
// ";

// if($query_verificar_sp){
//     $existente = 0;

//                 while ($row = sqlsrv_fetch_array($query_verificar_sp, SQLSRV_FETCH_ASSOC)) {
//                     //echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
//                     $existente += 1;
//                 }
//     $_CREATE_SP = "USE [".$selectDB."] 
//     SET ANSI_NULLS ON 
//     SET QUOTED_IDENTIFIER ON 
//     ". ($existente>0 ? "ALTER" : "CREATE") ."  ".$_CREATE_SP;
// // } 
// // print_r($_CREATE_SP);
// // $query_crear_sp = sqlsrv_query($nConnection, $_CREATE_SP);
// //  if($query_crear_sp){
            $spName = "SPR_Prueba_ENMASCARAR_DATOS_PHP";

            $params = array(&$selectedTB, implode(", ", $selectedCL));
            

            $paramMarkers = implode(', ', array_fill(0, count($params), '?'));
            echo "paramarker". print_r($paramMarkers);

            $sql ="EXEC $spName @PI_NOMBRE_TABLA = $selectedTB, @PI_COLUMNAS = '$params[1]'";
            // echo "Sentencia SQL: $sql";

            // $sql ="EXEC $spName $selectedTB, $params[1]";
            // echo "Sentencia SQL: $sql";
            
            
            $stmt = sqlsrv_prepare($nConnection, $sql, $params);
            // echo "PI_NOMBRE_TABLA: $selectedTB<br>";
            // echo "PI_COLUMNAS: " . implode(", ", $selectedCL) . "<br>";
        
        // Verificar la preparación del statement
        if (!$stmt) {
            throw new Exception('Error al preparar el procedimiento almacenado.');
        }

        // Ejecutar el procedimiento almacenado
        if (sqlsrv_execute($stmt)) {
        echo 'Procedimiento almacenado ejecutado correctamente.';
        } else {
            throw new Exception('Error al ejecutar el procedimiento almacenado.');
        }
    
        // Cierra la conexión después de su uso
        sqlsrv_close($nConnection);
        sqlsrv_close($conexions);
        
    //  }else{
    //      echo 'No se encontro el sp';
    //  }
    }
 }
 else {
    echo "Datos incompletos o no proporcionados.";
 }
}catch(Exception $e){
    echo 'Excepción capturada: ' . $e->getMessage();
    $sqlsrvErrors = sqlsrv_errors();
    if ($sqlsrvErrors !== null) {
        foreach ($sqlsrvErrors as $error) {
            echo 'SQLSTATE: ' . $error['SQLSTATE'] . ', Código: ' . $error['code'] . ', Mensaje: ' . $error['message'];
        }
    }
}
?>