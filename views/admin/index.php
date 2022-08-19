<h1 class="nombre-pagina">Panel de Administración</h1>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Buscar Citas</h2>

<form class="formulario">
    <div class="campo">
        <label for="fecha">Fecha</label>
        <input type="date" id="fecha" value="<?php echo $fecha ?>">
    </div>
</form>

<?php

    if(count($citas) === 0){
        echo '<h2>No hay citas en esta fecha</h2>';
    }

?>

<div class="admin-citas">

    <ul class="citas">
        <?php $idCita = 0;
         foreach($citas as $key => $cita):
            if($idCita !== $cita->id): 
                $total = 0; ?>

                <li>
                    <p>ID: <span><?php echo $cita->id; ?></span> </p>
                    <p>Hora: <span><?php echo $cita->hora ?></span> </p>
                    <p>Nombre: <span><?php echo $cita->cliente ?></span> </p>
                    <p>Email: <span><?php echo $cita->email ?></span> </p>
                    <p>Teléfono: <span><?php echo $cita->telefono ?></span> </p>

                    <h2>Servicios</h2>

                    <?php 
                        $idCita = $cita->id; 
                    ?>

            <?php endif; ?>

                <?php $total += $cita->precio; ?>
                <p class="servicio"> <?php echo $cita->servicio . ' $' . $cita->precio ?> </p>

                <?php
                    $actual = $cita->id;
                    $proximo = $citas[$key + 1]->id ?? 0;

                    if(esUltimo($actual, $proximo)): ?>
                        <p class="total"> Total: <span>$<?php echo $total ?>.00</span></p>

                        <form action="/api/eliminar" method="POST">
                            <input type="hidden" name="id" value="<?php echo $cita->id ?>">
                            <input type="submit" class="boton-eliminar" value="Eliminar">
                        </form>
                    <?php endif; ?>
            
        <?php endforeach ?>

    </ul>

</div>

<?php
    $script = '<script src="build/js/buscador.js"></script>';
?>