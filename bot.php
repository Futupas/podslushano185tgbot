<?php
    function strtolower_my($str) {
        $str2 = strtolower($str);
        $search = array(
        'Й','Ц','У','К','Е','Н','Г','Ш','Щ','З','Х','Ъ','Ф','Ы','В','А','П','Р','О','Л','Д','Ж','Э','Я','Ч','С','М','И','Т','Ь','Б','Ю','Ё','І','Ї'
        );
        $replace = array(
        'й','ц','у','к','е','н','г','ш','щ','з','х','ъ','ф','ы','в','а','п','р','о','л','д','ж','э','я','ч','с','м','и','т','ь','б','ю','ё','і','ї'
        );
        $str2 = str_replace($search, $replace, $str2);
        return $str2;
    };
    function SendMessage($chatid, $text) {
        $response = file_get_contents('https://api.telegram.org/bot'.getenv('bot_token').'/sendMessage?chat_id='.$chatid.'&text='.urlencode($text));
    };
    function SendMessageWithMarkdown($chatid, $text) {
        $response = file_get_contents('https://api.telegram.org/bot'.getenv('bot_token').'/sendMessage?chat_id='.$chatid.'&text='.urlencode($text).'&parse_mode=markdown&disable_web_page_preview=true');
    };
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $requestString = file_get_contents('php://input');
        $requestData = json_decode($requestString);

        $msg_chatid = $requestData->message->chat->id;

        if (property_exists($requestData->message, 'voice') || property_exists($requestData->message, 'video_note')) {
            SendMessage($msg_chatid, 'Голосовые и видеосообщения не поддерживаются ради сохранения приватности');
            exit(0);
        };

        $msg = $requestData->message->text;
        $lowermsg = strtolower_my($msg);

        if ($lowermsg == '/start') {
            SendMessageWithMarkdown($msg_chatid, 'Это бот для АНОНИМНОЙ отправки "подслушано" из сш№185 в канал @podslushano185. На канеле разрешены буллинг, травля, использование нецензурной лексики итд. Любая модерация отсутствует в связи с либертарианско-анархистскими убеждениями создателя и администра. Запрещено отправлять сообщения с админским хештегом (#Админ, #Адмін и #Admin). Их может отправлять только админ. Для отправки сообщений ему лично используйте хештеги #Админу, #Адміну либо #toAdmin. Бот иммет полностью открытый исходный код. Посмотреть его можно [тут](https://github.com/Futupas/podslushano185tgbot).');
            exit(0);
        }

        if (strpos($lowermsg, '#админу') !== false || strpos($lowermsg, '#адміну') !== false || strpos($lowermsg, '#toadmin') !== false) {
            SendMessage(getenv('admin_id'), $msg);
            exit(0);
        }
        if (strpos($lowermsg, '#админ') !== false || strpos($lowermsg, '#адмін') !== false || strpos($lowermsg, '#admin') !== false) {
            SendMessage($msg_chatid, 'Сообщения с админским хештегом может отправлять только админ');
            exit(0);
        }

        SendMessage('@podslushano185', $msg);

        http_response_code(200);

    } else {
        echo 'Podslushano185';
    }
?>
