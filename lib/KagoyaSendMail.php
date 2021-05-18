<?php
/*
  Kireimo Kagoya サーバからのメール送信用トランスポート

 使用方法
 require_once LIB_DIR . 'KagoyaSendMail.php';
 
 $kagoya = new KagoyaSendMail();
 $kagoya->send($to, $subject, $message, $headers);
 
 
*/
require_once 'LogObject.php';


mb_language('ja');
mb_internal_encoding("utf-8");




class KagoyaSendMail extends LogObject
{
    // 秘密鍵
    const SECRET_KEY = '5jwH3nS84dUHobtq8zRponhZyuKUwCor';
    
    // メール送信サーバ名（末尾スラッシュ不要）
    const MAIL_SERVER_NAME = 'http://mailsend.kireimo.jp';
    
    // メール送信API-URL
    const MAIL_SEND_URL = '/lib/ajax/mail_send.php';
    
    // クッキー保存ファイル（セッションID保存用）
    const COOKIE_FILE = 'cookie.txt';

    // Basic認証
    const USERNAME = 'kire1';
    const PASSWORD = '3ron';
    
    
    
    public function send($to, $subject, $message, $headers = null, $params = null, $options = null)
    {
        // プロセスID取得
        $pid = getmypid();
        
        // ユニークID取得
        $uniqId = uniqid();

        // 送信キー取得要求
        $result = $this->sendCurlRequest([
            'get_key' => true,
            'process_id' => $pid,
            'thread_id' => $uniqId,
        ]);
        $json_data = json_decode($result, true);
        if (empty($json_data['key'])) {
            $this->log('json key is empty.', 'error');
            return;
        }
        $key = $json_data['key'];

        // メール送信要求
        $result = $this->sendCurlRequest([
            'get_key' => null,
            'process_id' => $pid,
            'thread_id' => $uniqId,
            'hashed' => md5(self::SECRET_KEY . $key),
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'additional_headers' => $headers,
            'additional_param' => $params,
            'option' => [],
        ]);
        
        return;
    }

    protected function sendCurlRequest($postData)
    {
        $curl=curl_init(self::MAIL_SERVER_NAME . self::MAIL_SEND_URL);

        curl_setopt($curl, CURLOPT_POST, TRUE);
        
        // Basic認証がある場合
        $userName = self::USERNAME;
        if (!empty($userName)) {
            curl_setopt($curl, CURLOPT_USERPWD, self::USERNAME . ":" . self::PASSWORD);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        // クッキーファイル保存(セッションID保存用)
        $tempDir = sys_get_temp_dir() . '/';
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tempDir . self.COOKIE_FILE);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tempDir . self.COOKIE_FILE);
        
        // Locationヘッダを追跡
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        //curl_setopt($curl, CURLOPT_REFERER, "REFERER");
        //curl_setopt($curl, CURLOPT_USERAGENT, "USER_AGENT"); 

        $result = curl_exec($curl);
        if ($result === false) {
            $this->log('curl_exec return error!', 'error');
            return false;
        }
        curl_close($curl);
        
        return $result;
    }
}


