<?php

namespace CommonBundle\Service;

class To8toDbService extends BaseDbService
{
    const TO8TO_ENTITY = 'to8to';
    public function getEntityManager($name=null){
        return $this->getDoctrine()->getManager(self::TO8TO_ENTITY);
    }
}