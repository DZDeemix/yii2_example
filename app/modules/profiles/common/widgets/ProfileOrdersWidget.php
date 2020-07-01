<?php

namespace modules\profiles\common\widgets;

use modules\profiles\common\models\Profile;
use ms\loyalty\catalog\common\models\CatalogOrder;
use ms\loyalty\prizes\payments\common\models\Payment;
use ms\loyalty\shop\common\models\Order;
use Yii;
use yii\base\Widget;

class ProfileOrdersWidget extends Widget
{
    /** @var Profile */
    public $profile;

    public function run()
    {
        $orders = [];

        if (class_exists(Payment::class)) {
            /** @var Payment[] $payments */
            $payments = Payment::find()->where(['recipient_id' => $this->profile->id])->orderBy('id')->all();
            $options = Payment::getTypeOptions();
            $statuses = Payment::getStatusValues();

            for ($i = 0; $i < count($payments); $i++) {
                $type = $payments[$i]->type;
                $status =  $payments[$i]->status;
                $orders[] = [
                    'model' => $payments[$i],
                    'type' => 'Платеж/перевод',
                    'amount' => $payments[$i]->amount,
                    'title' => empty($options[$type]) ? '' : $options[$type],
                    'created_at' => $payments[$i]->created_at,
                    'status' => empty($statuses[$status]) ? '' : $statuses[$status],
                ];
            }
        }

        if (class_exists(CatalogOrder::class)) {
            /** @var CatalogOrder[] $catalogOrders */
            $catalogOrders = CatalogOrder::find()->where(['user_id' => $this->profile->id])->orderBy('id')->all();
            for ($i = 0; $i < count($catalogOrders); $i++) {
                $titles = [];
                $orderedCards = $catalogOrders[$i]->orderedCards;
                if (!empty($orderedCards)) {
                    foreach ($orderedCards as $orderedCard) {
                        if ($card = $orderedCard->card) {
                            $titles[] = "{$card->title} ({$orderedCard->nominal}р x {$orderedCard->quantity})";
                        }
                    }
                }
                $orders[] = [
                    'model' => $catalogOrders[$i],
                    'type' => 'Сертификаты ЭПС',
                    'amount' => $catalogOrders[$i]->amount,
                    'title' => implode('<br/>', $titles),
                    'created_at' => $catalogOrders[$i]->created_at,
                    'status' => $catalogOrders[$i]->getStatusLabel(),
                ];
            }
        }

        if (class_exists(Order::class)) {
            /** @var Order[] $shopOrders */
            $shopOrders = Order::find()->where(['profile_id' => $this->profile->id])->all();

            for ($i = 0; $i < count($shopOrders); $i++) {
                $titles = [];
                $orderItems = $shopOrders[$i]->orderItems;

                if (!empty($orderItems)) {
                    foreach ($orderItems as $orderedItem) {
                        if ($product = $orderedItem->product) {
                            $titles[] = $product->name . ($orderedItem->qty > 1 ? ' x ' . $orderedItem->qty : '');
                        }
                    }
                }
                $orders[] = [
                    'model' => $shopOrders[$i],
                    'type' => 'Витрина товаров',
                    'amount' => $shopOrders[$i]->total_cost,
                    'title' => implode(', ', $titles),
                    'created_at' => $shopOrders[$i]->created_at,
                    'status' => $shopOrders[$i]->status_label,
                ];
            }
        }

        usort($orders, function($a, $b) {
            if ($a['created_at'] == $b['created_at']) {
                return 0;
            }
            return $a['created_at'] < $b['created_at'];
        });

        return $this->render('profile-orders', [
            'profile' => $this->profile,
            'orders' => $orders,
        ]);
    }
}