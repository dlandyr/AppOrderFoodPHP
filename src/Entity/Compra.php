<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Compra
 *
 * @ORM\Table(name="compra", indexes={@ORM\Index(name="Compra_Usuario_Fk", columns={"Id_Usuario"})})
 * @ORM\Entity
 */
class Compra implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Compra", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCompra;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="Fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var float
     *
     * @ORM\Column(name="Subtotal", type="float", precision=6, scale=2, nullable=false)
     */
    private $subtotal;

    /**
     * @var float
     *
     * @ORM\Column(name="Iva", type="float", precision=6, scale=2, nullable=false)
     */
    private $iva;

    /**
     * @var float
     *
     * @ORM\Column(name="Total", type="float", precision=6, scale=2, nullable=false)
     */
    private $total;

    /**
     * Result usuario
     * @var Usuario
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
     * @param \DateTime $fecha fecha
     * @param float $subtotal subtotal
     * @param float $iva iva
     * @param float $total total
     * @param Usuario $usuario usuario
     *
     */
    public function __construct(
        DateTime $fecha,
        float $subtotal = 0.0,
        float $iva = 0.0,
        float $total = 0.0,
        Usuario $usuario= null
    ) {

        $this->idCompra =0;
        $this->fecha=$fecha ?? new DateTime("now");
        $this->subtotal =$subtotal;
        $this->iva =$iva;
        $this->total =$total;
        $this->usuario = $usuario;
    }

    /**
     * @return int
     */
    public function getIdCompra(): int
    {
        return $this->idCompra;
    }

    /**
     * @return string
     */
    public function getFecha(): string
    {
        return $this->fecha->format('Y-m-d H:i:s');
    }

    /**
     * @param DateTime $fecha
     * @return Compra
     */
    public function setFecha(DateTime $fecha): Compra
    {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * @return float
     */
    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    /**
     * @param float $subtotal
     */
    public function setSubtotal(float $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    /**
     * @return float
     */
    public function getIva(): float
    {
        return $this->iva;
    }

    /**
     * @param float $iva
     */
    public function setIva(float $iva): void
    {
        $this->iva = $iva;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal(float $total): void
    {
        $this->total = $total;
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
    public function setUsuario(Usuario $usuario):void
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
            'Id_Compra' => $this->idCompra,
            'fecha'     => $this->fecha->format('Y-m-d H:i:s'),
            'subtotal'   => $this->subtotal,
            'iva'   => $this->iva,
            'total'   => $this->total,
            'usuario'   => $this->usuario
        );
    }


}
