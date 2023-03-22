<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Restaurante;
use App\Entity\Calificacion;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CalificacionApiController
 *
 * @package App\Controller
 *
 * @Route(path=CalificacionApiController::CALIFICACION_API_PATH, name="api_calificaciones_")
 */
class CalificacionApiController extends AbstractController
{
    //Creación de constante para almacenar la ruta de la api horarios
    const CALIFICACION_API_PATH = "/api/v1/calificaciones";

    /**
     * @Route(path="", name="getCalificaciones", methods={ Request::METHOD_GET })
     * @return Response
     */
    public function getCalificaciones(): Response
    {
        $calificaciones = $this->getDoctrine()               // Usamos el gestor de entidades de Doctrine
        ->getRepository(Calificacion::class)  // Obtenemos los usuarios del repositorio
        ->findAll();                               // Método que recupera todas las Calificaciones

        // Validamos la respuesta $calificaciones
        return ($calificaciones === null)
            ? $this->mensajeError404()                   // Si no hay Calificaciones llamamos al método mensajeError404()
            : new JsonResponse(['Calificaciones' => $calificaciones]); // Devolvemos nuestro array de Calificaciones en formato json
    }

    /**
     * @Route(path="/{id}", name="getCalificacionId",  methods={ Request::METHOD_GET } )
     * @param Calificacion|null $calificacion
     * @return Response
     */
    public function getCalificacionId(?Calificacion $calificacion = null): Response
    {
        // Validamos la respuesta $calificacion
        return ($calificacion === null)
            ? $this->mensajeError404()                  // Si no hay $calificacion llamamos al método mensajeError404()
            : new JsonResponse(['calificacion' => $calificacion]);  // Devolvemos nuestro array con los datos de la $calificacion en formato json
    }

    /**
     * @Route(path="", name="postCalificacion", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postCalificacion(Request $request): Response
    {
        $peticion = $request->getContent();
        $datosCalificacion = json_decode($peticion,true);

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idUsuario en el repositorio de la clase Usuario
        $usuario = $em->getRepository(Usuario::class)
            ->find($datosCalificacion['usuario']);
        // Validamos si no existe el Usuario
        if ($usuario === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el usuario con id: ' . $datosCalificacion['usuario']);
        }

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idRestaurante en el repositorio de la clase Restaurante
        $restaurante = $em->getRepository(Restaurante::class)
            ->find($datosCalificacion['restaurante']);
        // Validamos si no existe el Restaurante
        if ($restaurante === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el restaurante con id: ' . $datosCalificacion['restaurante']);
        }

        // Asignamos los parametros al constructor para crear el objeto Calificacion
        $calificacion = new Calificacion($datosCalificacion['valor'],$datosCalificacion['comentario'],$restaurante,$usuario);

        $em = $this->getDoctrine()->getManager();
        $em->persist($calificacion); //Hacemos persistente $usuario
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['calificacion' => $calificacion],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="putCalificacionId", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putCalificacionId(?Calificacion $calificacion = null, Request $request): Response
    {
        if ($calificacion === null) {
            return $this->mensajeError404();
        }
        $peticion = $request->getContent();
        $datosCalificacion = json_decode($peticion, true);

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idUsuario en el repositorio de la clase Usuario
        $usuario = $em->getRepository(Usuario::class)
            ->find($datosCalificacion['usuario']);
        // Validamos si no existe el Usuario
        if ($usuario === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el usuario con id: ' . $datosCalificacion['usuario']);
        }

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idRestaurante en el repositorio de la clase Restaurante
        $restaurante = $em->getRepository(Restaurante::class)
            ->find($datosCalificacion['restaurante']);
        // Validamos si no existe el Restaurante
        if ($restaurante === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el restaurante con id: ' . $datosCalificacion['restaurante']);
        }

        //Se modifican los datos de la Calificacion
        $calificacion->setValor($datosCalificacion['valor']);
        $calificacion->setComentario($datosCalificacion['comentario']);
        $calificacion->setRestaurante($restaurante);
        $calificacion->setUsuario($usuario);

        $em = $this->getDoctrine()->getManager();
        $em->persist($calificacion); //Hacemos persistente $horario
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['calificacion' => $calificacion],
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", name="deleteCalificacionId", methods={ Request::METHOD_DELETE } )
     * @param Calificacion|null $calificacion
     * @return Response
     */
    public function deleteCalificacionId(?Calificacion $calificacion = null): Response
    {
        // Si la calificacion No existe
        if ($calificacion === null) {
            return $this->mensajeError404();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->remove($calificacion);
        $em->flush();
        // Devolvemos la respuesta
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(path="", name="deleteCalificaciones", methods={ Request::METHOD_DELETE })
     * @return JsonResponse
     */
    public function deleteCalificaciones(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $calificaciones = $this->getDoctrine()
            ->getRepository(Calificacion::class)
            ->findAll();
        // Elimina cada resultado que obtiene del repositorio
        foreach ($calificaciones as $calificacion) {
            $em->remove($calificacion);
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
