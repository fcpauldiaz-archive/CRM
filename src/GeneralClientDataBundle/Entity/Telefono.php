<?php

namespace GeneralClientDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Telefono
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Telefono
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
     * @ORM\Column(name="numeroTelefono", type="string", length=9)
     */
    private $numeroTelefono;

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
     * Set numeroTelefono
     *
     * @param string $numeroTelefono
     *
     * @return Telefono
     */
    public function setNumeroTelefono($numeroTelefono)
    {
        $this->numeroTelefono = $numeroTelefono;

        return $this;
    }

    /**
     * Get numeroTelefono
     *
     * @return string
     */
    public function getNumeroTelefono()
    {
        return $this->numeroTelefono;
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

