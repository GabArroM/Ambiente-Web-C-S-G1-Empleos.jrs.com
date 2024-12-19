<?php
session_start();

if(!empty($_SESSION)){
   if($_SESSION["user_type"] == "Junior"){
    $menu = [
        ["label" => "Inicio", "url" => "index.php"],
        ["url" => "sobreNosotros.php", "label" => "Sobre Nosotros"],
        ["url" => "BusquedaEmpleo.php", "label" => "Buscar Empleo"],
        ["url" => "ModSolicitantes.php", "label" => "Perfil"],
        ["url" => "logout.php", "label" => "Salir", "button" => true]
    ];
   } else if($_SESSION["user_type"] == "Empleador"){
    $menu = [
        ["label" => "Inicio", "url" => "index.php"],
        ["url" => "sobreNosotros.php", "label" => "Sobre Nosotros"],
        ["url" => "ModEmpleados.php", "label" => "Modulo Empleador"],
        ["url" => "logout.php", "label" => "Salir", "button" => true]
    ];
   } 
} else {
    $menu = [
        ["label" => "Inicio", "url" => "index.php"],
        ["url" => "sobreNosotros.php", "label" => "Sobre Nosotros"],
        ["label" => "Autenticarse", "url" => "Autenticarse.php"],
    ];
}
?>

<nav>
    <h1 id="LogoEncab">Empleos.jrs.com</h1>
    <ul>
    <?php foreach ($menu as $item) { ?>
        <?php if (!isset($item['button']) || !$item['button']) { ?>
            <li><a href="<?php echo $item["url"]; ?>"><?php echo $item["label"]; ?></a></li>
        <?php } ?>
    <?php } ?>
        <form action="" method="GET" class="search-form">
                    <input type="text" name="query" class="search-bar" placeholder="">
                    <button type="submit" class="search-button">Buscar</button>
        </form>

        <?php foreach ($menu as $item) { ?>
            <?php if (isset($item['button']) && $item['button']) { ?>
                <a href="<?php echo $item["url"]; ?>" class="btn-salir"><?php echo $item["label"]; ?></a>
                    <?php } ?>
        <?php } ?>
              
    </ul>
</nav>


