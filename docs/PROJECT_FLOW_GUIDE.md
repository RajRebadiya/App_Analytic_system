# Multi Android App Management System - Complete Flow Guide

This guide explains the full working flow for the admin team and Android developers.

## 1. First Setup

Run migrations and start the server:

```bash
php artisan migrate --force
php artisan storage:link
php artisan serve
```

Open admin panel:

```text
http://127.0.0.1:8000/admin/register
```

Create your first admin account. After registration, you will be logged in automatically.

## 2. Admin Adds an App

Go to:

```text
Admin Panel -> Apps -> Add App
```

You only enter:

- App Name
- Version

Example:

```text
App Name: My News App
Version: 1.0.0
```

The system automatically generates:

- App ID
- Package Name
- API Key
- Current Version
- Latest Version
- Minimum Supported Version
- Status
- Force Update flag
- Maintenance Mode flag

After saving, go to the Apps list. You will see the app details, including `app_id` and `api_key`.

## 3. Give Credentials to Android Developer

The Android developer needs these values from the Apps list or from the admin apps API response:

- `app_id`
- `api_key`

Admin API:

```http
GET /api/v1/admin/apps
```

Response includes:

```json
{
  "app_id": "jivanand-1cmdewqs",
  "api_key": "generated-api-key"
}
```

Every Android API request must send these headers:

```http
X-App-Id: generated-app-id
X-App-Key: generated-api-key
Accept: application/json
```

Base URL:

```text
http://your-domain.com/api/v1
```

For local testing:

```text
http://127.0.0.1:8000/api/v1
```

## 4. Android App First Launch Flow

When the Android app opens for the first time after install:

1. Call Install API
2. Save FCM token
3. Check version
4. Fetch advertisements
5. Send app open event

Recommended order:

```text
POST /install
POST /save-token
GET  /version-check
GET  /ads
POST /event
```

## 5. Installation Tracking

Call this once on first app launch, and safely call again if needed. Backend avoids duplicate installation records using `app_id + device_id`.

Endpoint:

```http
POST /api/v1/install
```

Headers:

```http
X-App-Id: generated-app-id
X-App-Key: generated-api-key
```

Body:

```json
{
  "device_id": "unique-device-id",
  "device_name": "Pixel 8",
  "device_brand": "Google",
  "android_version": "15",
  "app_version": "1.0.0"
}
```

Purpose:

- Tracks total installs
- Tracks device details
- Tracks Android version statistics
- Tracks app version statistics

## 6. Heartbeat / Daily Active Users

Call this whenever the app opens or comes to foreground.

Endpoint:

```http
POST /api/v1/heartbeat
```

Body:

```json
{
  "device_id": "unique-device-id",
  "app_version": "1.0.0"
}
```

Purpose:

- Updates `last_active_at`
- Calculates Daily Active Users
- Calculates Monthly Active Users
- Tracks active users by version

Recommended Android timing:

- App open
- App resume from background
- Once every few hours if the app stays open for a long time

## 7. FCM Token Save / Refresh

When Android receives an FCM token, send it to backend.

Endpoint:

```http
POST /api/v1/save-token
```

Body:

```json
{
  "device_id": "unique-device-id",
  "fcm_token": "firebase-device-token",
  "is_active": true
}
```

Call this:

- After Firebase generates token
- When token refreshes
- After user reinstalls app

If app supports logout/uninstall event and backend can be reached:

```json
{
  "device_id": "unique-device-id",
  "fcm_token": "firebase-device-token",
  "is_active": false
}
```

Purpose:

- Backend knows which devices can receive push notifications
- Admin can send notification app-wise

## 8. Version Check Flow

Call this on every app open before showing main screen.

Endpoint:

```http
GET /api/v1/version-check?current_version=1.0.0
```

Response example:

```json
{
  "status_code": 200,
  "message": "Version status fetched",
  "data": {
    "maintenance_mode": false,
    "force_update": false,
    "optional_update": true,
    "latest_version": "1.1.0",
    "min_supported_version": "1.0.0",
    "apk_url": "https://example.com/app.apk",
    "message": "New version available"
  }
}
```

Android handling:

- If `maintenance_mode = true`: show maintenance screen and block app usage.
- Else if `force_update = true`: show force update screen and block app usage.
- Else if `optional_update = true`: show optional update popup.
- Else continue normal app flow.

Admin controls this from:

```text
Admin Panel -> Versions
```

## 9. Advertisement Flow

There are two advertisement-related modules:

```text
Ad Settings      = AdMob credentials and runtime ad rules
Advertisements   = Image/banner promotional campaigns
```

### Ad Settings / AdMob Credentials

Admin enters AdMob credentials once from:

```text
Admin Panel -> Ad Settings
```

Admin can set:

- AdMob App ID
- Banner ID
- Interstitial ID
- Native ID
- Rewarded ID
- Ad show status
- Platform sequence
- Click counts
- Dialog before ad
- More App URL

Android fetches this config:

```http
GET /api/v1/ad-config
X-App-Id: generated-app-id
X-App-Key: generated-api-key
```

Response `data` contains keys compatible with the old `CoolBostManage` shared preference names:

```json
{
  "app_adShowStatus": 1,
  "app_howShowAd": 0,
  "app_adPlatformSequence": "Admob",
  "app_alernateAdShow": "Admob",
  "app_mainClickCntSwAd": 1,
  "app_innerClickCntSwAd": 1,
  "am_ad_showAdStatus": 1,
  "am_AppID": "ca-app-pub-xxx~yyy",
  "am_Banner1": "ca-app-pub-xxx/banner",
  "am_Interstitial1": "ca-app-pub-xxx/interstitial",
  "am_Native1": "ca-app-pub-xxx/native",
  "am_RewardedVideo1": "ca-app-pub-xxx/rewarded"
}
```

Android should save these values in local preferences and then use them to load/show ads.

### Admin Ad Settings

Admin can add or update app ad configuration from:

```text
Admin Panel -> Ad Settings -> Add Ad Settings
```

The form maps to the same payload used by:

```http
POST /api/v1/admin/ad-settings
```

Identify the app with the selected app in the admin panel, or with `app_package_name`, `package_name`, `app_db_id`, or numeric `app_id` when calling the API directly.

Important fields:

- AdMob IDs: `admob_interid`, `admob_bannerid`, `admob_medium_rectangleid`, `admob_nativeid`, `admob_appopenid`
- AdX IDs: `adx_inter_id`, `adx_banner_id`, `adx_medium_rectangleid`, `adx_native_id`, `adx_appopen_id`
- Facebook IDs: `fb_inter_id`, `fb_banner_id`, `fb_medium_rectangle_id`, `fb_native_id`, `fb_native_banner_id`
- Wortise IDs: `wortise_app_id`, `wortise_appopen_id`, `wortise_inter_id`, `wortise_banner_id`, `wortise_medium_rectangle_id`, `wortise_native_id`
- Placement rules: `ad_splash`, `ad_inter`, `ad_appopen`, `ad_native`, `ad_small_native`, `ad_banner`, `ad_qureka`
- Redirect app settings: `redirect_app`, `new_app_name`, `new_app_icon`, `new_app_banner`, `new_app_body`, `new_app_link`
- Redirect display toggles: `Download`, `Backgraound`, `Popup`, `Main_click`

After saving, Android receives these values from `GET /api/v1/ad-config`.

### Promotional Advertisements

Admin creates ads from:

```text
Admin Panel -> Advertisements
```

Admin can set:

- App
- Title
- Description
- Image
- Redirect type
- Redirect value
- Start date
- End date
- Priority
- Status

Android fetches active ads:

```http
GET /api/v1/ads
```

Android should show ads based on backend response.

Redirect types:

- `url`: open browser/webview
- `screen`: open app screen
- `category`: open category page
- `product`: open product/details page

When user clicks an ad, Android should send event:

```http
POST /api/v1/event
```

```json
{
  "device_id": "unique-device-id",
  "event_name": "ad_click",
  "event_data": {
    "ad_id": 10,
    "redirect_type": "url",
    "redirect_value": "https://example.com"
  }
}
```

## 10. Event Analytics Flow

Android should send important app events.

Endpoint:

```http
POST /api/v1/event
```

Allowed event names:

- `app_open`
- `screen_view`
- `ad_click`
- `notification_open`
- `button_click`

Examples:

App open:

```json
{
  "device_id": "unique-device-id",
  "event_name": "app_open",
  "event_data": {
    "app_version": "1.0.0"
  }
}
```

Screen view:

```json
{
  "device_id": "unique-device-id",
  "event_name": "screen_view",
  "event_data": {
    "screen": "home"
  }
}
```

Button click:

```json
{
  "device_id": "unique-device-id",
  "event_name": "button_click",
  "event_data": {
    "button": "subscribe_now",
    "screen": "pricing"
  }
}
```

Admin can view these from:

```text
Admin Panel -> Events
```

## 11. Push Notification Flow

Admin creates notification from:

```text
Admin Panel -> Push Notifications -> Create Notification
```

The admin form sends through OneSignal to all subscribed users.

Required environment variables:

```env
ONESIGNAL_APP_ID=your-onesignal-app-id
ONESIGNAL_REST_API_KEY=your-onesignal-rest-api-key
```

Setup commands:

```bash
php artisan migrate
php artisan storage:link
php artisan config:clear
```

Admin selects:

- Title
- Description
- Image upload

Internal flow:

```text
Admin Creates Notification
-> Notification Saved
-> Image Stored In storage/app/public/notifications
-> Public Image URL Sent As big_picture and chrome_web_image
-> OneSignal Sends Notification To included_segments ["All"]
-> OneSignal Response Stored In notifications.onesignal_response
-> Status Stored In notifications.status
```

Important:

If OneSignal returns an error, the admin sees the API error and the exception is logged in Laravel logs.

Android handling:

- Receive OneSignal notification.
- Open target screen.
- Send `notification_open` event.

Example event:

```json
{
  "device_id": "unique-device-id",
  "event_name": "notification_open",
  "event_data": {
    "notification_id": 25,
    "redirect_screen": "product_detail"
  }
}
```

## 12. Admin Dashboard Flow

Dashboard shows:

- Total apps
- Total installations
- Today installations
- Daily active users
- Monthly active users
- Total notifications sent
- Notification success rate
- Active advertisements
- Install trends
- Event trends
- Recent API activity

Admin can filter dashboard by:

- App
- Date range

## 13. API Logs Flow

Backend tracks API calls in:

```text
api_logs
```

Admin can view them from:

```text
Admin Panel -> API Logs
```

Tracked fields:

- Method
- Path
- Status code
- Response time
- App ID
- IP address
- Request payload
- Response payload
- User agent

Purpose:

- Debug Android API issues
- Monitor failed requests
- Track response time
- Identify invalid credentials or bad payloads

## 14. Recommended Android Startup Sequence

Use this sequence in Android app start:

```text
1. Generate/get stable device_id
2. Get current app version
3. Call /install
4. Get Firebase FCM token
5. Call /save-token
6. Call /version-check
7. If maintenance/force update, stop normal flow
8. Call /heartbeat
9. Call /ads
10. Call /event with app_open
11. Start normal app UI
```

## 15. Recommended Android Resume Sequence

When user opens app again or app returns from background:

```text
1. Call /version-check
2. If allowed, call /heartbeat
3. Optionally call /ads
4. Send screen_view or app_open event
```

## 16. Standard API Response Format

Success:

```json
{
  "status_code": 200,
  "message": "Success message",
  "data": {}
}
```

Validation error:

```json
{
  "status_code": 422,
  "message": "Validation failed",
  "errors": {}
}
```

Server error:

```json
{
  "status_code": 500,
  "message": "Internal server error"
}
```

## 17. Postman Documentation

Swagger UI:

```text
http://127.0.0.1:8000/api/documentation
```

Postman import URL:

```text
http://127.0.0.1:8000/docs
```

Local OpenAPI file:

```text
storage/api-docs/api-docs.json
```

Regenerate docs:

```bash
php artisan l5-swagger:generate
```

## 18. Full Practical Example

Admin:

```text
1. Register admin
2. Login
3. Add app: My News App, version 1.0.0
4. Copy App ID and API Key from Apps list
5. Give App ID and API Key to Android developer
6. Create ads if needed
7. Create version rules if needed
8. Create notifications when needed
9. Watch dashboard and analytics
```

Android developer:

```text
1. Store App ID and API Key in app config
2. Send those headers in every backend API call
3. On first launch call install API
4. Save FCM token
5. Check version
6. Track heartbeat
7. Fetch ads
8. Send analytics events
9. Handle FCM notification redirect
10. Report issues using API Logs data if needed
```
