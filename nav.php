<?php


if(!empty($_SESSION)){
   if($_SESSION["user_type"] == "Junior"){
    $menu = [
        ["label" => "Inicio", "url" => "index.php"],
        ["url" => "ModSolicitantes.php", "label" => "Modulo Solicitantes"],
        ["url" => "logout.php", "label" => "Salir"]
    ];
   } else if($_SESSION["user_type"] == "Empleador"){
    $menu = [
        ["label" => "Inicio", "url" => "index.php"],
        ["url" => "ModEmpleados.php", "label" => "Modulo Empleados"],
        ["url" => "logout.php", "label" => "Salir"]
    ];
   } 
} else {
    $menu = [
        ["label" => "Inicio", "url" => "index.php"],
        ["label" => "Autententicarse", "url" => " Autenticarse.php"],
    ];
    
}

?>
<nav>
<h1 id="LogoEncab">Empleos.jrs.com</h1>
    
        <?php
        foreach ($menu  as $item) { ?>
            <li ><a href="<?php echo $item["url"] ?>"><?php echo $item["label"] ?></a></li>
        <?php } ?>
    
</nav>

