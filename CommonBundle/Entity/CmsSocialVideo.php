<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialVideo
 *
 * @ORM\Table(name="cms_social_video", indexes={@ORM\Index(name="base_id", columns={"base_id"})})
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\CmsSocialVideoRepository")
 */
class CmsSocialVideo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="base_id", type="integer", nullable=false)
     */
    private $baseId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="video_url", type="string", length=255, nullable=false)
     */
    private $videoUrl = '';

    /**
     * @var string
     *
     * @ORM\Column(name="video_origin_url", type="string", length=255, nullable=false)
     */
    private $videoOriginUrl = '';

    /**
     * @var string
     *
     * @ORM\Column(name="video_cover_url", type="string", length=255, nullable=false)
     */
    private $videoCoverUrl = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="video_cover_width", type="integer", nullable=false)
     */
    private $videoCoverWidth = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="video_cover_height", type="integer", nullable=false)
     */
    private $videoCoverHeight = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="video_length", type="string", length=20, nullable=false)
     */
    private $videoLength = '';

    /**
     * @var string
     *
     * @ORM\Column(name="video_size", type="string", length=20, nullable=false)
     */
    private $videoSize = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="video_order", type="integer", nullable=false)
     */
    private $videoOrder = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="job_status", type="integer", nullable=false)
     */
    private $jobStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description = '';

    /**
     * @var string
     *
     * @ORM\Column(name="job_id", type="string", length=100, nullable=false)
     */
    private $jobId = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", nullable=false)
     */
    private $createTime = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="update_time", type="integer", nullable=false)
     */
    private $updateTime = '0';

    /**
     * @ORM\OneToOne(targetEntity="CmsSocialBase", inversedBy="cmsSocialVideo")
     * @ORM\JoinColumn(name="base_id", referencedColumnName="id")
     */

    private $cmsSocialBase;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set baseId
     *
     * @param integer $baseId
     *
     * @return CmsSocialVideo
     */
    public function setBaseId($baseId)
    {
        $this->baseId = $baseId;

        return $this;
    }

    /**
     * Get baseId
     *
     * @return integer
     */
    public function getBaseId()
    {
        return $this->baseId;
    }

    /**
     * Set videoUrl
     *
     * @param string $videoUrl
     *
     * @return CmsSocialVideo
     */
    public function setVideoUrl($videoUrl)
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    /**
     * Get videoUrl
     *
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->videoUrl;
    }

    /**
     * Set videoOriginUrl
     *
     * @param string $videoOriginUrl
     *
     * @return CmsSocialVideo
     */
    public function setVideoOriginUrl($videoOriginUrl)
    {
        $this->videoOriginUrl = $videoOriginUrl;

        return $this;
    }

    /**
     * Get videoOriginUrl
     *
     * @return string
     */
    public function getVideoOriginUrl()
    {
        return $this->videoOriginUrl;
    }

    /**
     * Set videoCoverUrl
     *
     * @param string $videoCoverUrl
     *
     * @return CmsSocialVideo
     */
    public function setVideoCoverUrl($videoCoverUrl)
    {
        $this->videoCoverUrl = $videoCoverUrl;

        return $this;
    }

    /**
     * Get videoCoverUrl
     *
     * @return string
     */
    public function getVideoCoverUrl()
    {
        return $this->videoCoverUrl;
    }

    /**
     * Set videoCoverWidth
     *
     * @param integer $videoCoverWidth
     *
     * @return CmsSocialVideo
     */
    public function setVideoCoverWidth($videoCoverWidth)
    {
        $this->videoCoverWidth = $videoCoverWidth;

        return $this;
    }

    /**
     * Get videoCoverWidth
     *
     * @return integer
     */
    public function getVideoCoverWidth()
    {
        return $this->videoCoverWidth;
    }

    /**
     * Set videoCoverHeight
     *
     * @param integer $videoCoverHeight
     *
     * @return CmsSocialVideo
     */
    public function setVideoCoverHeight($videoCoverHeight)
    {
        $this->videoCoverHeight = $videoCoverHeight;

        return $this;
    }

    /**
     * Get videoCoverHeight
     *
     * @return integer
     */
    public function getVideoCoverHeight()
    {
        return $this->videoCoverHeight;
    }


    /**
     * Set videoLength
     *
     * @param string $videoLength
     *
     * @return CmsSocialVideo
     */
    public function setVideoLength($videoLength)
    {
        $this->videoLength = $videoLength;

        return $this;
    }

    /**
     * Get videoLength
     *
     * @return string
     */
    public function getVideoLength()
    {
        return $this->videoLength;
    }

    /**
     * Set videoSize
     *
     * @param string $videoSize
     *
     * @return CmsSocialVideo
     */
    public function setVideoSize($videoSize)
    {
        $this->videoSize = $videoSize;

        return $this;
    }

    /**
     * Get videoSize
     *
     * @return string
     */
    public function getVideoSize()
    {
        return $this->videoSize;
    }

    /**
     * Set videoOrder
     *
     * @param integer $videoOrder
     *
     * @return CmsSocialVideo
     */
    public function setVideoOrder($videoOrder)
    {
        $this->videoOrder = $videoOrder;

        return $this;
    }

    /**
     * Get videoOrder
     *
     * @return integer
     */
    public function getVideoOrder()
    {
        return $this->videoOrder;
    }

    /**
     * Set jobStatus
     *
     * @param integer $jobStatus
     *
     * @return CmsSocialVideo
     */
    public function setJobStatus($jobStatus)
    {
        $this->jobStatus = $jobStatus;

        return $this;
    }

    /**
     * Get jobStatus
     *
     * @return integer
     */
    public function getJobStatus()
    {
        return $this->jobStatus;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CmsSocialVideo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set jobId
     *
     * @param string $jobId
     *
     * @return CmsSocialVideo
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;

        return $this;
    }

    /**
     * Get jobId
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * Set cmsSocialBase
     *
     * @param \CommonBundle\Entity\CmsSocialBase $cmsSocialBase
     *
     * @return CmsSocialVideo
     */
    public function setCmsSocialBase(\CommonBundle\Entity\CmsSocialBase $cmsSocialBase = null)
    {
        $this->cmsSocialBase = $cmsSocialBase;

        return $this;
    }

    /**
     * Get cmsSocialBase
     *
     * @return \CommonBundle\Entity\CmsSocialBase
     */
    public function getCmsSocialBase()
    {
        return $this->cmsSocialBase;
    }

    /**
     * Set createTime
     *
     * @param integer $createTime
     *
     * @return CmsSocialVideo
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return integer
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set updateTime
     *
     * @param integer $updateTime
     *
     * @return CmsSocialVideo
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return integer
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }
}
