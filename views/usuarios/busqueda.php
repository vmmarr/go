<?php
/* @var $this yii\web\View */

use kartik\icons\Icon;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\ListView;

$this->registerCssFile('@web/css/indexUsuarios.css');
?>

<?php $form = ActiveForm::begin([
        'action' => ['buscar'],
        'method' => 'get',
    ]); ?>
    <?= $form->field($searchModel, 'nombre') ?>

    <div class="form-group">
        <?= Html::submitButton(Icon::show('search', ['framework' => Icon::BSG]), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Icon::show('refresh', ['framework' => Icon::BSG]), ['class' => 'btn btn-outline-secondary']) ?>
    </div>
<?php ActiveForm::end();

    // $totalFilas = count($usuarios);
    $archivo;?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'indexBusqueda'
    ]) ?>
