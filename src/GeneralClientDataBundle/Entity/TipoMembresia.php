<?php

namespace GeneralClientDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

class TipoMembresia
{
    private $id;

    private $tipoMembresia;

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

