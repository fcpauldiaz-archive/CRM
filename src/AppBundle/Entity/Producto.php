<?php

namespace AppBundle\Entity;

/**
 * Producto
 *
 * 
 */
class Producto
{
    
    private $id;

    private $producto;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set producto
     *
     * @param string $producto
     *
     * @return Producto
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return string
     */
    public function getProducto()
    {
        return $this->producto;
    }
}

