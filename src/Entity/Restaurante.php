<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Restaurante
 *
 * @ORM\Table(name="restaurante")
 * @ORM\Entity
 */
class Restaurante implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Restaurante", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRestaurante;

    /**
     * @var string
     *
     * @ORM\Column(name="Descripcion", type="string", length=110, nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="Direccion", type="string", length=110, nullable=false)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="Telefono", type="string", length=20, nullable=false)
     */
    private $telefono;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Foto", type="string", length=300, nullable=true)
     */
    private $foto;

    /**
     * User constructor.
     *
     * @param string $descripcion descripcion
     * @param string $direccion direccion
     * @param string $telefono telefono
     * @param string $email email
     * @param string $foto foto
     *
     */
    public function __construct(
        string $descripcion = '',
        string $direccion= '',
        string $telefono = '',
        string $email = '',
        string $foto = ''
    ) {
        $this->idRestaurante =0;
        $this->descripcion = $descripcion;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email    = $email;
        $this->foto    = $foto;
    }

    /**
     * @return int
     */
    public function getIdRestaurante(): int
    {
        return $this->idRestaurante;
    }

    /**
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return string
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return string
     */
    public function getTelefono(): string
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFoto(): string
    {
        return $this->foto;
    }

    /**
     * @param string $foto
     */
    public function setFoto(string $foto): void
    {
        $this->foto = $foto;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'Id_Restaurante' => $this->idRestaurante,
            'descripcion'    => utf8_encode($this->descripcion),
            'direccion'      => utf8_encode($this->direccion),
            'telefono'       => utf8_encode($this->telefono),
            'email'          => utf8_encode($this->email),
            'foto'           => utf8_encode($this->foto)
        );
    }


}
