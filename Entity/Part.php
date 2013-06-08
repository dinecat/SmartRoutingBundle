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
 * Route named part entity class.
 * @package     DinecatSmartRoutingBundle
 * @subpackage  Entity
 * @author      Mykola Zyk <relo.san.pub@gmail.com>
 * @ORM\Table(
 *     name="dsr_part",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="dsr_part_name_idx", columns={"name"})}
 * )
 * @ORM\Entity(repositoryClass="Dinecat\SmartRoutingBundle\Entity\Repository\PartRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Part
{

    const CASE_NONE = 'none';
    const CASE_LOWER = 'lower';
    const CASE_UPPER = 'upper';
    const CASE_LETTER = 'letter';
    const CASE_CAPITALIZE = 'capitalize';

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="dsr_part_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="model", type="string", length=200, nullable=false)
     */
    private $modelName;

    /**
     * @var array
     * @ORM\Column(name="settings", type="json_array", nullable=false)
     */
    private $settings = [];

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
     * @param   string  $name
     * @param   string  $modelName
     */
    public function __construct($name, $modelName)
    {
        $this->name = $name;
        $this->modelName = $modelName;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->settings = [
            'case' => self::CASE_NONE,
            'multilang' => false
        ];
    }

    /**
     * Get part identifier.
     * @return  integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get part name.
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get associated model name.
     * @return  string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * Set case converting rule.
     * @param   string  $case   Case converting (one of CASE_* class constant)
     * @return  static
     */
    public function setCase($case)
    {
        $this->settings['case'] = $case;
        return $this;
    }

    /**
     * Get case converting rule.
     * @return  string
     */
    public function getCase()
    {
        return $this->settings['case'];
    }

    /**
     * Set is multilang slug rule.
     * @param   boolean $isMultilang
     * @return  static
     */
    public function setMultilang($isMultilang)
    {
        $this->settings['multilang'] = (bool)$isMultilang;
        return $this;
    }

    public function isMultilang()
    {
        return $this->settings['multilang'];
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
