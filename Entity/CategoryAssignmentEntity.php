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

namespace Kaikmedia\EventsModule\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zikula\CategoriesModule\Entity\AbstractCategoryAssignment;

/**
 * Events entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="km_events_category",
 *            uniqueConstraints={@ORM\UniqueConstraint(name="cat_unq",columns={"registryId", "categoryId", "entityId"})})
 */
class CategoryAssignmentEntity extends AbstractCategoryAssignment
{
    /**
     * @ORM\ManyToOne(targetEntity="Kaikmedia\EventsModule\Entity\EventEntity", inversedBy="categoryAssignments")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id")
     * @var \Kaikmedia\EventsModule\Entity\EventEntity
     */
    private $entity;

    /**
     * Set entity
     *
     * @return \Kaikmedia\EventsModule\Entity\EventEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entity
     *
     * @param \Kaikmedia\EventsModule\Entity\EventEntity $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}