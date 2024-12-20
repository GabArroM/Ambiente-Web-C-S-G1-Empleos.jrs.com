<?php
session_start();

// Inicializar menú predeterminado
$menu = [
    ["label" => "Inicio", "url" => "index.php"],
    ["url" => "sobreNosotros.php", "label" => "Sobre Nosotros"],
    ["label" => "Autenticarse", "url" => "Autenticarse.php"],
];

// Verificar si $_SESSION no está vacío y contiene el índice 'user_type'
if (!empty($_SESSION) && isset($_SESSION["user_type"])) {
    if ($_SESSION["user_type"] == "Junior") {
        $menu = [
            ["label" => "Inicio", "url" => "index.php"],
            ["url" => "sobreNosotros.php", "label" => "Sobre Nosotros"],
            ["url" => "BusquedaEmpleo.php", "label" => "Buscar Empleo"],
            ["url" => "ModSolicitantes.php", "label" => "Perfil"],
            ["url" => "logout.php", "label" => "Salir", "button" => true]
        ];
    } else if ($_SESSION["user_type"] == "Empleador") {
        $menu = [
            ["label" => "Inicio", "url" => "index.php"],
            ["url" => "sobreNosotros.php", "label" => "Sobre Nosotros"],
            ["url" => "ModEmpleados.php", "label" => "Modulo Empleador"],
            ["url" => "OfertasEmpleo.php", "label" => "Mis Ofertas"],
            ["url" => "logout.php", "label" => "Salir", "button" => true]
        ];
    }

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
        

        <?php foreach ($menu as $item) { ?>
            <?php if (isset($item['button']) && $item['button']) { ?>
                <a href="<?php echo $item["url"]; ?>" class="btn-salir"><?php echo $item["label"]; ?></a>
            <?php } ?>
        <?php } ?>
    </ul>
</nav>


