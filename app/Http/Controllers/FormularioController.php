<?php

namespace App\Http\Controllers;

use App\Services\FormularioService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FormularioController extends Controller
{
    /**
     * @OA\Info(
     *     title="Desafio Desenvolvedor BackEnd PHP",
     *     description="Este projeto é uma API Laravel que gerencia formulários dinâmicos e seus preenchimentos. O sistema armazena os dados dos formulários em arquivos JSON e permite que os usuários preencham e consultem esses formulários via uma interface RESTful",
     *     version="1.0.0"
     * )
     */

    protected $formularioService;

    public function __construct(FormularioService $formularioService)
    {
        $this->formularioService = $formularioService;
    }



   /**
     * @OA\Post(
     *     path="/api/formularios/{id_formulario}/preenchimentos",
     *     summary="Preenche um formulário",
     *     tags={"Formulário"},
     *     @OA\Parameter(
     *         name="id_formulario",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="form-2"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 additionalProperties={
     *                     @OA\Property(property="key", type="string")
     *                 },
     *                 example={
     *                     "field-2-1": "Smartphone X",
     *                     "field-2-2": 30,
     *                     "field-2-3": "Alimentos"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Formulário preenchido com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Formulário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação dos dados enviados",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="details", type="array", items={
     *                 @OA\Items(type="string")
     *             })
     *         )
     *     )
     * )
     */


    public function store(Request $request, $id_formulario)
    {
        $formulario = $this->formularioService->getFormulario($id_formulario);

        if (!$formulario) {
            return response()->json(['error' => 'Formulário não encontrado'], 404);
        }

        $errors = $this->formularioService->validateFormularioFields($request->all(), $formulario['fields']);

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }


        $this->formularioService->savePreenchimento($id_formulario, $request->all());

        return response()->json([
            'message' => 'Formulário preenchido com sucesso',
            'data' => $request->all(),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/formularios/{id_formulario}/preenchimentos",
     *     tags={"Formulários"},
     *     summary="Obter preenchimentos de formulário",
     *     operationId="getPreenchimentos",
     *     @OA\Parameter(
     *         name="id_formulario",
     *         in="path",
     *         description="ID do formulário",
     *         required=true,
     *         @OA\Schema(type="string", example="form-2")
     *     ),
     *     responses={
     *         @OA\Response(
     *             response="200",
     *             description="Lista de preenchimentos do formulário"
     *         ),
     *         @OA\Response(
     *             response="404",
     *             description="Formulário não encontrado"
     *         )
     *     }
     * )
     */

    public function index($id_formulario)
    {
        $formulario = $this->formularioService->getFormulario($id_formulario);

        if (!$formulario) {
            return response()->json(['error' => 'Formulário não encontrado'], 404);
        }

        $preenchimentos = $this->formularioService->getPreenchimentos($id_formulario);

        if (empty($preenchimentos)) {
            return response()->json(['message' => 'Nenhum preenchimento encontrado'], 200);
        }

        return response()->json($preenchimentos, 200);
    }
}
