<?php

/* @var $this yii\web\View */
/* @var $model \app\models\TransferForm */
use yii\widgets\ActiveForm as ActiveForm;

$this->title = 'Test task';
$user = Yii::$app->user->identity;
Yii::$app->formatter->thousandSeparator = " ";
?>

<?php if($user): ?>
    <h1>Your balance: <?=Yii::$app->formatter->asCurrency($user->balance) ?></h1>
    <hr>
    <?php $form = ActiveForm::begin([]) ?>
        <?= $form->field($model, 'nickname')->textInput() ?>
        <?= $form->field($model, 'amount')->textInput() ?>
        <?= \yii\helpers\Html::submitButton('Make transfer') ?>
    <?php ActiveForm::end() ?>
<?php else:?>

<?php endif;?>
