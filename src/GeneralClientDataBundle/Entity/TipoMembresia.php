<?php

namespace GeneralClientDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TipoMembresia
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TipoMembresia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoMembresia", type="string", length=25)
     */
    private $tipoMembresia;

    /**
     * @ORM\OneToMany(targetEntity="ClientBundle\Entity\Client", mappedBy="tipoMembresia")
     */
    private $clientes;

    public function __construct()
    {
        $this->clientes = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tipoMembresia
     *
     * @param string $tipoMembresia
     *
     * @return TipoMembresia
     */
    public function setTipoMembresia($tipoMembresia)
    {
        $this->tipoMembresia = $tipoMembresia;

        return $this;
    }

    /**
     * Get tipoMembresia
     *
     * @return string
     */
    public function getTipoMembresia()
    {
        return $this->tipoMembresia;
    }
}

