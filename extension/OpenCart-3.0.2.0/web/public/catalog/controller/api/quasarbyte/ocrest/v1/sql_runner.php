<?php

require_once("qbocrest_controller.php");

class ControllerAPIQuasarByteOCRestV1SQLRunner extends QuasarByteOCRestController
{
    public function queryGet()
    {
        $this->changeErrorHandler();
        $this->load->language('api/quasarbyte/ocrest/v1/sql_runner');

        $this->checkAuthorisation();

        $this->load->model('quasarbyte/ocrest/v1/sql_runner');

        $sql = base64_decode($this->request->server['HTTP_SQLQUERY']);

        $sqlRunResult = $this->runSql($sql);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($sqlRunResult));
    }

    public function parameterizedQueryPostBody()
    {
        $this->changeErrorHandler();
        $this->load->language('api/quasarbyte/ocrest/v1/sql_runner');

        $this->checkAuthorisation();

        $this->load->model('quasarbyte/ocrest/v1/sql_runner');

        $parameterizedSql = json_decode(file_get_contents('php://input'), true);

        $sqlRunResult = $this->runParameterizedSql($parameterizedSql);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($sqlRunResult));
    }

    public function queryPostBody()
    {
        $this->changeErrorHandler();
        $this->load->language('api/quasarbyte/ocrest/v1/sql_runner');

        $this->checkAuthorisation();

        $this->load->model('quasarbyte/ocrest/v1/sql_runner');

        $sql = file_get_contents('php://input');

        $sqlRunResult = $this->runSql($sql);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($sqlRunResult));
    }

    public function queryPostMultipartData()
    {
        $this->changeErrorHandler();
        $this->load->language('api/quasarbyte/ocrest/v1/sql_runner');

        $this->checkAuthorisation();

        $this->load->model('quasarbyte/ocrest/v1/sql_runner');

        $sql = $_POST['SQLQUERY'];

        $sqlRunResult = $this->runSql($sql);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($sqlRunResult));
    }

    public function parameterizedQuerySqlBatchPostBody()
    {
        $this->changeErrorHandler();
        $this->load->language('api/quasarbyte/ocrest/v1/sql_runner');

        $this->checkAuthorisation();

        $this->load->model('quasarbyte/ocrest/v1/sql_runner');

        $parameterizedSqlItems = json_decode(file_get_contents('php://input'), true);

        $json = array();

        foreach ($parameterizedSqlItems as $parameterizedSqlItem) {
            $code = $parameterizedSqlItem['code'];
            $parameterizedSql = $parameterizedSqlItem['parameterizedSql'];

            $sqlRunResult = $this->runParameterizedSql($parameterizedSql);

            $sqlRunItemResult = array();

            $sqlRunItemResult['code'] = $code;

            $sqlRunItemResult['sqlRunResult'] = $sqlRunResult;

            array_push($json, $sqlRunItemResult);

            if (!$sqlRunResult['success']) {
                break;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function querySqlBatchPostBody()
    {
        $this->changeErrorHandler();
        $this->load->language('api/quasarbyte/ocrest/v1/sql_runner');

        $this->checkAuthorisation();

        $this->load->model('quasarbyte/ocrest/v1/sql_runner');

        $sqlRunItems = json_decode(file_get_contents('php://input'), true);

        $json = array();

        foreach ($sqlRunItems as $sqlRunItem) {
            $code = $sqlRunItem['code'];
            $sql = $sqlRunItem['sql'];

            $sqlRunResult = $this->runSql($sql);

            $sqlRunItemResult = array();

            $sqlRunItemResult['code'] = $code;

            $sqlRunItemResult['sqlRunResult'] = $sqlRunResult;

            array_push($json, $sqlRunItemResult);

            if (!$sqlRunResult['success']) {
                break;
            }

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function runParameterizedSql($parameterizedSql)
    {

        $sqlRunResult = array();

        try {

            $sql = $parameterizedSql['sql'];
            $sqlParameters = $parameterizedSql['sqlParameters'];

            $executionResult = $this->model_quasarbyte_ocrest_v1_sql_runner->parameterizedQuery($sql, $sqlParameters);

            $sqlRunResult['rows'] = $executionResult->rows;
            $sqlRunResult['rowCount'] = $executionResult->rowCount;

            $sqlRunResult['lastInsertId'] = $this->model_quasarbyte_ocrest_v1_sql_runner->lastInsertId();

            $sqlRunResult['columnCount'] = $executionResult->columnCount;
            $sqlRunResult['columnsMeta'] = $executionResult->columnsMeta;

            $sqlRunResult['success'] = $executionResult->success;

            $sqlRunResult['sqlState'] = $executionResult->sqlState;
            $sqlRunResult['dbDriverErrorCode'] = $executionResult->dbDriverErrorCode;
            $sqlRunResult['dbDriverErrorMessage'] = $executionResult->dbDriverErrorMessage;

        } catch (\SQLRunnerConnectionException $e) {

            $this->sendInternalError($e->getMessage());

        } catch (\SQLRunnerSqlException $e) {

            $sqlRunResult['rows'] = array();
            $sqlRunResult['rowCount'] = 0;

            $sqlRunResult['lastInsertId'] = 0;

            $sqlRunResult['columnCount'] = 0;
            $sqlRunResult['columnsMeta'] = array();

            $sqlRunResult['success'] = false;

            $sqlRunResult['sqlState'] = $e->getSqlState();
            $sqlRunResult['dbDriverErrorCode'] = $e->getDbDriverErrorCode();
            $sqlRunResult['dbDriverErrorMessage'] = $e->getDbDriverErrorMessage();

        } catch (\Exception $e) {
            $this->sendInternalError($e->getMessage());
        }

        return $sqlRunResult;

    }

    private function runSql($sql)
    {

        $sqlRunResult = array();

        try {

            $executionResult = $this->model_quasarbyte_ocrest_v1_sql_runner->query($sql);

            $sqlRunResult['rows'] = $executionResult->rows;
            $sqlRunResult['rowCount'] = $executionResult->rowCount;

            $sqlRunResult['lastInsertId'] = $this->model_quasarbyte_ocrest_v1_sql_runner->lastInsertId();

            $sqlRunResult['columnCount'] = $executionResult->columnCount;
            $sqlRunResult['columnsMeta'] = $executionResult->columnsMeta;

            $sqlRunResult['success'] = $executionResult->success;

            $sqlRunResult['sqlState'] = $executionResult->sqlState;
            $sqlRunResult['dbDriverErrorCode'] = $executionResult->dbDriverErrorCode;
            $sqlRunResult['dbDriverErrorMessage'] = $executionResult->dbDriverErrorMessage;

        } catch (\SQLRunnerConnectionException $e) {

            $this->sendInternalError($e->getMessage());

        } catch (\SQLRunnerSqlException $e) {

            $sqlRunResult['rows'] = array();
            $sqlRunResult['rowCount'] = 0;

            $sqlRunResult['lastInsertId'] = 0;

            $sqlRunResult['columnCount'] = 0;
            $sqlRunResult['columnsMeta'] = array();

            $sqlRunResult['success'] = false;

            $sqlRunResult['sqlState'] = $e->getSqlState();
            $sqlRunResult['dbDriverErrorCode'] = $e->getDbDriverErrorCode();
            $sqlRunResult['dbDriverErrorMessage'] = $e->getDbDriverErrorMessage();

        } catch (\Exception $e) {
            $this->sendInternalError($e->getMessage());
        }

        return $sqlRunResult;

    }

}
