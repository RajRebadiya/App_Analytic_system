<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0.0', title: 'Multi Android App Management API')]
#[OA\Server(url: '/api/v1')]
#[OA\SecurityScheme(securityScheme: 'AppApiKey', type: 'apiKey', in: 'header', name: 'X-App-Key')]
#[OA\Schema(schema: 'ApiSuccess', properties: [
    new OA\Property(property: 'status_code', type: 'integer', example: 200),
    new OA\Property(property: 'message', type: 'string', example: 'Success message'),
    new OA\Property(property: 'data', type: 'object'),
])]
#[OA\Schema(schema: 'ValidationError', properties: [
    new OA\Property(property: 'status_code', type: 'integer', example: 422),
    new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
    new OA\Property(property: 'errors', type: 'object'),
])]
#[OA\Schema(schema: 'UniversalRequest', required: ['module', 'action'], description: 'Single endpoint request. Supported actions for apps: list, create, show, update, delete, install, heartbeat. For install and heartbeat, app credentials can be sent either as headers X-App-Id and X-App-Key, or inside payload as app_id and api_key.', properties: [
    new OA\Property(property: 'module', type: 'string', enum: ['apps'], description: 'Current supported module. Future modules will be added here.', example: 'apps'),
    new OA\Property(property: 'action', type: 'string', enum: ['list', 'create', 'show', 'update', 'delete', 'install', 'heartbeat'], description: 'Available actions: list, create, show, update, delete, install, heartbeat.', example: 'create'),
    new OA\Property(property: 'id', type: 'integer', nullable: true, description: 'Required for show, update, and delete. Can also be sent as payload.id.', example: 2),
    new OA\Property(property: 'payload', type: 'object', description: 'Action payload. create/update use name and version. install uses device_id, device_name, device_brand, android_version, app_version. heartbeat uses device_id and optional app_version. install/heartbeat may also include app_id and api_key.', example: ['name' => 'Jivanand', 'version' => '1.0.0']),
])]
#[OA\Post(path: '/universal', description: "Universal single API endpoint.\n\nActions available for module apps: list, create, show, update, delete, install, heartbeat.\n\nAdmin app CRUD examples:\n- List: {\"module\":\"apps\",\"action\":\"list\"}\n- Create: {\"module\":\"apps\",\"action\":\"create\",\"payload\":{\"name\":\"Jivanand\",\"version\":\"1.0.0\"}}\n- Show: {\"module\":\"apps\",\"action\":\"show\",\"id\":2}\n- Update: {\"module\":\"apps\",\"action\":\"update\",\"id\":2,\"payload\":{\"name\":\"Jivanand\",\"version\":\"1.1.0\"}}\n- Delete: {\"module\":\"apps\",\"action\":\"delete\",\"id\":2}\n\nInstall and heartbeat credentials:\n- Option 1 headers: X-App-Id and X-App-Key\n- Option 2 payload: app_id and api_key\n\nInstall payload example: {\"module\":\"apps\",\"action\":\"install\",\"payload\":{\"app_id\":\"jivanand-abc123\",\"api_key\":\"secret-key\",\"device_id\":\"device-12345\",\"device_name\":\"Pixel 8\",\"device_brand\":\"Google\",\"android_version\":\"15\",\"app_version\":\"1.0.0\"}}\n\nHeartbeat payload example: {\"module\":\"apps\",\"action\":\"heartbeat\",\"payload\":{\"app_id\":\"jivanand-abc123\",\"api_key\":\"secret-key\",\"device_id\":\"device-12345\",\"app_version\":\"1.0.0\"}}", tags: ['Universal'], parameters: [
    new OA\Parameter(name: 'X-App-Id', in: 'header', required: false, description: 'Optional for install/heartbeat when app_id is not sent inside payload.', schema: new OA\Schema(type: 'string'), example: 'jivanand-abc123'),
    new OA\Parameter(name: 'X-App-Key', in: 'header', required: false, description: 'Optional for install/heartbeat when api_key is not sent inside payload.', schema: new OA\Schema(type: 'string'), example: 'secret-key'),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UniversalRequest')), responses: [
    new OA\Response(response: 200, description: 'Universal action completed', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 201, description: 'Universal resource created', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 401, description: 'Invalid app credentials', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
])]
/*
|--------------------------------------------------------------------------
| Hidden Swagger endpoints
|--------------------------------------------------------------------------
| Only the universal API is visible for now. The old endpoints are still
| available in routes/api.php and can be documented again when needed.
|
| Commented/hidden from Swagger UI for future reference:
| - POST   /admin/register
| - POST   /admin/login
| - POST   /install
| - POST   /heartbeat
| - POST   /save-token
| - GET    /ads
| - GET    /ad-config
| - GET    /version-check
| - POST   /event
| - GET    /notifications
| - GET    /admin/dashboard
| - CRUD   /admin/apps
| - CRUD   /admin/ad-settings
| - CRUD   /admin/advertisements
| - CRUD   /admin/notifications
| - POST   /admin/notifications/{notification}/send
| - CRUD   /admin/app-versions
*/
class OpenApi {}
