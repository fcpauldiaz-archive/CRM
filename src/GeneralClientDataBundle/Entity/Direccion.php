<?php

namespace GeneralClientDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use ClientBundle\Entity\Cliente as ClienteEntity;

/**
 * Direccion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Direccion
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
     * @ORM\Column(name="direccion", type="string", length=75)
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity="ClientBundle\Entity\Client", inversedBy="correo")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     */
    private $cliente;

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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setCliente(ClienteEntity $cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getCliente()
    {
        return $this->cliente;
    }
}

