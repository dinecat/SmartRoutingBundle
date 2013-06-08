<?php

/**
 * This file is part of the DinecatSmartRoutingBundle package.
 * @copyright   2013 Dinecat, http://dinecat.com/
 * @license     http://dinecat.com/en/licenses/MIT.html MIT License
 * @link        https://github.com/dinecat/SmartRoutingBundle
 */

namespace Dinecat\SmartRoutingBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityNotFoundException;
use Dinecat\SmartRoutingBundle\Entity\Part;
use Dinecat\SmartRoutingBundle\Entity\Slug;

/**
 * Slug manager class.
 * @package     DinecatSmartRoutingBundle
 * @subpackage  Services
 * @author      Mykola Zyk <relo.san.pub@gmail.com>
 */
class SlugManager
{

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var Part[]
     */
    static protected $partCache = [];

    /**
     * Constructor.
     * @param   ObjectManager   $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * Add new slug.
     * @param   string  $partName
     * @param   integer $objectId
     * @param   string  $lang
     * @param   string  $name
     * @param   string  $state
     * @param   boolean $asAlternate
     */
    public function addSlug($partName, $objectId, $lang, $name, $state = Slug::STATE_ACTIVE, $asAlternate = false)
    {
        $part = $this->getPartByName($partName);

        if (!$part->isMultilang()) {
            $lang = Slug::LANG_ALL;
        }

        $items = $this->getSlugRepository()->findBy(['part' => $part, 'objectId' => $objectId]);

        $exist = false;
        foreach ($items as $item) {
            if ($item->getName() === $name && $item->getLang() === $lang) {
                switch ($state) {
                    case Slug::STATE_ACTIVE:
                        $item->activate();
                        break;
                    case Slug::STATE_OUTDATED:
                        $item->outdate();
                        break;
                    case Slug::STATE_HIDDEN:
                        $item->hide();
                        break;
                    case Slug::STATE_DELETED:
                        $item->delete();
                        break;
                    default:
                        break;
                }
                $this->em->persist($item);
                $exist = true;
            } elseif ($item->getLang() === $lang && $state === Slug::STATE_ACTIVE && !$asAlternate) {
                $item->outdate();
                $this->em->persist($item);
            }
        }

        if (!$exist) {
            $slug = new Slug($part, $objectId, $lang, $name, $state);
            $this->em->persist($slug);
        }

        $this->em->flush();
    }

    /**
     * Get object slug's.
     * @param   string  $partName
     * @param   integer $objectId
     * @return  Slug[]
     */
    public function getObjectSlugs($partName, $objectId)
    {
        return $this->getSlugRepository()->findBy(['part' => $this->getPartByName($partName), 'objectId' => $objectId]);
    }

    /**
     * Check if slug name is unique.
     * @param   string  $partName
     * @param   string  $slugName
     * @param   string  $lang
     * @param   integer $objectId   Object identifier (optional)
     * @return  boolean TRUE if slug name is unique (not exist), FALSE otherwise.
     */
    public function checkSlugName($partName, $slugName, $lang, $objectId = null)
    {
        $part = $this->getPartByName($partName);
        $slug = $this->getSlugRepository()->findOneBy([
            'part' => $part,
            'name' => $slugName,
            'lang' => $part->isMultilang() ? $lang : Slug::LANG_ALL
        ]);
        return $slug ? ($objectId && $slug->getObjectId() == $objectId ? true : false) : true;
    }

    /**
     * Find slug by name.
     * @param   string  $partName
     * @param   string  $slugName
     * @param   string  $lang
     * @return  Slug
     */
    public function findSlugByName($partName, $slugName, $lang)
    {
        $part = $this->getPartByName($partName);
        return $this->getSlugRepository()->findOneBy([
            'part' => $part,
            'name' => $slugName,
            'lang' => $part->isMultilang() ? $lang : Slug::LANG_ALL
        ]);
    }

    /**
     * Generate slug name from string.
     * @param   string  $partName
     * @param   string  $name
     * @return  string
     */
    public function prepareSlugName($partName, $name)
    {
        $part = $this->getPartByName($partName);
        switch ($part->getCase()) {
            case Part::CASE_NONE:
                break;
            case Part::CASE_LOWER:
                $name = mb_strtolower($name, 'UTF-8');
                break;
            case Part::CASE_UPPER:
                $name = mb_strtoupper($name, 'UTF-8');
                break;
            case Part::CASE_LETTER:
                $name = mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8')
                    . mb_strtolower(mb_substr($name, 1, mb_strlen($name), 'UTF-8'), 'UTF-8');
                break;
            case Part::CASE_CAPITALIZE:
                $name = mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
                break;
            default:
                break;
        }
        return $name;
    }

    /**
     * Get Part object by name.
     * @param   string  $partName
     * @return  Part
     * @throws  EntityNotFoundException If Part object not found.
     */
    public function getPartByName($partName)
    {
        if (!isset(self::$partCache[$partName])) {
            self::$partCache[$partName] = $this->getPartRepository()->findOneBy(['name' => $partName]);
        }
        if (!self::$partCache[$partName]) {
            throw new EntityNotFoundException(sprintf('Part with name "%s" not found.', $partName));
        }
        return self::$partCache[$partName];
    }

    /**
     * Get repository for Part entity.
     * @return  \Dinecat\SmartRoutingBundle\Entity\Repository\PartRepository
     */
    private function getPartRepository()
    {
        return $this->em->getRepository('DinecatSmartRoutingBundle:Part');
    }

    /**
     * Get repository for Slug entity.
     * @return  \Dinecat\SmartRoutingBundle\Entity\Repository\SlugRepository
     */
    private function getSlugRepository()
    {
        return $this->em->getRepository('DinecatSmartRoutingBundle:Slug');
    }

}
