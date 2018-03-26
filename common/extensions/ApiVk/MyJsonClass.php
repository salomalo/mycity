<?php
namespace common\extensions\ApiVk;

abstract class MyJsonClass
{
    /** @var array $errors */
    private static $errors = [
        JSON_ERROR_NONE => false,
        JSON_ERROR_DEPTH => 'Достигнута максимальная глубина стека',
        JSON_ERROR_STATE_MISMATCH => 'Некорректные разряды или не совпадение режимов',
        JSON_ERROR_CTRL_CHAR => 'Некорректный управляющий символ',
        JSON_ERROR_SYNTAX => 'Синтаксическая ошибка, не корректный JSON',
        JSON_ERROR_UTF8 => 'Некорректные символы UTF-8, возможно неверная кодировка',
        JSON_ERROR_RECURSION => 'Одна или несколько зацикленных ссылок в кодируемом значении',
        JSON_ERROR_INF_OR_NAN => 'Одно или несколько значений NAN или INF в кодируемом значении',
        JSON_ERROR_UNSUPPORTED_TYPE => 'Передано значение с неподдерживаемым типом'
    ];

    /**
     * @param $data string
     * @param bool $showErrors
     * @return \stdClass
     */
    public static function decodeJson($data, $showErrors = true)
    {
        $return = @json_decode($data);
        if ($showErrors) {
            self::echoErrors();
        }
        return $return;
    }

    private static function echoErrors()
    {
        $error = json_last_error();
        if (isset(self::$errors[$error])) {
            self::showMsg(self::$errors[$error]);
        } else {
            self::showMsg('Неизвестная ошибка!');
        }
    }

    /**
     * @param $msg string
     */
    private static function showMsg($msg)
    {
        if ($msg) {
            echo PHP_EOL, "\033[31m", ' - JSON_DECODE: ', "\033[1;33m", $msg, "\033[0m", PHP_EOL;
        }
    }
}
