<form class="cardx p-3 mb-3" method="GET">
    <div class="row g-2 align-items-end">
        <div class="col-md-4"><label class="form-label">App</label><select class="form-select" name="app_id"><option value="">All apps</option>@foreach($apps as $app)<option value="{{ $app->id }}" @selected(request('app_id') == $app->id)>{{ $app->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">From</label><input class="form-control" name="from" type="date" value="{{ request('from') }}"></div>
        <div class="col-md-3"><label class="form-label">To</label><input class="form-control" name="to" type="date" value="{{ request('to') }}"></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Apply</button></div>
    </div>
</form>
