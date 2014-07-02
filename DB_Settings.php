<?php

class DB_InvalidSettings extends Exception {};

class DB_Settings 
{
  public static $attribute_names = array(
    'database',
    'driver',
    'host',
    'password',
    'port',
    'username',
  );
  public static $cividsn_to_settings_name = array(
    'database' => 'database',
    'dbsyntax' => 'driver',
    'hostspec' => 'host',
    'password' => 'password',
    'port' => 'port',
    'username' => 'username',
  );
  public $database;
  public $driver;
  public $host;
  public $password;
  public $port;
  public static $settings_to_doctrine_options = array(
    'database' => 'dbname',
    'driver' => 'driver',
    'host' => 'host',
    'password' => 'password',
    'port' => 'port',
    'username' => 'user',
  );
  public static $settings_to_pdo_options = array(
    'host' => 'host',
    'port' => 'port',
    'database' => 'dbname',
    'socket_path' => 'unix_socket',
  );
  public $socket_path;
  public $username;

  function __construct($options = NULL) {
    if ($options == NULL) {
      $civi_dsn = $this->findCiviDSN();
      $this->loadFromCiviDSN($civi_dsn);
    } elseif (array_key_exists('civi_dsn', $options)) {
      $this->loadFromCiviDSN($options['civi_dsn']);
    } elseif (array_key_exists('settings_array', $options)) {
      $this->loadFromSettingsArray($options['settings_array']);
    } else {
      throw new Exception("The options parameter needs to be blank if you want to load from CIVICRM_DSN, or it can be an array with key 'civi_dsn' that is a CiviCRM formatted DSN string, or it can be an array with key 'settings_array' than points to another array of database settings.");
    }
  }

  function findCiviDSN() {
    if (defined('CIVICRM_DSN')) {
      return CIVICRM_DSN;
    }
    $civi_dsn = getenv('CIVICRM_TEST_DSN');
    if ($civi_dsn !== FALSE) {
      return $civi_dsn;
    }
    throw new DB_InvalidSettings("CIVCRM_DSN is not defined and there is not CIVCRM_TEST_DSN environment variable");
  }

  function loadFromCiviDSN($civi_dsn) {
    $parsed_dsn = $this->parseDSN($civi_dsn);
    foreach (static::$cividsn_to_settings_name as $key => $value) {
      if (array_key_exists($key, $parsed_dsn)) {
        $this->$value = $parsed_dsn[$key];
      }
    }
    $this->updateHost();
  }

  function loadFromSettingsArray($settings_array) {
    foreach ($settings_array as $key => $value) {
      $this->$key = $value;
    }
    $this->updateHost();
  }

  function toCiviDSN() {
    $civi_dsn = "mysql://{$this->username}:{$this->password}@{$this->host}";
    if ($this->port !== NULL) {
      $civi_dsn = "$civi_dsn:{$this->port}";
    }
    $civi_dsn = "$civi_dsn/{$this->database}?new_link=true";
    return $civi_dsn;
  }

  function toDoctrineArray() {
    $result = array();
    foreach (self::$settings_to_doctrine_options as $key => $value){
      $result[$value] = $this->$key;
    }
    $result['driver'] = "pdo_{$result['driver']}";
    return $result;
  }

  function toDrupalDSN() {
    $drupal_dsn = "{$this->driver}://{$this->username}:{$this->password}@{$this->host}";
    if ($this->port !== NULL) {
      $drupal_dsn = "$drupal_dsn:{$this->port}";
    }
    $drupal_dsn = "$drupal_dsn/{$this->database}";
    return $drupal_dsn;
  }

  function toMySQLArguments() {
    $args = "-h {$this->host} -u {$this->username} -p{$this->password}";
    if ($this->port != NULL) {
      $args .= " -P {$this->port}";
    }
    $args .= " {$this->database}";
    return $args;
  }

  function toPHPArrayString() {
    $result = "array(\n";
    foreach (static::$attribute_names as $attribute_name) {
      $result .= "  '$attribute_name' => '{$this->$attribute_name}',\n";
    }
    $result .= ")";
    return $result;
  }

  function toPDODSN($options = array()) {
    $pdo_dsn = "{$this->driver}:";
    $pdo_dsn_options = array();
    $settings_to_pdo_options = static::$settings_to_pdo_options;
    if (CRM_Utils_Array::fetch('no_database', $options, FALSE)) {
      unset($settings_to_pdo_options['database']);
    }
    foreach ($settings_to_pdo_options as $settings_name => $pdo_name) {
      if ($this->$settings_name !== NULL) {
        $pdo_dsn_options[] = "{$pdo_name}={$this->$settings_name}";
      }
    }
    $pdo_dsn .= implode(';', $pdo_dsn_options);
    return $pdo_dsn;
  }

  function updateHost() {
    /*
     * If you use localhost for the host, the MySQL client library will
     * use a unix socket to connect to the server and ignore the port,
     * so if someone is not going to use the default port, let's
     * assume they don't want to use the unix socket.
     */
    if ($this->port != NULL && $this->host == 'localhost') {
      $this->host = '127.0.0.1';
    }
  }

  function parseDSN($dsn)
  {
        $parsed = array(
            'phptype'  => false,
            'dbsyntax' => false,
            'username' => false,
            'password' => false,
            'protocol' => false,
            'hostspec' => false,
            'port'     => false,
            'socket'   => false,
            'database' => false,
        );

        if (is_array($dsn)) {
            $dsn = array_merge($parsed, $dsn);
            if (!$dsn['dbsyntax']) {
                $dsn['dbsyntax'] = $dsn['phptype'];
            }
            return $dsn;
        }

        // Find phptype and dbsyntax
        if (($pos = strpos($dsn, '://')) !== false) {
            $str = substr($dsn, 0, $pos);
            $dsn = substr($dsn, $pos + 3);
        } else {
            $str = $dsn;
            $dsn = null;
        }

        // Get phptype and dbsyntax
        // $str => phptype(dbsyntax)
        if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
            $parsed['phptype']  = $arr[1];
            $parsed['dbsyntax'] = !$arr[2] ? $arr[1] : $arr[2];
        } else {
            $parsed['phptype']  = $str;
            $parsed['dbsyntax'] = $str;
        }

        if (!count($dsn)) {
            return $parsed;
        }

        // Get (if found): username and password
        // $dsn => username:password@protocol+hostspec/database
        if (($at = strrpos($dsn,'@')) !== false) {
            $str = substr($dsn, 0, $at);
            $dsn = substr($dsn, $at + 1);
            if (($pos = strpos($str, ':')) !== false) {
                $parsed['username'] = rawurldecode(substr($str, 0, $pos));
                $parsed['password'] = rawurldecode(substr($str, $pos + 1));
            } else {
                $parsed['username'] = rawurldecode($str);
            }
        }

        // Find protocol and hostspec

        if (preg_match('|^([^(]+)\((.*?)\)/?(.*?)$|', $dsn, $match)) {
            // $dsn => proto(proto_opts)/database
            $proto       = $match[1];
            $proto_opts  = $match[2] ? $match[2] : false;
            $dsn         = $match[3];

        } else {
            // $dsn => protocol+hostspec/database (old format)
            if (strpos($dsn, '+') !== false) {
                list($proto, $dsn) = explode('+', $dsn, 2);
            }
            if (strpos($dsn, '/') !== false) {
                list($proto_opts, $dsn) = explode('/', $dsn, 2);
            } else {
                $proto_opts = $dsn;
                $dsn = null;
            }
        }

        // process the different protocol options
        $parsed['protocol'] = (!empty($proto)) ? $proto : 'tcp';
        $proto_opts = rawurldecode($proto_opts);
        if (strpos($proto_opts, ':') !== false) {
            list($proto_opts, $parsed['port']) = explode(':', $proto_opts);
        }
        if ($parsed['protocol'] == 'tcp') {
            $parsed['hostspec'] = $proto_opts;
        } elseif ($parsed['protocol'] == 'unix') {
            $parsed['socket'] = $proto_opts;
        }

        // Get dabase if any
        // $dsn => database
        if ($dsn) {
            if (($pos = strpos($dsn, '?')) === false) {
                // /database
                $parsed['database'] = rawurldecode($dsn);
            } else {
                // /database?param1=value1&param2=value2
                $parsed['database'] = rawurldecode(substr($dsn, 0, $pos));
                $dsn = substr($dsn, $pos + 1);
                if (strpos($dsn, '&') !== false) {
                    $opts = explode('&', $dsn);
                } else { // database?param1=value1
                    $opts = array($dsn);
                }
                foreach ($opts as $opt) {
                    list($key, $value) = explode('=', $opt);
                    if (!isset($parsed[$key])) {
                        // don't allow params overwrite
                        $parsed[$key] = rawurldecode($value);
                    }
                }
            }
        }

        return $parsed;
    }
}
