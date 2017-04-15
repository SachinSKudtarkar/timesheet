<?php

/*
 * Description:ssh connectivity
 * @author : Vishwas Zambre <vishwaszambre@benchmarkitsolutions.com>
 */

class SshConnect {

    protected $connection;
    private $username;
    private $password;
    private $host;
    public $gm_collect = true;
    protected $credentials;

    /*
     * connect with device for given credentials
     */

    public function connect($ip, $credentials) {
        $this->host = $ip;

        $this->username = isset($credentials['username']) ? $credentials['username'] : null;
        $this->password = isset($credentials['password']) ? $credentials['password'] : null;

        $port = 22;
        $connection = null;
        if (!empty($ip)) {
            $connection = @ssh2_connect($ip, $port, array(), array('disconnect' => function($reason, $message) {
                            throw new Exception(sprintf("Connection failure: Server disconnected with reason code [%d] and message: %s\n", $reason, $message));
                        }));
        }
        $this->connection = $connection;
        if ($this->connection) {
            if (@ssh2_auth_password($this->connection, $this->username, $this->password)) {
                return $this->connection;
            } else {
                throw new Exception(sprintf("Authentication Failure: Could not connect to %s", $ip));
            }
        } else {
            throw new Exception(sprintf("Connection Failure: Could not connect to %s", $ip));
        }
    }

    /*
     * executes command on the device & get results
     */

    public function getCommandOutput($command) {
       try{
        if ($this->gm_collect) {
            return $this->getGmCommandOutput($command);
        }
        $output = '';
        $this->reconnect();
        $stream = @ssh2_exec($this->connection, $command);
        $errorStream = @ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        @stream_set_blocking($errorStream, true);
        @stream_set_blocking($stream, true);

            while ($line = @fgets($stream)) {
                $output.= "\n";
                $output.= trim($line);
            }

            #Close streams
            @fclose($stream);
            @fclose($errorStream);

            return $output;
        } catch (Exception $exc) {
            echo "\n" . $exc->getMessage() . "\n";
            return FALSE;
        }
    }

    public function reconnect() {
        try {

            return $this->connect($this->host, array('username' => $this->username, 'password' => $this->password));
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getHost() {
        return $this->host;
    }

    public function getGmCommandOutput($command) {
        $credentials = $this->credentials;
        $collectionEngine = new CDCDeviceInfoCollectionClient(CDCDeviceInfoCollectionClient::DEFAULT_COLLECTION_SERVERS);
        $collectionEngine->setCredentials($credentials);
        $collectionEngine->setCollectionQueue(CDCDeviceInfoCollectionClient::COLLECTION_ENGINE_COMMAND_SERVERINFO_COLLECT);
        $json = $collectionEngine->realtimeCollect($this->host, array(array("name" => "command", "command" => $command)));
        $result = json_decode($json, true);
        return isset($result['result']['command']) ? implode("", $result['result']['command']) : null;
    }

    public function setCredentials($cre) {
        $this->credentials = $cre;
    }

    public function setHost($host) {
        $this->host = $host;
    }

}
