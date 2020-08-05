<?php
include_once 'presentation.class.php';

View::start('Página principal - GCActiva');
View::topBar(1);

echo "<img id=\"beach\" src=\"Imágenes/index_image.jpg\">
<div class=\"title\">
<img id=\"banner\" src=\"Imágenes/Logo.png\">
<h1 id=\"banner2\">CActiva </h1>
</div>
<div class=\"description\">
<div class=\"column1\">
<p>En GCActiva nos encargamos de ofrecer, tanto a los visitantes de nuestra
isla como a los residentes que quieren probar experiencias nuevas en su
tierra, una nueva forma de entretenimiento cubriendo desde senderismo por
nuestras preciadas montañas hasta paracaidismo y poder disfrutar
de las vistas de la isla en su plenitud.</p>
</div>
<div class=\"column\">
<img src=\"Imágenes/Gran Canaria.jpg\">
</div>
<div class=\"column\">
<img src=\"Imágenes/senderismo.png\">
</div>
<div class=\"column2\">
<h4>Para nuestros clientes</h4>
<p> Si necesitas nuevas experiencias, no dudes en visitar nuestra página
con todas las actividades que hay en la isla esperando a ser descubiertas
por gente con ganas de pasarlo bien y vivir grandes momentos.</p>
</div>
<div class=\"column3\">
<h4>Para las empresas</h4>
<p> Si eres una empresa que tiene actividades que ofrecer a sus clientes
y necesitas un portal donde miles de personas puedan captar lo que ofreces,
no dudes en contactarnos.</p>
</div>
<div class=\"column\">
<img src=\"Imágenes/turismo.png\">
</div>
</div>";

View::end();