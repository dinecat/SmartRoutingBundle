<?php

/**
 * This file is part of the DinecatSmartRoutingBundle package.
 * @copyright   2013 Dinecat, http://dinecat.com/
 * @license     http://dinecat.com/en/licenses/MIT.html MIT License
 * @link        https://github.com/dinecat/SmartRoutingBundle
 */

namespace Dinecat\SmartRoutingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Slug entity class.
 * @package     DinecatSmartRoutingBundle
 * @subpackage  Entity
 * @author      Mykola Zyk <relo.san.pub@gmail.com>
 * @ORM\Table(
 *     name="dsr_slug",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="dsr_slug_idx", columns={"part_id", "lang", "slug"})},
 *     indexes={@ORM\Index(name="dsr_object_idx", columns={"part_id", "object_id"})}
 * )
 * @ORM\Entity(repositoryClass="Dinecat\SmartRoutingBundle\Entity\Repository\SlugRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Slug
{

    const STATE_ACTIVE = 200;
    const STATE_OUTDATED = 301;
    const STATE_DELETED = 410;
    const STATE_HIDDEN = 403;

    const LANG_ALL = 'all';

    /**
     * Map of states for internal use.
     * @var array
     */
    static protected $stateMap = [
        self::STATE_ACTIVE => 'active',
        self::STATE_OUTDATED => 'outdated',
        self::STATE_DELETED => 'deleted',
        self::STATE_HIDDEN => 'hidden'
    ];

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="dsr_slug_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Part
     * @ORM\ManyToOne(targetEntity="Dinecat\SmartRoutingBundle\Entity\Part", fetch="EXTRA_LAZY", cascade={"all"})
     * @ORM\JoinColumn(name="part_id", referencedColumnName="id", nullable=false)
     */
    private $part;

    /**
     * @var integer
     * @ORM\Column(name="object_id", type="bigint", nullable=false)
     */
    private $objectId;

    /**
     * @var string
     * @ORM\Column(name="lang", type="string", length=7, nullable=false)
     */
    private $lang;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var integer
     * @ORM\Column(name="state", type="smallint", nullable=false)
     */
    private $state;

    /**
     * @var DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * Constructor.
     * @param   Part    $part
     * @param   integer $objectId   Target object identifier.
     * @param   string  $lang       Language identifier (in ISO 639-1 standard + specials).
     * @param   string  $name       Slug.
     * @param   integer $state      Slug state [optional, default STATE_ACTIVE constant].
     */
    public function __construct(Part $part, $objectId, $lang, $name, $state = self::STATE_ACTIVE)
    {
        $this->part = $part;
        $this->objectId = $objectId;
        $this->lang = $lang;
        $this->name = $name;
        $this->state = $state ? $state : self::STATE_ACTIVE;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * Get slug identifier.
     * @return  integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get associated Part object.
     * @return  Part
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * Change target object identifier.
     * @param   integer $newObjectId
     * @return  static
     */
    public function changeObjectId($newObjectId)
    {
        $this->objectId = $newObjectId;
        return $this;
    }

    /**
     * Get target object identifier.
     * @return  integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Change language.
     * @param   string  $newLang    Language identifier (in ISO 639-1 standard + specials).
     * @return  static
     */
    public function changeLang($newLang)
    {
        $this->lang = $newLang;
        return $this;
    }

    /**
     * Get language.
     * @return  string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Get slug name.
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug state to Active.
     * @return  static
     */
    public function activate()
    {
        $this->state = self::STATE_ACTIVE;
        return $this;
    }

    /**
     * Set slug state to Outdated.
     * @return  static
     */
    public function outdate()
    {
        $this->state = self::STATE_OUTDATED;
        return $this;
    }

    /**
     * Set slug state to Deleted.
     * @return  static
     */
    public function delete()
    {
        $this->state = self::STATE_DELETED;
        return $this;
    }

    /**
     * Set slug state to Hidden.
     * @return  static
     */
    public function hide()
    {
        $this->state = self::STATE_HIDDEN;
        return $this;
    }

    /**
     * Get current slug state.
     * @param   boolean $asName Return state name instead of state constant [optional, default false].
     * @return  string|integer
     */
    public function getState($asName = false)
    {
        return $asName ? self::$stateMap[$this->state] : $this->state;
    }

    /**
     * Get creation date.
     * @return  DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get date of last modification.
     * @return  DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set last modification.
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new DateTime();
    }

}
