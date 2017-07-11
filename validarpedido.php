<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/light-bootstrap-dashboard.css" rel="stylesheet"/>
<link href="css/facapolo.css" rel="stylesheet" />
<title>APOLO - Validacion</title>
<link rel="shortcut icon" href="img/ap.ico">
<body background="img/empacar.jpg" style="background-size: 100%; ">

<script> // ESTO ES PARA QUE AL PRESIONAR EL BOTON NO VUELVA ARRIBA
window.onload=function(){
var pos=window.name || 0;
window.scrollTo(0,pos);
}
window.onunload=function(){
window.name=self.pageYOffset || (document.documentElement.scrollTop+document.body.scrollTop);
}
</script>

<?php
//error_reporting(0);
require_once('db.php'); 
$info = array('Database'=>$basedatos, 'UID'=>$usuario, 'PWD'=>$pass); 
$conexion = sqlsrv_connect($servidor, $info);  
$numorden = $_GET['ord'];
$ordencompra = $_GET['id']; 
if(!$conexion){
 die(print_r(sqlsrv_errors(), true));
}

$query2 = "SELECT *
FROM (SELECT sod_pt_id, sod_qty_ord,sod_qty_emb, sod_qty_pick, pt_desc, mar_desc, sod_status      
FROM so_hist,sod_det,pt_mstr,mar_mstr
Where so_status = 'AL'
AND sod_so_id = so_id 
AND sod_pt_id = pt_id
AND pt_mar_id = mar_id
AND so_id = '".$ordencompra."') AS TM
LEFT JOIN alm_mstr ON alm_mstr.alm_pt_id = TM.sod_pt_id";

	$registros2 = sqlsrv_query($conexion, $query2);

?>

<header>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> 
  <img class="img-responsive img-center" src="img/apolo1.jpg">
  <script>
    function myFunction(orden,  producto, cnt, numorden) {
        var cantidad = prompt("Porfavor ingresar la cantidad", cnt );
        
        if (cantidad != null) {
            window.location.href = "validalineapedido.php?id=" + orden + "&pt=" + producto + "&cnt=" + cantidad + "&numorden=" + numorden ;
        }
    }
    var txt = "";
    function selectBarcode() {
    if (txt != $("#focus").val()) {
        setTimeout('use_rfid()', 3000);
        txt = $("#focus").val();
        className = $('#' + $("#focus").val()).attr('class');
        if(className == null){
          alert("Articulo no esta en el pedido");
        }else{
          partido = className.split(";");
          myFunction(partido[0],  partido[1], partido[2],partido[3]);
        }
        
    }
    $("#focus").select();
        setTimeout('selectBarcode()', 3000);
    }

    $(document).ready(function () {
       setTimeout(selectBarcode(),3000);       
    });
  </script>
</header>

<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <div class="card">
          <div class="header">
            <section class="main-row">
              <article class="col-md-8">
                <h4 class="title">Detalle Pedido <?php echo "$numorden"; ?></h4>
                <p class="category"><?php include ("fecha.php") ?></p>
              </article>

              <article class="col-md-4">
                <input class="form-control" type="text" name="tag" id="focus" placeholder="Codigo de Barras"> 
              </article>
            </section>        
          </div>

          <div class="content table-responsive table-full-width">
            <table class="table table-hover table-striped">
              <thead>
                <th>Articulo</th>
                <th><center>Cantidad Pedida</center></th>
                <th><center>Cantidad Separada</center></th>
                <th><center>Cantidad Embarcada</center></th>
                <th><center>Marca</center></th>
                <th><center>EAN Articulo</center></th>
                <th></th>
              </thead>
              <tbody>
              <?php  
                header('Content-Type: text/html; charset=ISO-8859-1');
                while($row2 = sqlsrv_fetch_object($registros2)){
                  $estilo = "alistado";

                    if ($row2->sod_status == 'x') {
                      $estilo = "pendiente";
                    }
                     if ($row2->sod_status == 'V') {
                      $estilo = "validado";
                      if ($row2->sod_qty_ord != $row2->sod_qty_emb) {
                        $estilo = "validadofaltante";
                      }
                    }
                  echo "<tr class='".$estilo."'>";                                       
                  
                  echo "<td id='".$row2->sod_pt_id."' class='".$ordencompra.";".$row2->sod_pt_id.";".$row2->sod_qty_pick.";".$numorden."'>".$row2->pt_desc."</td>" ;
                  echo "<td><center>".$row2->sod_qty_ord."</center></td> " ;
                  echo "<td><center>".$row2->sod_qty_pick."</center></td> " ;
                  echo "<td><center>".$row2->sod_qty_emb."</center></td> " ;
                  echo "<td><center>".$row2->mar_desc."</center></td> " ;  
                  echo "<td><center>".$row2->sod_pt_id."</center></td> " ; 
                  echo "<td> <button onClick = 'myFunction(".$ordencompra.",".$row2->sod_pt_id. "," .$row2->sod_qty_pick.", \"".$numorden."\")' class= 'btn btn-warning'> Validar 
                    <span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
                    </button></td>";
                   // href='valide.php?id=".$ordencompra."&pt=".$row2->sod_pt_id."'                                 
                  echo "</tr>";  
                }  
              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php  
          echo "<a href='pedidosporvalidar.php' class= 'btn btn-info'><span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span>Atras</a>";
          ?>
          <a href="logicaLogout.php" class= 'btn btn-info'> Cerrar Sesion 
            <span class='glyphicon glyphicon-log-out' aria-hidden='true'></span>
          </a>
  </div>
</section>