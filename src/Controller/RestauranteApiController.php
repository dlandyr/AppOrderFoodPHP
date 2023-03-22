<?php

namespace App\Controller;

use App\Entity\Restaurante;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RestauranteApiController
 *
 * @package App\Controller
 *
 * @Route(path=RestauranteApiController::RESTAURANTE_API_PATH, name="api_restaurantes_")
 */
class RestauranteApiController extends AbstractController
{
    //Creación de constante para almacenar la ruta de la api restaurantes
    const RESTAURANTE_API_PATH = "/api/v1/restaurantes";

    /**
     * @Route(path="", name="getRestaurantes", methods={ Request::METHOD_GET })
     * @return Response
     */
    public function getRestaurantes(): Response
    {
        $restaurantes = $this->getDoctrine()                // Usamos el gestor de entidades de Doctrine
        ->getRepository(Restaurante::class)  // Obtenemos los usuarios del repositorio
        ->findAll();                               // Método que recupera todos los restaurantes
        // Validamos la respuesta $usuarios
        return ($restaurantes === null)
            ? $this->mensajeError404()                   // Si no hay $usuarios llamamos al método mensajeError404()
            : new JsonResponse(['restaurantes' => $restaurantes]); // Devolvemos nuestro array de usuarios en formato json
    }

    /**
     * @Route(path="/{id}", name="getRestauranteId",  methods={ Request::METHOD_GET } )
     * @param Restaurante|null $restaurante
     * @return Response
     */
    public function getRestauranteId(?Restaurante $restaurante = null): Response
    {
        // Validamos la respuesta $usuario
        return ($restaurante === null)
            ? $this->mensajeError404()                  // Si no hay $usuario llamamos al método mensajeError404()
            : new JsonResponse(['usuario' => $restaurante]);  // Devolvemos nuestro array con los datos del usuario en formato json
    }

    /**
     * @Route(path="", name="postRestaurante", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postRestaurante(Request $request): Response
    {
        $peticion = $request->getContent();
        $datosRestaurante = json_decode($peticion, true);

        // Asignamos los parametros al constructor para crear el objeto Restaurante
        $restaurante = new Restaurante(
            $datosRestaurante['descripcion'],
            $datosRestaurante['direccion'],
            $datosRestaurante['telefono'],
            $datosRestaurante['email'],
            $datosRestaurante['foto']
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($restaurante); //Hacemos persistente $restaurante
        $em->flush();
        // Devuelve la respuesta
        return new JsonResponse(
            ['restaurante' => $restaurante],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="putRestauranteId", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putRestauranteId(?Restaurante $restaurante = null, Request $request): Response
    {
        if ($restaurante === null) {
            return $this->mensajeError404();
        }

        $peticion = $request->getContent();
        $datosRestaurante = json_decode($peticion, true);

        //Se modifican los parámetros del restaurante
        $restaurante->setDescripcion($datosRestaurante['descripcion']);
        $restaurante->setDireccion($datosRestaurante['direccion']);
        $restaurante->setTelefono($datosRestaurante['telefono']);
        $restaurante->setEmail($datosRestaurante['email']);
        $restaurante->setFoto($datosRestaurante['foto']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($restaurante); //Hacemos persistente $restaurante
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['restaurante' => $restaurante],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="deleteRestauranteId", methods={ Request::METHOD_DELETE } )
     * @param Restaurante|null $restaurante
     * @return Response
     */
    public function deleteRestauranteId(?Restaurante $restaurante = null): Response
    {
        // Si el usuario No existe
        if ($restaurante === null) {
            return $this->mensajeError404();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->remove($restaurante);
        $em->flush();
        // Devuelve la respuesta
        return new JsonResponse(
            ['restaurante' => $restaurante],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route(path="", name="deleteRestaurantes", methods={ Request::METHOD_DELETE })
     * @return JsonResponse
     */
    public function deleteRestaurantes(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $restaurantes = $this->getDoctrine()
            ->getRepository(Restaurante::class)
            ->findAll();
        // Elimina cada restaurante que obtiene del repositorio
        foreach ($restaurantes as $restaurante) {
            $em->remove($restaurante);
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
