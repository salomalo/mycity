<?php
use common\components\LiqPay\LiqPayActions;

return [
    LiqPayActions::PAY => 'Платеж',
    LiqPayActions::PAY_DONATE => 'Пожертвование',
    LiqPayActions::PAY_QR => 'Оплата по QR-коду',
    LiqPayActions::PAY_SENDER => 'Оплата через приложение Privat24/Sender',
    LiqPayActions::PAY_TOKEN => 'Оплата по токену карты без ввода реквизитов карты',
    LiqPayActions::PAY_CASH => 'Оплата наличными',
    LiqPayActions::PAY_TRACK => 'Оплата по треку карты',
    LiqPayActions::PAY_LC => 'Аккредитив',
    LiqPayActions::PAY_LC_CONFIRM => 'Подтверждение платежа от имени плательщика',
    LiqPayActions::HOLD => 'Блокировка средств на карте клиента',
    LiqPayActions::HOLD_COMPLETION => 'Списание заблокированной суммы',
    LiqPayActions::SUBSCRIBE => 'Подписка в магазине (регулярные платежи)',
    LiqPayActions::UNSUBSCRIBE => 'Отмена подписки (регулярного платежа)',
    LiqPayActions::STATUS => 'Проверка статуса платежа',
    LiqPayActions::REFUND => 'Возврат средств Клиенту',
    LiqPayActions::AUTH => 'Предавторизация карты',
    LiqPayActions::DATA => 'Добавление произвольных данных',
    LiqPayActions::MPI => 'Проверка поддержки 3D-Secure по карте',
    LiqPayActions::REPORTS => 'Получение отчета по платежам в формате CSV или JSON',
    LiqPayActions::VERIFY_3DS => 'Подтверждение 3ds для завершения платежа',
    LiqPayActions::VERIFY_OTP => 'Подтверждение OTP для завершения платежа',
    LiqPayActions::INVOICE => 'Выставление счета на E-mail клиента',
    LiqPayActions::INVOICE_CANCEL => 'Отмена выставленного счета',
];
