<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;

/**
 * Class Calificacion
 *
 * @ORM\Table(name="calificacion", indexes={@ORM\Index(name="Usuario_Calificacion_Fk", columns={"Id_Usuario"}), @ORM\Index(name="Calificacion_Restaurante_Fk", columns={"Id_Restaurante"})})
 * @ORM\Entity
 */
class Calificacion implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Calificacion", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCalificacion;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Valor", type="integer", nullable=true)
     */
    private $valor;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Comentario", type="string", length=110, nullable=true)
     */
    private $comentario;

    /**
     * @var \Restaurante
     *
     * @ORM\ManyToOne(targetEntity="Restaurante")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Restaurante", referencedColumnName="Id_Restaurante")
     * })
     */
    private $restaurante;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Usuario", referencedColumnName="Id_Usuario")
     * })
     */
    private $usuario;

    /**
     * User constructor.
     *
     * @param int $valor valor
     * @param string $comentario comentario
     * @param Restaurante $restaurante restaurante
     * @param Usuario $usuario usuario
     *
     */
    public function __construct(
        int $valor = 0,
        string $comentario ='',
        Restaurante $restaurante= null,
        Usuario $usuario= null
    ) {
        $this->idCalificacion =0;
        $this->valor =$valor;
        $this->comentario    = $comentario;
        $this->restaurante = $restaurante;
        $this->usuario = $usuario;
    }

    /**
     * @return int
     */
    public function getIdCalificacion(): int
    {
        return $this->idCalificacion;
    }

    /**
     * @return int
     */
    public function getValor(): int
    {
        return $this->valor;
    }

    /**
     * @param int $valor
     */
    public function setValor(int $valor): void
    {
        $this->valor = $valor;
    }

    /**
     * @return string
     */
    public function getComentario(): string
    {
        return $this->comentario;
    }

    /**
     * @param string $comentario
     */
    public function setComentario(string $comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return Restaurante
     */
    public function getRestaurante(): Restaurante
    {
        return $this->restaurante;
    }

    /**
     * @param Restaurante $restaurante
     */
    public function setRestaurante(Restaurante $restaurante): void
    {
        $this->restaurante = $restaurante;
    }

    /**
     * @return Usuario
     */
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario $usuario
     */
    public function setUsuario(?Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'Id_Calificacion' => $this->idCalificacion,
            'valor'     => $this->valor,
            'comentario'     => utf8_encode($this->comentario),
            'restaurante'   => $this->restaurante,
            'usuario'   => $this->usuario
        );
    }



}
