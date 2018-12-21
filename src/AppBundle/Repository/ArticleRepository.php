<?php
/**
 * Created by PhpStorm.
 * User: Antho
 * Date: 20/12/2018
 * Time: 17:28
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ArticleRepository extends EntityRepository
{

    public function getArticles($page = 1, $maxperpage = 3){

        $q = $this->_em->createQueryBuilder()
            ->select('article')
            ->from('AppBundle:Article','article')
            ->orderBy('article.published','DESC');

        $q->setFirstResult(($page - 1) * $maxperpage)
            ->setMaxResults($maxperpage);

        return new Paginator($q);
    }

    public function countPublishedArticles(){
        $q = $this->_em->createQueryBuilder()
            ->select('count(article)')
            ->from('AppBundle:Article', 'article');

        $count = $q->getQuery()->getSingleScalarResult();

        return $count;
    }
}