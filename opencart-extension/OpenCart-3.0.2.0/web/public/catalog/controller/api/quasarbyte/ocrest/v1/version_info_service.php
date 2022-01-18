<?php

require_once(DIR_APPLICATION . "controller/api/quasarbyte/ocrest/v1/version_info.php");

class VersionInfoService
{

    public function getVersionInfo() {

        $version = (explode('.', VERSION));

        $versionInfo = new VersionInfo();
        $versionInfo->setMajor((int)$version[0]);
        $versionInfo->setMinor((int)$version[1]);
        $versionInfo->setRelease((int)$version[2]);
        $versionInfo->setBuild((int)$version[3]);

        return $versionInfo;
    }

}