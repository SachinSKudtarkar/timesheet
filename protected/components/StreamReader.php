<?php

/**
 * Description of StreamReaderCommand
 *
 * @author Shriram Jadhav <shriramjadhav@infinitylabs.in>
 */
class StreamReader {

    public $waitLimit = 35;  // Seconds
    public $start;
    private $credentials = array(
        array("user" => "RJ55004355", "password" => "Nikesh@12345"),
        array("user" => "rjil", "password" => 'rjil123'),
        array("user" => "rjil", "password" => 'Rjil123'),
    );
    public $terminalChars = array("#", ">"); // removed "$"
    public $terminate = array("% Authentication failed", "timeout expired!", "Connection closed by foreign host.", "Wait timeout reached.", "Login incorrect");

    public function setCredentials($credentials) {
        $this->credentials = $credentials;
    }

    public function getCredentials() {
        return $this->credentials;
    }

    public function strRs($handle, $str = "", $limit = "#", $terminalChar = "#", $log = TRUE) {
        $length = -1 * strlen($limit);
        while (substr(rtrim($str, " \t\n\r\0\x0B"), $length) !== $limit) {
            if (substr($str, -1) !== $terminalChar && substr($str, -1) !== '0') {
                $this->log("Delaying ..." . substr($str, -1) . "\n", $log);
                sleep(0.05);
            }
            if (microtime(true) - $this->start >= $this->waitLimit) {
                return "Wait timeout reached.";
            }
            while ($ch = fgetc($handle)) {
                $str .= $ch;
                $checkString = substr($str, -34);
                if (strpos($checkString, "timeout expired!")) {
                    return "timeout expired!";
                }
                if (strpos($checkString, "Connection closed by foreign host.")) {
                    return "Connection closed by foreign host.";
                }
                if ($ch === $terminalChar) {
                    break;
                }
            }
            if ($ch == '0')
                $str .= $ch;
        }
        return $str;
    }

    public function strRsFindTeminalChar($handle, $str = "") {
        while (!in_array(substr($str, -1), $this->terminalChars)) {
            if (microtime(true) - $this->start >= $this->waitLimit) {
                return "Wait timeout reached.";
            }
            while ($ch = fgetc($handle)) {
                $str .= $ch;
                $checkString = substr($str, -34);
                if (strpos($checkString, "% Authentication failed")) {
                    return "% Authentication failed";
                }
                if (strpos($checkString, "timeout expired!")) {
                    return "timeout expired!";
                }
                if (strpos($checkString, "Connection closed by foreign host.")) {
                    return "Connection closed by foreign host.";
                }
                if (in_array($ch, $this->terminalChars)) {
                    break;
                }
            }
            if ($ch == '0')
                $str .= $ch;
        }
        return $str;
    }

    private function log($output, $log = TRUE) {
        if ($log) {
            echo $output;
        }
    }

    public function ping($target) {
        $result = array();
        $commnd = '';
        if (filter_var($target, FILTER_VALIDATE_IP))
            $commnd = "ping -c 2 -w 2 " . $target;
        if (filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
            $commnd = "ping6 -c 2 -w 2 " . $target;
        if (!empty($commnd))
            $cmd_result = shell_exec($commnd);
        else
            return 'Invalid target IP';
        $result = explode(",", $cmd_result);
        if (!empty($result[1]) && preg_match("/0 received/i", $result[1]))
            return 'Device is not rechable';
        else
            return 'online';
    }

    public function getUserPromt($handle, $timeOut = 3) {
        $str = '';
        while (!preg_match('/[Uu]ser/', $str)) {
            if (microtime(true) - $this->start >= $timeOut) {
                return FALSE;
            }
            while ($ch = fgetc($handle)) {
                $str .= $ch;
                if (preg_match('/[Uu]ser/', $str)) {
                    break;
                }
            }
            if ($ch == '0')
                $str .= $ch;
        }
        return TRUE;
    }

    public function collect($ip, $commands, $waitTimeout, $log = TRUE) {
        $this->waitLimit = $waitTimeout;
        $this->start = microtime(TRUE);
        if (!empty($ip) && (filter_var($ip, FILTER_VALIDATE_IP) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) && !empty($commands)) {
            $currentStatus = $this->ping($ip);
            if ($currentStatus != 'online') {
                $this->log('Failed: Device is not rechable. [' . $ip . "]\n", $log);
                return 'Failed: Device is not rechable.';
            } else {
                $this->log('Device is rechable.[' . $ip . "]\n", $log);
            }
            if (!is_array($commands)) {
                $commands = explode(",", $commands);
            }
            $timeOut = 3;
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $client = @stream_socket_client("tcp://[$ip]:23", $errno, $errorMessage, 2);
                $timeOut = 12;
            } else {
                $client = @stream_socket_client("tcp://$ip:23", $errno, $errorMessage, 2);
            }

            if ($client === FALSE) {
                return "Failed to connect: $errorMessage";
            }
//            print_r(stream_get_meta_data($client));
//            
            // Try to login on router
            $limit = '#';
            $terminalChar = '#';
            $str = "";
            $tryCount = 0;
            $credentials = $this->getCredentials();
            if (!empty($credentials) && is_array($credentials)) {
                array_filter($credentials, function($credential) {
                    return is_array($credential) && !empty($credential['user']) && !empty($credential['password']);
                });
            }
            if (!$this->getUserPromt($client, $timeOut)) {
                $this->log("Failed : Telnet is not enabled OR Userpromt not found\n", $log);
                return "Failed : Telnet is not enabled OR Userpromt not found";
            } else {
                $this->log("Found user promt\n", $log);
            }
            if (!empty($credentials)) {
                foreach ($credentials as $key => $value) {
                    $this->log("Login try (" . ++$tryCount . ") using '{$value['user']}'\n", $log);
                    fwrite($client, "{$value['user']}\r\n{$value['password']}\r\n");
                    $str = $this->strRsFindTeminalChar($client);
                    $this->log($str . "\n", $log);
                    if ($str === "% Authentication failed") {
                        continue;
                    } elseif (in_array($str, $this->terminate)) {
                        @fclose($client);
                        return $str;
                    } else {
                        break;
                    }
                }
                if (in_array($str, $this->terminate)) {
                    // % Authentication failed
                    @fclose($client);
                    return $str;
                }
                $limit = substr($str, -15);
                $terminalChar = substr($str, -1);
            } else {
                @fclose($client);
                return "No Device Credentials Are Available.";
            }
            // Logged in successfully

            $this->log("Login success\n", $log);
            $this->log("Hostname : " . substr($limit, 0, 14) . "\n", $log);
            $outPut['hostname'] = substr($limit, 0, 14);
            $this->log("Terminal Char : $terminalChar\n", $log);
            fwrite($client, "terminal length 0\r\n");
            $str = $this->strRs($client, "", $limit, $terminalChar, $log);
            $this->log($str, $log);
            if (in_array($str, $this->terminate)) {
                @fclose($client);
                return $str;
            }
            // Fetch commands output
            $this->log("Fetching commands output ...\n", $log);
            $outPut = array();
            foreach ($commands as $key => $command) {
                if (is_array($command)) {
                    if (!empty($command['name']) && !empty($command['command'])) {
                        fwrite($client, "{$command['command']}\r\n");
                        $str = $this->strRs($client, "", $limit, $terminalChar, $log);
                        $this->log($str, $log);

                        //Terminate if failed
                        if (in_array($str, $this->terminate)) {
                            @fclose($client);
                            return $str;
                        }
                        $str = str_replace("{$command['command']}", "", $str);
                        $this->log($str, $log);
                        $str = trim(str_replace("$limit", "", $str), " \t\n\r\0\x0B");
                        $outPut[$command['name']] = $str;
                    }
                } else {
                    fwrite($client, "$command\r\n");
                    $str = $this->strRs($client, "", $limit, $terminalChar, $log);
                    $this->log($str, $log);

                    //Terminate if failed
                    if (in_array($str, $this->terminate)) {
                        @fclose($client);
                        return $str;
                    }
                    $str = str_replace("$command", "", $str);
                        $str = trim(str_replace("$limit", "", $str), " \t\n\r\0\x0B");
                    $outPut[$key] = $str;
                }
            }

            // Exit from router
            $this->log("Closing connection ...\n", $log);
            fwrite($client, "exit\r\n");
            if (fclose($client)) {
                $this->log("Done.\n", $log);
            } else {
                $this->log("Failed to close connection.\n", $log);
            }
            return $outPut;
        }
        return "Invalid Request";
    }

    public function collectWithJump($ip, $commands, $waitTimeout, $log = TRUE, $jumpDevice) {
        $this->waitLimit = $waitTimeout;
        $this->start = microtime(TRUE);
        if (!empty($ip) && filter_var($ip, FILTER_VALIDATE_IP) && !empty($commands) && !empty($jumpDevice) && filter_var($jumpDevice, FILTER_VALIDATE_IP)) {
            if (!is_array($commands)) {
                $commands = explode(",", $commands);
            }
            $client = @stream_socket_client("tcp://$jumpDevice:23", $errno, $errorMessage, 2);

            if ($client === FALSE) {
                return "Failed to connect: $errorMessage";
            }
            // Try to login on router
            $limit = '#';
            $terminalChar = '#';
            $str = "";
            $tryCount = 0;
            fwrite($client, "cisco\r\ncisco$123\r\n");
            $str = $this->strRsFindTeminalChar($client);
            $this->log($str, $log);
            if ($str === "% Authentication failed") {
                return "% Authentication failed [jump Device : $jumpDevice]";
            }
            fwrite($client, "telnet $ip\r\n");
            $credentials = $this->getCredentials();
            if (!empty($credentials) && is_array($credentials)) {
                array_filter($credentials, function($credential) {
                    return is_array($credential) && !empty($credential['user']) && !empty($credential['password']);
                });
            }
            if (!empty($credentials)) {
                foreach ($credentials as $key => $value) {
                    $this->log("Login try (" . ++$tryCount . ") using '{$value['user']}' : '{$value['password']}'\n", $log);
                    fwrite($client, "{$value['user']}\r\n{$value['password']}\r\n");
                    $str = $this->strRsFindTeminalChar($client);
                    $this->log($str, $log);
                    if ($str === "% Authentication failed") {
                        continue;
                    } elseif (in_array($str, $this->terminate)) {
                        return $str;
                    } else {
                        break;
                    }
                }
                if (in_array($str, $this->terminate)) {
                    // % Authentication failed
                    return $str;
                }
                $limit = substr($str, -15);
                $terminalChar = substr($str, -1);
            } else {
                return "No Device Credentials Are Available.";
            }
            // Logged in successfully

            $this->log("Login success\n", $log);
            $this->log("Hostname : " . substr($limit, 0, 14) . "\n", $log);
            $this->log("Terminal Char : $terminalChar\n", $log);
            fwrite($client, "terminal length 0\r\n");
            $str = $this->strRs($client, "", $limit, $terminalChar, $log);
            $this->log($str, $log);
            if (in_array($str, $this->terminalChars)) {
                return $str;
            }
            // Fetch commands output
            $this->log("Fetching commands output ...\n", $log);
            $outPut = array();
            foreach ($commands as $key => $command) {
                if (is_array($command)) {
                    if (!empty($command['name']) && !empty($command['command'])) {
                        fwrite($client, "{$command['command']}\r\n");
                        $str = $this->strRs($client, "", $limit, $terminalChar, $log);
                        $this->log($str, $log);

                        //Terminate if failed
                        if (in_array($str, $this->terminate)) {
                            return $str;
                        }
                        $str = ltrim($str, "\r\n{$command['command']}\r\n");
                        $this->log($str, $log);
                        $outPut[$command['name']] = $str = rtrim($str, "\r\n$limit");
                    }
                } else {
                    fwrite($client, "$command\r\n");
                    $str = $this->strRs($client, "", $limit, $terminalChar, $log);
                    $this->log($str, $log);

                    //Terminate if failed
                    if (in_array($str, $this->terminate)) {
                        return $str;
                    }
                    $str = ltrim($str, "\r\n$command\r\n");
                    $outPut[$key] = $str = rtrim($str, "\r\n$limit");
                }
            }

            // Exit from router
            $this->log("Closing connection ...\n", $log);
            fwrite($client, "exit\r\n");
            fwrite($client, "exit\r\n");
            if (fclose($client)) {
                $this->log("Done.\n", $log);
            } else {
                $this->log("Failed to close connection.\n", $log);
            }
            return $outPut;
        }
        return "Invalid Request";
    }

    public function configTCommands($ip, $commands, $waitTimeout, $log = TRUE, $debug = FALSE) {
        $this->waitLimit = $waitTimeout;
        $this->start = microtime(TRUE);
        if (!empty($ip) && (filter_var($ip, FILTER_VALIDATE_IP) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) && !empty($commands)) {
            $currentStatus = $this->ping($ip);
            if ($currentStatus != 'online') {
                $this->log('Failed: Device is not rechable. [' . $ip . "]\n", $log);
                return 'Failed: Device is not rechable.';
            } else {
                $this->log('Device is rechable.[' . $ip . "]\n", $log);
            }
            $this->log('Trying telnet on ' . $ip . "\n", $log);
            $timeOut = 3;
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $client = @stream_socket_client("tcp://[$ip]:23", $errno, $errorMessage, 2);
                $timeOut = 12;
            } else {
                $client = @stream_socket_client("tcp://$ip:23", $errno, $errorMessage, 2);
            }

            if ($client === FALSE) {
                $this->log("Failed to connect: $errorMessage" . "\n", $log);
                return "Failed to connect: $errorMessage";
            }
            $this->log('Socket successful on ' . $ip . "\n", $log);
            // Try to login on router
            $limit = '#';
            $terminalChar = '#';
            $str = "";
            $tryCount = 0;
            $credentials = $this->getCredentials();
            if (!empty($credentials) && is_array($credentials)) {
                array_filter($credentials, function($credential) {
                    return is_array($credential) && !empty($credential['user']) && !empty($credential['password']);
                });
            }
            if (!$this->getUserPromt($client, $timeOut)) {
                $this->log("Failed : Telnet is not enabled OR Userpromt not found\n", $log);
                return "Failed : Telnet is not enabled OR Userpromt not found";
            } else {
                $this->log("Found user promt\n", $log);
            }
            if (!empty($credentials)) {
                foreach ($credentials as $key => $value) {
                    $this->log("Login try (" . ++$tryCount . ") using '{$value['user']}'\r\n", $log);
                    fwrite($client, "{$value['user']}\r\n{$value['password']}\r\n");
                    $str = $this->strRsFindTeminalChar($client);
                    $this->log($str, $log);
                    if ($str === "% Authentication failed") {
                        continue;
                    } elseif (in_array($str, $this->terminate)) {
                        @fclose($client);
                        return $str;
                    } else {
                        break;
                    }
                }
                if (in_array($str, $this->terminate)) {
                    @fclose($client);
                    // % Authentication failed
                    return $str;
                }
                $limit = substr($str, -15);
                $terminalChar = substr($str, -1);
            } else {
                @fclose($client);
                return "No Device Credentials Are Available.";
            }
            // Logged in successfully

            $this->log("Login success\n", $log);
            $this->log("Hostname : " . substr($limit, 0, 14) . "\n", $log);
            $this->log("Terminal Char : $terminalChar\n", $log);
            fwrite($client, "terminal length 0\r\n");
            $str = $this->strRs($client, "", $limit, $terminalChar, $log);
            $this->log($str, $log);
            if (in_array($str, $this->terminate)) {
                @fclose($client);
                return $str;
            }
            // Fetch commands output
            $this->log("Fetching commands output ...\n", $log);
            $outPut = array();
            if (is_array($commands)) {
                $commands = implode("\n", $commands);
            }
            $commands .= "\nend\nexit\n";
            fwrite($client, $commands);
            $limit = $limit . "exit";
            $str = $this->strRs($client, "", $limit, $terminalChar, $log);
            $this->log($str, $log);
            //Terminate if failed
            if (in_array($str, $this->terminate)) {
                @fclose($client);
                return $str;
            }

            if (fclose($client)) {
                $this->log("Done.\n", $log);
            } else {
                $this->log("Failed to close connection.\n", $log);
            }
            return $str;
        }
        return "Invalid Request";
    }

    public function configTCommandsWithJump($ip, $commands, $waitTimeout, $log = TRUE, $jumpDevice, $debug = FALSE) {
        $this->waitLimit = $waitTimeout;
        $this->start = microtime(TRUE);
        if (!empty($ip) && filter_var($ip, FILTER_VALIDATE_IP) && !empty($commands) && !empty($jumpDevice) && filter_var($jumpDevice, FILTER_VALIDATE_IP)) {
            $client = @stream_socket_client("tcp://$jumpDevice:23", $errno, $errorMessage, 2);

            if ($client === FALSE) {
                return "Failed to connect: $errorMessage";
            }
            // Try to login on router
            $hosts = array();
            $limit = '#';
            $terminalChar = '#';
            $str = "";
            $tryCount = 0;
            fwrite($client, "cisco\r\ncisco$123\r\n");
            $str = $this->strRsFindTeminalChar($client);
            $host[] = substr($str, -9);
            ;
            $this->log($str, $log);
            if ($str === "% Authentication failed") {
                return "Fail: % Authentication failed [jump Device : $jumpDevice]";
            }
            fwrite($client, "telnet $ip\r\n");
            $credentials = $this->getCredentials();
            if (!empty($credentials) && is_array($credentials)) {
                array_filter($credentials, function($credential) {
                    return is_array($credential) && !empty($credential['user']) && !empty($credential['password']);
                });
            }
            if (!$this->getUserPromt($client)) {
                $this->log("Failed : Telnet is not enabled OR Userpromt not found\n", $log);
                return "Failed : Telnet is not enabled OR Userpromt not found";
            } else {
                $this->log("Found user promt\n", $log);
            }
            if (!empty($credentials)) {
                foreach ($credentials as $key => $value) {
                    $this->log("Login try (" . ++$tryCount . ") using '{$value['user']}' : '{$value['password']}'\n", $log);
                    fwrite($client, "{$value['user']}\r\n{$value['password']}\r\n");
                    $str = $this->strRsFindTeminalChar($client);
                    $this->log($str, $log);
                    if ($str === "% Authentication failed") {
                        continue;
                    } elseif (in_array($str, $this->terminate)) {
                        return "Fail:" . $str;
                    } else {
                        break;
                    }
                }
                if (in_array($str, $this->terminate)) {
                    // % Authentication failed
                    return $str;
                }
                $limit = substr($str, -15);
                $host[] = $limit;
                $terminalChar = substr($str, -1);
            } else {
                return "Fail: No Device Credentials Are Available.";
            }
            // Logged in successfully

            $this->log("Login success\n", $log);
            $this->log("Hostname : " . substr($limit, 0, 14) . "\n", $log);
            $this->log("Terminal Char : $terminalChar\n", $log);
            fwrite($client, "terminal length 0\r\n");
            $limit = substr($limit, 0, 14) . $terminalChar;

            $str = $this->strRs($client, "", $limit, $terminalChar, $log);
            $this->log($str, $log);
            if (in_array($str, $this->terminalChars)) {
                return $str;
            }
            // Fetch commands output
            $this->log("Fetching commands output ...\n", $log);
            $outPut = array();
            if (is_array($commands)) {
                $commands = implode("\n", $commands);
            }
            $commands .= "\nend\nexit\nexit\n";
            fwrite($client, $commands);
            $str = $this->strRs1($client, "", $host, $terminalChar, $log);
            $this->log($str, $log);

            //Terminate if failed
            if (in_array($str, $this->terminate)) {
                return $str;
            }

            if (fclose($client)) {
                $this->log("Done.\n", $log);
            } else {
                $this->log("Failed to close connection.\n", $log);
            }
            return $str;
        }
        return "Fail: Invalid Request";
    }

}
