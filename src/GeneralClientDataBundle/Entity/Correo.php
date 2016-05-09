<?php

namespace GeneralClientDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Correo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Correo
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
     * @ORM\Column(name="correoElectronico", type="string", length=50)
     */
    private $correoElectronico;

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
     * Set correoElectronico
     *
     * @param string $correoElectronico
     *
     * @return Correo
     */
    public function setCorreoElectronico($correoElectronico)
    {
        $this->correoElectronico = $correoElectronico;

        return $this;
    }

    /**
     * Get correoElectronico
     *
     * @return string
     */
    public function getCorreoElectronico()
    {
        return $this->correoElectronico;
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

