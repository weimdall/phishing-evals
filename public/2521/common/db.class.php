<?php

if (!defined('OK_LOADME')) {
    die("<title>Error!</title><body>No such file or directory.</body>");
}

class Database {

    /**
     * database connection object
     * @var \PDO
     */
    protected $pdo;

    /**
     * Connect to the database
     */
    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Return the pdo connection
     */
    public function getPdo() {
        return $this->pdo;
    }

    /**
     * Changes a camelCase table or field name to lowercase,
     * underscore spaced name
     *
     * @param  string $string camelCase string
     * @return string underscore_space string
     */
    protected function camelCaseToUnderscore($string) {
        $string = "`" . str_replace("`", "``", $string) . "`";
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @param  string $param Name of the sequence object from which the ID should be returned.
     * @return string representing the row ID of the last row that was inserted into the database.
     */
    public function lastInsertId($param = null) {
        return $this->pdo->lastInsertId($param);
    }

    /**
     * handler for dynamic CRUD methods
     *
     * Format for dynamic methods names -
     * Create:  insertTableName($arrData)
     * Retrieve: getTableNameByFieldName($value)
     * Update: updateTableNameByFieldName($value, $arrUpdate)
     * Delete: deleteTableNameByFieldName($value)
     *
     * @param  string     $function
     * @param  array      $arrParams
     * @return array|bool
     */
    public function __call($function, array $params = array()) {
        if (!preg_match('/^(get|update|insert|delete)(.*)$/', $function, $matches)) {
            throw new \BadMethodCallException($function . ' is an invalid method Call');
        }

        if ('insert' == $matches[1]) {
            if (!is_array($params[0]) || count($params[0]) < 1) {
                throw new \InvalidArgumentException('insert values must be an array');
            }
            return $this->insert($this->camelCaseToUnderscore($matches[2]), $params[0]);
        }

        list($tableName, $fieldName) = explode('By', $matches[2], 2);
        if (!isset($tableName, $fieldName)) {
            throw new \BadMethodCallException($function . ' is an invalid method Call');
        }

        if ('update' == $matches[1]) {
            if (!is_array($params[1]) || count($params[1]) < 1) {
                throw new \InvalidArgumentException('update fields must be an array');
            }
            return $this->update(
                            $this->camelCaseToUnderscore($tableName), $params[1], array($this->camelCaseToUnderscore($fieldName) => $params[0])
            );
        }

        //select and delete method
        return $this->{$matches[1]}(
                        $this->camelCaseToUnderscore($tableName), array($this->camelCaseToUnderscore($fieldName) => $params[0])
        );
    }

    /**
     * Record retrieval method
     *
     * @param  string     $tableName name of the table
     * @param  array      $where     (key is field name)
     * @return array|bool (associative array for single records, multidim array for multiple records)
     */
    public function get($tableName, $whereAnd = array(), $whereOr = array(), $whereLike = array()) {
        $cond = '';
        $s = 1;
        $params = array();
        foreach ($whereAnd as $key => $val) {
            $cond .= " And " . $key . " = :a" . $s;
            $params['a' . $s] = $val;
            $s++;
        }
        foreach ($whereOr as $key => $val) {
            $cond .= " OR " . $key . " = :a" . $s;
            $params['a' . $s] = $val;
            $s++;
        }
        foreach ($whereLike as $key => $val) {
            $cond .= " OR " . $key . " like '% :a" . $s . "%'";
            $params['a' . $s] = $val;
            $s++;
        }
        $stmt = $this->pdo->prepare("SELECT  $tableName.* FROM $tableName WHERE 1 " . $cond);
        try {
            $stmt->execute($params);
            $res = $stmt->fetchAll();

            if (!$res || count($res) != 1) {
                return $res;
            }
            return $res;
        } catch (\PDOException $e) {
            throw new \RuntimeException("[" . $e->getCode() . "] : " . $e->getMessage());
        }
    }

    public function getAllRecords($tableName, $fields = '*', $cond = '', $orderBy = '', $limit = '') {
        $stmt = $this->pdo->prepare("SELECT $fields FROM $tableName WHERE 1 " . $cond . " " . $orderBy . " " . $limit);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function getRecFrmQry($query) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function doQueryStr($query, $skiperr = 0) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        } catch (\PDOException $e) {
            if ($skiperr != 1) {
                throw new \RuntimeException("[" . $e->getCode() . "] : " . $e->getMessage());
            }
        }
    }

    public function getQueryCount($tableName, $field, $cond = '') {
        $stmt = $this->pdo->prepare("SELECT count($field) as total FROM $tableName WHERE 1 " . $cond);
        try {
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$res || count($res) != 1) {
                return $res;
            }
            return $res;
        } catch (\PDOException $e) {
            throw new \RuntimeException("[" . $e->getCode() . "] : " . $e->getMessage());
        }
    }

    /**
     * Update Method
     *
     * @param  string $tableName
     * @param  array  $set       (associative where key is field name)
     * @param  array  $where     (associative where key is field name)
     * @return int    number of affected rows
     */
    public function update($tableName, array $set, array $where) {
        $arrSet = array_map(
                function($value) {
            return $value . '=:' . $value;
        }, array_keys($set)
        );

        $stmt = $this->pdo->prepare(
                "UPDATE $tableName SET " . implode(',', $arrSet) . ' WHERE ' . key($where) . '=:' . key($where) . 'Field'
        );

        foreach ($set as $field => $value) {
            $stmt->bindValue(':' . $field, $value);
        }
        $stmt->bindValue(':' . key($where) . 'Field', current($where));
        try {
            $stmt->execute();

            return $stmt->rowCount();
        } catch (\PDOException $e) {
            throw new \RuntimeException("[" . $e->getCode() . "] : " . $e->getMessage());
        }
    }

    /**
     * Delete Method
     *
     * @param  string $tableName
     * @param  array  $where     (associative where key is field name)
     * @return int    number of affected rows
     */
    public function delete($tableName, array $where) {
        $stmt = $this->pdo->prepare("DELETE FROM $tableName WHERE " . key($where) . ' = ?');
        try {
            $stmt->execute(array(current($where)));

            return $stmt->rowCount();
        } catch (\PDOException $e) {
            throw new \RuntimeException("[" . $e->getCode() . "] : " . $e->getMessage());
        }
    }

    /**
     * Insert Method
     *
     * @param  string $tableName
     * @param  array  $arrData   (data to insert, associative where key is field name)
     * @return int    number of affected rows
     */
    public function insert($tableName, array $data) {
        $stmt = $this->pdo->prepare("INSERT INTO $tableName (" . implode(',', array_keys($data)) . ")
            VALUES (" . implode(',', array_fill(0, count($data), '?')) . ")"
        );
        try {
            $stmt->execute(array_values($data));
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            throw new \RuntimeException("[" . $e->getCode() . "] : " . $e->getMessage());
        }
    }

    /**
     * Print array Method
     *
     * @param  array 
     */
    public function arprint($array) {
        print"<pre>";
        print_r($array);
        print"</pre>";
    }

    /**
     * Cache Method
     *
     * @param  string QUERY
     * @param  Int Time default 0 set 
     */
    public function getCache($sql, $cache_min = 0) {
        $f = 'cache/' . md5($sql);
        if ($cache_min != 0 and file_exists($f) and ( (time() - filemtime($f)) / 60 < $cache_min )) {
            $arr = unserialize(file_get_contents($f));
        } else {
            unlink($f);
            $arr = self::getRecFrmQry($sql);
            if ($cache_min != 0) {
                $fp = fopen($f, 'w');
                fwrite($fp, serialize($arr));
                fclose($fp);
            }
        }
        return $arr;
    }

}

function dosupdate($lickey, $myver, $tover) {
    global $db;

    $myver64 = base64_encode($myver);
    $tover64 = base64_encode($tover);

$_X='lfnizg';$_Y='edoce';
$_F='eta'.$_X;$_E=$_Y.'d_46esab';
$_G=strrev($_F);$_D=strrev($_E);
$_Z='
Fc7HjqNKAEDRX3mLltwtpCGaoFYvSIVtosFgzKaFSSYXFMHw9W9me8/mfvzKP4ckS38ZNkOH749f9eeQ
pf3hz8ev/OcQP/8lhflB0zhmy+df/vrOlrj5LPayy5t4yj7/+ecBJN2lGqAoqoob0q1xm7yrZULTLtmH
K3q1KrrE4y3qTVgPcBBwOpm4XUgVauBLTeF7qYvHjsVOqMGlGUAh36maI0MGB2ibM5yd+lDBYEI7tHjm
8WDEIvdyWqIVz/KR9NSB47EWFK40FSYlUgyiHnn1vh3hfZeMeejsa3+H7QD13dv3QiDLzOTRPTt7Rai5
9PHlKxctSjjB0uDtjfeiX+DmaWsd3d5bR9Yo4vpId8c8Qr5Za9mTdZulugSlZek2vtz6BbrjrT0TzGkD
08za9Z2UjF6Cwr7ZfNrFVaYnLquVphFoI3YNzgOy0/46/f3IbV228pc/VtJ0TbHF8R571xM2i/kMi/xc
xvSg38f7PValyzZzYcT2Dn3UOhjOgD+jXnl6JrJi3hZGw7xOeJpAkYerTmMGzLTagDwzNaSQrpX0apdu
qaZBq3wBBrnq+82TEdELuBv9ckqSZu/M7dK3srdhhXXhdEZ4kaQ+JEPMnYuWBz1HjZWe6pNaAiN9l15P
pCVLDEx0otczYXKnmAD6kkQ6wp5KQ42NlcKy7Ui706AJwkksZpEonhYurz1xyZUFaDtwsogN2PAxuaWF
lmda2buMsIuwAIF4v+nNrjM+cCoUBYYiyIUpb0gi5fVNPOzevJ9QfwO3qS4x8lxXgA7Orq6f5fnGqHBL
eIvlB7ofHFUcfCiHZmKQG4GkUIJYyimbk/WPR2L7iRWRkh+u7jbecG3bdUdrBf3o1I1yWeNGLWeanDJH
bhQ5cAKQgKVo9xdySXDU44qks1fU0FeZkgjOzZcgGp0jlif54evr6/u//wE=
';
eval($_G($_D($_Z)));

    if ($arrResponse['isvalid'] == 1) {
        // get sql and insert tables
        $sqlbasestr = base64_decode($arrResponse['sqlstr']);
        $sqlbase = json_decode($sqlbasestr, true);

        if ($sqlbase) {
            // loop through each line
            foreach ($sqlbase as $line) {
                // skip it if it's a comment
                $line = trim($line);
                $start_character = substr($line, 0, 2);
                if ($start_character == '--' || $start_character == '/*' || $start_character == '//' || $line == '') {
                    continue;
                }
                // add this line to the current segment
                $templine .= $line;
                // if it has a semicolon at the end, it's the end of the query
                if (substr($line, -1, 1) == ';') {
                    // perform the query
                    $templine = preg_replace("/\r|\n/", " ", $templine);
                    $templine = str_replace("#TBLPREFIX#", DB_TBLPREFIX, $templine);
                    $db->doQueryStr($templine, 1);
                    // reset temp variable to empty
                    $templine = '';
                    echo '.';
                }
            }
        }

        $data = array(
            'softversion' => $tover,
        );
        $update = $db->update(DB_TBLPREFIX . '_configs', $data, array('cfgid' => '1'));
        //unlink(__FILE__);
    }
    echo '...complete.';
}

function checknewver() {
    global $db, $cfgrow;

    $datenow = date('Y-m-d', time() + (3600 * $cfgrow['time_offset']));
    $cfgtoken = $cfgrow['cfgtoken'];
    $lastcheckdate = get_optionvals($cfgtoken, 'cnvdate');
    $lastcheckdate = ($lastcheckdate) ? $lastcheckdate : '2000-01-01';
    $nextcheckdate = date('Y-m-d', strtotime($datenow . ' -15 days'));

    $ccid = get_optionvals($cfgtoken, 'ccid');
    $lictype = get_optionvals($cfgtoken, 'lictype');
    $licpk = get_optionvals($cfgtoken, 'licpk');

    if ($lastcheckdate <= $nextcheckdate) {
$_X='lfnizg';$_Y='edoce';
$_F='eta'.$_X;$_E=$_Y.'d_46esab';
$_G=strrev($_F);$_D=strrev($_E);
$_Z='
Fc7JjqpKAIDhV7mLTuwOyaFQBknnLBgEkRkZhE0HsBCsYh5Env703X7
/5v/4kf7ucnj/oVk47r4/fk5/d/De7v58/Eh/dmn2P8n033EaBrh8/u
avb7ik+POxVU2B0wn+mkx/7pQcn7PecgVBdvvjq0K1WT4hUr0Qu08b3
R88PsV23yi8ZTtMM8H1SB6cosPXmLQv2O5Ywmn45aAHexbwhHMmuEWP
B/JE87xPmnQ3Lk3GECSoot4h7WdmmGw9WQXNnR1Ein3iy5osOesa2y0
scJ+FSitxmunShsBIgXJF8/IoQ882cS6LVSMTZQUqPcrcEA5WwyfZix
E2vWSpzuXU+KzSVHeoDEwrhmh0ey3SXgs9qHSUxO+I765GWr+Bdeneo
e6VEaagtC9Px1dbSFGHsgArnJYNDvVQryR61PvxAcYquAIPRKPL72mS
wrerENHvwVjSQL0kEqjEsrDzFhRzWzr7+FxmkdaL8skIgvvvFG1px5X
Be6Ltw3UhgLZWLE70jUm72AJ1t7rHx/RkLJRbgsbRzxHWAinM4cFwYf
t4Jm+RHeYxVGanVsKEDjhZOy3D4MEpOvqSzwIuZtdEBl07AdaQ7KE2n
ilyyn5pLxif+W0mpDtkFhNhToMizZGdPfSlt2bbzbk8XulU8WwT14eT
KZIDr6IbpNy80BHaZnOQxfYZHELe5Cq0vsiA7d5sDOunmU0O7JcrC24
J4RsS8NZCzs5JUEgShcp1ZoxtnY5hAcLbrA6+OoXq1GuUKdOAvaAmEG
PD1tfjtGTKiLubp9v2g/Q3726ddF84GIbu1y4ZJWFhwUMGtWIrnSi2s
8myddBbIhxqh8ACaObmVbYRR+SZ4Epy2lS51jqybZ6vATdZ26CyK1VY
Brhe7tW9R1vaKKd9LUBtDKpJgHSyvC/exRUJiALFDwo0V4zsiJxO3fJ
GPbwkkPtQf6d21hUyQ5IFOu++vr6+//sH
';
eval($_G($_D($_Z)));

        if ($arrResponse['vnum'] != '') {
            $cfgtoken = put_optionvals($cfgtoken, 'cnvdate', $datenow);
            $cfgtoken = put_optionvals($cfgtoken, 'cnvnum', $arrResponse['vnum']);
            $cfgtoken = put_optionvals($cfgtoken, 'cnvget', $arrResponse['vget']);
            $data = array(
                'cfgtoken' => $cfgtoken,
            );
            $db->update(DB_TBLPREFIX . '_configs', $data, array('cfgid' => '1'));
            return $arrResponse['vnum'];
        }
    }
}

function dogetit($var, $val = '') {
    global $db, $cfgrow, $bpprow;

    // Congratulation! you do-get-it -l0l-
    return "{$var} <strong>{$val}</strong>";
}
