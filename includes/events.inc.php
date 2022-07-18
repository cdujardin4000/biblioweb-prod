<?php

?>

<section class="container">
    <h2>Nos prochains Events</h2>
    <?php
    $row = 1;

    if ( ($handle = fopen("events.csv", "r")) !== false )
    {
        while ( ($data = fgetcsv($handle, 1000, ";")) !== false )
        {
            if ($row <= 3 && $row != 1) { ?>
                <p><?=strtoupper($data[5])?> <?=$data[1]?> le <?=$data[3]?> à <?=$data[4]?></p>
            <?php }
            $row++;
        }
        fclose($handle);
    }
    ?>
</section>
