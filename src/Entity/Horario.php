<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Horario
 *
 * @ORM\Table(name="horario", indexes={@ORM\Index(name="Restaurante_Horario_Fk", columns={"Id_Restaurante"})})
 * @ORM\Entity
 */
class Horario implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Horario", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idHorario;

    /**
     * @var string
     *
     * @ORM\Column(name="Descripcion", type="string", length=110, nullable=false)
     */
    private $descripcion;

    /**
     * @var Restaurante
     *
     * @ORM\ManyToOne(targetEntity="Restaurante")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Restaurante", referencedColumnName="Id_Restaurante")
     * })
     */
    private $restaurante;


    /**
     * User constructor.
     *
     * @param string $descripcion descripcion
     * @param Restaurante $restaurante restaurante
     *
     */
    public function __construct(
        string $descripcion = '',
        Restaurante $restaurante= null
    ) {
        $this->idHorario =0;
        $this->descripcion    = $descripcion;
        $this->restaurante = $restaurante;
    }

    /**
     * @return int
     */
    public function getIdHorario(): int
    {
        return $this->idHorario;
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
     * @return Restaurante
     */
    public function getRestaurante(): Restaurante
    {
        return $this->restaurante;
    }

    /**
     * @param Restaurante $restaurante
     */
    public function setRestaurante(?Restaurante $restaurante)
    {
        $this->restaurante = $restaurante;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'Id_Horario' => $this->idHorario,
            'descripcion'     => utf8_encode($this->descripcion),
            'restaurante'   => $this->restaurante
        );
    }


}
