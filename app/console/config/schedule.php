<?php
/**
 * @var \marketingsolutions\scheduling\Schedule $schedule
 */

/** Разовая рассылка по списку и ежедневные (повторяемые) рассылки */
$schedule->command('mailing/mails/send');
$schedule->command('mailing/mailer/send')->everyTenMinutes();

/** Рассылки PUSH-уведомлений */
$schedule->command('mobile/schedule/send')->everyTenMinutes();

/** Certificates */
$schedule->command('catalog/zakazpodarka-orders/create-soap')->everyFiveMinutes();
$schedule->command('catalog/zakazpodarka-orders/check-status')->everyTenMinutes();
$schedule->command('catalog/card-items/send-to-user')->everyTenMinutes();
$schedule->command('catalog/card-items/push-to-user')->everyTenMinutes();

/** Payments */
$schedule->command('payments/process')->everyMinute();
$schedule->command('payments/process/check')->everyFiveMinutes();
$schedule->command('payments/process/push')->everyMinute();

/** Tickets */
$schedule->command('tickets/run/restart polaris')->cron('00 * * * *');

/** Api Logs */
$schedule->command('api/clean')->cron('3 3 * * *');

/** Burnpoints */
$schedule->command('burnpoints/calculate')->daily();
$schedule->command('burnpoints/nullify')->daily();
$schedule->command('burnpoints/warning')->daily();

/** Bonuses */
$schedule->command('sales/bonuses/pay')->everyMinute();
# $schedule->command('bonuses/sms-bonuses/send')->everyNMinutes(5);

/** Activity */
# $schedule->command('activity/check')->cron('10 30 * * *');

/** Delivery */
# $schedule->command('delivery/process')->everyFiveMinutes();
# $schedule->command('delivery/process/auto-start')->everyMinute();

/** Callbacks */
# $schedule->command('callback/sent/notification')->everyMinute();

/** Extranet */
# $schedule->command('extranet/log/send')->everyMinute();
# $schedule->command('extranet/log/create')->everyMinute();
