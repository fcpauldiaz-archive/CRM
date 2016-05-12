<?php

namespace GeneralClientDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Telefono
{
    private $id;

    private $numeroTelefono;

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
     * Set setId
     *
     * @param string $id
     *
     * @return Telefono
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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

    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getCliente()
    {
        return $this->cliente;
    }
}

