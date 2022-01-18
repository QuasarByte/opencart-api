<?php

require_once("database_exception.php");
require_once("sql_exception.php");

class ModelQuasarByteOCRestV1PdoFactory extends Model
{

    private $pdo = null;

    public function __construct($registry)
    {
        parent::__construct($registry);
    }

    public function getPdoInstance()
    {

        if ($this->pdo != null) {

            $result = $this->pdo;

        } else {

            $this->load->model('quasarbyte/ocrest/v1/pdo_connection_parameter');

            $pdo = $this->createPDO($this->model_quasarbyte_ocrest_v1_pdo_connection_parameter);

            $this->initDBSession($pdo, $this->model_quasarbyte_ocrest_v1_pdo_connection_parameter);

            $this->pdo = $pdo;

            $result = $pdo;

        }

        return $result;

    }

    protected function createPDO($pdoConnectionParameter)
    {
        try {
            $pdo = new \PDO(
                $pdoConnectionParameter->getDSN(),
                $pdoConnectionParameter->getUserName(),
                $pdoConnectionParameter->getPassword(),
                array(
                    \PDO::ATTR_PERSISTENT => true
                )
            );
        } catch (\PDOException $e) {
            throw new \DatabaseException('Can not connect to database. Code: ' . $e->getCode() . ' Message: ' . $e->getMessage(), 1000, $e);
        }
        return $pdo;
    }

    protected function initDBSession($pdo, $pdoConnectionParameter)
    {

        $sqlArray = $pdoConnectionParameter->getInitDBSessionSql();

        foreach ($sqlArray as $sql) {
            $this->executeSqlQuery($pdo, $sql);
        }

    }

    protected function executeSqlQuery($pdo, $sql)
    {

        try {

            $affected = $pdo->exec($sql);

            if ($affected === false) {

                $err = $pdo->errorInfo();

                if ($err[0] === \PDO::ERR_NONE || $err[1] == null) {

                    return true;

                } else {

                    throw $this->createSqlException($pdo, $sql);

                }

            }

        } catch (\PDOException $e) {
            throw $this->createSqlExceptionWithParentException($pdo, $sql, $e);
        }

    }

    protected function createSqlException($pdo, $sql)
    {
        $errorInfo = $pdo->errorInfo();

        $sqlState = $errorInfo[0];
        $dbDriverErrorCode = $errorInfo[1];
        $dbDriverErrorMessage = $errorInfo[2];

        $exception = new \SqlException(
            'Can not run SQL query.' .
            ' SQL: ' . $sql .
            ' SQLSTATE: ' . $sqlState .
            ' DB Driver error code: ' . $dbDriverErrorCode .
            ' DB Driver error message: ' . $dbDriverErrorMessage,
            1100
        );

        $exception->setSql($sql);
        $exception->setSqlState($sqlState);
        $exception->setDbDriverErrorCode($dbDriverErrorCode);
        $exception->setDbDriverErrorMessage($dbDriverErrorMessage);
        return $exception;
    }

    protected function createSqlExceptionWithParentException($pdo, $sql, $e)
    {

        $errorInfo = $pdo->errorInfo();

        $sqlState = $errorInfo[0];
        $dbDriverErrorCode = $errorInfo[1];
        $dbDriverErrorMessage = $errorInfo[2];

        $exception = new \SqlException(
            'Can not run SQL query.' .
            ' Code: ' . $e->getCode() .
            ' Message: ' . $e->getMessage() .
            ' SQL: ' . $sql .
            ' SQLSTATE: ' . $sqlState .
            ' DB Driver error code: ' . $dbDriverErrorCode .
            ' DB Driver error message: ' . $dbDriverErrorMessage,
            1105,
            $e
        );

        $exception->setSql($sql);
        $exception->setSqlState($sqlState);
        $exception->setDbDriverErrorCode($dbDriverErrorCode);
        $exception->setDbDriverErrorMessage($dbDriverErrorMessage);

        return $exception;

    }

}

