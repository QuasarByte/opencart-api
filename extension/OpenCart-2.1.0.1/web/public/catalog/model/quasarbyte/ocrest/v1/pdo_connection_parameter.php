<?php

require_once("database_exception.php");

class ModelQuasarByteOCRestV1PdoConnectionParameter extends Model
{

    public function __construct($registry)
    {
        parent::__construct($registry);
    }

    public function getInitDBSessionSql()
    {
        return array(

            "SET CHARACTER SET utf8",
            "SET CHARACTER_SET_CONNECTION=utf8",
            "SET SQL_MODE = ''"

        );
    }

    public function getDSN()
    {
        return "mysql:host=" . $this->getHostName() .
            ";port=" . $this->getPortNumber() .
            ";dbname=" . $this->getDatabaseName();
    }

    public function getUserName()
    {
        if ($this->config->get('db_username') != null) {
            $database = $this->config->get('db_username');
        } else if (defined('DB_USERNAME')) {
            $database = constant('DB_USERNAME');
        } else {
            throw new \DatabaseException("Database user name can not be empty");
        }

        return $database;
    }

    public function getPassword()
    {
        if ($this->config->get('db_password') != null) {
            $password = $this->config->get('db_password');
        } else if (defined('DB_PASSWORD')) {
            $password = constant('DB_PASSWORD');
        } else {
            $password = '';
        }
        return $password;
    }

    protected function getPortNumber()
    {
        if ($this->config->get('db_port') != null) {
            $port = $this->config->get('db_port');
        } else if (defined('DB_PORT')) {
            $port = constant('DB_PORT');
        } else {
            $port = '3306';
        }
        return $port;
    }

    protected function getDatabaseName()
    {
        if ($this->config->get('db_database') != null) {
            $database = $this->config->get('db_database');
        } else if (defined('DB_DATABASE')) {
            $database = constant('DB_DATABASE');
        } else {
            throw new \DatabaseException("Database name can not be empty");
        }

        return $database;
    }

    protected function getHostName()
    {
        if ($this->config->get('db_hostname') != null) {
            $dbHostName = $this->config->get('db_hostname');
        } else if (defined('DB_HOSTNAME')) {
            $dbHostName = constant('DB_HOSTNAME');
        } else {
            throw new \DatabaseException("Database host name can not be empty");
        }

        return $dbHostName;
    }

}