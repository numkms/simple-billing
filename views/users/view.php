<?php
use app\models\Operation;
use app\models\Transaction;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/**
 * I know, we can use better ways like a use subviews like $this->render('_subview', ['etc' => $etc]);
 * But now i have not enough time for it :)
 */

/**
 * And things like  getting user of transaction could be simplified but not now.
 */


/**
 * And yes
 * Dont build your queries in VIEW files.
 * Just in this file.
 */

$this->title = "История операций {$model->login}";
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['/users']];
$this->params['breadcrumbs'][] = $model->login;
?>
<h1><?=$this->title?></h1>

<?= GridView::widget([
    'summary' => false,
    'tableOptions' => [
        'class' => 'table table-stripped table-condensed'
    ],
    'columns' => [
        'timestamp:datetime',
        [
            'label' => 'From',
            'attribute' => 'fromUser.login'
        ],
        [
            'label' => 'To',
            'attribute' => 'toUser.login',
        ],
        [
            'label' => 'Transactions',
            'format' => 'raw',
            'value' => function($model) {
                return GridView::widget([
                    'summary' => false,
                    'showHeader' => false,
                    'rowOptions' => function($model) {
                        return [
                            'class' => $model->type == 0 ? 'danger' : 'success'
                        ];
                    },
                    'tableOptions' => [
                        'class' => 'table table-stripped table-condensed'
                    ],
                    'columns' => [
                        [
                            'value' => function(Transaction $model) {
                                return $model->type == 0 ? $model->operation->fromUser->login : $model->operation->toUser->login;
                            }
                        ],
                        'typeAsString',
                        'attribute' => 'sum:currency'
                    ],
                    'dataProvider' => new ActiveDataProvider([
                        'query' => $model->getTransactions(),
                        'pagination' => false,
                    ]),
                ]);
            }
        ]

    ],
    'dataProvider' => new ActiveDataProvider([
        'query' => Operation::find()
        ->andWhere([
                'OR',
                ['to_user_id' => $model->id],
                ['from_user_id' => $model->id]

        ])
        ->orderBy('timestamp DESC')
    ])
]); ?>
