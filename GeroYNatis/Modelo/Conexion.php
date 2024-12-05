<?php
function Conectarse(){
    $Conexion = mysqli_connect("localhost","root","","geroynatis");
    return $Conexion;
}
?>