<?php

/**
 * KaikMedia EventsModule
 *
 * @package    KaikmediaEventsModule
 * @author     Kaik <contact@kaikmedia.com>
 * @copyright  KaikMedia
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaEvents.git
 */

namespace Kaikmedia\EventsModule\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Kaikmedia\EventsModule\Security\AccessManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zikula\CategoriesModule\Api\ApiInterface\CategoryPermissionApiInterface;
use Zikula\Core\RouteUrl;
use Zikula\SearchModule\Entity\SearchResultEntity;
use Zikula\SearchModule\SearchableInterface;

class SearchHelper implements SearchableInterface
{
    /**
     * @var bool
     */
    private $enableCategorization;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CategoryPermissionApiInterface
     */
    private $categoryPermissionApi;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var AccessManager
     */
    private $accessManager;

    /**
     * SearchHelper constructor.
     * @param EntityManagerInterface $entityManager
     * @param CategoryPermissionApiInterface $categoryPermissionApi
     * @param SessionInterface $session
     * @param bool $enableCategorization
     * @param AccessManager $accessManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryPermissionApiInterface $categoryPermissionApi,
        SessionInterface $session,
        $enableCategorization,
        AccessManager $accessManager
    ) {
        $this->entityManager = $entityManager;
        $this->categoryPermissionApi = $categoryPermissionApi;
        $this->session = $session;
        $this->enableCategorization = $enableCategorization;
        $this->accessManager = $accessManager;
    }

    /**
     * {@inheritdoc}
     */
    public function amendForm(FormBuilderInterface $form)
    {
        // not needed because `active` child object is already added and that is all that is needed.
    }

    /**
     * {@inheritdoc}
     */
    public function getResults(array $words, $searchType = 'AND', $modVars = null)
    {
        if (!$this->accessManager->hasPermission(ACCESS_READ, false)) {
            return [];
        }
        $method = ('OR' == $searchType) ? 'orX' : 'andX';
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('p')
            ->from('Kaikmedia\EventsModule\Entity\EventEntity', 'p');
        /** @var $where \Doctrine\ORM\Query\Expr\Composite */
        $where = $qb->expr()->$method();
        $i = 1;
        foreach ($words as $word) {
            $subWhere = $qb->expr()->orX();
            foreach (['p.title', 'p.content'] as $field) {
                $expr = $qb->expr()->like($field, "?$i");
                $subWhere->add($expr);
                $qb->setParameter($i, '%' . $word . '%');
                $i++;
            }
            $where->add($subWhere);
        }
        $qb->andWhere($where);
        $events = $qb->getQuery()->getResult();
        $results = [];
        /** @var $events \Zikula\EventsModule\Entity\EventEntity[] */
        foreach ($events as $event) {
            $eventPermissionCheck = $this->accessManager->hasPermission(ACCESS_OVERVIEW, false, '::', $event->getTitle() . '::' . $event->getId());
            if ($this->enableCategorization) {
                $eventPermissionCheck = $eventPermissionCheck && $this->categoryPermissionApi->hasCategoryAccess($event->getCategoryAssignments()->toArray());
            }
            if (!$eventPermissionCheck) {
                continue;
            }
            $result = new SearchResultEntity();
            $result->setTitle($event->getTitle())
                ->setText($event->getContent())
                ->setModule('KaikmediaEventsModule')
                ->setCreated($event->getCreatedAt())
                ->setUrl(RouteUrl::createFromRoute('kaikmediaeventsmodule_event_display', ['urltitle' => $event->getUrltitle()]))
                ->setSesid($this->session->getId());
            $results[] = $result;
        }
        return $results;
    }

    public function getErrors()
    {
        return [];
    }
}
