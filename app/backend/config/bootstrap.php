<?php

$listener = new \marketingsolutions\events\Listener(
    new \marketingsolutions\events\PatternEventsProvider(),
    new \marketingsolutions\events\PrefixMethodFinder()
);

# ПРИМЕР. Переопределение модели поиска заказанных сертификатов, если где-то понадобится.
//\yii\base\Event::on(
//    \ms\loyalty\catalog\backend\controllers\OrderedCardsController::class,
//    \ms\loyalty\catalog\backend\controllers\OrderedCardsController::EVENT_BEFORE_ACTION,
//    [\backend\listeners\OrderedCards::class, 'whenBeforeAction']
//);