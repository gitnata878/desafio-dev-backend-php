<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FormularioService
{
    /**
     * Valida os dados de preenchimento de acordo com as regras do formulário.
     */
    public function validateFormularioFields($data, $fields)
    {
        $errors = [];

        foreach ($fields as $field) {
            $fieldId = $field['id'];
            $isRequired = $field['required'];

            // Verificar campo obrigatório
            if ($isRequired && (!isset($data[$fieldId]) || empty($data[$fieldId]) || $data[$fieldId] === null)) {
                $errors[$fieldId][] = "O campo {$field['label']} é obrigatório.";
            }

            // Se o campo existe no dado, fazemos a validação de tipo
            if (isset($data[$fieldId])) {
                switch ($field['type']) {
                    case 'text':
                        if (!is_string($data[$fieldId])) {
                            $errors[$fieldId][] = "O campo {$field['label']} deve ser uma string.";
                        }
                        break;

                    case 'number':
                        if (!is_numeric($data[$fieldId])) {
                            $errors[$fieldId][] = "O campo {$field['label']} deve ser um número.";
                        }
                        break;

                    case 'select':
                        if (!isset($data[$fieldId]) || !is_string($data[$fieldId]) || !in_array($data[$fieldId], $field['choices'])) {
                            $errors[$fieldId][] = "O campo {$field['label']} deve ser uma das opções válidas.";
                        }
                        break;
                }
            }
        }

        return $errors;
    }


    /**
     * Obtém a definição do formulário a partir do arquivo JSON.
     */
    public function getFormulario($id)
    {
        $forms = json_decode(Storage::get('forms_definition.json'), true);
        return collect($forms)->firstWhere('id', $id);
    }

    /**
     * Salva o preenchimento do formulário.
     */
    public function savePreenchimento($id_formulario, $data)
    {
        $preenchimentos = Storage::exists("preenchimentos/{$id_formulario}.json")
            ? json_decode(Storage::get("preenchimentos/{$id_formulario}.json"), true)
            : [];

        $preenchimentos[] = $data;
        Storage::put("preenchimentos/{$id_formulario}.json", json_encode($preenchimentos));
    }

    /**
     * Obtém os preenchimentos do formulário.
     */
    public function getPreenchimentos($id_formulario)
    {
        if (!Storage::exists("preenchimentos/{$id_formulario}.json")) {
            return [];
        }

        return json_decode(Storage::get("preenchimentos/{$id_formulario}.json"), true);
    }
}
