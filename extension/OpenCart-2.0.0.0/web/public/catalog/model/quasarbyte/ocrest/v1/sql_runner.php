<?php

require_once("database_exception.php");
require_once("sql_exception.php");
require_once("pdo_factory.php");

class ModelQuasarByteOCRestV1SQLRunner extends Model
{

    private $pdo;

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('quasarbyte/ocrest/v1/pdo_factory');

        $this->pdo = $this->model_quasarbyte_ocrest_v1_pdo_factory->getPdoInstance();
    }

    public function query($sql, $params = array())
    {
        $statement = $this->pdo->prepare($sql);

        $result = false;

        try {
            if ($statement && $statement->execute($params)) {

                $data = array();

                while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->rows = $data;
                $result->rowCount = $statement->rowCount();

                $result->columnCount = $statement->columnCount();

                $result->columnsMeta = $this->getColumnsMeta($statement);

                $errorInfo = $statement->errorInfo();

                $result->sqlState = $errorInfo[0];
                $result->dbDriverErrorCode = $errorInfo[1];
                $result->dbDriverErrorMessage = $errorInfo[2];

                $result->success = $result->sqlState == \PDO::ERR_NONE || $result->dbDriverErrorCode == null;

            }

        } catch (\PDOException $e) {
            throw $this->createSqlException($statement, $e, $sql, $params);
        }

        if ($result) {
            return $result;
        } else {

            $result = new \stdClass();

            $result->rows = array();

            $result->rowCount = 0;

            $result->columnCount = 0;

            $result->columnsMeta = array();

            $result->errorCode = $statement->errorCode();

            $errorInfo = $statement->errorInfo();

            $result->sqlState = $errorInfo[0];
            $result->dbDriverErrorCode = $errorInfo[1];
            $result->dbDriverErrorMessage = $errorInfo[2];

            $result->success = $result->sqlState == \PDO::ERR_NONE;

            return $result;
        }
    }

    public function parameterizedQuery($sql, $sqlParameters)
    {
        $statement = $this->pdo->prepare($sql);

        foreach ($sqlParameters as $sqlParameter) {
            $bindingValueResult = $statement->bindValue($sqlParameter['name'], $sqlParameter['value'], $sqlParameter['type']);

            if (!$bindingValueResult) {

                $result = new \stdClass();

                $result->rows = array();

                $result->rowCount = 0;

                $result->columnCount = 0;

                $result->columnsMeta = array();

                $result->errorCode = $statement->errorCode();

                $errorInfo = $statement->errorInfo();

                $result->sqlState = $errorInfo[0];
                $result->dbDriverErrorCode = $errorInfo[1];
                $result->dbDriverErrorMessage = $errorInfo[2];

                $result->success = $result->sqlState == \PDO::ERR_NONE;

                return $result;

            }

        }

        $result = false;

        try {
            if ($statement && $statement->execute()) {

                $data = array();

                while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->rows = $data;
                $result->rowCount = $statement->rowCount();

                $result->columnCount = $statement->columnCount();

                $result->columnsMeta = $this->getColumnsMeta($statement);

                $errorInfo = $statement->errorInfo();

                $result->sqlState = $errorInfo[0];
                $result->dbDriverErrorCode = $errorInfo[1];
                $result->dbDriverErrorMessage = $errorInfo[2];

                $result->success = $result->sqlState == \PDO::ERR_NONE || $result->dbDriverErrorCode == null;

            }

        } catch (\PDOException $e) {
            throw $this->createSqlException($statement, $e, $sql, $sqlParameters);
        }

        if ($result) {
            return $result;
        } else {

            $result = new \stdClass();

            $result->rows = array();

            $result->rowCount = 0;

            $result->columnCount = 0;

            $result->columnsMeta = array();

            $result->errorCode = $statement->errorCode();

            $errorInfo = $statement->errorInfo();

            $result->sqlState = $errorInfo[0];
            $result->dbDriverErrorCode = $errorInfo[1];
            $result->dbDriverErrorMessage = $errorInfo[2];

            $result->success = $result->sqlState == \PDO::ERR_NONE;

            return $result;
        }
    }

    private function getColumnsMeta($statement)
    {
        $columnsMeta = array();

        for ($i = 0; $i < $statement->columnCount(); $i++) {
            $columnsMeta[] = $statement->getColumnMeta($i);
        }

        return $columnsMeta;
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

    private function createSqlException($statement, $e, $sql, $params)
    {
        $errorInfo = $statement->errorInfo();

        $sqlState = $errorInfo[0];
        $dbDriverErrorCode = $errorInfo[1];
        $dbDriverErrorMessage = $errorInfo[2];

        $exception = new \SqlException(
            'Can not run SQL query.' .
            ' Code: ' . $e->getCode() .
            ' Message: ' . $e->getMessage() .
            ' SQLSTATE: ' . $sqlState .
            ' DB Driver error code: ' . $dbDriverErrorCode .
            ' DB Driver error message: ' . $dbDriverErrorMessage,
            1200,
            $e
        );

        $exception->setSql($sql);
        $exception->setParams($params);
        $exception->setSqlState($sqlState);
        $exception->setDbDriverErrorCode($dbDriverErrorCode);
        $exception->setDbDriverErrorMessage($dbDriverErrorMessage);
        return $exception;
    }

}