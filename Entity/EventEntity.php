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
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Kaikmedia\EventsModule\Entity\Base\AbstractBaseEntity;

/**
 * Events
 *
 * @ORM\Entity
 * @ORM\Table(name="km_events")
 * @ORM\Entity(repositoryClass="Kaikmedia\EventsModule\Entity\Repository\EventsRepository")
 */
class EventEntity extends AbstractBaseEntity
{
    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * categories
     *
     * @ORM\OneToMany(targetEntity="Kaikmedia\EventsModule\Entity\CategoryAssignmentEntity",
     *                mappedBy="entity", cascade={"remove", "persist"},
     *                orphanRemoval=true, fetch="EAGER")
     */
    private $categoryAssignments;

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->categoryAssignments = new ArrayCollection();
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Events
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
     * Get event category assignments
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategoryAssignments()
    {
        return $this->categoryAssignments;
    }

    /**
     * Set event category assignments
     *
     * @param ArrayCollection $assignments
     */
    public function setCategoryAssignments(ArrayCollection $assignments)
    {
        foreach ($this->categoryAssignments as $categoryAssignment) {
            if (false === $key = $this->collectionContains($assignments, $categoryAssignment)) {
                $this->categoryAssignments->removeElement($categoryAssignment);
            } else {
                $assignments->remove($key);
            }
        }

        foreach ($assignments as $assignment) {
            $this->categoryAssignments->add($assignment);
        }
    }

    /**
     * Check if a collection contains an element based only on two criteria (categoryRegistryId, category).
     * @param ArrayCollection $collection
     * @param CategoryAssignmentEntity $element
     * @return bool|int
     */
    private function collectionContains(ArrayCollection $collection, CategoryAssignmentEntity $element)
    {
        foreach ($collection as $key => $collectionAssignment) {
            /** @var \Kaikmedia\EventsModule\Entity\CategoryAssignmentEntity $collectionAssignment */
            if ($collectionAssignment->getCategoryRegistryId() == $element->getCategoryRegistryId()
                && $collectionAssignment->getCategory() == $element->getCategory()
            ) {
                return $key;
            }
        }
        return false;
    }
}
