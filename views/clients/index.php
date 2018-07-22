<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchClients */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Clients', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_number',
            'name',
            'surname',
            [
                'attribute' => 'gender_id',
                'value' => function($data) {
                    return $data->gender->name;
                },
                'filter' => ArrayHelper::map(\app\models\Genders::find()->all(),'id','name'),
                'headerOptions' => ['style' => 'width:100px;'],
            ],
            [
                'attribute' => 'date_of_birth',
                'filter' => DatePicker::widget([
                    'name' => $searchModel->formName() . '[date_of_birth_1]',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $searchModel->date_of_birth_1,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd.mm.yyyy',
                        'pickerPosition' => 'top-left'
                    ],

                ])
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
