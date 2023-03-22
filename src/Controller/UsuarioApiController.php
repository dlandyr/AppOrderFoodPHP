<?php

namespace App\Controller;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class UsuarioApiController
 *
 * @package App\Controller
 *
 * @Route(path=UsuarioApiController::USUARIO_API_PATH, name="api_usuarios_")
 */
class UsuarioApiController extends AbstractController
{
    //Creación de constante para almacenar la ruta de la api usuarios
     const USUARIO_API_PATH = "/api/v1/usuarios";

    /**
     * @Route(path="", name="getUsuarios", methods={ Request::METHOD_GET })
     * @return Response
     */
    public function getUsuarios(): Response
    {
        $usuarios = $this->getDoctrine()                // Usamos el gestor de entidades de Doctrine
        ->getRepository(Usuario::class)  // Obtenemos los usuarios del repositorio
        ->findAll();                               // Método que recupera todos los usuarios
        // Validamos la respuesta $usuarios
        return ($usuarios === null)
            ? $this->mensajeError404()                   // Si no hay $usuarios llamamos al método mensajeError404()
            : new JsonResponse(['usuarios' => $usuarios]); // Devolvemos nuestro array de usuarios en formato json
    }

    /**
     * @Route(path="/{id}", name="getUsuarioId",  methods={ Request::METHOD_GET } )
     * @param Usuario|null $usuario
     * @return Response
     */
    public function getUsuarioId(?Usuario $usuario = null): Response
    {
        // Validamos la respuesta $usuario
        return ($usuario === null)
            ? $this->mensajeError404()                  // Si no hay $usuario llamamos al método mensajeError404()
            : new JsonResponse(['usuario' => $usuario]);  // Devolvemos nuestro array con los datos del usuario en formato json
    }

    /**
     * @Route(path="", name="postUsuario", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postUsuario(Request $request): Response
    {
        $peticion = $request->getContent();
        $datosUsuario = json_decode($peticion, true);

        // Si no envía nombres
        if (!array_key_exists('nombres', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe nombres
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['nombres' => $datosUsuario['nombres']])) {
            return $this->mensajeError400();
        }
        // Si no envía apellidos
        if (!array_key_exists('apellidos', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe apellidos
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['apellidos' => $datosUsuario['apellidos']])) {
            return $this->mensajeError400();
        }

        // Si no envía username
        if (!array_key_exists('username', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe username
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['username' => $datosUsuario['username']])) {
            return $this->mensajeError400();
        }

        // Si no envía password
        if (!array_key_exists('password', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe password
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['password' => $datosUsuario['password']])) {
            return $this->mensajeError400();
        }

        // Si no envía email
        if (!array_key_exists('email', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe este email
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['email' => $datosUsuario['email']])) {
            return $this->mensajeError400();
        }

        // Asignamos los parametros al constructor para crear el objeto Usuario
        $usuario = new Usuario(
            $datosUsuario['nombres'],
            $datosUsuario['apellidos'],
            $datosUsuario['username'],
            $datosUsuario['password'],
            $datosUsuario['email'],
            $datosUsuario['telefono'],
            $datosUsuario['direccion']
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($usuario); //Hacemos persistente $usuario
        $em->flush();
        // Devuelve la respuesta
        return new JsonResponse(
            ['usuario' => $usuario],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="putUsuarioId", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putUsuarioId(?Usuario $usuario = null, Request $request): Response
    {
        if ($usuario === null) {
            return $this->mensajeError404();
        }

        $peticion = $request->getContent();
        $datosUsuario = json_decode($peticion, true);

        // Si no envía nombres
        if (!array_key_exists('nombres', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe nombres
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['nombres' => $datosUsuario['nombres']])) {
            return $this->mensajeError400();
        }
        // Si no envía apellidos
        if (!array_key_exists('apellidos', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe apellidos
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['apellidos' => $datosUsuario['apellidos']])) {
            return $this->mensajeError400();
        }

        // Si no envía username
        if (!array_key_exists('username', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe username
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['username' => $datosUsuario['username']])) {
            return $this->mensajeError400();
        }

        // Si no envía password
        if (!array_key_exists('password', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe password
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['password' => $datosUsuario['password']])) {
            return $this->mensajeError400();
        }

        // Si no envía email
        if (!array_key_exists('email', $datosUsuario)) {
            return $this->mensajeError422();
        }
        // Verifica en el repositorio de Usuario si ya existe este email
        if ($this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findOneBy(['email' => $datosUsuario['email']])) {
            return $this->mensajeError400();
        }

        //Se modifican los parámetros del usuario
        $usuario->setNombres($datosUsuario['nombres']);
        $usuario->setApellidos($datosUsuario['apellidos']);
        $usuario->setUsername($datosUsuario['username']);
        $usuario->setPassword($datosUsuario['password']);
        $usuario->setEmail($datosUsuario['email']);
        $usuario->setTelefono($datosUsuario['telefono']);
        $usuario->setDireccion($datosUsuario['direccion']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($usuario); //Hacemos persistente $usuario
        $em->flush();

        // Devuelve la respuesta
        return new JsonResponse(
            ['usuario' => $usuario],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="deleteUsuarioId", methods={ Request::METHOD_DELETE } )
     * @param Usuario|null $usuario
     * @return Response
     */
    public function deleteUsuarioId(?Usuario $usuario = null): Response
    {
        // Si el usuario No existe
        if ($usuario === null) {
            return $this->mensajeError404();
        }
        // Usamos el gestor de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->remove($usuario);
        $em->flush();
        // Devuelve la respuesta
        return new JsonResponse(
            ['user' => $usuario],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route(path="", name="deleteUsuarios", methods={ Request::METHOD_DELETE })
     * @return JsonResponse
     */
    public function deleteUsuarios(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findAll();
        // Elimina cada usuario que obtiene del repositorio
        foreach ($usuarios as $usuario) {
            $em->remove($usuario);
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
