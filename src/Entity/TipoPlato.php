<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoPlato
 *
 * @ORM\Table(name="tipo_plato")
 * @ORM\Entity
 */
class TipoPlato implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_TipoPlato", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTipoplato;

    /**
     * @var string
     *
     * @ORM\Column(name="Descripcion", type="string", length=80, nullable=false)
     */
    private $descripcion;

    /**
     * User constructor.
     *
     * @param string $descripcion descripcion
     *
     */
    public function __construct(
        string $descripcion = ''
    ) {
        $this->idTipoplato =0;
        $this->descripcion = $descripcion;
    }

    /**
     * @return int
     */
    public function getIdTipoplato(): int
    {
        return $this->idTipoplato;
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
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'Id_TipoPlato' => $this->idTipoplato,
            'descripcion'     => utf8_encode($this->descripcion)
        );
    }

}
