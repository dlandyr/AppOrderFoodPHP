<?php

namespace App\Controller;

use App\Entity\Restaurante;
use App\Entity\Horario;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class HorarioApiController
 *
 * @package App\Controller
 *
 * @Route(path=HorarioApiController::HORARIO_API_PATH, name="api_horarios_")
 */
class HorarioApiController extends AbstractController
{
    //Creación de constante para almacenar la ruta de la api horarios
    const HORARIO_API_PATH = "/api/v1/horarios";

    /**
     * @Route(path="", name="getHorarios", methods={ Request::METHOD_GET })
     * @return Response
     */
    public function getHorarios(): Response
    {
        $horarios = $this->getDoctrine()               // Usamos el gestor de entidades de Doctrine
        ->getRepository(Horario::class)  // Obtenemos los usuarios del repositorio
        ->findAll();                               // Método que recupera todos los Horarios

        // Validamos la respuesta $horarios
        return ($horarios === null)
            ? $this->mensajeError404()                   // Si no hay Horarios llamamos al método mensajeError404()
            : new JsonResponse(['Horarios' => $horarios]); // Devolvemos nuestro array de Horarios en formato json
    }

    /**
     * @Route(path="/{id}", name="getHorarioId",  methods={ Request::METHOD_GET } )
     * @param Horario|null $horario
     * @return Response
     */
    public function getHorarioId(?Horario $horario = null): Response
    {
        // Validamos la respuesta $horario
        return ($horario === null)
            ? $this->mensajeError404()                  // Si no hay $horario llamamos al método mensajeError404()
            : new JsonResponse(['horario' => $horario]);  // Devolvemos nuestro array con los datos del $horario en formato json
    }

    /**
     * @Route(path="", name="postHorario", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postHorario(Request $request): Response
    {
        $peticion = $request->getContent();
        $datosHorario = json_decode($peticion, true);

        // Si no envía descripcion
        if (!array_key_exists('descripcion', $datosHorario)) {
            return $this->mensajeError422();
        }
        // Si no envía idRestaurante
        if (!array_key_exists('restaurante', $datosHorario)) {
            return $this->mensajeError422();
        }

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idRestaurante en el repositorio de la clase Restaurante
        $restaurante = $em->getRepository(Restaurante::class)
            ->find($datosHorario['restaurante']);
        // Validamos si no existe el Restaurante
        if ($restaurante === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el restaurante con id: ' . $datosHorario['restaurante']);
        }

        // Asignamos los parametros al constructor para crear el objeto Horario
        $horario = new Horario($datosHorario['descripcion'],$restaurante);

        $em = $this->getDoctrine()->getManager();
        $em->persist($horario); //Hacemos persistente $usuario
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['horario' => $horario],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="putHorarioId", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putHorarioId(?Horario $horario = null, Request $request): Response
    {
        if ($horario === null) {
            return $this->mensajeError404();
        }
        $peticion = $request->getContent();
        $datosHorario = json_decode($peticion, true);

        // Si no envía descripcion
        if (!array_key_exists('descripcion', $datosHorario)) {
            return $this->mensajeError422();
        }
        // Si no envía idRestaurante
        if (!array_key_exists('restaurante', $datosHorario)) {
            return $this->mensajeError422();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idRestaurante en el repositorio de la clase Restaurante
        $restaurante = $em->getRepository(Restaurante::class)
            ->find($datosHorario['restaurante']);
        // Validamos si no existe el restaurante
        if ($restaurante === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el restaurante con id: ' . $datosHorario['restaurante']);
        }
        //Se modifican los datos del Horario
        $horario->setDescripcion($datosHorario['descripcion']);
        $horario->setRestaurante($restaurante);
        $horario->setTime(new DateTime('now'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($horario); //Hacemos persistente $horario
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['horario' => $horario],
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", name="deleteHorarioId", methods={ Request::METHOD_DELETE } )
     * @param Horario|null $horario
     * @return Response
     */
    public function deleteHorarioId(?Horario $horario = null): Response
    {
        // Si el horario No existe
        if ($horario === null) {
            return $this->mensajeError404();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->remove($horario);
        $em->flush();
        // Devolvemos la respuesta
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(path="", name="deleteHorarios", methods={ Request::METHOD_DELETE })
     * @return JsonResponse
     */
    public function deleteHorarios(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $horarios = $this->getDoctrine()
            ->getRepository(Horario::class)
            ->findAll();
        // Elimina cada resultado que obtiene del repositorio
        foreach ($horarios as $horario) {
            $em->remove($horario);
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
