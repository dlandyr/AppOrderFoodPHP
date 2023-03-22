<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plato
 *
 * @ORM\Table(name="plato", indexes={@ORM\Index(name="Plato_Tipo_Plato_Fk", columns={"Id_TipoPlato"}), @ORM\Index(name="Plato_Restaurante_Fk", columns={"Id_Restaurante"})})
 * @ORM\Entity
 */
class Plato implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Plato", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPlato;

    /**
     * @var string
     *
     * @ORM\Column(name="Descripcion", type="string", length=80, nullable=false)
     */
    private $descripcion;

    /**
     * @var float
     *
     * @ORM\Column(name="Precio", type="float", precision=6, scale=2, nullable=false)
     */
    private $precio;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Foto", type="string", length=300, nullable=true)
     */
    private $foto;

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
     * @var TipoPlato
     *
     * @ORM\ManyToOne(targetEntity="TipoPlato")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_TipoPlato", referencedColumnName="Id_TipoPlato")
     * })
     */
    private $tipoplato;

    /**
     * User constructor.
     *
     * @param string $descripcion descripcion
     * @param float $precio precio
     * @param string $foto foto
     * @param Restaurante $restaurante restaurante
     * @param TipoPlato $tipoplato tipoplato
     *
     */
    public function __construct(
        string $descripcion ='',
        float $precio = 0.0,
        string $foto ='',
        Restaurante $restaurante= null,
        TipoPlato $tipoplato= null
    ) {
        $this->idPlato =0;
        $this->descripcion    = $descripcion;
        $this->precio =$precio;
        $this->foto    = $foto;
        $this->restaurante = $restaurante;
        $this->tipoplato = $tipoplato;
    }

    /**
     * @return int
     */
    public function getIdPlato(): int
    {
        return $this->idPlato;
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
     * @return float
     */
    public function getPrecio(): float
    {
        return $this->precio;
    }

    /**
     * @param float $precio
     */
    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;

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
     * @return TipoPlato
     */
    public function getTipoplato(): TipoPlato
    {
        return $this->tipoplato;
    }

    /**
     * @param TipoPlato $tipoplato
     */
    public function setTipoplato(TipoPlato $tipoplato): void
    {
        $this->tipoplato = $tipoplato;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'Id_Plato' => $this->idPlato,
            'descripcion'     => utf8_encode($this->descripcion),
            'precio'     => $this->precio,
            'foto'     => utf8_encode($this->foto),
            'restaurante'   => $this->restaurante,
            'tipoPlato'   => $this->tipoplato
        );
    }


}
