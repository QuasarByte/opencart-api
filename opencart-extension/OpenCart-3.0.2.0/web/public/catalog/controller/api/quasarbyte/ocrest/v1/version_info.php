<?php

class VersionInfo
{

    private $major;
    private $minor;
    private $release;
    private $build;

    /**
     * @return mixed
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * @param mixed $major
     */
    public function setMajor($major)
    {
        $this->major = $major;
    }

    /**
     * @return mixed
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * @param mixed $minor
     */
    public function setMinor($minor)
    {
        $this->minor = $minor;
    }

    /**
     * @return mixed
     */
    public function getRelease()
    {
        return $this->release;
    }

    /**
     * @param mixed $release
     */
    public function setRelease($release)
    {
        $this->release = $release;
    }

    /**
     * @return mixed
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @param mixed $build
     */
    public function setBuild($build)
    {
        $this->build = $build;
    }

}