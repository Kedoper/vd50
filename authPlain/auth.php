<?php

/*
 * Ниже проверяем, есть ли файлы с логинами пользоватей и их паролями. Если нет - создаем.
 * */
if (!file_exists(__DIR__ . '/users.txt')) touch(__DIR__ . '/users.txt');
if (!file_exists(__DIR__ . '/passwords.txt')) touch(__DIR__ . '/passwords.txt');


/**
 * Функция, которая возвращает номер строки, на которой находится логин пользователя
 *
 * В качестве параметра идет логин пользователя
 *
 * @param string $user_name
 * @return int
 */
function searchUser($user_name): int
{
    /*Открываем файл с пользователями*/
    $users = fopen(__DIR__ . '/users.txt', 'r');
    $search = false;
    /*Если файл успешно открылся то идем дальше*/
    if ($users) {
        /*Тут объявляем номер строки с которой начинаем (условно)*/
        $line = 1;

        /*Тут мы начинаем читать наш файл построчно. При этом, задаем максимальную длинну строки - 15 символов.
            Так же, тут мы проверяем, если при прочтении строки не возникло ошибок - продолжаем дальше
        */
        while (($text = fgets($users, 15)) !== false) {
            /*Удаляем пробелы в начале и в конце строки и сверяем с логином пользователя, которого ищем*/
            if (trim($text) === $user_name) {
                /*Если мы нашли нужного нам пользователя, ставим значение переменной search true. Чтобы выйти из цикла*/
                $search = !$search;
                /*Возвращаем номер линии*/
                return $line;
            }
            /*Если search = true то выходим из цикла и возвращаем линию*/
            if ($search) return $line;
            $line++;
        }
    } else {
        /*Если файл не удалось открыть, то возвращаем -1*/
        return -1;
    }
    /*Закрываем поток*/
    fclose($users);
    /*Если search = true то возвращаем номер линии, инчае -1*/
    if ($search) return $line; else return -1;
}

/**
 * Функция, которая возвращает пароль пользователя.
 *
 * В качестве первого параметра идет номер строки, полученный из функции  searchUser()
 *
 * @param int $line_id
 * @return string
 */
function getUserPassword($line_id): string
{

    /*Тут все то же самое что и в функции для поиска пользователя*/

    $passwords = fopen(__DIR__ . '/passwords.txt', 'r');
    $search = false;
    if ($passwords) {
        $line = 1;
        while (($text = fgets($passwords, 15)) !== false) {

            if ($line === $line_id) {
                $search = !$search;
                return $text;
            }
            if ($search) return $text;
            $line++;
        }
    } else {
        return -1;
    }
    fclose($passwords);
    if ($search) return $text; else return -1;
}

/**
 * Добавляем новго пользователя в нашу "базу"
 * Первым параметром идет логин пользователя, вторым - его пароль
 *
 * @param string $user_name
 * @param string $user_password
 */
function addNewUser($user_name, $user_password)
{
    $users = fopen(__DIR__ . '/users.txt', 'a');
    $passwords = fopen(__DIR__ . '/passwords.txt', 'a');

    /*Записываем логин пользователя и его пароль в конец файлов.

        Константа PHP_EOL в данном случае обозначает переход на новую строку.
    */
    fwrite($users, $user_name . PHP_EOL);
    fwrite($passwords, $user_password . PHP_EOL);


    fclose($users);
    fclose($passwords);
}


/*Пример как пользоваться*/

/* С GET параметрами:*/
if ($_GET['search']) {
    $user_name = $_GET['search'];
    $pass_line = searchUser($user_name);
    if ($pass_line !== -1) {
        $user_password = getUserPassword($pass_line);
        echo "Логин пользователя: $user_name, его пароль: $user_password\n";
    }
    echo "Пользователь с логином $user_name не найден\n";
}

if ($_GET['add'] === "add") {
    $user_name = $_GET['user_name'];
    $user_password = $_GET['user_password'];
    addNewUser($user_name, $user_password);
    echo "Пользователь успешно создан.";
}

/*
 * Из кода.
 * Расскоментируй нужное
 */

/*Поиск пользователя*/
//$user_name = "type name here";
//$pass_line = searchUser($user_name);
//$user_password = getUserPassword($pass_line);
//echo "Логин пользователя: $user_name, его пароль: $user_password\n";

/*Создание пользователя*/
//$user_name = "type name here";
//$user_password = "type password here";
//addNewUser($user_name, $user_password);
//echo "Пользователь успешно создан.";