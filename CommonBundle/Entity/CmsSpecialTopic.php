<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSpecialTopic
 *
 * @ORM\Table(name="cms_special_topic", indexes={@ORM\Index(name="publish_index", columns={"publish_time"})})
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\CmsSpecialTopicRepository")
 */
class CmsSpecialTopic
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
     * @ORM\Column(name="title", type="string", length=30, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=100, nullable=false)
     */
    private $cover = '';

    /**
     * @var string
     *
     * @ORM\Column(name="`desc`", type="string", length=350, nullable=false)
     */
    private $desc = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="publish_uid", type="integer", nullable=false)
     */
    private $publishUid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="publish_user_name", type="string", length=50, nullable=false)
     */
    private $publishUserName = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="recommend", type="integer", nullable=false)
     */
    private $recommend = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="recommend_time", type="integer", nullable=false)
     */
    private $recommendTime = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="publish_time", type="integer", nullable=false)
     */
    private $publishTime = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="down_time", type="integer", nullable=false)
     */
    private $downTime = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", nullable=false)
     */
    private $createTime = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="edit_time", type="integer", nullable=false)
     */
    private $editTime = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="content_num", type="integer", nullable=false)
     */
    private $contentNum = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer", nullable=false)
     */
    private $width = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", nullable=false)
     */
    private $height = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="create_uid", type="integer", nullable=false)
     */
    private $createUid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="create_user_name", type="string", length=50, nullable=false)
     */
    private $createUserName = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="string", length=200, nullable=true)
     */
    private $thumbnail = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="thumbnail_width", type="integer", nullable=true)
     */
    private $thumbnailWidth = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="thumbnail_height", type="integer", nullable=true)
     */
    private $thumbnailHeight = '0';



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
     * Set title
     *
     * @param string $title
     *
     * @return CmsSpecialTopic
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
     * Set cover
     *
     * @param string $cover
     *
     * @return CmsSpecialTopic
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set desc
     *
     * @param string $desc
     *
     * @return CmsSpecialTopic
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get desc
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set publishUid
     *
     * @param integer $publishUid
     *
     * @return CmsSpecialTopic
     */
    public function setPublishUid($publishUid)
    {
        $this->publishUid = $publishUid;

        return $this;
    }

    /**
     * Get publishUid
     *
     * @return integer
     */
    public function getPublishUid()
    {
        return $this->publishUid;
    }

    /**
     * Set publishUserName
     *
     * @param string $publishUserName
     *
     * @return CmsSpecialTopic
     */
    public function setPublishUserName($publishUserName)
    {
        $this->publishUserName = $publishUserName;

        return $this;
    }

    /**
     * Get publishUserName
     *
     * @return string
     */
    public function getPublishUserName()
    {
        return $this->publishUserName;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return CmsSpecialTopic
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
     * Set recommend
     *
     * @param integer $recommend
     *
     * @return CmsSpecialTopic
     */
    public function setRecommend($recommend)
    {
        $this->recommend = $recommend;

        return $this;
    }

    /**
     * Get recommend
     *
     * @return integer
     */
    public function getRecommend()
    {
        return $this->recommend;
    }

    /**
     * Set recommendTime
     *
     * @param integer $recommendTime
     *
     * @return CmsSpecialTopic
     */
    public function setRecommendTime($recommendTime)
    {
        $this->recommendTime = $recommendTime;

        return $this;
    }

    /**
     * Get recommendTime
     *
     * @return integer
     */
    public function getRecommendTime()
    {
        return $this->recommendTime;
    }

    /**
     * Set publishTime
     *
     * @param integer $publishTime
     *
     * @return CmsSpecialTopic
     */
    public function setPublishTime($publishTime)
    {
        $this->publishTime = $publishTime;

        return $this;
    }

    /**
     * Get publishTime
     *
     * @return integer
     */
    public function getPublishTime()
    {
        return $this->publishTime;
    }

    /**
     * Set downTime
     *
     * @param integer $downTime
     *
     * @return CmsSpecialTopic
     */
    public function setDownTime($downTime)
    {
        $this->downTime = $downTime;

        return $this;
    }

    /**
     * Get downTime
     *
     * @return integer
     */
    public function getDownTime()
    {
        return $this->downTime;
    }

    /**
     * Set createTime
     *
     * @param integer $createTime
     *
     * @return CmsSpecialTopic
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
     * Set editTime
     *
     * @param integer $editTime
     *
     * @return CmsSpecialTopic
     */
    public function setEditTime($editTime)
    {
        $this->editTime = $editTime;

        return $this;
    }

    /**
     * Get editTime
     *
     * @return integer
     */
    public function getEditTime()
    {
        return $this->editTime;
    }

    /**
     * Set contentNum
     *
     * @param integer $contentNum
     *
     * @return CmsSpecialTopic
     */
    public function setContentNum($contentNum)
    {
        $this->contentNum = $contentNum;

        return $this;
    }

    /**
     * Get contentNum
     *
     * @return integer
     */
    public function getContentNum()
    {
        return $this->contentNum;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return CmsSpecialTopic
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return CmsSpecialTopic
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set createUid
     *
     * @param integer $createUid
     *
     * @return CmsSpecialTopic
     */
    public function setCreateUid($createUid)
    {
        $this->createUid = $createUid;

        return $this;
    }

    /**
     * Get createUid
     *
     * @return integer
     */
    public function getCreateUid()
    {
        return $this->createUid;
    }

    /**
     * Set createUserName
     *
     * @param string $createUserName
     *
     * @return CmsSpecialTopic
     */
    public function setCreateUserName($createUserName)
    {
        $this->createUserName = $createUserName;

        return $this;
    }

    /**
     * Get createUserName
     *
     * @return string
     */
    public function getCreateUserName()
    {
        return $this->createUserName;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return CmsSpecialTopic
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set thumbnailWidth
     *
     * @param integer $thumbnailWidth
     *
     * @return CmsSpecialTopic
     */
    public function setThumbnailWidth($thumbnailWidth)
    {
        $this->thumbnailWidth = $thumbnailWidth;

        return $this;
    }

    /**
     * Get thumbnailWidth
     *
     * @return integer
     */
    public function getThumbnailWidth()
    {
        return $this->thumbnailWidth;
    }

    /**
     * Set thumbnailHeight
     *
     * @param integer $thumbnailHeight
     *
     * @return CmsSpecialTopic
     */
    public function setThumbnailHeight($thumbnailHeight)
    {
        $this->thumbnailHeight = $thumbnailHeight;

        return $this;
    }

    /**
     * Get thumbnailHeight
     *
     * @return integer
     */
    public function getThumbnailHeight()
    {
        return $this->thumbnailHeight;
    }
}
