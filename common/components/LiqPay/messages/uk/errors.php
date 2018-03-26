<?php
use common\components\LiqPay\LiqPayErrors;

return [
    //Ошибки нефинансовые
    LiqPayErrors::AUTH => 'Требуется авторизация',
    LiqPayErrors::CACHE => 'Истекло время хранения данных для данной операции',
    LiqPayErrors::USER_NOT_FOUND => 'Пользователь не найден',
    LiqPayErrors::SMS_SEND => 'Не удалось отправить смс',
    LiqPayErrors::SMS_OTP => 'Неверно указан пароль из смс',
    LiqPayErrors::SHOP_BLOCKED => 'Магазин заблокирован',
    LiqPayErrors::SHOP_NOT_ACTIVE => 'Магазин не активный',
    LiqPayErrors::INVALID_SIGNATURE => 'Неверная подпись запроса',
    LiqPayErrors::ORDER_ID_EMPTY => 'Передан пустой order_id',
    LiqPayErrors::SHOP_NOT_AGENT => 'Вы не являетесь агентом для указанного магазина',
    LiqPayErrors::CARD_DEF_NOT_FOUND => 'Карта для приема платежей не найдена в кошельке',
    LiqPayErrors::NO_CARD_TOKEN => 'У пользователя нет карты с таким card_token',
    LiqPayErrors::CARD_LIQPAY_DEF => 'Укажите другую карту',
    LiqPayErrors::CARD_TYPE => 'Неверный тип карты',
    LiqPayErrors::CARD_COUNTRY => 'Укажите другую карту',
    LiqPayErrors::LIMIT_AMOUNT => 'Сумма перевода меньше или больше заданного лимита',
    LiqPayErrors::PAYMENT_AMOUNT_LIMIT => 'Сумма перевода меньше или больше заданного лимита',
    LiqPayErrors::AMOUNT_LIMIT => 'Превышен лимит суммы',
    LiqPayErrors::PAYMENT_SENDER_CARD => 'Укажите другую карту отправителя',
    LiqPayErrors::PAYMENT_PROCESSING => 'Платеж обрабатывается',
    LiqPayErrors::PAYMENT_DISCOUNT => 'Не найдена скидка для данного платежа',
    LiqPayErrors::WALLET => 'Не удалось загрузить кошелек',
    LiqPayErrors::GET_VERIFY_CODE => 'Требуется верифицировать карту',
    LiqPayErrors::VERIFY_CODE => 'Неверный код верификации',
    LiqPayErrors::WAIT_INFO => 'Ожидается дополнительная информация, попробуйте позже',
    LiqPayErrors::PATH => 'Неверный адрес запроса',
    LiqPayErrors::PAYMENT_CASH_ACQ => 'Платеж не может быть проведен в этом магазине',
    LiqPayErrors::SPLIT_AMOUNT => 'Сумма платежей ращепления не совпадает с суммой платежа',
    LiqPayErrors::CARD_RECEIVER_DEF => 'У получателя не установлена карта для приема платежей',
    LiqPayErrors::PAYMENT_STATUS => 'Неверный статус платежа',
    LiqPayErrors::PUBLIC_KEY_NOT_FOUND => 'Не найден public_key',
    LiqPayErrors::PAYMENT_NOT_FOUND => 'Платеж не найден',
    LiqPayErrors::PAYMENT_NOT_SUBSCRIBED => 'Платеж не является регулярным',
    LiqPayErrors::WRONG_AMOUNT_CURRENCY => 'Валюта платежа не совпадает с валютой debit',
    LiqPayErrors::AMOUNT_HOLD => 'Сумма не может быть больше суммы платежа',
    LiqPayErrors::ACCESS => 'Ошибка доступа',
    LiqPayErrors::ORDER_ID_DUPLICATE => 'Такой order_id уже есть',
    LiqPayErrors::BLOCKED => 'Доступ в аккаунт закрыт',
    LiqPayErrors::EMPTY_ERROR => 'Параметр не заполнен',
    LiqPayErrors::EMPTY_PHONE => 'Параметр phone не заполнен',
    LiqPayErrors::MISSING => 'Не передан параметр',
    LiqPayErrors::WRONG => 'Неверно указан параметр',
    LiqPayErrors::WRONG_CURRENCY => 'Неверно указана валюта. Используйте (USD, UAH, RUB, EUR)',
    LiqPayErrors::PHONE => 'Указан неверный номер телефона',
    LiqPayErrors::CARD => 'Неверно указан номер карты',
    LiqPayErrors::CARD_BIN => 'Бин карты не найден',
    LiqPayErrors::TERMINAL_NOT_FOUND => 'Терминал не найден',
    LiqPayErrors::COMMISSION_NOT_FOUND => 'Комиссия не найдена',
    LiqPayErrors::PAYMENT_CREATE => 'Не удалось создать платеж',
    LiqPayErrors::MPI => 'Не удалось проверить карту',
    LiqPayErrors::LIMIT => 'Превышен лимит',
    LiqPayErrors::CURRENCY_NOT_ALLOWED => 'Валюта запрещена',
    LiqPayErrors::LOOK => 'Не удалось завершить операцию',
    LiqPayErrors::MODS_EMPTY => 'Не удалось завершить операцию',
    LiqPayErrors::ERR_TYPE => 'Неверный тип платежа',
    LiqPayErrors::PAYMENT_CURRENCY => 'Валюта карты или перевода запрещены',
    LiqPayErrors::PAYMENT_EXCHANGERATES => 'Не найден подходящий курс валют',
    LiqPayErrors::SIGNATURE => 'Неверная подпись запроса',
    LiqPayErrors::API_ACTION => 'Не передан параметр action',
    LiqPayErrors::API_CALLBACK => 'Не передан параметр callback',
    LiqPayErrors::API_IP => 'В этом мерчанте запрещен вызов API с этого IP',
    LiqPayErrors::CARD_3DS_NOT_AVAILABLE => 'Карта не поддерживает 3DSecure',

    //Ошибки финансовые
    LiqPayErrors::ERROR_WHILE_PROCESSING => 'Общая ошибка во время обработки',
    LiqPayErrors::TOKEN_NOT_FROM_THIS_MERCHANT => 'Токен создан не этим мерчантом',
    LiqPayErrors::TOKEN_NOT_ACTIVE => 'Присланый токен не активен',
    LiqPayErrors::EXCEEDED_PURCHASE_LIMIT => 'Достингута максимальная сумма покупок по токену',
    LiqPayErrors::EXCEEDED_TRANSACTION_LIMIT => 'Лимит транзакций по токену исчерпан',
    LiqPayErrors::CARD_NOT_ALLOW => 'Карта не поддерживается',
    LiqPayErrors::MERCHANT_MAY_NOT_PREVENT_AUTH => 'Мерчанту не разрешена предавторизация',
    LiqPayErrors::ACQUIRE_NOT_ALLOW_3DS => 'Экваер не поддерживает 3ds',
    LiqPayErrors::TOKEN_NOT_EXIST => 'Такой токен не существует',
    LiqPayErrors::EXCEEDED_TRIES_BY_IP => 'Превышен лимит попыток по данному IP',
    LiqPayErrors::SESSION_EXPIRED => 'Сессия истекла',
    LiqPayErrors::CARD_BRANCH_IS_BLOCKED => 'Бранч карты заблокирован',
    LiqPayErrors::EXCEEDED_CARD_BRANCH_DAILY_LIMIT => 'Достигнут лимит по дневному лимиту карты по бранчу',
    LiqPayErrors::P2P_NOT_POSSIBLE_FROM_PB_TO_FOREIGN => 'Временно закрыта возможность проведения P2P-платежей с карт ПБ на карты зарубежных банков',
    LiqPayErrors::EXCEEDED_COMPLETE_LIMIT => 'Достигнут лимит по комплитам',
    LiqPayErrors::INVALID_RECIPIENT_NAME => 'Неверное имя получателя',
    LiqPayErrors::EXCEEDED_CARD_USE_DAILY_LIMIT => 'Достигнут дневной лимит использования карты',
    LiqPayErrors::ORDER_ID_EXIST => 'Такой order_id уже есть',
    LiqPayErrors::COUNTRY_NOT_ALLOW => 'Платежи для данной страны запрещены',
    LiqPayErrors::EXPIRED_CARD => 'Карта просрочена',
    LiqPayErrors::INVALID_CARD => 'Неправильная карта',
    LiqPayErrors::PAYMENT_DECLINED => 'Платеж отклонен. Попробуйте позже',
    LiqPayErrors::TRANSACTION_NOT_ALLOW_BY_CARD => 'Карта не поддерживает данный вид транзакции',
    LiqPayErrors::TRANSACTION_NOT_ALLOW_BY_CARD_ALIAS => 'Карта не поддерживает данный вид транзакции',
    LiqPayErrors::INSUFFICIENT_FUNDS => 'Недостаточно средств',
    LiqPayErrors::EXPIRED_CARD_OPERATION_LIMIT => 'Превышен лимит операций по карте',
    LiqPayErrors::WOULD_BE_EXPIRED_CASH_LIMIT => 'Лимит на снятие наличных будет превышен',
    LiqPayErrors::EXPIRED_CASH_LIMIT => 'Достигнут лимит на снятие наличных',
    LiqPayErrors::INVALID_TRANSACTION_AMOUNT => 'Неверно указанна сумма транзакции',
    LiqPayErrors::OPERATION_NOT_CONFIRMED_BY_BANK => 'Платеж отклонен. Банк не подтвердил операцию. Обратитесь в банк',
    LiqPayErrors::DESTINATION_NOT_AVAILABLE => 'Авторизатор недоступен',
    LiqPayErrors::INVALID_PARAMETERS => 'Неверно переданы параметры или транзакция с такимим условиями не разрешена',
    LiqPayErrors::MERCHANT_NOT_ALLOW_RECURRENCE_PAYMENTS => 'Мерчанту не разрешены рекурентные платежи',
    LiqPayErrors::EXCEEDS_WITHDRAWAL_LIMIT => 'Превышен лимит на снятие наличных',
    LiqPayErrors::INVALID_CARD_DETAILS => 'Платеж отклонен. Проверьте правильность введеных реквизитов карты',
];