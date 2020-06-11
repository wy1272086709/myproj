<?php

namespace CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSocialBase
 *
 * @ORM\Table(name="cms_social_base", indexes={@ORM\Index(name="status", columns={"status"}), @ORM\Index(name="create_time", columns={"create_time"})})
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\CmsSocialBaseRepository")
 */
class CmsSocialBase
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
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=1000, nullable=false)
     */
    private $content = '';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="base_type", type="integer", nullable=false)
     */
    private $baseType = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="source", type="integer", nullable=false)
     */
    private $source = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="yidun_status", type="integer", nullable=false)
     */
    private $yidunStatus = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="manual_status", type="integer", nullable=false)
     */
    private $manualStatus = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="recommend_type", type="integer", nullable=false)
     */
    private $recommendType = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="author_id", type="integer", nullable=false)
     */
    private $authorId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="author_name", type="string", length=128, nullable=false)
     */
    private $authorName = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="author_identity", type="integer", nullable=false)
     */
    private $authorIdentity = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="author_avatar", type="string", length=255, nullable=false)
     */
    private $authorAvatar = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="auditor_id", type="integer", nullable=false)
     */
    private $auditorId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="auditor_name", type="string", length=128, nullable=false)
     */
    private $auditorName = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="recommender_id", type="integer", nullable=false)
     */
    private $recommenderId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="recommender_name", type="string", length=128, nullable=false)
     */
    private $recommenderName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="reject_reason", type="string", length=128, nullable=false)
     */
    private $rejectReason = '';

    /**
     * @var string
     *
     * @ORM\Column(name="yidun_reject_reason", type="string", length=1000, nullable=false)
     */
    private $yidunRejectReason = '';

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
     * @var integer
     *
     * @ORM\Column(name="publish_time", type="integer", nullable=false)
     */
    private $publishTime = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="recommend_time", type="integer", nullable=false)
     */
    private $recommendTime = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="city_name", type="string", nullable=false)
     */
    private $cityName = '0';


    /**
     * @var string
     *
     * @ORM\Column(name="city_id", type="string", nullable=false)
     */
    private $cityId = '0';


    /**
     * @var string
     *
     * @ORM\Column(name="content_tags", type="string", nullable=false)
     */
    private $contentTags = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="original", type="integer", nullable=false)
     */
    private $original = 0;

    /**
     * @ORM\OneToMany(targetEntity="CmsSocialImg", mappedBy="cmsSocialBase", cascade={"remove"})
     */
    private $cmsSocialImgs;

    /**
     * @ORM\OneToMany(targetEntity="CmsSocialImgTagMap", mappedBy="cmsSocialBase", cascade={"remove"})
     */
    private $cmsSocialImgTagMaps;

    /**
     * @ORM\OneToOne(targetEntity="CmsSocialImgExt", mappedBy="cmsSocialBase", cascade={"remove"})
     */
    private $cmsSocialImgExt;

    /**
     * @ORM\OneToOne(targetEntity="CmsSocialVideo", mappedBy="cmsSocialBase", cascade={"remove"})
     */
    private $cmsSocialVideo;


    public function __construct()
    {
        $this->cmsSocialImgs = new ArrayCollection();
        $this->cmsSocialImgTagMaps = new ArrayCollection();
    }



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
     * Set content
     *
     * @param string $content
     *
     * @return CmsSocialBase
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return CmsSocialBase
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }


    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set baseType
     *
     * @param integer $baseType
     *
     * @return CmsSocialBase
     */
    public function setBaseType($baseType)
    {
        $this->baseType = $baseType;

        return $this;
    }

    /**
     * Get baseType
     *
     * @return integer
     */
    public function getBaseType()
    {
        return $this->baseType;
    }

    /**
     * Set source
     *
     * @param integer $source
     *
     * @return CmsSocialBase
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return integer
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return CmsSocialBase
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set authorId
     *
     * @param integer $authorId
     *
     * @return CmsSocialBase
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * Get authorId
     *
     * @return integer
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * Set authorName
     *
     * @param string $authorName
     *
     * @return CmsSocialBase
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorIdentity
     *
     * @param integer $authorIdentity
     *
     * @return CmsSocialBase
     */
    public function setAuthorIdentity($authorIdentity)
    {
        $this->authorIdentity = $authorIdentity;

        return $this;
    }

    /**
     * Get authorIdentity
     *
     * @return integer
     */
    public function getAuthorIdentity()
    {
        return $this->authorIdentity;
    }

    /**
     * Set authorAvatar
     *
     * @param string $authorAvatar
     *
     * @return CmsSocialBase
     */
    public function setAuthorAvatar($authorAvatar)
    {
        $this->authorAvatar = $authorAvatar;

        return $this;
    }

    /**
     * Get authorAvatar
     *
     * @return string
     */
    public function getAuthorAvatar()
    {
        return $this->authorAvatar;
    }

    /**
     * Set auditorId
     *
     * @param integer $auditorId
     *
     * @return CmsSocialBase
     */
    public function setAuditorId($auditorId)
    {
        $this->auditorId = $auditorId;

        return $this;
    }

    /**
     * Get auditorId
     *
     * @return integer
     */
    public function getAuditorId()
    {
        return $this->auditorId;
    }

    /**
     * Set auditorName
     *
     * @param string $auditorName
     *
     * @return CmsSocialBase
     */
    public function setAuditorName($auditorName)
    {
        $this->auditorName = $auditorName;

        return $this;
    }

    /**
     * Get auditorName
     *
     * @return string
     */
    public function getAuditorName()
    {
        return $this->auditorName;
    }

    /**
     * Set rejectReason
     *
     * @param string $rejectReason
     *
     * @return CmsSocialBase
     */
    public function setRejectReason($rejectReason)
    {
        $this->rejectReason = $rejectReason;

        return $this;
    }

    /**
     * Get rejectReason
     *
     * @return string
     */
    public function getRejectReason()
    {
        return $this->rejectReason;
    }

    /**
     * Set createTime
     *
     * @param integer $createTime
     *
     * @return CmsSocialBase
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
     * @return CmsSocialBase
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


    /**
     * Add cmsSocialImg
     *
     * @param \CommonBundle\Entity\CmsSocialImg $cmsSocialImg
     *
     * @return CmsSocialBase
     */
    public function addCmsSocialImg(\CommonBundle\Entity\CmsSocialImg $cmsSocialImg)
    {
        $this->cmsSocialImgs[] = $cmsSocialImg;

        return $this;
    }

    /**
     * Remove cmsSocialImg
     *
     * @param \CommonBundle\Entity\CmsSocialImg $cmsSocialImg
     */
    public function removeCmsSocialImg(\CommonBundle\Entity\CmsSocialImg $cmsSocialImg)
    {
        $this->cmsSocialImgs->removeElement($cmsSocialImg);
    }

    /**
     * Get cmsSocialImgs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCmsSocialImgs()
    {
        return $this->cmsSocialImgs;
    }

    /**
     * Add cmsSocialImgTagMap
     *
     * @param \CommonBundle\Entity\CmsSocialImgTagMap $cmsSocialImgTagMap
     *
     * @return CmsSocialBase
     */
    public function addCmsSocialImgTagMap(\CommonBundle\Entity\CmsSocialImgTagMap $cmsSocialImgTagMap)
    {
        $this->cmsSocialImgTagMaps[] = $cmsSocialImgTagMap;

        return $this;
    }

    /**
     * Remove cmsSocialImgTagMap
     *
     * @param \CommonBundle\Entity\CmsSocialImgTagMap $cmsSocialImgTagMap
     */
    public function removeCmsSocialImgTagMap(\CommonBundle\Entity\CmsSocialImgTagMap $cmsSocialImgTagMap)
    {
        $this->cmsSocialImgTagMaps->removeElement($cmsSocialImgTagMap);
    }

    /**
     * Get cmsSocialImgTagMaps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCmsSocialImgTagMaps()
    {
        return $this->cmsSocialImgTagMaps;
    }

    /**
     * Set cmsSocialImgExt
     *
     * @param \CommonBundle\Entity\CmsSocialImgExt $cmsSocialImgExt
     *
     * @return CmsSocialBase
     */
    public function setCmsSocialImgExt(\CommonBundle\Entity\CmsSocialImgExt $cmsSocialImgExt = null)
    {
        $this->cmsSocialImgExt = $cmsSocialImgExt;

        return $this;
    }

    /**
     * Get cmsSocialImgExt
     *
     * @return \CommonBundle\Entity\CmsSocialImgExt
     */
    public function getCmsSocialImgExt()
    {
        return $this->cmsSocialImgExt;
    }

    /**
     * @return int
     */
    public function getYidunStatus(): int
    {
        return $this->yidunStatus;
    }

    /**
     * @param int $yidunStatus
     */
    public function setYidunStatus(int $yidunStatus)
    {
        $this->yidunStatus = $yidunStatus;
    }

    /**
     * @return int
     */
    public function getManualStatus(): int
    {
        return $this->manualStatus;
    }

    /**
     * @param int $manualStatus
     */
    public function setManualStatus(int $manualStatus)
    {
        $this->manualStatus = $manualStatus;
    }

    /**
     * @return int
     */
    public function getRecommendType(): int
    {
        return $this->recommendType;
    }

    /**
     * @param int $recommendType
     */
    public function setRecommendType(int $recommendType)
    {
        $this->recommendType = $recommendType;
    }

    /**
     * @return string
     */
    public function getYidunRejectReason(): string
    {
        return $this->yidunRejectReason;
    }

    /**
     * @param string $yidunRejectReason
     */
    public function setYidunRejectReason(string $yidunRejectReason)
    {
        $this->yidunRejectReason = $yidunRejectReason;
    }

    /**
     * @return int
     */
    public function getRecommendTime(): int
    {
        return $this->recommendTime;
    }

    /**
     * @param int $recommendTime
     */
    public function setRecommendTime(int $recommendTime)
    {
        $this->recommendTime = $recommendTime;
    }

    /**
     * @return int
     */
    public function getPublishTime(): int
    {
        return $this->publishTime;
    }

    /**
     * @param int $publishTime
     */
    public function setPublishTime(int $publishTime)
    {
        $this->publishTime = $publishTime;
    }

    /**
     * @return int
     */
    public function getRecommenderId(): int
    {
        return $this->recommenderId;
    }

    /**
     * @param int $recommenderId
     */
    public function setRecommenderId(int $recommenderId)
    {
        $this->recommenderId = $recommenderId;
    }

    /**
     * @return string
     */
    public function getRecommenderName(): string
    {
        return $this->recommenderName;
    }

    /**
     * @param string $recommenderName
     */
    public function setRecommenderName(string $recommenderName)
    {
        $this->recommenderName = $recommenderName;
    }

    /**
     * Set cityName
     *
     * @param string $cityName
     *
     * @return CmsSocialBase
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get cityName
     *
     * @return string
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Set cityId
     *
     * @param string $cityId
     *
     * @return CmsSocialBase
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return string
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set contentTags
     *
     * @param string $contentTags
     *
     * @return CmsSocialBase
     */
    public function setContentTags($contentTags)
    {
        $this->contentTags = $contentTags;

        return $this;
    }

    /**
     * Get contentTags
     *
     * @return string
     */
    public function getContentTags()
    {
        return $this->contentTags;
    }

    /**
     * Set original
     *
     * @param integer $original
     *
     * @return CmsSocialBase
     */
    public function setOriginal($original)
    {
        $this->original = $original;

        return $this;
    }

    /**
     * Get original
     *
     * @return integer
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * Set cmsSocialVideo
     *
     * @param \CommonBundle\Entity\CmsSocialVideo $cmsSocialVideo
     *
     * @return CmsSocialBase
     */
    public function setCmsSocialVideo(\CommonBundle\Entity\CmsSocialVideo $cmsSocialVideo = null)
    {
        $this->cmsSocialVideo = $cmsSocialVideo;

        return $this;
    }

    /**
     * Get cmsSocialVideo
     *
     * @return \CommonBundle\Entity\CmsSocialVideo
     */
    public function getCmsSocialVideo()
    {
        return $this->cmsSocialVideo;
    }

}
