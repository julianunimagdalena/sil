Sr. <?php echo $user->getuser->nombres.' '.$user->getuser->apellidos ?> <br>
<p>
    El convenio que la empresa <?= $empresa->nombre ?> tiene en proceso, cambió a estado: <?php echo $estado ?>
    con las siguientes observaciones: 
    <br>
    <br>
    <b>
        <?php echo $observaciones ?>
    </b>
</p>
