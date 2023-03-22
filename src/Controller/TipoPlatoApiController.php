<?php

namespace App\Controller;
use App\Entity\TipoPlato;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TipoPlatoApiController
 *
 * @package App\Controller
 *
 * @Route(path=TipoPlatoApiController::TIPOPLATO_API_PATH, name="api_tipoPlato_")
 */
class TipoPlatoApiController extends AbstractController
{
    //Creación de constante para almacenar la ruta de la api usuarios
    const TIPOPLATO_API_PATH = "/api/v1/tipoPlatos";

    /**
     * @Route(path="", name="getTipoPlatos", methods={ Request::METHOD_GET })
     * @return Response
     */
    public function getTipoPlatos(): Response
    {
        $tipoPlatos = $this->getDoctrine()                // Usamos el gestor de entidades de Doctrine
        ->getRepository(TipoPlato::class)  // Obtenemos los usuarios del repositorio
        ->findAll();                               // Método que recupera todos los tipoPlatos
        // Validamos la respuesta $usuarios
        return ($tipoPlatos === null)
            ? $this->mensajeError404()                   // Si no hay $tipoPlatos llamamos al método mensajeError404()
            : new JsonResponse(['tipoPlatos' => $tipoPlatos]); // Devolvemos nuestro array de tipoPlatos en formato json
    }

    /**
     * @Route(path="/{id}", name="getTipoPlatoId",  methods={ Request::METHOD_GET } )
     * @param TipoPlato|null $tipoPlato
     * @return Response
     */
    public function getTipoPlatoId(?TipoPlato $tipoPlato = null): Response
    {
        // Validamos la respuesta $tipoPlato
        return ($tipoPlato === null)
            ? $this->mensajeError404()                  // Si no hay $tipoPlato llamamos al método mensajeError404()
            : new JsonResponse(['tipoPlato' => $tipoPlato]);  // Devolvemos nuestro array con los datos del tipoPlato en formato json
    }

    /**
     * @Route(path="", name="postTipoPlato", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postTipoPlato(Request $request): Response
    {
        $peticion = $request->getContent();
        $datosTipoPlato = json_decode($peticion, true);

        // Si no envía descripcion
        if (!array_key_exists('descripcion', $datosTipoPlato)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de TipoPlatos si ya existe descripciones
        if ($this->getDoctrine()
            ->getRepository(TipoPlato::class)
            ->findOneBy(['descripcion' => $datosTipoPlato['descripcion']])) {
            return $this->mensajeError400();
        }

        // Asignamos los parametros al constructor para crear el objeto TipoPlato
        $tipoPlato = new TipoPlato(
            $datosTipoPlato['descripcion']
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($tipoPlato); //Hacemos persistente $tipoPlato
        $em->flush();
        // Devuelve la respuesta
        return new JsonResponse(
            ['tipoPlato' => $tipoPlato],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="putTipoPlatoId", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putTipoPlatoId(?TipoPlato $tipoPlato = null, Request $request): Response
    {
        if ($tipoPlato === null) {
            return $this->mensajeError404();
        }

        $peticion = $request->getContent();
        $datosTipoPlato = json_decode($peticion, true);

        // Si no envía descripcion
        if (!array_key_exists('descripcion', $datosTipoPlato)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de TipoPlatos si ya existe descripcion
        if ($this->getDoctrine()
            ->getRepository(TipoPlato::class)
            ->findOneBy(['descripcion' => $datosTipoPlato['descripcion']])) {
            return $this->mensajeError400();
        }

        //Se modifican los parámetros del TipoPlato
        $tipoPlato->setDescripcion($datosTipoPlato['descripcion']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($tipoPlato); //Hacemos persistente $tipoPlato
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['tipoPlato' => $tipoPlato],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="deleteTipoPlatoId", methods={ Request::METHOD_DELETE } )
     * @param TipoPlato|null $tipoPlato
     * @return Response
     */
    public function deleteTipoPlatoId(?TipoPlato $tipoPlato = null): Response
    {
        // Si el tipoPlato No existe
        if ($tipoPlato === null) {
            return $this->mensajeError404();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->remove($tipoPlato);
        $em->flush();
        // Devuelve la respuesta
        return new JsonResponse(
            ['tipoPlato' => $tipoPlato],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route(path="", name="deleteTipoPlatos", methods={ Request::METHOD_DELETE })
     * @return JsonResponse
     */
    public function deleteTipoPlatos(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $tipoPlatos = $this->getDoctrine()
            ->getRepository(TipoPlato::class)
            ->findAll();

        // Elimina cada tipoPlato que obtiene del repositorio
        foreach ($tipoPlatos as $tipoPlato) {
            $em->remove($tipoPlato);
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
