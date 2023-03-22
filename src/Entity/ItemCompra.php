<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemCompra
 *
 * @ORM\Table(name="item_compra", indexes={@ORM\Index(name="Item_Compra_Plato_Fk", columns={"Id_Plato"}), @ORM\Index(name="Item_Compra_Compra_Fk", columns={"Id_Compra"})})
 * @ORM\Entity
 */
class ItemCompra implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_ItemCompra", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idItemcompra;

    /**
     * @var int
     *
     * @ORM\Column(name="Cantidad", type="integer", nullable=false)
     */
    private $cantidad;

    /**
     * @var float
     *
     * @ORM\Column(name="Precio_Unitario", type="float", precision=6, scale=2, nullable=false)
     */
    private $precioUnitario;

    /**
     * @var Compra
     *
     * @ORM\ManyToOne(targetEntity="Compra")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Compra", referencedColumnName="Id_Compra")
     * })
     */
    private $compra;

    /**
     * @var Plato
     *
     * @ORM\ManyToOne(targetEntity="Plato")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Plato", referencedColumnName="Id_Plato")
     * })
     */
    private $plato;

    /**
     * User constructor.
     *
     * @param int $cantidad cantidad
     * @param float $precioUnitario precioUnitario
     * @param Compra $compra compra
     * @param Plato $plato plato
     *
     */
    public function __construct(
        int $cantidad =0,
        float $precioUnitario = 0.0,
        Compra $compra= null,
        Plato $plato= null
    ) {
        $this->idItemcompra =0;
        $this->cantidad    = $cantidad;
        $this->precioUnitario =$precioUnitario;
        $this->compra = $compra;
        $this->plato = $plato;
    }

    /**
     * @return int
     */
    public function getIdItemcompra(): int
    {
        return $this->idItemcompra;
    }

    /**
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return float
     */
    public function getPrecioUnitario(): float
    {
        return $this->precioUnitario;
    }

    /**
     * @param float $precioUnitario
     */
    public function setPrecioUnitario(float $precioUnitario): void
    {
        $this->precioUnitario = $precioUnitario;
    }

    /**
     * @return Compra
     */
    public function getCompra(): Compra
    {
        return $this->compra;
    }

    /**
     * @param Compra $compra
     */
    public function setCompra(?Compra $compra): void
    {
        $this->idCompra = $compra;
    }

    /**
     * @return Plato
     */
    public function getPlato(): Plato
    {
        return $this->plato;
    }

    /**
     * @param Plato $plato
     */
    public function setPlato(Plato $plato): void
    {
        $this->plato = $plato;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'Id_ItemCompra' => $this->idItemcompra,
            'cantidad'     => $this->cantidad,
            'precioUnitario' => $this->precioUnitario,
            'compra'   => $this->compra,
            'plato'   => $this->plato
        );
    }

}
