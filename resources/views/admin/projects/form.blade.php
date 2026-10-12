<x-app-layout
    ><x-slot name="header"
        ><h1 class="text-2xl font-semibold">{{ $project->exists ? 'Edit project' : 'New project' }}</h1></x-slot
    >
    <div class="mx-auto max-w-2xl px-4 py-8">
        <form
            method="POST"
            action="{{ $project->exists ? route('admin.projects.update',$project) : route('admin.projects.store') }}"
            class="space-y-5 rounded-xl bg-white p-6 shadow-sm"
        >
            @csrf @if($project->exists)@method('PUT')@endif<label class="block"
                >Name <span class="text-red-600" aria-hidden="true">*</span
                ><input
                    name="name"
                    maxlength="255"
                    value="{{ old('name',$project->name) }}"
                    class="mt-1 block w-full rounded-lg border-slate-300"
                />@error('name')<span class="mt-1 block text-sm text-red-600">{{ $message }}</span>@enderror</label
            ><label class="block"
                >Description<textarea name="description" rows="4" class="mt-1 block w-full rounded-lg border-slate-300">
{{ old('description',$project->description) }}</textarea
                >@error('description')<span class="mt-1 block text-sm text-red-600">{{ $message }}</span
                >@enderror</label
            ><button class="rounded-lg bg-indigo-600 px-4 py-2 text-white">Save project</button>
        </form>
    </div></x-app-layout
>
