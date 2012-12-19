<div class="topbar">
    <div class="fill">
        <div class="container">
            <a class="brand" href="index.php">IUGOGenerator</a>
            <ul class="nav">
                <li <?php if($_SESSION["seccion"] == "index"){echo 'class="active"';}?>><a href="index.php">Inicio</a></li>
                <?php if(isset($_SESSION["date_base"])){?>
                <li <?php if($_SESSION["seccion"] == "tabla"){echo 'class="active"';}?>><a href="tablas.php">Tablas</a></li>
                <li <?php if($_SESSION["seccion"] == "columnas"){echo 'class="active"';}?>><a href="columnas.php">Nueva tabla</a></li>
                <li><a href="../" target="_blank">Ir a CMS</a></li>
                <?php }?>
                
            </ul>
            
            <?php if($_SESSION["seccion"] == "columnas"){?>
            <button id="agregar-linea-head" class="top-button btn success pull-right small" onclick="agregarLinea()"  >Agregar linea</button>
            <?php }?>
        </div>
    </div>
</div>
