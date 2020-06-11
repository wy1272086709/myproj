<?php
namespace CommonBundle\Repository;

use CommonBundle\Entity\To8toAnswer;

class To8toAnswerRepository extends BaseCmsRepository
{
    public $tableAlias = "to8toAnswer";
    public $entityClassName = To8toAnswer::class;

    /**
     * get askId array by params
     * 获取问题ID数组
     * @param $comIds
     * @param array $fields
     * @return array
     */
    public function getAskIdList($comIds, $fields=[ 'askId', 'anid' ])
    {
        $query = $this->getQueryBuilder()
            ->select($this->fields($fields, $this->tableAlias))
            ->from($this->entityClassName, $this->tableAlias)
            ->where("{$this->tableAlias}.anid IN (:anids)")
            ->setParameter('anids', $comIds)
            ->getQuery();
        $result = $query->getArrayResult();
        return $result? $result: [];
    }
}