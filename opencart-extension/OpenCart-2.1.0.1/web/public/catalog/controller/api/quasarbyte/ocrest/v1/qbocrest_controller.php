<?php

require_once("qbocrest_base_controller.php");

require_once(DIR_APPLICATION . "controller/api/quasarbyte/ocrest/v1/version_info_service.php");

abstract class QuasarByteOCRestController extends QuasarByteOCRestBaseController
{

    private $versionInfoService;

    function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->language('api/quasarbyte/ocrest/v1/qbocrest_controller');
        $this->versionInfoService = new VersionInfoService();
    }

    protected function isAuthorized($session)
    {
        if ($this->versionInfoService->getVersionInfo()->getMajor() >= 3) {

            //Do check manually until OC bug is fixed
            $sql = "SELECT CASE WHEN EXISTS(SELECT 1 FROM `" . DB_PREFIX . "api` `a` JOIN `"
                . DB_PREFIX . "api_session` `as` ON (a.api_id = as.api_id) JOIN "
                . DB_PREFIX . "api_ip `ai` ON (a.api_id = ai.api_id) WHERE a.status = '1' AND `as`.`session_id` = '"
                . $this->db->escape(isset($this->request->get['api_token']) ? $this->request->get['api_token'] : '')
                . "' AND ai.ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "') THEN 1 ELSE 0 END flag";

            // Make sure the IP is allowed
            $api_query = $this->db->query($sql);

            if ($api_query->rows[0]['flag'] == 1) {
                return true;
            } else {
                return false;
            }

        } else {
            //todo: for OC version 2 test and consider direct check via database also
            return isset($session->data['api_id']);
        }
    }

    protected function checkAuthorisation()
    {
        if (!$this->isAuthorized($this->session)) {
            http_response_code(401);
            $this->response->addHeader('Content-Type: application/json');
            $this->response->output();
            exit();
        }
    }

    protected function sendHTTPStatus($responseCode, $errorMessage)
    {
        http_response_code($responseCode);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(array('error' => $errorMessage)));
        $this->response->output();
        exit();
    }

    protected function sendBadRequest($errorMessage)
    {
        $this->sendHTTPStatus(400, $errorMessage);
    }

    protected function sendInternalError($errorMessage)
    {
        $this->sendHTTPStatus(500, $errorMessage);
    }

}