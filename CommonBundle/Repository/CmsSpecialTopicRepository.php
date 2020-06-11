<?php


namespace CommonBundle\Repository;


use CommonBundle\Entity\CmsSpecialTopic;
use CommonBundle\Service\SpecialTopicService;
use Doctrine\ORM\QueryBuilder;


class CmsSpecialTopicRepository extends BaseCmsRepository
{
    protected $tableAlias = "CmsSpecialTopic";

    /**
     * get special topic list by ids
     * @param string $ids
     * @param  array $statusArr
     * @return array
     */
    public function getSpecailTopicByIds($ids, $statusArr)
    {
        if (empty($ids)) {
            return [];
        }

        $ids = explode(',', $ids);

        return $this->createQueryBuilder($this->tableAlias)->select($this->tableAlias)->where(
            "{$this->tableAlias}.id in (:id)"
        )->andWhere(
            "{$this->tableAlias}.status in (:status)"
        )->setParameters(["id" => $ids, "status" => $statusArr])->getQuery()->getArrayResult();
    }

    /**
     * get special list by db
     * @param string $ids
     * @return array
     */
    public function getSpecialTopicByIdsIgnortStatus($ids)
    {
        if (empty($ids)) {
            return [];
        }

        $ids = explode(',', $ids);

        return $this->createQueryBuilder($this->tableAlias)->select($this->tableAlias)->where(
            "{$this->tableAlias}.id in (:id)"
        )->setParameters(["id" => $ids])->getQuery()->getArrayResult();

    }

    /**
     * get latest published special topic
     * @param int $maxLen
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getLatestPublishedSpecialTopic($maxLen, $sort = 'publishTime', $order = 'desc')
    {
        $status = [SpecialTopicService::PUBLISHED_STATUS];

        return $this->createQueryBuilder($this->tableAlias)->select($this->tableAlias)->where(
            "{$this->tableAlias}.status in (:status)"
        )->orderBy("{$this->tableAlias}.{$sort}", $order)->setParameters(['status' => $status])->setFirstResult(
            0
        )->setMaxResults(
            $maxLen
        )->getQuery()->getArrayResult();

    }

    /**
     * recommend special topic to db
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function recomendSpecialTopic($id)
    {
        $critical = ['id' => $id];
        $data = [
            'recommend' => SpecialTopicService::RECOMMEND_STATUS,
            'recommendTime' => time(),
        ];

        return $this->save(CmsSpecialTopic::class, $critical, $data);
    }

    /**
     * cancel recommend special topic to db
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function cancelRecomendSpecialTopic($id)
    {

        $critical = ['id' => $id];
        $data = [
            'recommend' => SpecialTopicService::NOT_RECOMMEND_STATUS,
        ];

        return $this->save(CmsSpecialTopic::class, $critical, $data);

    }

    /**
     * publish special topic to db
     * @param $id
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function publishSpecialTopic($id, $data)
    {
        $critical = ['id' => $id];
        $data['status'] = SpecialTopicService::PUBLISHED_STATUS;
        $data['publishTime'] = time();

        return $this->save(CmsSpecialTopic::class, $critical, $data);

    }

    /**
     * cancel publish special topic to db
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function cancelPublishSpecialTopic($id)
    {
        $critical = ['id' => $id];
        $data = [
            'downTime' => time(),
            'recommend' => SpecialTopicService::NOT_RECOMMEND_STATUS,
            'status' => SpecialTopicService::CANCEL_PUBLISH_STATUS,
        ];

        return $this->save(CmsSpecialTopic::class, $critical, $data);
    }


    /**
     * get special topic list by condition
     * @param $condition
     * @param array $feild
     * @param array $orderBy
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function querySpecialTopicByCondition($condition, $feild = [], $orderBy = [], $offset = 0, $limit = 20)
    {

        $data = $this->getResultSpecialTopicByCondition($condition, $feild, $orderBy, $offset, $limit);

        if (empty($data)) {
            return [];
        }
        $allRows = $this->getCountSpecialTopicByCondition($condition);
        $result = [
            'data' => $data,
            'allRows' => $allRows,
        ];

        return $result;
    }

    /**
     * build eq condition
     * @param $condition
     * @return array
     */
    protected function buildEqCondition($condition)
    {
        if (empty($condition)) {
            return [];
        }
        $eqCondition = $condition;
        unset($eqCondition['publishStartTime']);
        unset($eqCondition['publishEndTime']);
        unset($eqCondition['title']);

        return $eqCondition;
    }

    /**
     * get special topic by condition
     * @param $condition
     * @param array $feild
     * @param array $orderBy
     * @param null $offset
     * @param null $limit
     * @return mixed
     */
    protected function buildSpecialTopicByConditionQb(
        $condition,
        $feild = [],
        $orderBy = [],
        $offset = null,
        $limit = null
    ) {
        $eqCondition = $this->buildEqCondition($condition);
        $qb = $this->buildQueryQb(CmsSpecialTopic::class, $eqCondition, $feild, $orderBy, $offset, $limit);
        $qb = $this->isPublishTimeStartSearch($qb, $condition);
        $qb = $this->isPublishTimeEndSearch($qb, $condition);
        $qb = $this->isTitleLikeSearch($qb, $condition);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * get count of special topic
     * @param $condition
     * @return int
     */
    protected function getCountSpecialTopicByCondition($condition)
    {
        $data = $this->buildSpecialTopicByConditionQb($condition);
        if (empty($data)) {
            return 0;
        }

        return \count($data);
    }

    /**
     * get special topic result by condition
     * @param $condition
     * @param array $feild
     * @param array $orderBy
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    protected function getResultSpecialTopicByCondition(
        $condition,
        $feild = [],
        $orderBy = [],
        $offset = 0,
        $limit = 20
    ) {
        return $this->buildSpecialTopicByConditionQb($condition, $feild, $orderBy, $offset, $limit);
    }

    /**
     * add condition to $qb
     * @param QueryBuilder $qb
     * @param $condition
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function isPublishTimeStartSearch($qb, $condition)
    {
        if (isset($condition['publishStartTime']) && $condition['publishStartTime']) {
            $tableKey = $this->buildTableKey('publishTime');
            $value = strtotime($condition['publishStartTime']);
            $placeholder = $this->buildPlaceHolder("publishStartTime");
            $qb->andWhere(
                $qb->expr()->gte($tableKey, $placeholder)
            );
            $qb->setParameter($placeholder, $value);
        }

        return $qb;
    }

    /**
     * get publish time end search
     * @param QueryBuilder $qb
     * @param $condition
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function isPublishTimeEndSearch($qb, $condition)
    {

        if (isset($condition['publishEndTime']) && $condition['publishEndTime']) {
            $tableKey = $this->buildTableKey('publishTime');
            $value = strtotime($condition['publishEndTime']);
            $placeholder = $this->buildPlaceHolder("publishEndTime");
            $qb->andWhere(
                $qb->expr()->lte($tableKey, $placeholder)
            );
            $qb->setParameter($placeholder, $value);
        }

        return $qb;
    }

    /**
     * build title like search condition
     * @param QueryBuilder $qb
     * @param $condition
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function isTitleLikeSearch($qb, $condition)
    {
        if (isset($condition['title']) && $condition['title']) {
            $where = [
                'title' => $condition['title'],
            ];
            $qb = $this->buildLikeQueryBuilder($qb, $where);
        }

        return $qb;
    }


}