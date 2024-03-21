<?php
 session_start();

 if(!isset($_SESSION['usuario'])){
    echo '
     <script>
      alert("please, log in");
   </script>
    ';
    header("location: ../Login/Login.php");
    session_destroy();
    die();
 }

?>
    <!DOCTYPE html>

    <html lang="en">
        
    <head>
        <meta charset="utf-8" />

    <link rel="stylesheet" href="../css/styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <!-- <link href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&family=Lato:wght@100&family=Press+Start+2P&display=swap" rel="stylesheet"> -->
        <link rel="stylesheet" href="../css/Font.css">
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
        <link href="../css/boostrap.min.css" rel="stylesheet"> 
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
        <script src="../JS/bootstrap.bundle.min.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script> -->
        <script src="../JS/jquery.min.js"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
        <script src="../JS/sweetalert2@11.js"></script>
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> -->
        <link rel="stylesheet" href="../css/all.min.css">
        <link rel="icon" href="../img/atento_colombia_oficial_logo.jpg">
    <title>Login y Regitro</title>
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
  <script src="../JS/bootstrap.min.js"></script>

        <script type="text/javascript">
            var url_serv = "http://localhost:8080/Enmascarador/Controller";
            function obtenerBases(){
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                            
                $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerBases.php",                
                    success: function (datos){
                        //console.log(JSON.parse(datos));
                        let targetSelect = document.getElementById("bds");
                        let arrDatos = datos.split(",");
                //    let option = document.createElement("option");
                //  for(i=0;i<arrDatos.length;i++){
                            
                // option.value = arrDatos[45];
                // option.innerHTML = arrDatos[45];
                // targetSelect.appendChild(option);
                        for(i=0;i<arrDatos.length;i++){
                   let option = document.createElement("option");
                    option.value = arrDatos[i];
                  option.innerHTML = arrDatos[i];
                  targetSelect.appendChild(option);


                    //       /*  obtenerTablasBases(arrDatos[i],(err,data)=>{
                    //         });*/
                            
                  }                    
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las bases",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                
            }
            let textoExportado;
            let inputExportado = [];
            function obtenerTablasR(e){
                e.preventDefault();
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                let targetSelect = document.getElementById("bds");       
                $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerTablasR.php", 
                    data:{
                        "bds": targetSelect.value
                    },              
                    success: function (datos){
                        //console.log(JSON.parse(datos));
                        targetSelect = document.getElementById("tbls"); 
                        let arrDatos = datos.split(",");
                        let TGtable=document.getElementById("TablasContainer"); 
                        TGtable.innerHTML="";
                        for(i=0;i<arrDatos.length;i++){
                            let Tbr=document.createElement("tr");
                            let Td=document.createElement("td");
                            Td.innerHTML= arrDatos[i];
                            
                            Td.setAttribute("data-valor", arrDatos[i]);
                            /*console.log(arrDatos[i]);*/
                           
                            Td.addEventListener("click",()=>{
                                let text= Td.dataset['valor'];
                                // console.log(text);
                                textoExportado = text;
                                obtenerColumnasxTablas(text);
                            })
                            Tbr.appendChild(Td);
                            TGtable.appendChild(Tbr);
                            /*let option = document.createElement("option");
                            option.value = arrDatos[i];
                            option.innerHTML = arrDatos[i];
                            targetSelect.appendChild(option);*/
                        }

                    
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'block';
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las tablas",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                
            }
            function obtenerTablasT(e){
                e.preventDefault();
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                let targetSelect = document.getElementById("bds");       
                $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerTablasT.php", 
                    data:{
                        "bds": targetSelect.value
                    },              
                    success: function (datos){
                        //console.log(JSON.parse(datos));
                        targetSelect = document.getElementById("tbls"); 
                        let arrDatos = datos.split(",");
                        let TGtable=document.getElementById("TablasContainer"); 
                        TGtable.innerHTML="";
                        for(i=0;i<arrDatos.length;i++){
                            let Tbr=document.createElement("tr");
                            let Td=document.createElement("td");
                            Td.innerHTML= arrDatos[i];
                            Td.setAttribute("data-valor", arrDatos[i]);
                           
                            Td.addEventListener("click",()=>{
                                let text= Td.dataset['valor'];
                                /*console.log(text);*/
                                textoExportado = text;
                                obtenerColumnasxTablas(text);
                            })
                            Tbr.appendChild(Td);
                            TGtable.appendChild(Tbr);
                            /*let option = document.createElement("option");
                            option.value = arrDatos[i];
                            option.innerHTML = arrDatos[i];
                            targetSelect.appendChild(option);*/
                        }

                    
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'block';
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las tablas",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                
            }
            function obtenerTablasU(e){
                e.preventDefault();
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                let targetSelect = document.getElementById("bds");       
                $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerTablasU.php", 
                    data:{
                        "bds": targetSelect.value
                    },              
                    success: function (datos){
                        //console.log(JSON.parse(datos));
                        targetSelect = document.getElementById("tbls"); 
                        let arrDatos = datos.split(",");
                        let TGtable=document.getElementById("TablasContainer"); 
                        TGtable.innerHTML="";
                        for(i=0;i<arrDatos.length;i++){
                            let Tbr=document.createElement("tr");
                            let Td=document.createElement("td");
                            Td.innerHTML= arrDatos[i];
                            Td.setAttribute("data-valor", arrDatos[i]);
                            console.log(arrDatos[i]);
                            Td.addEventListener("click",()=>{
                                let text= Td.dataset['valor'];
                                /*console.log(text);*/
                                textoExportado = text;
                                obtenerColumnasxTablas(text);
                            })
                            Tbr.appendChild(Td);
                            TGtable.appendChild(Tbr);
                            /*let option = document.createElement("option");
                            option.value = arrDatos[i];
                            option.innerHTML = arrDatos[i];
                            targetSelect.appendChild(option);*/
                        }

                    
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'block';
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las tablas",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                
            }
            function obtenerTablasH(e){
                e.preventDefault();
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                let targetSelect = document.getElementById("bds");       
                $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerTablasH.php", 
                    data:{
                        "bds": targetSelect.value
                    },              
                    success: function (datos){
                        //console.log(JSON.parse(datos));
                        targetSelect = document.getElementById("tbls"); 
                        let arrDatos = datos.split(",");
                        let TGtable=document.getElementById("TablasContainer"); 
                        TGtable.innerHTML="";
                        for(i=0;i<arrDatos.length;i++){
                            let Tbr=document.createElement("tr");
                            let Td=document.createElement("td");
                            Td.innerHTML= arrDatos[i];
                            Td.setAttribute("data-valor", arrDatos[i]);
                            /*console.log(arrDatos[i]);*/
                           
                            Td.addEventListener("click",()=>{
                                let text= Td.dataset['valor'];
                                /*console.log(text);*/
                                obtenerColumnasxTablas(text);
                                textoExportado = text;
                            })
                            Tbr.appendChild(Td);
                            TGtable.appendChild(Tbr);
                            /*let option = document.createElement("option");
                            option.value = arrDatos[i];
                            option.innerHTML = arrDatos[i];
                            targetSelect.appendChild(option);*/
                        }

                    
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'block';
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las tablas",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                
            }
            function obtenerTablasL(e){
                e.preventDefault();
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                let targetSelect = document.getElementById("bds");       
                $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerTablasL.php", 
                    data:{
                        "bds": targetSelect.value
                    },              
                    success: function (datos){
                        //console.log(JSON.parse(datos));
                        targetSelect = document.getElementById("tbls"); 
                        let arrDatos = datos.split(",");
                        let TGtable=document.getElementById("TablasContainer"); 
                        TGtable.innerHTML="";
                        for(i=0;i<arrDatos.length;i++){
                            let Tbr=document.createElement("tr");
                            let Td=document.createElement("td");
                            Td.innerHTML= arrDatos[i];
                            Td.setAttribute("data-valor", arrDatos[i]);
                            /*console.log(arrDatos[i]);*/

                            Td.addEventListener("click",()=>{
                                let text= Td.dataset['valor'];
                                /*console.log(text);*/
                                textoExportado = text;
                                obtenerColumnasxTablas(text);
                            })
                            Tbr.appendChild(Td);
                            TGtable.appendChild(Tbr);
                            /*let option = document.createElement("option");
                            option.value = arrDatos[i];
                            option.innerHTML = arrDatos[i];
                            targetSelect.appendChild(option);*/
                        }

                    
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'block';
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las tablas",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                
            }
            function obtenerTablasC(e){
                e.preventDefault();
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                let targetSelect = document.getElementById("bds");       
                $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerTablasC.php", 
                    data:{
                        "bds": targetSelect.value
                    },              
                    success: function (datos){
                        //console.log(JSON.parse(datos));
                        targetSelect = document.getElementById("tbls"); 
                        let arrDatos = datos.split(",");
                        let TGtable=document.getElementById("TablasContainer"); 
                        TGtable.innerHTML="";
                        for(i=0;i<arrDatos.length;i++){
                            let Tbr=document.createElement("tr");
                            let Td=document.createElement("td");
                            Td.innerHTML= arrDatos[i];
                            Td.setAttribute("data-valor", arrDatos[i]);
                            /*console.log(arrDatos[i]);*/
                           
                            Td.addEventListener("click",()=>{
                                let text= Td.dataset['valor'];
                                /*console.log(text);*/
                                textoExportado = text;
                                obtenerColumnasxTablas(text);
                            })
                            Tbr.appendChild(Td);
                            TGtable.appendChild(Tbr);
                            /*let option = document.createElement("option");
                            option.value = arrDatos[i];
                            option.innerHTML = arrDatos[i];
                            targetSelect.appendChild(option);*/
                        }

                    
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'block';
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las tablas",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                
            }
            function obtenerTablasA(e){
                e.preventDefault();
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                let targetSelect = document.getElementById("bds");       
                $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerTablasA.php", 
                    data:{
                        "bds": targetSelect.value
                    },              
                    success: function (datos){
                        //console.log(JSON.parse(datos));
                        targetSelect = document.getElementById("tbls"); 
                        let arrDatos = datos.split(",");
                        let TGtable=document.getElementById("TablasContainer"); 
                        TGtable.innerHTML="";
                        for(i=0;i<arrDatos.length;i++){
                            let Tbr=document.createElement("tr");
                            let Td=document.createElement("td");
                            Td.innerHTML= arrDatos[i];
                            Td.setAttribute("data-valor", arrDatos[i]);
                            /*console.log(arrDatos[i]);*/
                           
                            Td.addEventListener("click",()=>{
                                let text= Td.dataset['valor'];
                                /*console.log(text);*/
                                textoExportado = text;
                                obtenerColumnasxTablas(text);
                            })
                            Tbr.appendChild(Td);
                            TGtable.appendChild(Tbr);
                            /*let option = document.createElement("option");
                            option.value = arrDatos[i];
                            option.innerHTML = arrDatos[i];
                            targetSelect.appendChild(option);*/
                        }

                    
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'block';
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las tablas",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                
            }
            function obtenerTablasNUM(e){
                e.preventDefault();
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                let targetSelect = document.getElementById("bds");       
                $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerTablasNUM.php", 
                    data:{
                        "bds": targetSelect.value
                    },              
                    success: function (datos){
                        //console.log(JSON.parse(datos));
                        targetSelect = document.getElementById("tbls"); 
                        let arrDatos = datos.split(",");
                        let TGtable=document.getElementById("TablasContainer"); 
                        TGtable.innerHTML="";
                        for(i=0;i<arrDatos.length;i++){
                            let Tbr=document.createElement("tr");
                            let Td=document.createElement("td");

                            Td.innerHTML= arrDatos[i];
                            Td.setAttribute("data-valor", arrDatos[i]);
                            /*console.log(arrDatos[i]);*/
                           
                            Td.addEventListener("click",()=>{
                                let text= Td.dataset['valor'];
                                // console.log(text);
                                textoExportado = text;
                                obtenerColumnasxTablas(text);
                            })
                            Tbr.appendChild(Td);
                            TGtable.appendChild(Tbr);
                            /*let option = document.createElement("option");
                            option.value = arrDatos[i];
                            option.innerHTML = arrDatos[i];
                            targetSelect.appendChild(option);*/
                        }

                    
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'block';
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        let tcont = document.getElementById("tblContainer");
                        tcont.style.display = 'none';
                        SWal.fire({
                            icon:"warning",
                            title:"Error al consultar las tablas",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                
            }
            
            function obtenerColumnasxTablas(Td){
            // let tdb = localStorage.getItem("lstDbSelected");
            let targetSelect;
                if (true){
                    Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                    });
                    let tdb = document.getElementById("bds");
                    targetSelect = Td;
                    let tables = textoExportado;
     
                    //let ts = document.getElementById("tbls");
                    //ts.value = tdb;
                    
                    $.ajax({
                    type: "POST",
                    url: url_serv+"/ObtenerColumnas.php", 
                    data:{
                        "Cols" : targetSelect ,
                        "bds" : tdb.value
                    },              
                    success: function (datos){
                        let arrDatos = datos.split(",");
                        let Nomtabla = document.getElementById("NomTabla");
                        let TGCoumnCheck=document.getElementById("ColumnChecker"); 
                        let TGColumn=document.getElementById("ColumnContainer"); 
                        Nomtabla.innerHTML=tables;
                        TGColumn.innerHTML="";
                        TGCoumnCheck.innerHTML="";
                        for(i=0;i<arrDatos.length;i++){
                            let tr=document.createElement("tr");
                            let td=document.createElement("td");
                            td.innerHTML= arrDatos[i];
                            td.setAttribute("data-valores", arrDatos[i]);
                            // let trCheck=document.createElement("tr");
                            let trCheck=document.createElement("tr");
                            let tdCheck=document.createElement("td");
                            var checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.id = arrDatos[i];
                            checkbox.name ="checkbox[]";
                         
                            tdCheck.setAttribute("Tabla-checkbox", arrDatos[i]);
                            
                            
                            
                            tr.appendChild(td);
                            TGColumn.appendChild(tr);
                            trCheck.appendChild(tdCheck);
                            tdCheck.appendChild(checkbox);
                            TGCoumnCheck.appendChild(trCheck);
                            /*console.log(checkbox);*/
                        }
                        let tcont = document.getElementById("ClContainer");
                        tcont.style.display = 'block';
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        let tcont = document.getElementById("ClContainer");
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las bases",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
                    

                
                } else{
                    swal.fire({
                        texts:"No se envio la tabla"
                    });
                }
            }
        function Enmascarar(e,text) {
            e.preventDefault();
            Swal.fire({
                    icon:"success",
                    title:"Proceso Completado",  
                });
                
            var checkboxes = document.querySelectorAll('input[name="checkbox[]"]');
            var checkboxValues = [];

            checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                // Obtener el valor del checkbox y agregarlo al array
                var checkboxValue = checkbox.id;
                checkboxValues.push(checkboxValue);
            }
            });

            let Columns = document.getElementById("bds");
            let tables = textoExportado;
            // console.log(textoExportado);
            // Enviar los valores al servidor usando AJAX
            $.ajax({
                    type: "POST",
                    url: url_serv+"/EnmascararDatos.php", 
                    data:{
                        "tbls": tables ,
                        "CheckColumns" : checkboxValues ,
                        "bds": Columns.value
                    },              
                    success: function (datos){
                        console.log(datos);
                    },
                    error: function (err){
                        console.log(err);
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al consultar las bases",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
            }); 
        }
        function InsertData(e,text) {
            e.preventDefault();
            Swal.fire({
                    icon:"success",
                    title:"Proceso Completado",  
                });
                
            var Inputs = document.querySelectorAll('input[name="texto[]"]');
            var InputValues = [];

            Inputs.forEach(function(input) {
                // Obtener el valor del Input y agregarlo al array
                var InputValue = input.value;
                InputValues.push(InputValue);
            });

            let Columns = document.getElementById("bds");
            let tables = textoExportado;
            let insert = [];
            insert = inputExportado;
            console.log(insert);
            // console.log(textoExportado);
            // Enviar los valores al servidor usando AJAX
            $.ajax({
                    type: "POST",
                    url: url_serv+"/InsertData.php", 
                    data:{
                        "tbls": tables ,
                        "InsertColumns" : insert ,
                        "bds": Columns.value ,
                        "TextInsert": InputValues
                    },              
                    success: function (datos){
                        console.log(datos);
                        // console.log(insert);
                        // let arrDatos = InputValues.join(",");
                        // console.log(arrDatos);
                        // Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al Insertar los datos",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
        }
        function ChargeData(e,text) {
            e.preventDefault();
                Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                }); 
                
            var checkboxes = document.querySelectorAll('input[name="checkbox[]"]');
            var checkboxValues = [];

            checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                // Obtener el valor del checkbox y agregarlo al array
                var checkboxValue = checkbox.id;
                checkboxValues.push(checkboxValue);
            }
            });

            let Columns = document.getElementById("bds");
            let tables = textoExportado;
            // console.log(textoExportado);
            // Enviar los valores al servidor usando AJAX
            $.ajax({   
                    success: function (datos){
                        let TGCoumnTexts=document.getElementById("ColumnText"); 
                        let TGColumnText=document.getElementById("ColumnContainerText"); 
                        TGColumnText.innerHTML="";
                        TGCoumnTexts.innerHTML="";
                        console.log(checkboxValues);
                        inputExportado = checkboxValues;
                        for (i = 0; i < checkboxValues.length; i++) {
                            let tr=document.createElement("tr");
                            let td=document.createElement("td");
                       
                            td.innerHTML= checkboxValues[i];
                            td.setAttribute("data-valores", checkboxValues[i]);
                            // let trCheck=document.createElement("tr");
                            let trtext=document.createElement("tr");
                            let tdtext=document.createElement("td");
                            var texto = document.createElement('input');
                            texto.type = 'text';
                            texto.id = checkboxValues[i];
                            texto.name ="texto[]";
                            texto.placeholder = "Ingrese los datos";
                            
                     
                            tdtext.setAttribute("Tabla-Inpunt", checkboxValues[i]);
                            
                            
                            
                            tr.appendChild(td);
                            TGColumnText.appendChild(tr);
                            trtext.appendChild(tdtext);
                            tdtext.appendChild(texto);
                            TGCoumnTexts.appendChild(trtext);
                            
                        }
                        
                        
                        Swal.close();
                    },
                    error: function (err){
                        console.log(err);
                        tcont.style.display = 'none';
                        Swal.fire({
                            icon:"warning",
                            title:"Error al cargar la Data",
                            text:"Verifique la consola"
                        });
                    },
                    dataType: 'text'
                    });
        }

            function Limpiar(e){
                e.preventDefault();
            // let tdb = localStorage.getItem("lstDbSelected");
            
                if (true){
                    Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                location.reload(true);
            
            }
            }
            function CerrarSesion(e){
                e.preventDefault();

                if (true){
                    Swal.fire({
                    icon:"info",
                    title:"Cargando",
                    allowOutsideClick: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });
                window.location.href = "../Login/php_datos_db/cerrar_sesion.php";
            }
            }
        </script>
    </head>
    <body onload="obtenerBases()">

    <header class="row">
        <div class="col-2 p-2 m-4">
        <img src="../img/Atento.png" alt="" style="width: 60%">
        </div>
        <div class="col-9 p-5 m-4">
        <label for="" style="display: block; text-align: right;"><a href="" onclick="CerrarSesion(event)">Cerrar Sesión</a></label>
        <?php
            echo '<label for="" style="display: block; text-align: right;">'.$_SESSION['usuario'].'</label>';
?>
        </div>
    </header>


    <!-- ** Select base datos *** -->
    <form method="POST" class="text-center" id="frm">
<div class="row">
    <div class="card d-flex justify-content-center align-items-center col-10 m-auto" style="background-color:rgba(255,255,255,0.4)">
            <div class="col-12 card-body text-center">
                <h1 class="card-title mt-2 p-2 text-center">Bienvenido</h1>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="BaseDatos col-6 justify-content-center">
                            <h4 class="text-center">Base de Datos</h4>
                                <div class="col-8 m-auto">
                                    <select class="form-select text-center " name="bds" id="bds" >    

                                    </select>
                                    <br>
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasT(event)">Seleccionar Tablas Transaccionales</button>
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasC(event)">Seleccionar Tablas de Carga</button>
                                    <!-- <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasL(event)">Seleccionar Tablas de Logs</button> 
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasR(event)">Seleccionar Tablas Relacionales</button>
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasT(event)">Seleccionar Tablas Transaccionales</button>
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasU(event)">Seleccionar Tablas Unión</button>
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasH(event)">Seleccionar Tablas Históricas</button>
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasL(event)">Seleccionar Tablas de Logs</button>
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasC(event)">Seleccionar Tablas de Carga</button>
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasA(event)">Seleccionar Tablas de Auditoria</button>
                                    <button type="submit" name="submitBDS" class="btn btn-primary col-6 mb-2" onclick="obtenerTablasNUM(event)">Seleccionar Tablas Temporales</button> 
                                    -->
                                </div> 
                        </div>

          

                        <div class="Tablas col-6 justify-content-center" style="display:none" id="tblContainer">
                       


                            <h4 class="text-center">Tablas</h4>
                                <div class="col-8 m-auto cursor-pointer">
                                    
                                <table class="table data-table col-12"  id="Tablas" >
                                        <tbody id="TablasContainer">

                                        </tbody>
                                </table>
                                
                           
                                 <!--   <select class="form-select text-center " name="tbls" id="tbls" >
                            
                                    </select>
                                    <br>
                                    <button type="submit" name="submitTBL" class="btn btn-primary col-6" onclick="obtenerColumnas(event)">Seleccionar Tablas</button>-->
                                </div> 
                        </div>    
                    </div>
            <!-- ** Select Columnas *** -->

                <br><br>
        <div class="Submit row d-flex justify-content-center align-items-center p-2" style="display:none" id="ClContainer">
            <h4 class="text-center" id="NomTabla"></h4>
            <div class="col-10 d-flex justify-content-center" >
                    <!-- <select class="form-select text-center " name="Cols" id="Cols" >
                            
                    </select>-->
                    <div class="col-4">
                            <table class="table data-table col-4"  id="Columnas" >
                                <thead>
                                    <tr>
                                        <th>>Columna Tabla</th>
                                    </tr>
                                </thead>          
                            
                                <tbody id="ColumnContainer">
                            
                                </tbody>
                            </table>
                    </div>
                    <div class="col-4">
                            <table class="table data-table col-4"  id="ColumnasC" >
                                <thead>
                                    <tr>
                                        <th>>Enmascarar</th>
                                    </tr>
                                </thead>          
                            
                                <tbody id="ColumnChecker">
                            
                                </tbody>
                            </table>
                    </div>
                    <div>
                        <br>
                    </div>
                <div>
                    <br>
                </div>
                <br>
            </div>
        </div>
        <div class="text-center">
                    <button type="button" class="btn btn-primary col-2" onclick="Limpiar(event)">Limpiar</button>
                    <button type="button" class="btn btn-primary col-2" onclick="Enmascarar(event)">Enmascarar</button>
                   <!-- <button type="button" class="btn btn-primary col-2" data-bs-toggle="modal" data-bs-target="#Modal" onclick="ChargeData(event)">Insertar Datos</button> -->
                    <form method="POST">
                    <div class="modal fade " id="Modal" tabindex = "-1" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title fs-5" id="ModalLabel">Inserción de datos</h4>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <div class="Insertar row d-flex justify-content-center align-items-center p-2" style="display:none" id="InContainer">
                                            <div class="col-10 d-flex justify-content-center" >
                                                    <!-- <select class="form-select text-center " name="Cols" id="Cols" >
                                                            
                                                    </select>-->
                                                    <div class="col-4">
                                                            <table class="table data-table col-4"  id="InsertColumnas" >
                                                                <thead>
                                                                    <tr>
                                                                        <th>>Columnas</th>
                                                                    </tr>
                                                                </thead>          
                                                            
                                                                <tbody id="ColumnContainerText">
                                                            
                                                                </tbody>
                                                            </table>
                                                    </div>
                                                    <div class="col-4">
                                                            <table class="table data-table col-4"  id="InsertColumnasC" >
                                                                <thead>
                                                                    <tr>
                                                                        <th>>insersión</th>
                                                                    </tr>
                                                                </thead>          
                                                                
                                                                <tbody id="ColumnText">
                                                            
                                                                </tbody>
                                                                
                                                            </table>
                                                    </div>
                                                    <div>
                                                        <br>
                                                    </div>
                                                <div>
                                                    <br>
                                                </div>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="InsertData(event)">Insertar</button>
                                </div>
                            </div>
                        </div>
                   </div>
                </form>

    </div>
</div>
    </form>  
</body>

    </html>
