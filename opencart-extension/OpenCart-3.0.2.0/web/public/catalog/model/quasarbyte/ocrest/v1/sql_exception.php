<?php

require_once("database_exception.php");

class SqlException extends DatabaseException
{

    private $sql = null;
    private $params = array();

    private $sqlState = null;
    private $dbDriverErrorCode = null;
    private $dbDriverErrorMessage = null;

    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return null
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param null $sql
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return null
     */
    public function getSqlState()
    {
        return $this->sqlState;
    }

    /**
     * @param null $sqlState
     */
    public function setSqlState($sqlState)
    {
        $this->sqlState = $sqlState;
    }

    /**
     * @return null
     */
    public function getDbDriverErrorCode()
    {
        return $this->dbDriverErrorCode;
    }

    /**
     * @param null $dbDriverErrorCode
     */
    public function setDbDriverErrorCode($dbDriverErrorCode)
    {
        $this->dbDriverErrorCode = $dbDriverErrorCode;
    }

    /**
     * @return null
     */
    public function getDbDriverErrorMessage()
    {
        return $this->dbDriverErrorMessage;
    }

    /**
     * @param null $dbDriverErrorMessage
     */
    public function setDbDriverErrorMessage($dbDriverErrorMessage)
    {
        $this->dbDriverErrorMessage = $dbDriverErrorMessage;
    }



}

