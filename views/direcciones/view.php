<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Direcciones */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Direcciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/leaflet.css');
$this->registerJsFile('@web/js/leaflet.js', [
    'depends' => [
        \yii\web\JqueryAsset::className()
    ]
]);
?>
 <style>
  #map { 
  width: 800px;
  height: 800px; }
 </style>
 <!--fin-->
<div class="direcciones-view">    
    <div class="contenedor-mapa">
        <div id="map" class="map justify-content-center"></div>
    </div>

    <script>
        window.onload = function() {
        var map = L.map('map').setView(['<?= $model->latitud ?>', '<?= $model->longitud ?>'], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href=" https://www.openstreetmap.org/copyright">OpenStreetMap </a> contributors'
        }).addTo(map);
        L.control.scale().addTo(map);

        var maker = L.marker(['<?= $model->latitud ?>', '<?= $model->longitud ?>']).addTo(map)
        }
    </script>
</div>
