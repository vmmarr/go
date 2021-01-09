<?php
/* @var $this yii\web\View */

use yii\widgets\ListView;
use yii\widgets\Pjax;
?>
<div class="likes-index">
<?php Pjax::begin([
    'timeout' => 500000,
]); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,        
        'itemView' => 'vistaLikes',
    ]) ?>
<?php Pjax::end(); ?>
    </div>