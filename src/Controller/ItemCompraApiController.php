<?php

namespace App\Controller;
use App\Entity\Plato;
use App\Entity\Compra;
use App\Entity\ItemCompra;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ItemCompraApiController
 *
 * @package App\Controller
 *
 * @Route(path=ItemCompraApiController::ITEM_COMPRA_API_PATH, name="api_itemCompras_")
 */
class ItemCompraApiController extends AbstractController
{
    //Creación de constante para almacenar la ruta de la api itemCompras
    const ITEM_COMPRA_API_PATH = "/api/v1/itemCompras";

    /**
     * @Route(path="", name="getItemCompras", methods={ Request::METHOD_GET })
     * @return Response
     */
    public function getItemCompras(): Response
    {
        $itemCompras = $this->getDoctrine()               // Usamos el gestor de entidades de Doctrine
        ->getRepository(ItemCompra::class)  // Obtenemos los itemCompras del repositorio
        ->findAll();                               // Método que recupera todos los ItemCompras

        // Validamos la respuesta $itemCompras
        return ($itemCompras === null)
            ? $this->mensajeError404()                   // Si no hay ItemCompras llamamos al método mensajeError404()
            : new JsonResponse(['ItemCompras' => $itemCompras]); // Devolvemos nuestro array de ItemCompras en formato json
    }

    /**
     * @Route(path="/{id}", name="getItemCompraId",  methods={ Request::METHOD_GET } )
     * @param ItemCompra|null $itemCompra
     * @return Response
     */
    public function getItemCompraId(?ItemCompra $itemCompra = null): Response
    {
        // Validamos la respuesta $itemCompra
        return ($itemCompra === null)
            ? $this->mensajeError404()                  // Si no hay $itemCompra llamamos al método mensajeError404()
            : new JsonResponse(['ItemCompra' => $itemCompra]);  // Devolvemos nuestro array con los datos del $itemCompra en formato json
    }

    /**
     * @Route(path="", name="postItemCompra", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postItemCompra(Request $request): Response
    {
        $peticion = $request->getContent();
        $datosItemCompra = json_decode($peticion,true);

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idCompra en el repositorio de la clase Compra
        $compra = $em->getRepository(Compra::class)
            ->find($datosItemCompra['compra']);

        // Validamos si no existe el Restaurante
        if ($compra === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe la compra con id: ' . $datosItemCompra['compra']);
        }

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idRestaurante en el repositorio de la clase Plato
        $plato = $em->getRepository(Plato::class)
            ->find($datosItemCompra['plato']);
        // Validamos si no existe el Restaurante
        if ($plato === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el plato con id: ' . $datosItemCompra['plato']);
        }

        // Asignamos los parametros al constructor para crear el objeto ItemCompra
        $itemCompra = new ItemCompra($datosItemCompra['cantidad'],$datosItemCompra['precioUnitario'],$compra,$plato);

        $em = $this->getDoctrine()->getManager();
        $em->persist($itemCompra); //Hacemos persistente $itemCompra
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['$ItemCompra' => $itemCompra],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="putItemCompraId", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putItemCompraId(?ItemCompra $itemCompra = null, Request $request): Response
    {
        if ($itemCompra === null) {
            return $this->mensajeError404();
        }
        $peticion = $request->getContent();
        $datosItemCompra = json_decode($peticion, true);

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idCompra en el repositorio de la clase Compra
        $compra = $em->getRepository(Usuario::class)
            ->find($datosItemCompra['compra']);
        // Validamos si no existe el Restaurante
        if ($compra === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe la compra con id: ' . $datosItemCompra['compra']);
        }

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idRestaurante en el repositorio de la clase Plato
        $plato = $em->getRepository(Plato::class)
            ->find($datosItemCompra['plato']);
        // Validamos si no existe el Restaurante
        if ($plato === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el plato con id: ' . $datosItemCompra['plato']);
        }

        //Se modifican los datos de ItemCompra
        $itemCompra->setCantidad($datosItemCompra['cantidad']);
        $itemCompra->setPrecioUnitario($datosItemCompra['precioUnitario']);
        $itemCompra->setCompra($compra);
        $itemCompra->setPlato($plato);

        $em = $this->getDoctrine()->getManager();
        $em->persist($itemCompra); //Hacemos persistente $itemCompra
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['ItemCompra' => $itemCompra],
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", name="deleteItemCompraId", methods={ Request::METHOD_DELETE } )
     * @param ItemCompra|null $itemCompra
     * @return Response
     */
    public function deleteItemCompraId(?ItemCompra $itemCompra = null): Response
    {
        // Si itemCompra No existe
        if ($itemCompra === null) {
            return $this->mensajeError404();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->remove($itemCompra);
        $em->flush();
        // Devolvemos la respuesta
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(path="", name="deleteItemCompras", methods={ Request::METHOD_DELETE })
     * @return JsonResponse
     */
    public function deleteItemCompras(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $itemCompras = $this->getDoctrine()
            ->getRepository(ItemCompra::class)
            ->findAll();
        // Elimina cada resultado que obtiene del repositorio
        foreach ($itemCompras as $itemCompra) {
            $em->remove($itemCompra);
            $em->flush();
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Genera una respuesta 400 - Bad Request
     * @return JsonResponse
     * @codeCoverageIgnore
     */
    private function mensajeError400(): JsonResponse
    {
        $mensaje = [
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => 'Bad Request'
        ];

        return new JsonResponse(
            $mensaje,
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * Genera una respuesta 404 - Not Found
     * @return JsonResponse
     * @codeCoverageIgnore
     */
    private function mensajeError404(): JsonResponse
    {
        $mensaje = [
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Not Found'
        ];

        return new JsonResponse(
            $mensaje,
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Genera una respuesta 422 - Unprocessable Entity
     * @return JsonResponse
     * @codeCoverageIgnore
     */
    private function mensajeError422(): JsonResponse
    {
        $mensaje = [
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'Unprocessable Entity'
        ];

        return new JsonResponse(
            $mensaje,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }


}
