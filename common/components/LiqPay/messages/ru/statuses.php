<?php
use common\components\LiqPay\LiqPayStatuses;

return [
    LiqPayStatuses::REQUEST => 'Запрошена форма',

    //Конечные статусы платежа
    LiqPayStatuses::SUCCESS => 'Успешный платеж',
    LiqPayStatuses::FAIL => 'Неуспешный платеж',
    LiqPayStatuses::ERROR => 'Неуспешный платеж. Некорректно заполнены данные',
    LiqPayStatuses::SUBSCRIBED => 'Подписка успешно оформлена',
    LiqPayStatuses::UNSUBSCRIBED => 'Подписка успешно деактивирована',
    LiqPayStatuses::REVERSED => 'Платеж возвращен',
    LiqPayStatuses::SANDBOX => 'Тестовый платеж',

    //Cтатусы требующие подтверждения платежа
    LiqPayStatuses::VERIFY_OTP => 'Требуется OTP подтверждение клиента. OTP пароль отправлен на номер телефона Клиента',
    LiqPayStatuses::VERIFY_3DS => 'Требуется 3DS верификация',
    LiqPayStatuses::VERIFY_CVV => 'Требуется ввод CVV карты отправителя',
    LiqPayStatuses::VERIFY_SENDER => 'Требуется ввод данных отправителя',
    LiqPayStatuses::VERIFY_RECEIVER => 'Требуется ввод данных получателя',
    LiqPayStatuses::VERIFY_PHONE => 'Ожидается ввод телефона клиентом',
    LiqPayStatuses::VERIFY_IVR => 'Ожидается подтверждение звонком ivr',
    LiqPayStatuses::VERIFY_PIN => 'Ожидается подтверждение pin-code',
    LiqPayStatuses::VERIFY_CAPTCHA => 'Ожидается подтверждение captcha',
    LiqPayStatuses::VERIFY_PASSWORD => 'Ожидается подтверждение пароля приложения Приват24',
    LiqPayStatuses::VERIFY_SENDER_APP => 'Ожидается подтверждение в приложении Sender',

    //Cтатусы ожидающие обработку платежа
    LiqPayStatuses::PROCESSING => 'Платеж обрабатывается',
    LiqPayStatuses::PREPARED => 'Платеж создан, ожидается его завершение отправителем',
    LiqPayStatuses::WAIT_BITCOIN => 'Ожидается перевод bitcoin от клиента',
    LiqPayStatuses::WAIT_SECURE => 'Платеж на проверке',
    LiqPayStatuses::WAIT_ACCEPT => 'Деньги с клиента списаны, но магазин еще не прошел проверку',
    LiqPayStatuses::WAIT_LC => 'Аккредитив. Деньги с клиента списаны, ожидается подтверждение доставки товара',
    LiqPayStatuses::WAIT_HOLD => 'Сумма успешно заблокирована на счету отправителя',
    LiqPayStatuses::WAIT_CASH => 'Ожидается оплата наличными в ТСО',
    LiqPayStatuses::WAIT_QR => 'Ожидается сканировани QR-кода клиентом.',
    LiqPayStatuses::WAIT_SENDER => 'Ожидается подтверждение оплаты клиентом в приложении Privat24/Sender',
    LiqPayStatuses::WAIT_CARD => 'Не установлен способ возмещения у получателя',
    LiqPayStatuses::WAIT_COMPENSATION => 'Платеж успешный, будет зачислен в ежесуточной проводке',
    LiqPayStatuses::WAIT_INVOICE => 'Инвойс создан успешно, ожидается оплата',
    LiqPayStatuses::WAIT_RESERVE => 'Средства по платежу зарезервированы для проведения возврата по ранее поданной заявке',
];
