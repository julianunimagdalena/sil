Sr. <?php echo $user->getuser->nombres.' '.$user->getuser->apellidos ?> <br>
<p>
    El convenio que usted tiene en proceso, cambió a estado: <?php echo $estado ?>
    con las siguientes observaciones: 
    <br>
    <br>
    <b>
        <?php echo $observaciones ?>
    </b>
    
</p>
<ul>
    <li>
        Si el estado es Aprobado quiere decir que el convenio presentó alguna irregularidad en los documentos, razón por la cual debe corregirlos según las 
        observaciones
    </li>
    <li>
        Si el estado es En revisión por la oficina jurídica, quiere decir que su convenio va por buen camino
    </li>
</ul>