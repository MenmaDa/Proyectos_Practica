<?php
require '../conexion/setting.php';

/**
DROP TABLE TBL_TESTADO_EJECUCION
DROP PROCEDURE SPR_IU_ESTADO_EJECUCION
DROP PROCEDURE SPR_UDP_ENMASCARARDATOS
DROP FUNCTION FUN_CAMBIAR_NUMERO
DROP FUNCTION FUN_CAMBIAR_NUMERO_INT
DROP FUNCTION FUN_CAMBIAR_TEXTOA
DROP FUNCTION FUN_TYPE_DATA
DROP FUNCTION FUN_TYPE_DATA_INT
DROP FUNCTION FUN_TYPE_DATA_PK
 */
//Creamos la tabla de validación
$query = "
    DECLARE @VR_RESULT VARCHAR(MAX) = '';
    DECLARE @VR_RESULT_CODE INT = 500;
    BEGIN TRY
        IF NOT(EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'dbo' AND  TABLE_NAME = 'TBL_TESTADO_EJECUCION'))
            BEGIN
                CREATE TABLE TBL_TESTADO_EJECUCION (
                    TEE_OESTADO BIT DEFAULT 0,
                    TEE_CDESCRIPCION_ESTADO VARCHAR(500),
                    TEE_CTRAZA VARCHAR(MAX) NULL,
                    TEE_DFECHA_ACTUALIZACION DATETIME NULL
                );

                INSERT INTO TBL_TESTADO_EJECUCION (TEE_OESTADO,TEE_CDESCRIPCION_ESTADO,TEE_DFECHA_ACTUALIZACION) VALUES (0,'',GETDATE())

                SET @VR_RESULT = 'TABLA CREADA';
                SET @VR_RESULT_CODE = 200;
            END
        
        IF NOT EXISTS (SELECT * FROM sys.objects WHERE type = 'P' AND OBJECT_ID = OBJECT_ID('SPR_IU_ESTADO_EJECUCION'))
            BEGIN
                EXECUTE ('
                    CREATE PROCEDURE SPR_IU_ESTADO_EJECUCION
                        @PI_OESTADO BIT,
                        @PI_CDESCRIPCION VARCHAR(500),
                        @PI_CTRACE VARCHAR(MAX) = NULL
                    AS
                    BEGIN
                        IF EXISTS(SELECT * FROM TBL_TESTADO_EJECUCION)
                            BEGIN
                                UPDATE TBL_TESTADO_EJECUCION SET TEE_OESTADO = @PI_OESTADO, TEE_CDESCRIPCION_ESTADO = @PI_CDESCRIPCION,  TEE_DFECHA_ACTUALIZACION = GETDATE(), TEE_CTRAZA = ISNULL(@PI_CTRACE,'''');
                            END
                        ELSE
                            BEGIN
                                INSERT INTO TBL_TESTADO_EJECUCION (TEE_OESTADO,TEE_CDESCRIPCION_ESTADO,TEE_DFECHA_ACTUALIZACION,TEE_CTRAZA) VALUES (@PI_OESTADO,@PI_CDESCRIPCION,GETDATE(),ISNULL(@PI_CTRACE,''''));
                            END
                    END
                ');
                SET @VR_RESULT = @VR_RESULT + (CASE WHEN ISNULL(@VR_RESULT,'')<>'' THEN ', ' ELSE '' END) + 'SPR_IU_ESTADO_EJECUCION CREADO';
                SET @VR_RESULT_CODE = 200;
            END
        
        IF NOT EXISTS ( SELECT * FROM Information_schema.Routines WHERE Specific_schema = 'dbo' AND specific_name = 'FUN_CAMBIAR_NUMERO' AND Routine_Type = 'FUNCTION') 
            BEGIN
                EXECUTE('
                    CREATE FUNCTION [dbo].[FUN_CAMBIAR_NUMERO] (@numero NVARCHAR(MAX))
                    RETURNS NVARCHAR(MAX)
                    AS
                    BEGIN
                        -- Declare the return variable here
                        DECLARE @numero1 bigint
                        DECLARE @numero2 bigint
                        DECLARE @numero3 bigint
                            IF LEN(@numero) >= 3
                            BEGIN
                                SELECT @numero1 = CAST(SUBSTRING(@numero, 1, 3) AS bigint)
                                SELECT @numero2 = STUFF(''00000'',1,0,@numero1)
                                SELECT @numero3 = RIGHT(@numero2,10)
                            END
                            ELSE
                            BEGIN
                                SET @numero3 = @numero
                            END
                            RETURN (ISNULL(@numero3,-1))
                    END
                ');
                SET @VR_RESULT = @VR_RESULT + (CASE WHEN ISNULL(@VR_RESULT,'')<>'' THEN ', ' ELSE '' END) + 'FUN_CAMBIAR_NUMERO CREADO';
                SET @VR_RESULT_CODE = 200;
            END
        
        IF NOT EXISTS ( SELECT * FROM Information_schema.Routines WHERE Specific_schema = 'dbo' AND specific_name = 'FUN_CAMBIAR_NUMERO_INT' AND Routine_Type = 'FUNCTION') 
            BEGIN
                EXECUTE('
                    CREATE FUNCTION [dbo].[FUN_CAMBIAR_NUMERO_INT] (@numero NVARCHAR(MAX))
                    RETURNS VARCHAR(1000)
                    AS
                    BEGIN
                        DECLARE @numero1 bigint
                        DECLARE @numero2 bigint
                        DECLARE @numero3 bigint

                        IF ISNUMERIC(@numero)=1
                            BEGIN
                                IF LEN(@numero) >= 3
                                BEGIN
                                    SELECT @numero1 = CAST(SUBSTRING(@numero, 1, 3) AS bigint)
                                    SELECT @numero2 = STUFF(''000000'',1,0,@numero1)
                                    SELECT @numero3 = RIGHT(@numero2,10)
                                END
                                ELSE
                                BEGIN
                                    SET @numero3 = @numero
                                END
                            END
                        ELSE
                            BEGIN
                                SET @numero3 = 0;			
                            END	
                        RETURN (CONVERT(INT, @numero3));
                    END
                ');
                SET @VR_RESULT = @VR_RESULT + (CASE WHEN ISNULL(@VR_RESULT,'')<>'' THEN ', ' ELSE '' END) + 'FUN_CAMBIAR_NUMERO_INT CREADO';
                SET @VR_RESULT_CODE = 200;
            END
        
        IF NOT EXISTS ( SELECT * FROM Information_schema.Routines WHERE Specific_schema = 'dbo' AND specific_name = 'FUN_CAMBIAR_TEXTOA' AND Routine_Type = 'FUNCTION') 
            BEGIN
                EXECUTE('
                    CREATE FUNCTION [dbo].[FUN_CAMBIAR_TEXTOA] (@cadena NVARCHAR(MAX))
                    RETURNS VARCHAR(1000)
                    AS
                    BEGIN
            
                        DECLARE @posicion INT
                        DECLARE @subcadena1 VARCHAR(100)
                        DECLARE @subcadena2 VARCHAR(100)
            
                        -- Elimina espacios en blanco al principio y al final de la cadena
                        SET @cadena = LTRIM(RTRIM(@cadena))
            
                        IF @cadena IS NULL
                        BEGIN 	
                            SET @subcadena1 = ''''
                            SET @subcadena2 = ''''
                        END
                        ELSE
                        BEGIN
                            SET @posicion = CHARINDEX('' '', @cadena, 1)
                            IF @posicion = 0
                            BEGIN 	
                                SET @subcadena1 = @cadena
                                SET @subcadena2 = ''''
                            END
                            ELSE
                            BEGIN 	
                                SET @subcadena1 = SUBSTRING(@cadena, 1, @posicion)
                                SET @subcadena2 = SUBSTRING(@cadena, @posicion + 1, LEN(@cadena))
                            END
                        END
            
                        SET @subcadena1 = STUFF(@subcadena1, 3, LEN(@subcadena1) - 2, ''XXXXXX'')
                        SET @subcadena2 = STUFF(@subcadena2, 3, LEN(@subcadena2) - 2, ''XXXXXX'')
            
                        RETURN CONCAT(ISNULL(@subcadena1,''''), ISNULL(@subcadena2,''''))
                    END
                ');
                SET @VR_RESULT = @VR_RESULT + (CASE WHEN ISNULL(@VR_RESULT,'')<>'' THEN ', ' ELSE '' END) + 'FUN_CAMBIAR_TEXTOA CREADO';
                SET @VR_RESULT_CODE = 200;
            END
        
        IF NOT EXISTS ( SELECT * FROM Information_schema.Routines WHERE Specific_schema = 'dbo' AND specific_name = 'FUN_TYPE_DATA' AND Routine_Type = 'FUNCTION') 
            BEGIN
                EXECUTE('
                    CREATE FUNCTION [dbo].[FUN_TYPE_DATA] (@Column NVARCHAR(MAX), @Table NVARCHAR(100))
                    RETURNS NVARCHAR(MAX)
                    AS
                    BEGIN
                    DECLARE @Select varchar(max)
                        select @Select = DATA_TYPE from INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = N'''' + @Column +  '''' and TABLE_NAME like ''%'' + @Table + ''%''		
                        return(UPPER (@Select));
                    END
                ');
                SET @VR_RESULT = @VR_RESULT + (CASE WHEN ISNULL(@VR_RESULT,'')<>'' THEN ', ' ELSE '' END) + 'FUN_TYPE_DATA CREADO';
                SET @VR_RESULT_CODE = 200;
            END
        
        IF NOT EXISTS ( SELECT * FROM Information_schema.Routines WHERE Specific_schema = 'dbo' AND specific_name = 'FUN_TYPE_DATA_INT' AND Routine_Type = 'FUNCTION') 
            BEGIN
                EXECUTE('
                    CREATE FUNCTION [dbo].[FUN_TYPE_DATA_INT] (@Column NVARCHAR(MAX), @Table NVARCHAR(100))
                    RETURNS NVARCHAR(MAX)
                    AS
                    BEGIN
                    DECLARE @Select varchar(max)
                        select @Select = COLUMNPROPERTY(OBJECT_ID('''' + @Table + ''''), '''' + @Column + '''', ''IsIdentity'')
                        return(UPPER (@Select));
                    END
                ');
                SET @VR_RESULT = @VR_RESULT + (CASE WHEN ISNULL(@VR_RESULT,'')<>'' THEN ', ' ELSE '' END) + 'FUN_TYPE_DATA_INT CREADO';
                SET @VR_RESULT_CODE = 200;
            END

        IF NOT EXISTS ( SELECT * FROM Information_schema.Routines WHERE Specific_schema = 'dbo' AND specific_name = 'FUN_TYPE_DATA_PK' AND Routine_Type = 'FUNCTION') 
            BEGIN
                EXECUTE('
                    CREATE FUNCTION [dbo].[FUN_TYPE_DATA_PK] (@Column NVARCHAR(MAX), @Table NVARCHAR(100))
                    RETURNS NVARCHAR(MAX)
                    AS
                    BEGIN
                        DECLARE @Select varchar(max)
                        select @Select = a.COLUMN_NAME 
                        FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS b 
                        JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE a 
                            ON a.CONSTRAINT_CATALOG = b.CONSTRAINT_CATALOG 
                            AND a.CONSTRAINT_NAME = b.CONSTRAINT_NAME 
                        WHERE a.COLUMN_NAME = '''' + @Column +  ''''
                            AND a.TABLE_NAME = '''' + @Table + ''''
                        return(UPPER (@Select));
                    END
                ');
                SET @VR_RESULT = @VR_RESULT + (CASE WHEN ISNULL(@VR_RESULT,'')<>'' THEN ', ' ELSE '' END) + 'FUN_TYPE_DATA_PK CREADO';
                SET @VR_RESULT_CODE = 200;
            END  

        IF NOT EXISTS (SELECT * FROM sys.objects WHERE type = 'P' AND OBJECT_ID = OBJECT_ID('SPR_UDP_ENMASCARARDATOS'))
            BEGIN
                EXECUTE('
                    CREATE PROCEDURE [dbo].[SPR_UDP_ENMASCARARDATOS]
                        @PI_NOMBRE_TABLA AS NVARCHAR(100),
                        @PI_COLUMNAS NVARCHAR(MAX)
                    AS
                    BEGIN
                        DECLARE @VR_CDESC VARCHAR(500) = ''EJECUTANDO ENMASCARADO SOBRE LA TABLA: '' + @PI_NOMBRE_TABLA;
                        EXEC SPR_IU_ESTADO_EJECUCION @PI_OESTADO = 1, @PI_CDESCRIPCION = @VR_CDESC;

                        BEGIN TRY
                            DECLARE @ENMASCARA_BOOLEAN BIT = 0
                            DECLARE @SQLSENTENCIA NVARCHAR(MAX) = '''';    
                        
                            DECLARE @ColumnaActual NVARCHAR(100);
                            DECLARE @PosicionInicio INT = 1;
                            DECLARE @PosicionComa INT;
                            DECLARE @CantidadCols INT = CASE WHEN LEN(@PI_COLUMNAS) = LEN(REPLACE(@PI_COLUMNAS,'','','''')) THEN 1 ELSE (LEN(@PI_COLUMNAS) - LEN(REPLACE(@PI_COLUMNAS,'','',''''))) END;
                            
                            /**
                                ACTUALIZACIÓN VARIABLES
                                1. Se agrega una variable acumulativa para poder ejecutar la consulta en una sola sentencia
                            **/
                            DECLARE @VR_SET_UPD VARCHAR(2000) = '''';
                            DECLARE @VR_RESULT_MSG VARCHAR(MAX) = '''';
                        
                            IF @CantidadCols > 0
                                BEGIN
                                    WHILE @CantidadCols >= 0
                                        BEGIN
                                            SET @PosicionComa = CHARINDEX('','', @PI_COLUMNAS, @PosicionInicio)
                        
                                            SET @ColumnaActual = CASE WHEN @PosicionComa>0 THEN 
                                                                    SUBSTRING(@PI_COLUMNAS, @PosicionInicio, @PosicionComa - @PosicionInicio)
                                                                ELSE
                                                                    SUBSTRING(@PI_COLUMNAS, @PosicionInicio, LEN(@PI_COLUMNAS) - @PosicionInicio + 1)
                                                                END;
                                            --Verificamos que la columna no sea un PK O FK
                                            IF ISNULL(@ColumnaActual,'''')<>'''' AND dbo.FUN_TYPE_DATA_INT(ISNULL(@ColumnaActual, ''''), @PI_NOMBRE_TABLA)<>1 AND ISNULL(dbo.FUN_TYPE_DATA_PK(ISNULL(@ColumnaActual, ''''), @PI_NOMBRE_TABLA),'''')<>@ColumnaActual
                                                BEGIN
                                                    
                                                    DECLARE @VR_TARGETUPD VARCHAR(1000) = '''';
                                                    IF dbo.FUN_TYPE_DATA(@ColumnaActual,@PI_NOMBRE_TABLA) IN (''NVARCHAR'',''VARCHAR'')
                                                        BEGIN
                                                            SET @VR_TARGETUPD = ''CASE WHEN ISNUMERIC('' + @ColumnaActual + '') = 1 THEN dbo.FUN_CAMBIAR_NUMERO(CAST ('' + @ColumnaActual + '' AS VARCHAR)) WHEN UPPER('' + @ColumnaActual + '') IN (''''TRUE'''', ''''FALSE'''') THEN ''''0'''' ELSE dbo.FUN_CAMBIAR_TEXTOA('' + @ColumnaActual + '') END'';
                                                        END
                                                    ELSE IF dbo.FUN_TYPE_DATA(@ColumnaActual,@PI_NOMBRE_TABLA) IN (''BIGINT'',''INT'')
                                                        BEGIN
                                                            SET @VR_TARGETUPD = ''CASE WHEN ISNUMERIC('' + @ColumnaActual + '') = 1 THEN dbo.FUN_CAMBIAR_NUMERO_INT(CAST ('' + @ColumnaActual + '' AS VARCHAR)) ELSE '' + @ColumnaActual + '' END'';
                                                        END
                                            
                                                    --COMPARAMOS SI LA COLUMNA TIENE CONDICIONES VALIDAS DE ACTUALIZACIÓN, ENTONCES LA AGREGAMOS A LA SENTENCIA DEL CONJUNTO DE DATOS A ACTUALIZAR
                                                    IF (ISNULL(@VR_TARGETUPD,'''')<>'''')
                                                        BEGIN
                                                            SET @VR_SET_UPD = @VR_SET_UPD + (CASE WHEN @VR_SET_UPD = '''' THEN '''' ELSE '', '' END) + @ColumnaActual + '' = '' + @VR_TARGETUPD;
                                                        END
                                                    ELSE
                                                        BEGIN
                                                            SET @VR_RESULT_MSG = @VR_RESULT_MSG + (CASE WHEN ISNULL(@VR_RESULT_MSG,'''') = '''' THEN '''' ELSE '', '' END) + @ColumnaActual + '' NO TIENE CAMBIOS APLICABLES'';
                                                        END							
                                                END						
                                            ELSE
                                                BEGIN
                                                    SET @VR_RESULT_MSG = @VR_RESULT_MSG + (CASE WHEN ISNULL(@VR_RESULT_MSG,'''') = '''' THEN '''' ELSE '', '' END) + @ColumnaActual + '' NO ES ACTUALIZABLE'';
                                                END
                                            SET @PosicionInicio = @PosicionComa + 1;
                                            SET @PosicionComa = CHARINDEX('','', @PI_COLUMNAS, @PosicionInicio);
                                            SET @PosicionComa = CASE WHEN @PosicionComa <= 0 THEN (LEN(@PI_COLUMNAS)+1) ELSE @PosicionComa END;
                                            SET @CantidadCols = @CantidadCols - 1;				
                                        END
                                    IF ISNULL(@VR_SET_UPD,'''')<>''''
                                        BEGIN
                                            EXECUTE(''UPDATE dbo.'' + @PI_NOMBRE_TABLA + '' SET ''  + @VR_SET_UPD);
                                        END	
                                    ELSE
                                        BEGIN
                                            SET @VR_RESULT_MSG = @VR_RESULT_MSG + (CASE WHEN ISNULL(@VR_RESULT_MSG,'''') = '''' THEN '''' ELSE '', '' END) + ''NO SE DEFINIO UN CONJUNTO DE DATOS MODIFICABLE'';
                                        END
                                END
                            EXEC SPR_IU_ESTADO_EJECUCION @PI_OESTADO = 0, @PI_CDESCRIPCION = ''PROCESO FINALIZADO'', @PI_CTRACE = @VR_RESULT_MSG;
                        END TRY
                        BEGIN CATCH
                            DECLARE @VR_ERROR VARCHAR(MAX) = ERROR_MESSAGE();
                            EXEC SPR_IU_ESTADO_EJECUCION @PI_OESTADO = 0, @PI_CDESCRIPCION = ''PROCESO FINALIZADO CON ERRORES'', @PI_CTRACE = @VR_ERROR;
                        END CATCH    
                    END
                ');
                SET @VR_RESULT = @VR_RESULT + (CASE WHEN ISNULL(@VR_RESULT,'')<>'' THEN ', ' ELSE '' END) + 'SPR_UDP_ENMASCARARDATOS CREADO';
                SET @VR_RESULT_CODE = 200;
            END
    END TRY
    BEGIN CATCH
        SET @VR_RESULT = @VR_RESULT + (CASE WHEN ISNULL(@VR_RESULT,'')<>'' THEN ', ' ELSE '' END) + ERROR_MESSAGE();
        SET @VR_RESULT_CODE = 500;
    END CATCH
    SELECT @VR_RESULT AS RESULT_MSG,@VR_RESULT_CODE AS CODE;
";
header('Content-Type: application/json');

try{
    if (isset($_POST['bds'])) {
        $selectedBD = $_POST['bds'];
        
        if ($selectedBD!="" && $selectedBD!=null){
    
            $nParamsConn = array("Database" => $selectedBD, "UID" => USER, "PWD" => PASSWORD, "CharacterSet" => "UTF-8", "language"=>"spanish");
            sqlsrv_configure('WarningsReturnAsErrors',0);
            $nConn = sqlsrv_connect(SERVER,$nParamsConn);
            $resultQuery = sqlsrv_query($nConn, $query);   
            
            if ($resultQuery){
                $code = 0;
                $msg = "";
                $count = 0;
                while ($statusRs = sqlsrv_fetch_array($resultQuery, SQLSRV_FETCH_ASSOC)) {
                    $code = $statusRs['CODE'];
                    $msg = $statusRs['RESULT_MSG'];
                    /**print_r($code);
                    print_r($msg);**/
                    $count += 1;
                }
                if ($code!=0){       
                    echo('{"message" : "'.$msg.'", "code":'.$code.'}');
                }else{
                    echo('{"message" : "NOPASS", "code":0}');
                }
            }else{
                //indica que la consulta de errores retorno datos
                echo('{Error:"Ocurrió un error al realizar las validaciones previas en la base de datos",desc:""}');
            }
        }
    }else{
        echo('{Error:"No se envío la base de datos",desc:"El proceso no se puede ejecutar sin una base de datos de origen"}');
    }
}catch(Exception $e){
    echo('{Error:"Se produjo una excepción durante el proceso",desc:"'. $e->getMessage() .'"}');
}

?>