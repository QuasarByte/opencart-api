<?php

require_once("qbocrest_controller.php");

class ControllerAPIQuasarByteOCRestV1Image extends QuasarByteOCRestController
{
    public function resizeImageFile()
    {
        $this->changeErrorHandler();
        $this->load->language('api/quasarbyte/ocrest/v1/image');

        $this->checkAuthorisation();

        $this->load->model('tool/image');

        $filename = base64_decode($this->request->server['HTTP_FILENAME']);
        $width = $this->request->server['HTTP_WIDTH'];
        $height = $this->request->server['HTTP_HEIGHT'];

        $newImage = $this->model_tool_image->resize($filename, $width, $height);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(array('newImage' => $newImage)));
    }

    public function resizeImageFilesWithIndividualSize()
    {
        $this->changeErrorHandler();
        $this->load->language('api/quasarbyte/ocrest/v1/image');

        $this->checkAuthorisation();

        $this->load->model('tool/image');

        $imageFilesToResize = json_decode(file_get_contents('php://input'), true);

        $json = array();

        foreach ($imageFilesToResize as $imageFileToResize) {
            $newImageFileName = $this->model_tool_image->resize(
                $imageFileToResize['fileName'],
                $imageFileToResize['imageSize']['width'],
                $imageFileToResize['imageSize']['height']
            );

            $resizedImageFile = array();
            $resizedImageFile['fileName'] = $imageFileToResize['fileName'];
            $resizedImageFile['imageSize'] = array();
            $resizedImageFile['imageSize']['width'] = $imageFileToResize['imageSize']['width'];
            $resizedImageFile['imageSize']['height'] = $imageFileToResize['imageSize']['width'];
            $resizedImageFile['linkToImageFile'] = $newImageFileName;

            array_push($json, $resizedImageFile);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function resizeImageFilesWithCommonSize()
    {
        $this->changeErrorHandler();
        $this->load->language('api/quasarbyte/ocrest/v1/image');

        $this->checkAuthorisation();

        $this->load->model('tool/image');

        $filesToResize = json_decode(file_get_contents('php://input'), true);

        $json = array();

        $width = $filesToResize['imageSize']['width'];
        $height = $filesToResize['imageSize']['height'];
        $fileNames = $filesToResize['fileNames'];

        foreach ($fileNames as $fileName) {
            $newImage = $this->model_tool_image->resize($fileName, $width, $height);
            array_push($json, $newImage);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
