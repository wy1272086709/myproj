<?php
namespace CommonBundle\Repository;
use CommonBundle\Entity\To8toComment;
use Doctrine\ORM\QueryBuilder;

class To8toCommentRepository extends BaseCmsRepository
{
    protected $tableAlias = "comment";
    protected $entityClassName = To8toComment::class;
    //to8to_comment 表中PC 问吧对应的comtype 枚举值
    const PC_ANSWER = 22;
    //装修家居
    const PC_ZXGL_ZXJJ = 23;
    //选材手册
    const PC_ZXGL_XCSC = 31;
    //易盾中对应的问吧对应的module_code
    const MODULECODE_ANSWER_COMMENT  = 'answerComment';
    //易盾中对应的装修攻略对应的module_code
    const MODULECODE_ZXGL_COMMENT = 'zxglComment';

    /**
     * get comment page list by param condition, perge, page
     * 获取分页的评论列表
     * 通过评论人ID来查询,或者查询PC问吧评论，都需要连表来查询结果
     * @param $condition array 查询条件
     * @param $perPage
     * @param $page
     */
    public function queryPageListComment(array $condition, $perPage = 10, $page = 1)
    {
        $comType= $condition['comtype'];
        $select = $this->getSelectFields($comType);
        if (!empty($condition['commentatorId']) && $comType!=self::PC_ANSWER)
        {
            return [];
        }
        if ($comType == self::PC_ANSWER) {
            $queryBuilder = $this->createQueryBuilder($this->tableAlias)->select(implode(',', $select));
                //->join($this->tableAlias . '.to8toAnswer', 'to8toAnswer');
        }
        else
        {
            $queryBuilder = $this->createQueryBuilder($this->tableAlias)->select(implode(',', $select));
        }
        $queryBuilder->andWhere("{$this->tableAlias}.comtype={$comType}");
        $this->addCommentQueryCondition($queryBuilder, $condition);
        $queryObj = $queryBuilder->orderBy($this->tableAlias . '.puttime', 'desc')
        ->setFirstResult($perPage * ($page - 1))
        ->setMaxResults($perPage)
        ->getQuery();
        //echo $queryObj->getSQL();
        $result = $queryObj->getArrayResult();
        return $result && is_array($result) ? $result : [];
    }

    /**
     * get pc comment list count by condition
     * @param array $condition
     * @return int
     */
    public function queryPageListCount(array $condition)
    {
        $comType= $condition['comtype'];
        if (!empty($condition['commentatorId']) && $comType!=self::PC_ANSWER)
        {
            return 0;
        }
        $select = "COUNT(DISTINCT $this->tableAlias.comid)";
        if ($comType == self::PC_ANSWER)
        {
            $queryBuilder = $this->createQueryBuilder($this->tableAlias)->select($select)
                //->join($this->tableAlias . '.to8toAnswer', 'to8toAnswer')
                ->where(" {$this->tableAlias}.comtype={$comType}");
        }
        else
        {
            $queryBuilder = $this->createQueryBuilder($this->tableAlias)->select($select);
        }
        $queryBuilder->andWhere("{$this->tableAlias}.comtype={$comType}");
        $this->addCommentQueryCondition($queryBuilder, $condition);
        $queryBuilder->setMaxResults(1);
        $count = $queryBuilder->getQuery()->getSingleScalarResult();
        return (int)$count;
    }

    /**
     * get select fields
     * @param $comType
     * @return array
     */
    private function getSelectFields($comType)
    {
        $select = $commentSelect = [
            $this->tableAlias.'.comid',
            $this->tableAlias.'.oid',
            $this->tableAlias.'.uid',
            $this->tableAlias.'.hostid AS commentatorId',
            $this->tableAlias.'.puttime',
            $this->tableAlias.'.content',
            $this->tableAlias.'.url',
            $this->tableAlias.'.comtype',
            $this->tableAlias.'.url',
            $this->tableAlias.'.ishidden',
        ];
        /*answerSelect = [
            'to8toAnswer.anid',
            'to8toAnswer.askId',
            'to8toAnswer.uid AS commentatorId'
        ];
        $select = array_merge($commentSelect, $answerSelect);*/
        return $comType == self::PC_ANSWER?$select: $commentSelect;
    }

    /**
     * add condition to $queryBuilder
     * @param QueryBuilder $queryBuilder
     * @param $condition
     */
    private function addCommentQueryCondition(QueryBuilder $queryBuilder, $condition)
    {
        $commentatorId = !empty($condition['commentatorId'])?$condition['commentatorId']: '';
        //用户ID
        $uid    = !empty($condition['uid'])?$condition['uid']: '';
        //评论ID
        $comId = !empty($condition['comid'])?$condition['comid']: '';
        //评论对象ID
        $oId    = !empty($condition['oid'])? $condition['oid']: '';
        //搜索内容
        $content   = !empty($condition['content'])? trim($condition['content']): '';
        //开始日期
        $beginTime = !empty($condition['beginTime'])? strtotime($condition['beginTime']. ' 00:00:00'): 0;
        //结束日期
        $endTime   = !empty($condition['endTime'])? strtotime($condition['endTime']. ' 23:59:59'): 0;
        $isHidden  = !empty($condition['isHidden'])? $condition['isHidden']: 0;
        $queryBuilder->andWhere("{$this->tableAlias}.ishidden = (:isHidden)")
            ->setParameter('isHidden', $isHidden);
        if ($commentatorId)
        {
            $queryBuilder->andWhere("{$this->tableAlias}.hostid = (:hostid)")
                ->setParameter('hostid', $commentatorId);
        }
        else if ($uid)
        {
            $queryBuilder->andWhere("{$this->tableAlias}.uid = (:uid)")
                ->setParameter('uid', $uid);
        }
        else if ($comId)
        {
            $queryBuilder->andWhere("{$this->tableAlias}.comid = (:comid)")
                ->setParameter('comid', $comId);
        }
        else if ($oId)
        {
            $queryBuilder->andWhere("{$this->tableAlias}.oid = (:oid)")
                ->setParameter('oid', $oId);
        }
        if ($beginTime && $endTime)
        {
            $queryBuilder->andWhere("{$this->tableAlias}.puttime > = (:start) AND 
            {$this->tableAlias}.puttime < = (:end)")
                ->setParameter('start', $beginTime)
                ->setParameter('end', $endTime);
        }
        if ($content)
        {
            $queryBuilder->andWhere("{$this->tableAlias}.content LIKE :content")
                ->setParameter('content', '%'.$content. '%');
        }
    }

    /**
     * set status pass or hidden
     * 更改状态为通过或者不通过
     * @param $comIds array
     * @param int $isHidden
     * @return array
     */
    public function setPassStatus($comIds, $isHidden = 0)
    {
        if (!is_array($comIds)&& $comIds)
        {
            $comIds = (array) $comIds;
        }
        $map = [
            'comid' => $comIds
        ];
        $saveData = [
            'ishidden' => $isHidden
        ];
        return $this->save($this->entityClassName, $map, $saveData);
    }

    /**
     * get module_code by comtype
     * @param $comType
     * @return mixed
     */
    public function getModuleCodeByComtype($comType)
    {
        $moduleCodeMap = [
            self::PC_ANSWER    => self::MODULECODE_ANSWER_COMMENT,
            self::PC_ZXGL_ZXJJ => self::MODULECODE_ZXGL_COMMENT,
            self::PC_ZXGL_XCSC => self::MODULECODE_ZXGL_COMMENT
        ];
        return $moduleCodeMap[$comType];
    }

}