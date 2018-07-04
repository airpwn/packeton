<?php

namespace Packagist\WebBundle\Entity;

/**
 * GruopRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param User $user
     * @param Package $package
     *
     * @return array
     */
    public function getAllowedVersionByPackage(User $user, Package $package)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('acl.version')
            ->distinct(true)
            ->from('PackagistWebBundle:User', 'u')
            ->innerJoin('u.groups', 'g')
            ->innerJoin('g.aclPermissions', 'acl')
            ->innerJoin('acl.package', 'p')
            ->where($qb->expr()->eq('u.id', $user->getId()))
            ->andWhere($qb->expr()->eq('p.id', $package->getId()));

        $result = $qb->getQuery()->getResult();
        if ($result) {
            $result = \array_column($result, 'version');
        }

        return $result;
    }

    /**
     * @param User $user
     *
     * @return Package[]
     */
    public function getAllowedPackagesForUser(User $user)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('p')
            ->distinct(true)
            ->from('PackagistWebBundle:User', 'u')
            ->innerJoin('u.groups', 'g')
            ->innerJoin('g.aclPermissions', 'acl')
            ->innerJoin('acl.package', 'p')
            ->where($qb->expr()->eq('u.id', $user->getId()));

        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
