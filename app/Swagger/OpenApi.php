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
#[OA\Schema(schema: 'AdminRegisterRequest', required: ['name', 'email', 'password', 'password_confirmation'], properties: [
    new OA\Property(property: 'name', type: 'string', example: 'Admin User'),
    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password123'),
])]
#[OA\Schema(schema: 'AdminLoginRequest', required: ['email', 'password'], properties: [
    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
])]
#[OA\Schema(schema: 'AdminAppRequest', required: ['name', 'version'], properties: [
    new OA\Property(property: 'name', type: 'string', example: 'Jivanand'),
    new OA\Property(property: 'version', type: 'string', example: '1.0.0'),
])]
#[OA\Schema(schema: 'InstallRequest', required: ['device_id', 'app_version'], properties: [
    new OA\Property(property: 'device_id', type: 'string', example: 'device-12345'),
    new OA\Property(property: 'device_name', type: 'string', nullable: true, example: 'Pixel 8'),
    new OA\Property(property: 'device_brand', type: 'string', nullable: true, example: 'Google'),
    new OA\Property(property: 'android_version', type: 'string', nullable: true, example: '15'),
    new OA\Property(property: 'app_version', type: 'string', example: '1.0.0'),
])]
#[OA\Schema(schema: 'HeartbeatRequest', required: ['device_id'], properties: [
    new OA\Property(property: 'device_id', type: 'string', example: 'device-12345'),
    new OA\Property(property: 'app_version', type: 'string', nullable: true, example: '1.0.0'),
])]
#[OA\Schema(schema: 'SaveTokenRequest', required: ['device_id', 'fcm_token'], properties: [
    new OA\Property(property: 'device_id', type: 'string', example: 'device-12345'),
    new OA\Property(property: 'fcm_token', type: 'string', example: 'firebase-fcm-token'),
    new OA\Property(property: 'is_active', type: 'boolean', nullable: true, example: true),
])]
#[OA\Schema(schema: 'EventRequest', required: ['device_id', 'event_name'], properties: [
    new OA\Property(property: 'device_id', type: 'string', example: 'device-12345'),
    new OA\Property(property: 'event_name', type: 'string', enum: ['app_open', 'screen_view', 'ad_click', 'notification_open', 'button_click'], example: 'screen_view'),
    new OA\Property(property: 'event_data', type: 'object', nullable: true, example: ['screen' => 'home']),
])]
#[OA\Schema(schema: 'AdvertisementRequest', required: ['app_id', 'title', 'redirect_type', 'status'], properties: [
    new OA\Property(property: 'app_id', type: 'integer', example: 2),
    new OA\Property(property: 'title', type: 'string', example: 'Summer Offer'),
    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Limited time promotion'),
    new OA\Property(property: 'image', type: 'string', nullable: true, example: 'https://example.com/ad.jpg'),
    new OA\Property(property: 'image_file', type: 'string', format: 'binary', nullable: true, description: 'Optional multipart image upload'),
    new OA\Property(property: 'redirect_type', type: 'string', enum: ['url', 'screen', 'category', 'product'], example: 'url'),
    new OA\Property(property: 'redirect_value', type: 'string', nullable: true, example: 'https://example.com'),
    new OA\Property(property: 'start_date', type: 'string', format: 'date-time', nullable: true, example: '2026-06-01 00:00:00'),
    new OA\Property(property: 'end_date', type: 'string', format: 'date-time', nullable: true, example: '2026-06-30 23:59:59'),
    new OA\Property(property: 'priority', type: 'integer', nullable: true, example: 10),
    new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
])]
#[OA\Schema(schema: 'NotificationRequest', required: ['app_id', 'title', 'notification_type', 'send_to'], properties: [
    new OA\Property(property: 'app_id', type: 'integer', example: 2),
    new OA\Property(property: 'title', type: 'string', example: 'New Update'),
    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Open the app to see new content'),
    new OA\Property(property: 'image', type: 'string', nullable: true, example: 'https://example.com/notification.jpg'),
    new OA\Property(property: 'image_file', type: 'string', format: 'binary', nullable: true, description: 'Optional multipart image upload'),
    new OA\Property(property: 'notification_type', type: 'string', example: 'general'),
    new OA\Property(property: 'send_to', type: 'string', enum: ['all', 'active'], example: 'all'),
    new OA\Property(property: 'redirect_screen', type: 'string', nullable: true, example: 'product_detail'),
    new OA\Property(property: 'redirect_data', type: 'object', nullable: true, example: ['product_id' => 1001]),
    new OA\Property(property: 'send_now', type: 'boolean', nullable: true, example: false),
])]
#[OA\Schema(schema: 'AppVersionRequest', required: ['app_id', 'latest_version', 'min_supported_version'], properties: [
    new OA\Property(property: 'app_id', type: 'integer', example: 2),
    new OA\Property(property: 'latest_version', type: 'string', example: '1.1.0'),
    new OA\Property(property: 'min_supported_version', type: 'string', example: '1.0.0'),
    new OA\Property(property: 'force_update', type: 'boolean', nullable: true, example: false),
    new OA\Property(property: 'maintenance_mode', type: 'boolean', nullable: true, example: false),
    new OA\Property(property: 'apk_url', type: 'string', format: 'uri', nullable: true, example: 'https://example.com/app.apk'),
    new OA\Property(property: 'message', type: 'string', nullable: true, example: 'New version available'),
    new OA\Property(property: 'change_log', type: 'string', nullable: true, example: 'Bug fixes and performance improvements'),
])]
#[OA\Schema(schema: 'AdConfigResponse', properties: [
    new OA\Property(property: 'is_active', type: 'boolean', example: true),
    new OA\Property(property: 'app_adShowStatus', type: 'integer', example: 1),
    new OA\Property(property: 'app_howShowAd', type: 'integer', example: 0),
    new OA\Property(property: 'app_adPlatformSequence', type: 'string', example: 'Admob'),
    new OA\Property(property: 'app_alernateAdShow', type: 'string', example: 'Admob'),
    new OA\Property(property: 'app_mainClickCntSwAd', type: 'integer', example: 1),
    new OA\Property(property: 'app_innerClickCntSwAd', type: 'integer', example: 1),
    new OA\Property(property: 'app_dialogBeforeAdShow', type: 'integer', example: 0),
    new OA\Property(property: 'ad_dialog_time_in_second', type: 'integer', example: 2),
    new OA\Property(property: 'am_ad_showAdStatus', type: 'integer', example: 1),
    new OA\Property(property: 'am_AppID', type: 'string', example: 'ca-app-pub-xxxxxxxx~yyyyyyyy'),
    new OA\Property(property: 'am_Banner1', type: 'string', example: 'ca-app-pub-xxxxxxxx/yyyyyyyy'),
    new OA\Property(property: 'am_Interstitial1', type: 'string', example: 'ca-app-pub-xxxxxxxx/yyyyyyyy'),
    new OA\Property(property: 'am_Native1', type: 'string', example: 'ca-app-pub-xxxxxxxx/yyyyyyyy'),
    new OA\Property(property: 'am_RewardedVideo1', type: 'string', example: 'ca-app-pub-xxxxxxxx/yyyyyyyy'),
    new OA\Property(property: 'MORE_APP', type: 'string', example: 'https://example.com/more-apps.json'),
])]
#[OA\Schema(schema: 'AdConfigApiResponse', properties: [
    new OA\Property(property: 'status_code', type: 'integer', example: 200),
    new OA\Property(property: 'message', type: 'string', example: 'Ad config fetched'),
    new OA\Property(property: 'data', ref: '#/components/schemas/AdConfigResponse'),
])]
#[OA\Schema(schema: 'AdNetworkSettingRequest', required: ['app_id', 'how_show_ad', 'main_click_count', 'inner_click_count', 'dialog_time_seconds'], properties: [
    new OA\Property(property: 'app_id', type: 'integer', example: 2),
    new OA\Property(property: 'is_active', type: 'boolean', nullable: true, example: true),
    new OA\Property(property: 'ad_show_status', type: 'boolean', nullable: true, example: true),
    new OA\Property(property: 'admob_status', type: 'boolean', nullable: true, example: true),
    new OA\Property(property: 'admob_app_id', type: 'string', nullable: true, example: 'ca-app-pub-xxxxxxxx~yyyyyyyy'),
    new OA\Property(property: 'admob_banner_id', type: 'string', nullable: true, example: 'ca-app-pub-xxxxxxxx/banner'),
    new OA\Property(property: 'admob_interstitial_id', type: 'string', nullable: true, example: 'ca-app-pub-xxxxxxxx/interstitial'),
    new OA\Property(property: 'admob_native_id', type: 'string', nullable: true, example: 'ca-app-pub-xxxxxxxx/native'),
    new OA\Property(property: 'admob_rewarded_id', type: 'string', nullable: true, example: 'ca-app-pub-xxxxxxxx/rewarded'),
    new OA\Property(property: 'how_show_ad', type: 'integer', enum: [0, 1], example: 0, description: '0=sequence, 1=alternate'),
    new OA\Property(property: 'ad_platform_sequence', type: 'string', nullable: true, example: 'Admob'),
    new OA\Property(property: 'alternate_ad_show', type: 'string', nullable: true, example: 'Admob'),
    new OA\Property(property: 'main_click_count', type: 'integer', example: 1),
    new OA\Property(property: 'inner_click_count', type: 'integer', example: 1),
    new OA\Property(property: 'dialog_before_ad_show', type: 'boolean', nullable: true, example: false),
    new OA\Property(property: 'dialog_time_seconds', type: 'integer', example: 2),
    new OA\Property(property: 'need_internet', type: 'boolean', nullable: true, example: false),
    new OA\Property(property: 'redirect_other_app_status', type: 'boolean', nullable: true, example: false),
    new OA\Property(property: 'new_package_name', type: 'string', nullable: true, example: 'com.example.newapp'),
    new OA\Property(property: 'update_dialog_status', type: 'boolean', nullable: true, example: false),
    new OA\Property(property: 'version_codes', type: 'string', nullable: true, example: '1,2,3'),
    new OA\Property(property: 'privacy_policy_url', type: 'string', format: 'uri', nullable: true, example: 'https://example.com/privacy'),
    new OA\Property(property: 'more_app_url', type: 'string', format: 'uri', nullable: true, example: 'https://example.com/more-apps.json'),
])]
#[OA\Post(path: '/admin/register', tags: ['Admin Auth'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AdminRegisterRequest')), responses: [
    new OA\Response(response: 201, description: 'Create admin user', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
])]
#[OA\Post(path: '/admin/login', tags: ['Admin Auth'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AdminLoginRequest')), responses: [
    new OA\Response(response: 200, description: 'Admin login', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
])]
#[OA\Post(path: '/install', tags: ['App'], security: [['AppApiKey' => []]], parameters: [
    new OA\Parameter(name: 'X-App-Id', in: 'header', required: true, schema: new OA\Schema(type: 'string'), example: 'jivanand-1cmdewqs'),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/InstallRequest')), responses: [
    new OA\Response(response: 200, description: 'Installation tracked', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
])]
#[OA\Post(path: '/heartbeat', tags: ['App'], security: [['AppApiKey' => []]], parameters: [
    new OA\Parameter(name: 'X-App-Id', in: 'header', required: true, schema: new OA\Schema(type: 'string'), example: 'jivanand-1cmdewqs'),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/HeartbeatRequest')), responses: [
    new OA\Response(response: 200, description: 'Heartbeat tracked', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Post(path: '/save-token', tags: ['App'], security: [['AppApiKey' => []]], parameters: [
    new OA\Parameter(name: 'X-App-Id', in: 'header', required: true, schema: new OA\Schema(type: 'string'), example: 'jivanand-1cmdewqs'),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/SaveTokenRequest')), responses: [
    new OA\Response(response: 200, description: 'Device token saved', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/ads', tags: ['App'], security: [['AppApiKey' => []]], parameters: [
    new OA\Parameter(name: 'X-App-Id', in: 'header', required: true, schema: new OA\Schema(type: 'string'), example: 'jivanand-1cmdewqs'),
], responses: [
    new OA\Response(response: 200, description: 'Active advertisements', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/ad-config', tags: ['App'], security: [['AppApiKey' => []]], parameters: [
    new OA\Parameter(name: 'X-App-Id', in: 'header', required: true, schema: new OA\Schema(type: 'string'), example: 'jivanand-1cmdewqs'),
], responses: [
    new OA\Response(response: 200, description: 'Runtime ad credentials and rules for Android ad manager', content: new OA\JsonContent(ref: '#/components/schemas/AdConfigApiResponse')),
])]
#[OA\Get(path: '/admin/ad-settings', tags: ['Admin Ad Settings'], parameters: [
    new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), example: 25),
], responses: [
    new OA\Response(response: 200, description: 'List ad settings', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Post(path: '/admin/ad-settings', tags: ['Admin Ad Settings'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AdNetworkSettingRequest')), responses: [
    new OA\Response(response: 201, description: 'Create or update app ad settings', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
])]
#[OA\Get(path: '/admin/ad-settings/{ad_setting}', tags: ['Admin Ad Settings'], parameters: [
    new OA\Parameter(name: 'ad_setting', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
], responses: [
    new OA\Response(response: 200, description: 'Show ad settings', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Put(path: '/admin/ad-settings/{ad_setting}', tags: ['Admin Ad Settings'], parameters: [
    new OA\Parameter(name: 'ad_setting', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AdNetworkSettingRequest')), responses: [
    new OA\Response(response: 200, description: 'Update ad settings', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
    new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
])]
#[OA\Delete(path: '/admin/ad-settings/{ad_setting}', tags: ['Admin Ad Settings'], parameters: [
    new OA\Parameter(name: 'ad_setting', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
], responses: [
    new OA\Response(response: 200, description: 'Delete ad settings', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/version-check', tags: ['App'], security: [['AppApiKey' => []]], parameters: [
    new OA\Parameter(name: 'X-App-Id', in: 'header', required: true, schema: new OA\Schema(type: 'string'), example: 'jivanand-1cmdewqs'),
    new OA\Parameter(name: 'current_version', in: 'query', required: true, schema: new OA\Schema(type: 'string'), example: '1.0.0'),
], responses: [
    new OA\Response(response: 200, description: 'Version status', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Post(path: '/event', tags: ['App'], security: [['AppApiKey' => []]], parameters: [
    new OA\Parameter(name: 'X-App-Id', in: 'header', required: true, schema: new OA\Schema(type: 'string'), example: 'jivanand-1cmdewqs'),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/EventRequest')), responses: [
    new OA\Response(response: 201, description: 'Event stored', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/notifications', tags: ['App'], security: [['AppApiKey' => []]], parameters: [
    new OA\Parameter(name: 'X-App-Id', in: 'header', required: true, schema: new OA\Schema(type: 'string'), example: 'jivanand-1cmdewqs'),
], responses: [
    new OA\Response(response: 200, description: 'Notification list', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/admin/dashboard', tags: ['Admin'], parameters: [
    new OA\Parameter(name: 'app_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), example: 2),
], responses: [
    new OA\Response(response: 200, description: 'Dashboard analytics', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/admin/apps', tags: ['Admin Apps'], parameters: [
    new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), example: 25),
], responses: [
    new OA\Response(response: 200, description: 'List apps', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Post(path: '/admin/apps', tags: ['Admin Apps'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AdminAppRequest')), responses: [
    new OA\Response(response: 201, description: 'Create app', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/admin/apps/{app}', tags: ['Admin Apps'], parameters: [
    new OA\Parameter(name: 'app', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 2),
], responses: [
    new OA\Response(response: 200, description: 'Show app', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Put(path: '/admin/apps/{app}', tags: ['Admin Apps'], parameters: [
    new OA\Parameter(name: 'app', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 2),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AdminAppRequest')), responses: [
    new OA\Response(response: 200, description: 'Update app', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Delete(path: '/admin/apps/{app}', tags: ['Admin Apps'], parameters: [
    new OA\Parameter(name: 'app', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 2),
], responses: [
    new OA\Response(response: 200, description: 'Delete app', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/admin/advertisements', tags: ['Admin Advertisements'], parameters: [
    new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), example: 25),
], responses: [
    new OA\Response(response: 200, description: 'List advertisements', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Post(path: '/admin/advertisements', tags: ['Admin Advertisements'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AdvertisementRequest')), responses: [
    new OA\Response(response: 201, description: 'Create advertisement', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/admin/advertisements/{advertisement}', tags: ['Admin Advertisements'], parameters: [
    new OA\Parameter(name: 'advertisement', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 10),
], responses: [
    new OA\Response(response: 200, description: 'Show advertisement', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Put(path: '/admin/advertisements/{advertisement}', tags: ['Admin Advertisements'], parameters: [
    new OA\Parameter(name: 'advertisement', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 10),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AdvertisementRequest')), responses: [
    new OA\Response(response: 200, description: 'Update advertisement', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Delete(path: '/admin/advertisements/{advertisement}', tags: ['Admin Advertisements'], parameters: [
    new OA\Parameter(name: 'advertisement', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 10),
], responses: [
    new OA\Response(response: 200, description: 'Delete advertisement', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/admin/notifications', tags: ['Admin Notifications'], parameters: [
    new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), example: 25),
], responses: [
    new OA\Response(response: 200, description: 'List notifications', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Post(path: '/admin/notifications', tags: ['Admin Notifications'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/NotificationRequest')), responses: [
    new OA\Response(response: 201, description: 'Create notification', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/admin/notifications/{notification}', tags: ['Admin Notifications'], parameters: [
    new OA\Parameter(name: 'notification', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 25),
], responses: [
    new OA\Response(response: 200, description: 'Show notification', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Put(path: '/admin/notifications/{notification}', tags: ['Admin Notifications'], parameters: [
    new OA\Parameter(name: 'notification', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 25),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/NotificationRequest')), responses: [
    new OA\Response(response: 200, description: 'Update notification', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Delete(path: '/admin/notifications/{notification}', tags: ['Admin Notifications'], parameters: [
    new OA\Parameter(name: 'notification', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 25),
], responses: [
    new OA\Response(response: 200, description: 'Delete notification', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Post(path: '/admin/notifications/{notification}/send', tags: ['Admin Notifications'], parameters: [
    new OA\Parameter(name: 'notification', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 25),
], responses: [
    new OA\Response(response: 200, description: 'Notification queued', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/admin/app-versions', tags: ['Admin Versions'], parameters: [
    new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), example: 25),
], responses: [
    new OA\Response(response: 200, description: 'List app versions', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Post(path: '/admin/app-versions', tags: ['Admin Versions'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AppVersionRequest')), responses: [
    new OA\Response(response: 201, description: 'Create app version', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Get(path: '/admin/app-versions/{app_version}', tags: ['Admin Versions'], parameters: [
    new OA\Parameter(name: 'app_version', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 5),
], responses: [
    new OA\Response(response: 200, description: 'Show app version', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Put(path: '/admin/app-versions/{app_version}', tags: ['Admin Versions'], parameters: [
    new OA\Parameter(name: 'app_version', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 5),
], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/AppVersionRequest')), responses: [
    new OA\Response(response: 200, description: 'Update app version', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
#[OA\Delete(path: '/admin/app-versions/{app_version}', tags: ['Admin Versions'], parameters: [
    new OA\Parameter(name: 'app_version', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 5),
], responses: [
    new OA\Response(response: 200, description: 'Delete app version', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
])]
class OpenApi {}
