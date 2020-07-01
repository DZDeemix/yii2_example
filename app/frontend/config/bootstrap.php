<?php

$listener = new \marketingsolutions\events\Listener(
    new \marketingsolutions\events\PatternEventsProvider(),
    new \marketingsolutions\events\PrefixMethodFinder()
);

// Payments create validate
\yii\base\Event::on(
    \ms\loyalty\prizes\payments\frontend\forms\CreatePaymentForm::class,
    \ms\loyalty\prizes\payments\frontend\forms\CreatePaymentForm::EVENT_BEFORE_VALIDATE_AMOUNT,
    [\frontend\listeners\CreatePaymentFormListener::class, 'whenBeforeValidateAmount']
);

// Catalog orders create validate
\yii\base\Event::on(
    \ms\loyalty\catalog\frontend\forms\CatalogOrderForm::class,
    \ms\loyalty\catalog\frontend\forms\CatalogOrderForm::EVENT_BEFORE_VALIDATE_AMOUNT,
    [\frontend\listeners\CreateCatalogOrderFormListener::class, 'whenBeforeValidateAmount']
);



