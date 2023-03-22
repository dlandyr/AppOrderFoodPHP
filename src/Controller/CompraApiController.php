<?php

namespace App\Controller;

use App\Entity\Compra;
use App\Entity\Usuario;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CompraApiController
 *
 * @package App\Controller
 *
 * @Route(path=CompraApiController::COMPRA_API_PATH, name="api_compras_")
 */
class CompraApiController extends AbstractController
{
    //Creación de constante para almacenar la ruta de la api compras
    const COMPRA_API_PATH = "/api/v1/compras";

    /**
     * @Route(path="", name="getCompras", methods={ Request::METHOD_GET })
     * @return Response
     */
    public function getCompras(): Response
    {
        $compras = $this->getDoctrine()               // Usamos el gestor de entidades de Doctrine
        ->getRepository(Compra::class)  // Obtenemos los usuarios del repositorio
        ->findAll();                               // Método que recupera todas las Compras

        // Validamos la respuesta $calificaciones
        return ($compras === null)
            ? $this->mensajeError404()                   // Si no hay Calificaciones llamamos al método mensajeError404()
            : new JsonResponse(['Compra' => $compras]); // Devolvemos nuestro array de Compras en formato json
    }

    /**
     * @Route(path="/{id}", name="getCompraId",  methods={ Request::METHOD_GET } )
     * @param Compra|null $compra
     * @return Response
     */
    public function getCompraId(?Ccompra $compra = null): Response
    {
        // Validamos la respuesta $compra
        return ($compra === null)
            ? $this->mensajeError404()                  // Si no hay $compra llamamos al método mensajeError404()
            : new JsonResponse(['Compra' => $compra]);  // Devolvemos nuestro array con los datos de la $compra en formato json
    }

    /**
     * @Route(path="", name="postCompra", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postCompra(Request $request): Response
    {
        $peticion = $request->getContent();
        $datosCompra = json_decode($peticion,true);

        // Si no envía userId
        if (!array_key_exists('usuario', $datosCompra)) {
            return $this->mensajeError422();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Buscamos el idUsuario en el repositorio de la clase Usuario
        $usuario = $em->getRepository(Usuario::class)
            ->find($datosCompra['usuario']);

        // Validamos si no existe el Usuario
        if ($usuario === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el usuario con id: ' . $datosCompra['usuario']);
        }

        // Asignamos los parametros al constructor para crear el objeto Compra

        $compra = new Compra(new DateTime("now"),$datosCompra['subtotal'],$datosCompra['iva'],$datosCompra['total'],$usuario);
        $em = $this->getDoctrine()->getManager();
        $em->persist($compra); //Hacemos persistente $compra
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['Compra' => $compra],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="putCompraId", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putCompraId(?Compra $compra = null, Request $request): Response
    {
        if ($compra === null) {
            return $this->mensajeError404();
        }
        $peticion = $request->getContent();
        $datosCompra= json_decode($peticion, true);

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idUsuario en el repositorio de la clase Usuario
        $usuario = $em->getRepository(Usuario::class)
            ->find($datosCompra['usuario']);
        // Validamos si no existe el Usuario
        if ($usuario === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el usuario con id: ' . $datosCompra['usuario']);
        }

        //Se modifican los datos de la Compra
        $compra->setFecha(new DateTime('now'));
        $compra->setSubtotal($datosCompra['subtotal']);
        $compra->setIva($datosCompra['iva']);
        $compra->setTotal($datosCompra['total']);
        $compra->setUsuario($usuario);

        $em = $this->getDoctrine()->getManager();
        $em->persist($compra); //Hacemos persistente $compra
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['compra' => $compra],
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", name="deleteCompraId", methods={ Request::METHOD_DELETE } )
     * @param Compra|null $compra
     * @return Response
     */
    public function deleteCompraId(?Compra $compra = null): Response
    {
        // Si la compra No existe
        if ($compra === null) {
            return $this->mensajeError404();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->remove($compra);
        $em->flush();
        // Devolvemos la respuesta
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(path="", name="deleteCompras", methods={ Request::METHOD_DELETE })
     * @return JsonResponse
     */
    public function deleteCompras(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $compras = $this->getDoctrine()
            ->getRepository(Compra::class)
            ->findAll();
        // Elimina cada resultado que obtiene del repositorio
        foreach ($compras as $compra) {
            $em->remove($compra);
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
