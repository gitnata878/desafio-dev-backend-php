<?php

namespace Tests\Feature;

use Tests\TestCase;

class FormularioTest extends TestCase
{
    // Teste de preenchimento de formulário com sucesso
    public function testFormularioPreenchidoComSucesso()
    {
        $response = $this->postJson('/api/formularios/form-2/preenchimentos', [
            'field-2-1' => 'Smartphone X',
            'field-2-2' => 1200.50,
            'field-2-3' => 'Eletrônicos',
        ]);

        $response->assertStatus(201);  // Verifique a resposta com sucesso
        $response->assertJsonStructure([
            'message',
            'data',
        ]);
    }

    // Teste de formulário inexistente
    public function testFormularioNaoExistente()
    {
        $response = $this->postJson('/api/formularios/form-inexistente/preenchimentos', [
            'field-2-1' => 'Smartphone X',
            'field-2-2' => 1200.50,
            'field-2-3' => 'Eletrônicos',
        ]);

        $response->assertStatus(404);  // Esperando erro 404
    }

    // Teste de campo obrigatório não preenchido
    public function testCampoObrigatorioNaoPreenchido()
    {
        $response = $this->postJson('/api/formularios/form-2/preenchimentos', [
            'field-2-2' => 1200.50,
            'field-2-3' => 'Eletrônicos',
        ]);

        $response->assertStatus(422);  // Esperando erro 422 de validação
        $response->assertJsonValidationErrors(['field-2-1']);
    }

    // Teste de campo select com valor inválido
    public function testCampoSelectComValorInvalido()
    {
        $response = $this->postJson('/api/formularios/form-2/preenchimentos', [
            'field-2-1' => 'Smartphone X',
            'field-2-2' => 1200.50,
            'field-2-3' => 'Carros',  // Valor inválido para campo de escolha
        ]);

        $response->assertStatus(422);  // Esperando erro 422 de validação
        $response->assertJsonValidationErrors(['field-2-3']);
    }

    //Teste de campo text deve ser uma string (erro de validação)
    public function testCampoTextDeveSerUmaString()
    {
        $response = $this->postJson('/api/formularios/form-2/preenchimentos', [
            'field-2-1' => 12345,  // Valor inválido (número em vez de string)
            'field-2-2' => 1200.50,
            'field-2-3' => 'Eletrônicos',
        ]);

        $response->assertStatus(422);  // Esperando erro 422 de validação
        $response->assertJsonValidationErrors(['field-2-1']);  // Esperando erro no campo text
    }

    //Teste de campo number deve ser um número (erro de validação)
    public function testCampoNumberDeveSerUmNumero()
    {
        $response = $this->postJson('/api/formularios/form-2/preenchimentos', [
            'field-2-1' => 'Smartphone X',
            'field-2-2' => 'dois mil',  // Valor inválido (não é um número)
            'field-2-3' => 'Eletrônicos',
        ]);

        $response->assertStatus(422);  // Esperando erro 422 de validação
        $response->assertJsonValidationErrors(['field-2-2']);  // Esperando erro no campo number
    }

}
