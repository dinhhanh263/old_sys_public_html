<?php

/*
if(!($cn = mysqli_connect( HOST_NAME, DB_UESR, DB_PW ))) die("Could not connect: ".$GLOBALS['mysqldb']->error);
if(!($GLOBALS['mysqldb']->select_db(DB_NAME))) die("DB:Could not connect: ".$GLOBALS['mysqldb']->error);
$GLOBALS['mysqldb']->query('SET NAMES utf8');
*/

$mysqldb = new mysqli(HOST_NAME, DB_UESR, DB_PW, DB_NAME);




$pdo = new PdoManagement();
$pdo->connect(HOST_NAME, DB_NAME, DB_UESR, DB_PW);




class PdoManagement {
    
    const ErrorReportEmail = 'technical_support@googlegroups.com';
    
    private $dbh = null;
    private $sql = null;
    private $statement = null;
    private $params = [];
    
    
    // データベース接続
    public function connect($hostName, $dbName, $dbUser, $dbPassword) {
        $this->dbh = new PDO('mysql:dbname=' . $dbName . ';host=' . $hostName, $dbUser, $dbPassword,
                    [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
        if (empty($this->dbh)) {
            $this->error_log('PDO connect failed.');
        }
    }
    
    public function setStatement($statement) {
        $this->statement = $statement;
    }
    
    public function getStatement() {
        return $this->statement;
    }
    
    public function prepare($sql) {
        
        $this->params = [];
        
        $this->sql = $this->sql_cleaner($sql);
        
        // SQL文プリペア
        $this->statement = $this->dbh->prepare($this->sql);
        if ($this->statement === false) {
            $this->error_log('SQL statement prepare error.');
        }
    }
    
    public function bindParam($params, $options = []) {
        if (empty($params)) return;
        
        if (empty($this->statement)) {
            $this->error_log('statement object is null.');
            return;
        }
        
        // パラメータバインド
        if (is_array($params)) {
            foreach ($params as $key => &$param) {
                $this->params[] = $param;
                $rtn = $this->statement->bindParam(($key + 1), $param);
                if ($rtn != true) {
                    $this->error_log('bindParam returned false.');
                }
            }
        } else {
            $rtn = $this->statement->bindParam(1, $params);
        }
        
        if ($rtn != true) {
            $this->error_log('bindParam returned false.');
        }
    }
    
    public function execute($sql = null) {
        if (empty($this->statement)) {
            $this->error_log('statement object is null.');
            return;
        }
        
        // SQL実行
        $rtn = $this->statement->execute();
        if (!$rtn) {
            $this->error_log('SQL statement execute error.');
            $errors = $this->dbh->errorInfo();
            $this->error_log('errorInfo = ' . var_export($errors, true));
        }
        
        return $rtn;
    }

    public function fetch($option = PDO::FETCH_ASSOC) {
        if (empty($this->statement)) {
            $this->error_log('statement object is null.');
            return null;
        }
        
        return $this->statement->fetch($option);
    }
    
    public function fetchAll($option = PDO::FETCH_ASSOC) {
        if (empty($this->statement)) {
            $this->error_log('statement object is null.');
            return null;
        }
        
        return $this->statement->fetchAll($option);
    }
    
    public function query($sql) {
        if (empty($this->dbh)) {
            $this->error_log('dbh object is null.');
            return;
        }

        $this->sql = $this->sql_cleaner($sql);

        $this->params = [];

        $rtn = $this->dbh->query($this->sql);
        if ($rtn === false) {
            $this->error_log('SQL query error.');
        }
        
        $this->statement = $rtn;
        
        return $rtn;
    }
    
    public function find($sql, $params = [], $options = []) {
        $this->sql = $sql;
        $this->prepare($this->sql);
        
        if (!empty($params)) {
            $this->bindParam($params);
        }
        $this->execute();
        $result = $this->fetch();
        
        return $result;
    }
    
    public function count($sql) {
        if (empty($this->statement)) {
            $this->error_log('statement object is null.');
            return;
        }

        return $this->statement->rowCount();
    }
    
    public function quote($string) {
        return $this->dbh->quote($string);
    }
    
    public function error_log($string) {
        mb_language("ja");
        mb_internal_encoding('UTF-8');
        
        error_log($string . ', SQL=' . $this->sql);
        error_log(' params=' . var_export($this->params, true));
        error_log('errorInfo = ' . var_export($this->dbh->errorInfo(), true));
        
        $to = self::ErrorReportEmail;
        
        $host = gethostname();
        
        // メールアドレスが設定され、ホスト名がkireimo.jpで終わる場合、メール送信
        if (!empty($to) && preg_match("/kireimo\.jp$/", $host)) {
            $subject = 'PdoManagement エラーログ';
            $message = 'Error Report from ' . gethostname() . ', ' . date('Y-m-d H:i:s') . "\n";
            $message .= $string . "\n";
            $message .= 'SQL = ' . "\n";
            $message .= $this->sql . "\n";
            $message .= "\n\n";
            $message .= 'params = ' . var_export($this->params, true) . "\n";
            $message .= 'errorInfo = ' . var_export($this->dbh->errorInfo(), true) . "\n";
            $message .= 'Server Information: ($_SERVER[]) ' . "\n";
            $message .= var_export($_SERVER, true);
            
            mb_send_mail($to, $subject, $message);
        }
    }
    
    public function sql_cleaner($sql) {
        mb_regex_encoding('UTF-8');
        $sql = mb_ereg_replace(['/\n/', '/\r/', '/\t/'], ' ', $sql);
        
        $sql = mb_ereg_replace("/[\x00-\x1f\x7f]/", '', $sql);
        $sql = mb_ereg_replace("/\"/", '', $sql);
        $sql = trim($sql);

        $sql = mb_ereg_replace('/;$/', '', $sql);
        $sql = mb_ereg_replace('/;/', '', $sql);
        $sql = $sql . ';';
        
        return $sql;
    }
}
