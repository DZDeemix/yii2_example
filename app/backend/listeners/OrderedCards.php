<?php

namespace backend\listeners;

use backend\models\OrderedCardCustomSearch;
use ms\loyalty\catalog\backend\models\OrderedCardWithProfileSearch;
use yii\base\Event;
use yz\admin\grid\GridView;

class OrderedCards
{
    public static function whenBeforeAction(Event $event)
    {
        \Yii::$container->set(
            OrderedCardWithProfileSearch::class,
            OrderedCardCustomSearch::class
        );

        Event::on(
            GridView::class,
            GridView::EVENT_SETUP_GRID,
            [get_called_class(), 'whenSetupGrid']
        );
    }

    public static function whenSetupGrid(Event $event)
    {
        /** @var GridView $grid */
        $grid = $event->sender;
        $grid->columns = array_merge(array_slice($grid->columns, 0, -1), [
            [
                'attribute' => 'dealer__name',
                'label' => 'Дистрибьютор',
            ],
        ], array_slice($grid->columns, -1));
    }
}