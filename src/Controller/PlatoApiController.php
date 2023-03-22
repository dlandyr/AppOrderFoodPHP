<?php

namespace App\Controller;

use App\Entity\Plato;
use App\Entity\Restaurante;
use App\Entity\TipoPlato;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PlatoApiController
 *
 * @package App\Controller
 *
 * @Route(path=PlatoApiController::PLATO_API_PATH, name="api_platos_")
 */
class PlatoApiController extends AbstractController
{
    //Creación de constante para almacenar la ruta de la api platos
    const PLATO_API_PATH = "/api/v1/platos";

    /**
     * @Route(path="", name="getPlatos", methods={ Request::METHOD_GET })
     * @return Response
     */
    public function getPlatos(): Response
    {
        $platos = $this->getDoctrine()               // Usamos el gestor de entidades de Doctrine
        ->getRepository(Plato::class)  // Obtenemos los usuarios del repositorio
        ->findAll();                               // Método que recupera todas los Platos

        // Validamos la respuesta $platos
        return ($platos === null)
            ? $this->mensajeError404()                   // Si no hay Platos llamamos al método mensajeError404()
            : new JsonResponse(['platos' => $platos]); // Devolvemos nuestro array de Platos en formato json
    }

    /**
     * @Route(path="/{id}", name="getPlatoId",  methods={ Request::METHOD_GET } )
     * @param Plato|null $plato
     * @return Response
     */
    public function getPlatoId(?Plato $plato = null): Response
    {
        // Validamos la respuesta $plato
        return ($plato === null)
            ? $this->mensajeError404()                  // Si no hay $plato llamamos al método mensajeError404()
            : new JsonResponse(['Plato' => $plato]);  // Devolvemos nuestro array con los datos del $plato en formato json
    }

    /**
     * @Route(path="", name="postPlato", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postPlato(Request $request): Response
    {
        $peticion = $request->getContent();
        $datosPlato = json_decode($peticion,true);

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idUsuario en el repositorio de la clase TipoPlato
        $tipoPlato = $em->getRepository(TipoPlato::class)
            ->find($datosPlato['tipoPlato']);
        // Validamos si no existe el TipoPlato
        if ($tipoPlato === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el tipoPlato con id: ' . $datosPlato['tipoPlato']);
        }

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idRestaurante en el repositorio de la clase Restaurante
        $restaurante = $em->getRepository(Restaurante::class)
            ->find($datosPlato['restaurante']);
        // Validamos si no existe el Restaurante
        if ($restaurante === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el restaurante con id: ' . $datosPlato['restaurante']);
        }

        // Asignamos los parametros al constructor para crear el objeto Plato
        $plato = new Plato($datosPlato['descripcion'],$datosPlato['precio'],$datosPlato['foto'],$restaurante,$tipoPlato);

        $em = $this->getDoctrine()->getManager();
        $em->persist($plato); //Hacemos persistente $tipoPlato
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['$Plato' => $plato],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="putPlatoId", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putPlatoId(?Plato $plato = null, Request $request): Response
    {
        if ($plato === null) {
            return $this->mensajeError404();
        }
        $peticion = $request->getContent();
        $datosPlato = json_decode($peticion, true);

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idUsuario en el repositorio de la clase TipoPlato
        $tipoPlato = $em->getRepository(TipoPlato::class)
            ->find($datosPlato['tipoPlato']);
        // Validamos si no existe el TipoPlato
        if ($tipoPlato === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el tipoPlato con id: ' . $datosPlato['tipoPlato']);
        }

        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Buscamos el idRestaurante en el repositorio de la clase Restaurante
        $restaurante = $em->getRepository(Restaurante::class)
            ->find($datosPlato['restaurante']);
        // Validamos si no existe el Restaurante
        if ($restaurante === null) {
            return $this->error(Response::HTTP_NOT_FOUND, 'No existe el restaurante con id: ' . $datosPlato['restaurante']);
        }

        //Se modifican los datos del Plato
        $plato->setDescripcion($datosPlato['descripcion']);
        $plato->setPrecio($datosPlato['precio']);
        $plato->setFoto($datosPlato['foto']);
        $plato->setRestaurante($restaurante);
        $plato->setTipoplato($tipoPlato);

        $em = $this->getDoctrine()->getManager();
        $em->persist($plato); //Hacemos persistente $plato
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['Plato' => $plato],
            Response::HTTP_OK
        );
    }

    /**
     * @Route(path="/{id}", name="deletePlatoId", methods={ Request::METHOD_DELETE } )
     * @param Plato|null $plato
     * @return Response
     */
    public function deletePlatoId(?Plato $plato = null): Response
    {
        // Si el plato No existe
        if ($plato === null) {
            return $this->mensajeError404();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->remove($plato);
        $em->flush();
        // Devolvemos la respuesta
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(path="", name="deletePlatos", methods={ Request::METHOD_DELETE })
     * @return JsonResponse
     */
    public function deletePlatos(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $platos = $this->getDoctrine()
            ->getRepository(Plato::class)
            ->findAll();
        // Elimina cada resultado que obtiene del repositorio
        foreach ($platos as $plato) {
            $em->remove($plato);
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
