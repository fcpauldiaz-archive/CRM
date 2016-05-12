<?php

namespace ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use GeneralClientDataBundle\Entity\TipoMembresia as TipoMembresiaEntity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Client
{
    private $id;

    private $fechaNacimiento;

    private $nit;

    private $frecuente;

    private $nombres;

    private $apellidos;

    private $estadoCivil;

    private $imageFile;

    private $fotoCliente;

    private $sexo;

    private $profesion;

    private $dpi;

    private $nacionalidad;

    private $direccion;

    private $correo;

    private $telefono;

    private $tipoMembresia;

    private $twitterUsername;

    public function __construct()
    {
        $this->correo = new ArrayCollection();
        $this->telefono = new ArrayCollection();
        $this->direccion = new ArrayCollection();
    }

    
    public function setImageFile(UploadedFile $image = null)
    {
        $this->imageFile = $image;

        return $this;
    }

    public function uploadImage()
    {
        // si es nulo, se sale
        if (null === $this->getImageFile()) {
            return;
        }
        //usar el nombre original de la imagen

        // guardar archivo
        
        $this->getImageFile()->move(
            $this->getUploadRootDir(),
            $this->getImageFile()->getClientOriginalName()
        );

        // guardar el nombre en la base de datos
        $this->fotoCliente = $this->getUploadRootDir();

        // ya no se necesita
        $this->file = null;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
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
     * Get id
     *
     * @return integer
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set fechaNacimiento
     *
     * @param string $fechaNacimiento
     *
     * @return Client
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return string
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return Client
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set frecuente
     *
     * @param boolean $frecuente
     *
     * @return Client
     */
    public function setFrecuente($frecuente)
    {
        $this->frecuente = $frecuente;

        return $this;
    }

    /**
     * Get frecuente
     *
     * @return boolean
     */
    public function getFrecuente()
    {
        return $this->frecuente;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     *
     * @return Client
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     *
     * @return Client
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set estadoCivil
     *
     * @param string $estadoCivil
     *
     * @return Client
     */
    public function setEstadoCivil($estadoCivil)
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    /**
     * Get estadoCivil
     *
     * @return string
     */
    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    /**
     * Set fotoCliente
     *
     * @param string $fotoCliente
     *
     * @return Client
     */
    public function setFotoCliente($fotoCliente)
    {
        $this->fotoCliente = $fotoCliente;

        return $this;
    }

    /**
     * Get fotoCliente
     *
     * @return string
     */
    public function getFotoCliente()
    {
        return $this->fotoCliente;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Client
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set profesion
     *
     * @param string $profesion
     *
     * @return Client
     */
    public function setProfesion($profesion)
    {
        $this->profesion = $profesion;

        return $this;
    }

    /**
     * Get profesion
     *
     * @return string
     */
    public function getProfesion()
    {
        return $this->profesion;
    }

    /**
     * Set dpi
     *
     * @param string $dpi
     *
     * @return Client
     */
    public function setDpi($dpi)
    {
        $this->dpi = $dpi;

        return $this;
    }

    /**
     * Get dpi
     *
     * @return string
     */
    public function getDpi()
    {
        return $this->dpi;
    }

    /**
     * Set nacionalidad
     *
     * @param string $nacionalidad
     *
     * @return Client
     */
    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    /**
     * Get nacionalidad
     *
     * @return string
     */
    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }

    public function setTipoMembresia(TipoMembresiaEntity $tipoMembresia)
    {
        $this->tipoMembresia = $tipoMembresia;

        return $this;
    }

    public function getTipoMembresia()
    {
        return $this->tipoMembresia;
    }

    public function setTwitterUsername($username)
    {
        $this->twitterUsername = $username;
    }

    public function getTwitterUsername()
    {
        return $this->twitterUsername;
    }

    public function __toString()
    {
        return $this->nombres.' '.$this->apellidos;
    }

     protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads';
    }
}

