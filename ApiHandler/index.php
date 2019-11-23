<?php
/**
 * Author: kFerst <n.anoshin@icloud.com>.
 * Date of creation: 23.11.2019, 19:17
 */


// Подключаем файл с функциями
include './handlers.php';
header("Content-type: Application/json");
$requestUrl = explode('?', $_SERVER['REQUEST_URI'])[0];

function getUrl(string $url): array
{
    $explodedUrl = explode('/', mb_strtolower($url));
    unset($explodedUrl[0]);
    $explodedUrl = array_values($explodedUrl);

    $serializedUrl = [
        'root_patch' => $explodedUrl[0],
        'method' => $_SERVER['REQUEST_METHOD']
    ];
    if ($serializedUrl['method'] === "GET") {
        $serializedUrl['params'] = $_GET;
    } else {
        $serializedUrl['params'] = json_decode(file_get_contents('php://input'), true);
    }

    if ($serializedUrl['root_patch'] === "api") {
        $apiUrl = explode('.', $explodedUrl[1]);
        $serializedUrl['api'] = [
            'section' => $apiUrl[0],
            'method' => $apiUrl[1]
        ];
    } else {
        unset($explodedUrl[0]);
        $explodedUrl = array_values($explodedUrl);
        $serializedUrl['patch'] = implode('/', $explodedUrl);
    }


    return $serializedUrl;
}

function returnMessage(string $message, array $other, ?string $message_type = "message"): string
{

    $returnMessage = [
        'message_type' => $message_type,
        'message' => $message,
        'message_data' => $other
    ];

    return json_encode($returnMessage);
}


// Тут определяем наши методы API
$apiUrls = [
    'GET' => [ // Метод запроса (GET, POST, DELETE и тд)
        'users' => [ // Секция запроса (messages, users, pages и тд)
            'get' => "testFunction", // Метод секции (delete, add, change, edit и тд) и название функции, которую
                                    // необходимо будет выполнить (функции описываются в отдельном файле, который
                                    // подключается в начале данного файла
        ],
    ],
    'POST' => [
        'users' => [
            'del' => "testDelete",
        ],
    ]
];

$normalizeUrl = getUrl($requestUrl);
if ($normalizeUrl['root_patch'] === "api") {

    $apiMethod = $apiUrls[$normalizeUrl['method']][$normalizeUrl['api']['section']][$normalizeUrl['api']['method']];
    if (key_exists($normalizeUrl['method'], $apiUrls) && function_exists($apiMethod)) {
        call_user_func($apiMethod, $normalizeUrl['params']);
    } else {
        echo returnMessage("{$normalizeUrl['method']} method is forbidden or this api method does not exist", $normalizeUrl, 'error');
    }

} else {
    echo returnMessage("Website page", $normalizeUrl, 'message');
}

