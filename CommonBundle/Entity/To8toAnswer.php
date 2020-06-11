<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * To8toAnswer
 *
 * @ORM\Table(name="to8to_answer", indexes={@ORM\Index(name="ask_id", columns={"ask_id", "uid"}), @ORM\Index(name="askid", columns={"ask_id"}), @ORM\Index(name="uid", columns={"uid", "accept"}), @ORM\Index(name="answerdate", columns={"answerdate"}), @ORM\Index(name="askidatime", columns={"ask_id", "answerdate"})})
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\To8toAnswerRepository")
 */
class To8toAnswer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="anid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $anid;

    /**
     * @var integer
     *
     * @ORM\Column(name="ask_id", type="integer", nullable=false)
     */
    private $askId;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=200, nullable=false)
     */
    private $filename = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="vote", type="integer", nullable=false)
     */
    private $vote = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="answerdate", type="integer", nullable=false)
     */
    private $answerdate = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="accept", type="integer", nullable=false)
     */
    private $accept = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="add_content", type="string", length=255, nullable=false)
     */
    private $addContent = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="onvoting", type="integer", nullable=false)
     */
    private $onvoting = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="tid", type="integer", nullable=false)
     */
    private $tid = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="pass", type="integer", nullable=false)
     */
    private $pass = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="like_num", type="integer", nullable=true)
     */
    private $likeNum = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="check", type="integer", nullable=true)
     */
    private $check = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="indentity", type="integer", nullable=false)
     */
    private $indentity = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="check_time", type="integer", nullable=true)
     */
    private $checkTime = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="town_id", type="integer", nullable=false)
     */
    private $townId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="original_degree", type="integer", nullable=false)
     */
    private $originalDegree = '0';

    /**
     * @ORM\OneToMany(targetEntity="To8toComment", mappedBy="to8toComment", cascade={"remove"})
     */
    private $to8toComment;

    /**
     * Get anid
     *
     * @return integer
     */
    public function getAnid()
    {
        return $this->anid;
    }

    /**
     * Set askId
     *
     * @param integer $askId
     *
     * @return To8toAnswer
     */
    public function setAskId($askId)
    {
        $this->askId = $askId;

        return $this;
    }

    /**
     * Get askId
     *
     * @return integer
     */
    public function getAskId()
    {
        return $this->askId;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     *
     * @return To8toAnswer
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return integer
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return To8toAnswer
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
     * Set filename
     *
     * @param string $filename
     *
     * @return To8toAnswer
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set vote
     *
     * @param integer $vote
     *
     * @return To8toAnswer
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return integer
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set answerdate
     *
     * @param integer $answerdate
     *
     * @return To8toAnswer
     */
    public function setAnswerdate($answerdate)
    {
        $this->answerdate = $answerdate;

        return $this;
    }

    /**
     * Get answerdate
     *
     * @return integer
     */
    public function getAnswerdate()
    {
        return $this->answerdate;
    }

    /**
     * Set accept
     *
     * @param integer $accept
     *
     * @return To8toAnswer
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * Get accept
     *
     * @return integer
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Set addContent
     *
     * @param string $addContent
     *
     * @return To8toAnswer
     */
    public function setAddContent($addContent)
    {
        $this->addContent = $addContent;

        return $this;
    }

    /**
     * Get addContent
     *
     * @return string
     */
    public function getAddContent()
    {
        return $this->addContent;
    }

    /**
     * Set onvoting
     *
     * @param integer $onvoting
     *
     * @return To8toAnswer
     */
    public function setOnvoting($onvoting)
    {
        $this->onvoting = $onvoting;

        return $this;
    }

    /**
     * Get onvoting
     *
     * @return integer
     */
    public function getOnvoting()
    {
        return $this->onvoting;
    }

    /**
     * Set tid
     *
     * @param integer $tid
     *
     * @return To8toAnswer
     */
    public function setTid($tid)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * Get tid
     *
     * @return integer
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Set pass
     *
     * @param integer $pass
     *
     * @return To8toAnswer
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get pass
     *
     * @return integer
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set likeNum
     *
     * @param integer $likeNum
     *
     * @return To8toAnswer
     */
    public function setLikeNum($likeNum)
    {
        $this->likeNum = $likeNum;

        return $this;
    }

    /**
     * Get likeNum
     *
     * @return integer
     */
    public function getLikeNum()
    {
        return $this->likeNum;
    }

    /**
     * Set check
     *
     * @param integer $check
     *
     * @return To8toAnswer
     */
    public function setCheck($check)
    {
        $this->check = $check;

        return $this;
    }

    /**
     * Get check
     *
     * @return integer
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * Set indentity
     *
     * @param integer $indentity
     *
     * @return To8toAnswer
     */
    public function setIndentity($indentity)
    {
        $this->indentity = $indentity;

        return $this;
    }

    /**
     * Get indentity
     *
     * @return integer
     */
    public function getIndentity()
    {
        return $this->indentity;
    }

    /**
     * Set checkTime
     *
     * @param integer $checkTime
     *
     * @return To8toAnswer
     */
    public function setCheckTime($checkTime)
    {
        $this->checkTime = $checkTime;

        return $this;
    }

    /**
     * Get checkTime
     *
     * @return integer
     */
    public function getCheckTime()
    {
        return $this->checkTime;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return To8toAnswer
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set townId
     *
     * @param integer $townId
     *
     * @return To8toAnswer
     */
    public function setTownId($townId)
    {
        $this->townId = $townId;

        return $this;
    }

    /**
     * Get townId
     *
     * @return integer
     */
    public function getTownId()
    {
        return $this->townId;
    }

    /**
     * Set originalDegree
     *
     * @param integer $originalDegree
     *
     * @return To8toAnswer
     */
    public function setOriginalDegree($originalDegree)
    {
        $this->originalDegree = $originalDegree;

        return $this;
    }

    /**
     * Get originalDegree
     *
     * @return integer
     */
    public function getOriginalDegree()
    {
        return $this->originalDegree;
    }

    public function setTo8toComment(To8toComment $comment)
    {
        $this->to8toComment = $comment;
    }

    public function getTo8toComment()
    {
        return $this->to8toComment;
    }
}
