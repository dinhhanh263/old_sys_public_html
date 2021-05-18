<?php
/*
  Kireimo Kagoya サーバからのメール送信API

 機能概要
 kireimo.jpサーバに配置し、外部からメール送信要求を受けてkireimo.jpサーバからメールを送信する。
 メール送信要求はajax POSTリクエストを受信する。PHPプログラムからは、curl_*関数で呼び出し可能
 不正利用を防止するため、HTTPのDigest認証風の認証方法を使用している。
 （↑パスワード平文送信をしないため）
 ログファイルを日付で作成し、保存日数を指定可能で、それ以前の古いログは削除している。
 
 使用方法
 1. get_key = true , process_id, thread_id をセットしたポストデータを送信し、送信キーを取得する。
    POSTパラメータ
        get_key = true,
        process_id,
        thread_id

     送信キーは、json形式 '{"key" : "xyz999xxxyyyzzz" }' で返却される。

 2. 返却されたkey と秘密鍵でMD5ハッシュ値を計算し、hashedに計算値をセットして送信要求する。
    
    $hashed = md5(SECRET_KEY . $key);   // 秘密鍵＋送信キーのハッシュ

    POSTパラメータ
        get_key = null,
        process_id,
        thread_id,
        hashed,
        to,
        subject,
        message,
        additional_headers,
        additional_param,
        option
    
    to ～ additonal_param は、PHPのmb_send_mail関数のパラメータと同じ

    送信結果は、json形式 '{"result" : true }' などで返却される。このresultは、mb_send_mail関数の戻り値と同じ

*/

mb_language("ja");
mb_internal_encoding("UTF-8");

// デバッグモード
define('DEBUG', true);

define('LOG_NOTICE', 1);
define('LOG_DEBUG', 5);

// ログ保存期間（日）
define('LOG_KEEP_DAYS', 7);

// ログファイル名先頭文字列 (ex. file-name_2017-12-31.log)
define('LOG_FILENAME_HEAD', 'kireimo_mail_');

// 秘密鍵（呼び出し元と共通）
define('SECRET_KEY', '5jwH3nS84dUHobtq8zRponhZyuKUwCor');



session_start();

$get_key = empty($_POST['get_key']) ? null : $_POST['get_key'];
if (!empty($get_key)) {
    // キー取得処理
    getKey();
} else {
    // メール送信処理
    sendMail();
}

// 終了
return;




function getKey()
{
    // リクエストID取得
    $requestId = getRequestID();
    log_output('get_key : requestId = ' . $requestId, LOG_DEBUG);

    // ランダムキー生成
    $key = bin2hex(openssl_random_pseudo_bytes(10));
    log_output('get_key : get key = ' . $key, LOG_DEBUG);
    
    // キー保存
    if (empty($_SESSION['mail_send_key'])) {
        $_SESSION['mail_send_key'] = [];
    }
    $_SESSION['mail_send_key'][$requestId] = $key;
    
    $json = json_encode(['key' => $key]);
    
    echo $json;
    return;
}

function sendMail()
{
    $requestId = getRequestID();
    log_output('sendMail : requestId = ' . $requestId, LOG_DEBUG);
    if (empty($_SESSION['mail_send_key'][$requestId])) {
        log_output('sendMail : session mail_send_key ' . $requestId . ' is NULL', LOG_DEBUG);
        return;
    }

    $key = $_SESSION['mail_send_key'][$requestId];
    log_output('sendMail : key = ' . $key, LOG_DEBUG);
    
    unset($_SESSION['mail_send_key'][$requestId]);
    
    $hashed = $_POST['hashed'];
    log_output('sendMail : POST hashed = ' . $hashed, LOG_DEBUG);
    
    $original = md5(SECRET_KEY . $key);
    if ($hashed != $original) {
        log_output('sendMail : hash check NG.', LOG_DEBUG);
        log_output('sendMail : original = ' . $original, LOG_DEBUG);
        return;
    }
    
    if (empty($_POST['to']) || empty($_POST['subject']) || empty($_POST['message'])) {
        echo json_encode([
            'result' => false,
            'message' => 'lack of some parameters, to, subject or message.'
        ]);
        return;
    }
    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $additional_headers = empty($_POST['additional_headers']) ? null : $_POST['additional_headers'];
    $additional_param = empty($_POST['additional_param']) ? null : $_POST['additional_param'];
    $option = empty($_POST['option']) ? '' : $_POST['option'];

    // メール送信
    $result = mb_send_mail($to, $subject, $message, $additional_headers, $additional_param);
    log_output('send_mail result = ' . var_export($result, true) . ', to = ' . $to . ', subject = ' . $subject, LOG_NOTICE);

    echo json_encode(['result' => $result]);
    return;
}

/*
 複数のサーバからアクセスされる可能性があるため、送信元IPアドレスを保存する。
 同一サーバからでも、複数プロセスからアクセスされるためプロセスIDを保存する。
 同一プロセスのスレッド（FastCGI, php-fpmなど）の対応のため、ユニークIDを保存する。

*/
function getRequestID()
{
    // リクエスト送信元IPアドレス
    $ipAddress = str_replace(['.', ':'], '_', $_SERVER["REMOTE_ADDR"]);
    
    // 送信元サーバ内プロセスID
    if (empty($_POST['process_id'])) {
        return '';
    }
    $processId = $_POST['process_id'];
    
    // 送信元サーバ内スレッドID
    if (empty($_POST['thread_id'])) {
        return '';
    }
    $threadId = $_POST['thread_id'];

    return 'km' . $ipAddress . '_' . $processId . '_' . $threadId;
}


function log_output($str, $flag = false)
{
    if (DEBUG) {
        // デバッグモード
        log_output_file($str);
    } else {
        // 本番モード
        if ($flag == LOG_DEBUG) {
            return;
        }
        log_output_file($str);
    }
}

function log_output_file($msg)
{
    $logPath = sys_get_temp_dir();
    $logFile = $logPath . '/' . LOG_FILENAME_HEAD . date('Y-m-d') . '.log';
    
    $fp = fopen($logFile, 'a');
    
    $msg = date('Y-m-d H:i:s') . ' ' . $msg;
    if (!preg_match("/\n$/", $msg)) {
        $msg = $msg . "\n";
    }
    
    fputs($fp, $msg);
    
    fclose($fp);

    // 古いログファイル削除
    deleteOldLog($logPath);
}

function deleteOldLog($logPath)
{
    $dt = new DateTime();
    $dt->modify('-' . LOG_KEEP_DAYS . ' days');
    $logfiles = glob($logPath . '/' . LOG_FILENAME_HEAD . '*.log');
    
    foreach ($logfiles as $file) {
        $rtn = preg_match("/([0-9]{4}\-[0-9]{2}\-[0-9]{2})/", $file, $matches);
        if ($rtn == 1) {
            if (!empty($matches[1]) && $matches[1] < $dt->format('Y-m-d')) {
                unlink($file);
            }
        }
    }
}

