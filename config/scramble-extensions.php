<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Scramble Extensions Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración adicional para mejorar la documentación de Scramble
    |
    */

    'tags' => [
        [
            'name' => 'Clientes',
            'description' => 'Endpoints para la gestión de clientes del sistema de pagos',
        ],
        [
            'name' => 'Beneficiarios',
            'description' => 'Endpoints para la gestión de beneficiarios asociados a clientes',
        ],
        [
            'name' => 'Órdenes de Pago',
            'description' => 'Endpoints para la gestión de órdenes de pago con validación de saldo',
        ],
        [
            'name' => 'Auditoría',
            'description' => 'Endpoints para consultar el historial de auditoría y transacciones',
        ],
    ],

    'schemas' => [
        'Client' => [
            'type' => 'object',
            'properties' => [
                'id' => ['type' => 'integer', 'example' => 1],
                'uuid' => ['type' => 'string', 'example' => '550e8400-e29b-41d4-a716-446655440000'],
                'name' => ['type' => 'string', 'example' => 'Juan Pérez'],
                'email' => ['type' => 'string', 'example' => 'juan@example.com'],
                'balance' => ['type' => 'number', 'format' => 'decimal', 'example' => 1000.00],
                'created_at' => ['type' => 'string', 'format' => 'date-time'],
                'updated_at' => ['type' => 'string', 'format' => 'date-time'],
            ],
        ],
        'Beneficiary' => [
            'type' => 'object',
            'properties' => [
                'id' => ['type' => 'integer', 'example' => 1],
                'uuid' => ['type' => 'string', 'example' => '550e8400-e29b-41d4-a716-446655440001'],
                'client_id' => ['type' => 'integer', 'example' => 1],
                'name' => ['type' => 'string', 'example' => 'María García'],
                'email' => ['type' => 'string', 'example' => 'maria@example.com'],
                'account_number' => ['type' => 'string', 'example' => '1234567890'],
                'bank_name' => ['type' => 'string', 'example' => 'Banco Nacional'],
                'identification' => ['type' => 'string', 'example' => '12345678'],
                'created_at' => ['type' => 'string', 'format' => 'date-time'],
                'updated_at' => ['type' => 'string', 'format' => 'date-time'],
            ],
        ],
        'PaymentOrder' => [
            'type' => 'object',
            'properties' => [
                'id' => ['type' => 'integer', 'example' => 1],
                'uuid' => ['type' => 'string', 'example' => '550e8400-e29b-41d4-a716-446655440002'],
                'client_id' => ['type' => 'integer', 'example' => 1],
                'total_amount' => ['type' => 'number', 'format' => 'decimal', 'example' => 300.00],
                'status' => ['type' => 'string', 'enum' => ['pending', 'successful', 'rejected'], 'example' => 'successful'],
                'created_at' => ['type' => 'string', 'format' => 'date-time'],
                'updated_at' => ['type' => 'string', 'format' => 'date-time'],
            ],
        ],
        'Payment' => [
            'type' => 'object',
            'properties' => [
                'id' => ['type' => 'integer', 'example' => 1],
                'uuid' => ['type' => 'string', 'example' => '550e8400-e29b-41d4-a716-446655440003'],
                'payment_order_id' => ['type' => 'integer', 'example' => 1],
                'beneficiary_id' => ['type' => 'integer', 'example' => 1],
                'amount' => ['type' => 'number', 'format' => 'decimal', 'example' => 100.00],
                'status' => ['type' => 'string', 'enum' => ['pending', 'successful', 'rejected'], 'example' => 'successful'],
                'created_at' => ['type' => 'string', 'format' => 'date-time'],
                'updated_at' => ['type' => 'string', 'format' => 'date-time'],
            ],
        ],
        'ApiResponse' => [
            'type' => 'object',
            'properties' => [
                'success' => ['type' => 'boolean', 'example' => true],
                'message' => ['type' => 'string', 'example' => 'Operación exitosa'],
                'data' => ['type' => 'object'],
                'timestamp' => ['type' => 'string', 'format' => 'date-time'],
            ],
        ],
        'Pagination' => [
            'type' => 'object',
            'properties' => [
                'current_page' => ['type' => 'integer', 'example' => 1],
                'last_page' => ['type' => 'integer', 'example' => 5],
                'per_page' => ['type' => 'integer', 'example' => 15],
                'total' => ['type' => 'integer', 'example' => 75],
                'from' => ['type' => 'integer', 'example' => 1],
                'to' => ['type' => 'integer', 'example' => 15],
            ],
        ],
    ],
];
