@php
    $nav = [
        ['Dashboard', 'admin.dashboard', 'admin.dashboard'],
        ['Apps', 'admin.apps.index', 'admin.apps.*'],
        ['Install Analytics', 'admin.analytics.installations', 'admin.analytics.installations'],
        ['DAU / MAU', 'admin.analytics.active-users', 'admin.analytics.active-users'],
        ['Advertisements', 'admin.advertisements.index', 'admin.advertisements.*'],
        ['Ad Settings', 'admin.ad-settings.index', 'admin.ad-settings.*'],
        ['Notifications', 'admin.notifications.index', 'admin.notifications.*'],
        ['Versions', 'admin.versions.index', 'admin.versions.*'],
        ['Events', 'admin.analytics.events', 'admin.analytics.events'],
        ['API Logs', 'admin.api-logs.index', 'admin.api-logs.*'],
    ];
@endphp
<aside class="sidebar">
    <div class="brand">App Analytics</div>
    @foreach ($nav as [$label, $route, $match])
        <a href="{{ route($route) }}" class="{{ request()->routeIs($match) ? 'active' : '' }}">
            <span>{{ $label }}</span>
        </a>
    @endforeach
</aside>
