@extends('admin.layouts.app', ['title' => 'Create Notification', 'heading' => 'Compose Notification', 'subtitle' => 'Draft a new push notification and send it immediately to all subscribed users.'])

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Notification Content</h3>
            </div>
            
            <div class="p-6 space-y-6">
                <div>
                    <label for="app_id" class="block text-sm font-bold text-slate-700 mb-2">Select App</label>
                    <select name="app_id" id="app_id" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm @error('app_id') border-rose-300 bg-rose-50 @enderror">
                        <option value="">Choose an app</option>
                        @foreach($apps as $app)
                            <option value="{{ $app->id }}" @selected(old('app_id', $notification->app_id) == $app->id)>{{ $app->name }} ({{ $app->package_name }})</option>
                        @endforeach
                    </select>
                    @error('app_id')<p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p>@enderror
                    <p class="mt-2 text-xs text-slate-500 font-medium">App-level OneSignal credentials are configured in the App edit screen and reused automatically.</p>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6">Notification Content</h4>
                </div>

                <div>
                    <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Notification Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm @error('title') border-rose-300 bg-rose-50 @enderror"
                           placeholder="e.g. New Update Available!">
                    @error('title')<p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Message Description</label>
                    <textarea name="description" id="description" rows="4" required
                              class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm @error('description') border-rose-300 bg-rose-50 @enderror"
                              placeholder="Describe the purpose of this notification in detail...">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start pt-4 border-t border-slate-100">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Cover Image (Optional)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-indigo-400 hover:bg-slate-50 transition-all duration-200 relative group">
                            <div class="space-y-1 text-center">
                                <i data-lucide="image" class="mx-auto h-12 w-12 text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                                <div class="flex text-sm text-slate-600">
                                    <label for="image_file" class="relative cursor-pointer bg-white rounded-md font-bold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="image_file" name="image_file" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.webp" data-image-input="#notificationPreview">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-slate-500">PNG, JPG, WEBP up to 2MB</p>
                            </div>
                        </div>
                        @error('image_file')<p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="flex flex-col items-center justify-center h-full min-h-[160px] bg-slate-50 rounded-2xl border border-slate-200 p-4">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Live Preview</p>
                        <img id="notificationPreview" class="{{ $notification->image ? '' : 'hidden' }} max-h-32 rounded-lg shadow-md border border-white" src="{{ $notification->image_url ?? '' }}" alt="Notification image preview">
                        <div id="previewPlaceholder" class="{{ $notification->image ? 'hidden' : '' }} flex flex-col items-center text-slate-300">
                            <i data-lucide="eye-off" class="w-8 h-8 mb-2"></i>
                            <span class="text-[10px] font-bold">No image selected</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-emerald-50/50 border-t border-emerald-100 flex items-center gap-3">
                <div class="p-1.5 bg-emerald-500 rounded-lg text-white shadow-lg shadow-emerald-500/20">
                    <i data-lucide="globe" class="w-4 h-4"></i>
                </div>
                <p class="text-xs font-bold text-emerald-800">This notification will be broadcasted to all active segments.</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.notifications.index') }}" class="px-6 py-3 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                Discard Draft
            </a>
            <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent shadow-lg shadow-indigo-200 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 group">
                <i data-lucide="send" class="w-4 h-4 mr-2 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                Send Notification Now
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#image_file').on('change', function() {
        if (this.files && this.files[0]) {
            $('#previewPlaceholder').addClass('hidden');
            $('#notificationPreview').removeClass('hidden');
        } else {
            $('#previewPlaceholder').removeClass('hidden');
            $('#notificationPreview').addClass('hidden');
        }
    });
});
</script>
@endpush
