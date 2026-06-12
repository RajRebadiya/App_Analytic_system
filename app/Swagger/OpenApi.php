<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0.0', title: 'Multi Android App Management API')]
#[OA\Server(url: '/api/v1')]
#[OA\SecurityScheme(securityScheme: 'AppPackageName', type: 'apiKey', in: 'header', name: 'app_package_name')]
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
#[OA\Schema(schema: 'AdConfigPayload', properties: [
    new OA\Property(property: 'app_name', type: 'string', example: 'My News App'),
    new OA\Property(property: 'app_db_id', type: 'integer', example: 1),
    new OA\Property(property: 'app_package_name', type: 'string', example: 'com.example.jivanand'),
    new OA\Property(property: 'package_name', type: 'string', example: 'com.appanalytics.mynewsapp.abc12345'),
    new OA\Property(property: 'admob_interid', type: 'string', example: 'ca-app-pub-3940256099942544/1033173712'),
    new OA\Property(property: 'admob_bannerid', type: 'string', example: 'ca-app-pub-3940256099942544/6300978111'),
    new OA\Property(property: 'admob_medium_rectangleid', type: 'string', example: 'ca-app-pub-3940256099942544/6300978111'),
    new OA\Property(property: 'admob_nativeid', type: 'string', example: 'ca-app-pub-3940256099942544/2247696110'),
    new OA\Property(property: 'admob_appopenid', type: 'string', example: 'ca-app-pub-3940256099942544/9257395921'),
    new OA\Property(property: 'adx_inter_id', type: 'string', example: '499/example/interstitial'),
    new OA\Property(property: 'adx_banner_id', type: 'string', example: '499/example/adaptive-banner'),
    new OA\Property(property: 'adx_medium_rectangleid', type: 'string', example: '499/example/adaptive-banner'),
    new OA\Property(property: 'adx_native_id', type: 'string', example: '499/example/native'),
    new OA\Property(property: 'adx_appopen_id', type: 'string', example: '499/example/app-open'),
    new OA\Property(property: 'fb_inter_id', type: 'string', example: ''),
    new OA\Property(property: 'fb_banner_id', type: 'string', example: ''),
    new OA\Property(property: 'fb_medium_rectangle_id', type: 'string', example: ''),
    new OA\Property(property: 'fb_native_id', type: 'string', example: ''),
    new OA\Property(property: 'fb_native_banner_id', type: 'string', example: ''),
    new OA\Property(property: 'wortise_app_id', type: 'string', example: ''),
    new OA\Property(property: 'wortise_appopen_id', type: 'string', example: ''),
    new OA\Property(property: 'wortise_inter_id', type: 'string', example: ''),
    new OA\Property(property: 'wortise_banner_id', type: 'string', example: ''),
    new OA\Property(property: 'wortise_medium_rectangle_id', type: 'string', example: ''),
    new OA\Property(property: 'wortise_native_id', type: 'string', example: ''),
    new OA\Property(property: 'inter_count', type: 'string', example: '2'),
    new OA\Property(property: 'ad_splash', type: 'string', example: 'splash_appopen'),
    new OA\Property(property: 'ad_inter', type: 'string', example: 'admob'),
    new OA\Property(property: 'ad_appopen', type: 'string', example: 'appopen'),
    new OA\Property(property: 'ad_native', type: 'string', example: 'admob'),
    new OA\Property(property: 'ad_small_native', type: 'string', example: 'admob'),
    new OA\Property(property: 'ad_banner', type: 'string', example: 'admob'),
    new OA\Property(property: 'ad_qureka', type: 'string', example: 'off'),
    new OA\Property(property: 'privacy_url', type: 'string', example: 'https://example.com/privacy-policy.html'),
    new OA\Property(property: 'redirect_app', type: 'string', example: ''),
    new OA\Property(property: 'new_app_name', type: 'string', example: ''),
    new OA\Property(property: 'new_app_icon', type: 'string', example: ''),
    new OA\Property(property: 'new_app_banner', type: 'string', example: ''),
    new OA\Property(property: 'new_app_body', type: 'string', example: ''),
    new OA\Property(property: 'new_app_link', type: 'string', example: ''),
    new OA\Property(property: 'Download', type: 'string', example: 'off'),
    new OA\Property(property: 'Backgraound', type: 'string', example: 'off'),
    new OA\Property(property: 'Popup', type: 'string', example: 'off'),
    new OA\Property(property: 'Main_click', type: 'string', example: 'on'),
])]
#[OA\Schema(schema: 'AdConfigResponse', properties: [
    new OA\Property(property: 'status_code', type: 'integer', example: 200),
    new OA\Property(property: 'message', type: 'string', example: 'Ad config fetched'),
    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/AdConfigPayload')),
])]
#[OA\Schema(schema: 'UniversalRequest', required: ['module', 'action'], description: 'Single endpoint request. Supported actions for apps: list, create, show, update, delete, install, heartbeat, save-token. For install, heartbeat, and save-token, identify the app with app_package_name header or payload.app_package_name.', properties: [
    new OA\Property(property: 'module', type: 'string', enum: ['apps'], description: 'Current supported module. Future modules will be added here.', example: 'apps'),
    new OA\Property(property: 'action', type: 'string', enum: ['list', 'create', 'show', 'update', 'delete', 'install', 'heartbeat', 'save-token', 'save-fcm'], description: 'Available actions: list, create, show, update, delete, install, heartbeat, save-token.', example: 'create'),
    new OA\Property(property: 'id', type: 'integer', nullable: true, description: 'Required for show, update, and delete. Can also be sent as payload.id.', example: 2),
    new OA\Property(property: 'payload', type: 'object', description: 'Action payload. create/update use name and package_name. install uses device_id, device_name, device_brand, android_version, app_version. heartbeat uses device_id and optional app_version. save-token uses device_id, fcm_token, and optional is_active.', example: ['name' => 'Jivanand', 'package_name' => 'com.example.jivanand']),
])]
#[OA\Post(path: '/universal', description: "Universal single API endpoint.\n\nActions available for module apps: list, create, show, update, delete, install, heartbeat, save-token/save-fcm.\n\nAdmin app CRUD examples:\n- List: {\"module\":\"apps\",\"action\":\"list\"}\n- Create: {\"module\":\"apps\",\"action\":\"create\",\"payload\":{\"name\":\"Jivanand\",\"package_name\":\"com.example.jivanand\"}}\n- Show: {\"module\":\"apps\",\"action\":\"show\",\"id\":2}\n- Update: {\"module\":\"apps\",\"action\":\"update\",\"id\":2,\"payload\":{\"name\":\"Jivanand\",\"package_name\":\"com.example.jivanand\"}}\n- Delete: {\"module\":\"apps\",\"action\":\"delete\"}\n\nApp credential: send app_package_name header or payload.app_package_name.\n\nInstall payload example: {\"module\":\"apps\",\"action\":\"install\",\"payload\":{\"app_package_name\":\"com.example.jivanand\",\"device_id\":\"device-12345\",\"device_name\":\"Pixel 8\",\"device_brand\":\"Google\",\"android_version\":\"15\",\"app_version\":\"1.0.0\"}}\n\nHeartbeat payload example: {\"module\":\"apps\",\"action\":\"heartbeat\",\"payload\":{\"app_package_name\":\"com.example.jivanand\",\"device_id\":\"device-12345\",\"app_version\":\"1.0.0\"}}\n\nSave FCM token example: {\"module\":\"apps\",\"action\":\"save-token\",\"payload\":{\"app_package_name\":\"com.example.jivanand\",\"device_id\":\"device-12345\",\"fcm_token\":\"firebase-device-token\",\"is_active\":true}}", tags: ['Universal'], parameters: [
    new OA\Parameter(name: 'app_package_name', in: 'header', required: false, description: 'Optional when app_package_name is sent inside payload.', schema: new OA\Schema(type: 'string'), example: 'com.example.jivanand'),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UniversalRequest')), responses: [
    new OA\Response(response: 200, description: 'Universal action completed', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 201, description: 'Universal resource created', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 401, description: 'Invalid app package name', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
])]
#[OA\Get(path: '/ad-config', description: 'Fetch Android ad config in the new mobile contract. Response data is always an array with one object.', tags: ['Android App'], parameters: [
    new OA\Parameter(name: 'app_package_name', in: 'header', required: true, schema: new OA\Schema(type: 'string'), example: 'com.example.jivanand'),
], responses: [
    new OA\Response(response: 200, description: 'Ad config fetched', content: new OA\JsonContent(ref: '#/components/schemas/AdConfigResponse')),
    new OA\Response(response: 401, description: 'Invalid app package name', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Post(path: '/admin/ad-settings', description: 'Create or update ad settings. Payload accepts the same ad config keys returned to the Android app. Identify the app with app_package_name, package_name, app_db_id, or numeric app_id.', tags: ['Admin Ad Settings'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AdConfigPayload')), responses: [
    new OA\Response(response: 201, description: 'Ad settings saved', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'status_code', type: 'integer', example: 201),
        new OA\Property(property: 'message', type: 'string', example: 'Ad settings saved'),
        new OA\Property(property: 'data', ref: '#/components/schemas/AdConfigPayload'),
    ])),
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
