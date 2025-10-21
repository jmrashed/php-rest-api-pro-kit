<?php

namespace App\DebugBar;

use App\DebugBar\Collectors\QueryCollector;
use PDO;
use PDOStatement;

class DebugPDO extends PDO
{
    private $queryCollector;

    public function __construct($dsn, $username = null, $password = null, $options = null)
    {
        parent::__construct($dsn, $username, $password, $options);
        $this->queryCollector = DebugBar::getInstance()->getCollector('queries');
    }

    #[\ReturnTypeWillChange]
    public function prepare($statement, $driver_options = [])
    {
        $stmt = parent::prepare($statement, $driver_options);
        return new DebugPDOStatement($stmt, $statement, $this->queryCollector);
    }

    #[\ReturnTypeWillChange]
    public function exec($statement)
    {
        $start = microtime(true);
        $result = parent::exec($statement);
        $time = microtime(true) - $start;
        
        if ($this->queryCollector) {
            $this->queryCollector->addQuery($statement, [], $time);
        }
        
        return $result;
    }

    #[\ReturnTypeWillChange]
    public function query($statement, $mode = null, ...$fetch_mode_args)
    {
        $start = microtime(true);
        if ($mode === null) {
            $result = parent::query($statement);
        } else {
            $result = parent::query($statement, $mode, ...$fetch_mode_args);
        }
        $time = microtime(true) - $start;
        
        if ($this->queryCollector) {
            $this->queryCollector->addQuery($statement, [], $time);
        }
        
        return $result;
    }
}

class DebugPDOStatement
{
    private $stmt;
    private $sql;
    private $queryCollector;

    public function __construct(PDOStatement $stmt, string $sql, ?QueryCollector $queryCollector)
    {
        $this->stmt = $stmt;
        $this->sql = $sql;
        $this->queryCollector = $queryCollector;
    }

    public function execute($input_parameters = null)
    {
        $start = microtime(true);
        $result = $this->stmt->execute($input_parameters);
        $time = microtime(true) - $start;
        
        if ($this->queryCollector) {
            $this->queryCollector->addQuery($this->sql, $input_parameters ?? [], $time);
        }
        
        return $result;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->stmt, $method], $args);
    }
}