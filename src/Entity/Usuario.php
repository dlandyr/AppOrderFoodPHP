<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity
 */
class Usuario implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Usuario", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombres", type="string", length=110, nullable=false)
     */
    private $nombres;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellidos", type="string", length=110, nullable=false)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="Username", type="string", length=20, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=20, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Telefono", type="string", length=20, nullable=true)
     */
    private $telefono;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Direccion", type="string", length=110, nullable=true)
     */
    private $direccion;

    /**
     * User constructor.
     *
     * @param string $nombres nombres
     * @param string $apellidos apellidos
     * @param string $username username
     * @param string $password password
     * @param string $email email
     * @param string $telefono telefono
     * @param string $direccion direccion
     *
     */
    public function __construct(
        string $nombres = '',
        string $apellidos= '',
        string $username = '',
        string $password = '',
        string $email = '',
        string $telefono = '',
        string $direccion = ''
    ) {
        $this->idUsuario =0;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->username = $username;
        $this->password = $password;
        $this->email    = $email;
        $this->telefono    = $telefono;
        $this->direccion    = $direccion;
    }

    /**
     * @return int
     */
    public function getIdUsuario(): int
    {
        return $this->idUsuario;
    }

    /**
     * @return string
     */
    public function getNombres(): string
    {
        return $this->nombres;
    }

    /**
     * @param string $nombres
     */
    public function setNombres(string $nombres): void
    {
        $this->nombres = $nombres;
    }

    /**
     * @return string
     */
    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    /**
     * @param string $apellidos
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'Id_Usuario' => $this->idUsuario,
            'nombres'     => utf8_encode($this->nombres),
            'apellidos'   => utf8_encode($this->apellidos),
            'username'    => utf8_encode($this->username),
            'password'    => utf8_encode($this->password),
            'email'       => utf8_encode($this->email),
            'telefono'    => utf8_encode($this->telefono),
            'direccion'   => utf8_encode($this->direccion)
        );
    }
}
