@extends('layouts.app')

@section('title', 'Edit ' . $person->name . ' - Admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 sm:pb-10">
    <a href="{{ route('admin.people.index') }}" class="inline-flex items-center gap-2 sm:gap-3 text-blue-600 hover:text-blue-800 mb-5 sm:mb-6 font-medium text-sm sm:text-base min-h-[44px] sm:min-h-0 px-1 -ml-1 rounded-xl hover:bg-blue-50/80 transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar</span>
    </a>

    <div class="card overflow-hidden shadow-md border-0 ring-1 ring-gray-100/80 fade-in">
        <div class="hero-gradient px-5 py-6 sm:px-8 sm:py-8 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex h-14 w-14 sm:h-16 sm:w-16 shrink-0 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 shadow-lg">
                    <i class="fas fa-user-edit text-2xl sm:text-3xl"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight">
                        Edit Data: {{ $person->name }}
                    </h1>
                    <p class="text-white/90 text-sm sm:text-base mt-1 max-w-lg leading-relaxed">
                        Perbarui data anggota keluarga dengan tampilan yang nyaman di mobile.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-8 bg-gradient-to-b from-white to-gray-50/50">
        <form action="{{ route('admin.people.update', $person) }}" method="POST" enctype="multipart/form-data" class="form-stack space-y-6 sm:space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Info Section -->
            <div class="border-b border-gray-200 pb-6 sm:pb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Informasi Dasar</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input type="text" id="name" name="name" required class="text-sm sm:text-base" placeholder="Masukkan nama lengkap" value="{{ old('name', $person->name) }}">
                        @error('name')
                            <p class="text-red-600 text-xs sm:text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                        <select id="gender" name="gender" class="text-sm sm:text-base">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="male" {{ old('gender', $person->gender) === 'male' ? 'selected' : '' }}>👨 Laki-laki</option>
                            <option value="female" {{ old('gender', $person->gender) === 'female' ? 'selected' : '' }}>👩 Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" id="birth_date" name="birth_date" class="text-sm sm:text-base" value="{{ old('birth_date', $person->birth_date?->format('Y-m-d')) }}">
                    </div>

                    <div>
                        <label for="death_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Meninggal</label>
                        <input type="date" id="death_date" name="death_date" class="text-sm sm:text-base" value="{{ old('death_date', $person->death_date?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <!-- Family Relations Section -->
            <div class="border-b border-gray-200 pb-6 sm:pb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Hubungan Keluarga</h2>
                <div>
                    <label for="parent_id" class="block text-sm font-semibold text-gray-700 mb-2">Orang Tua</label>
                    <select id="parent_id" name="parent_id" class="text-sm sm:text-base">
                        <option value="">Pilih Orang Tua (Kosongkan untuk Generasi 1)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $person->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }} (Gen {{ $parent->generation }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Photo Section -->
            <div class="border-b border-gray-200 pb-6 sm:pb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Foto Profil</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8">
                    <div>
                        <label for="photo" class="relative flex flex-col items-center justify-center w-full min-h-[140px] sm:min-h-[160px] px-4 py-6 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/80 hover:border-blue-300 hover:bg-blue-50/30 transition-colors cursor-pointer group">
                            <input type="file" id="photo" name="photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <span class="pointer-events-none flex flex-col items-center text-center gap-2">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm border border-gray-100 text-blue-600 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-lg"></i>
                                </span>
                                <span class="text-sm font-semibold text-gray-800">Ketuk untuk pilih foto</span>
                                <span class="text-xs text-gray-500 hidden sm:inline">atau seret file</span>
                            </span>
                        </label>
                        <p class="text-gray-500 text-xs mt-2">Format: JPG, PNG. Maks. 2MB.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Saat Ini</label>
                        <img
                            id="photo-preview"
                            src="{{ $person->photo ? asset('storage/' . $person->photo) : '' }}"
                            alt="{{ $person->name }}"
                            class="h-32 w-32 object-cover rounded-lg shadow-md {{ $person->photo ? '' : 'hidden' }}"
                        >
                        @if(!$person->photo)
                            <p class="text-gray-400 text-xs mt-2">Belum ada foto.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Marriage Management Section -->
            <div class="border-b border-gray-200 pb-6 sm:pb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-6">
                    <i class="fas fa-ring text-pink-600 mr-2"></i>
                    Manajemen Pernikahan
                </h2>

                @if($marriages->count() > 0)
                    <div class="mb-6 sm:mb-8">
                        <h3 class="font-semibold text-gray-900 mb-3 sm:mb-4">Pernikahan Saat Ini</h3>
                        <div class="space-y-2">
                            @foreach($marriages as $marriage)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-pink-50 p-4 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $person->id === $marriage->person1_id ? $marriage->person2->name : $marriage->person1->name }}
                                        </p>
                                        @if($marriage->marriage_date)
                                            <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $marriage->marriage_date->format('d M Y') }}
                                            </p>
                                        @endif
                                    </div>
                                        <button
                                            type="button"
                                            class="text-red-600 hover:text-red-800 px-3 py-1 min-h-[40px] sm:min-h-0 self-start sm:self-auto"
                                            onclick="submitDeleteMarriage('{{ route('admin.people.marriages.delete', $marriage) }}')"
                                            aria-label="Hapus pernikahan">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <details class="group">
                    <summary class="cursor-pointer font-semibold text-blue-600 hover:text-blue-800 p-4 bg-blue-50 rounded-lg transition-colors min-h-[44px] flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pernikahan Baru
                    </summary>
                    <div class="mt-4 p-4 sm:p-6 bg-blue-50 rounded-lg space-y-4 sm:space-y-6">
                        <div>
                            <label for="spouse_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Pasangan</label>
                            <select name="spouse_id" class="w-full text-sm sm:text-base">
                                <option value="">Pilih Pasangan</option>
                                @foreach($potentialSpouses as $spouse)
                                    <option value="{{ $spouse->id }}">{{ $spouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="marriage_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pernikahan</label>
                            <input type="date" name="marriage_date" class="w-full text-sm sm:text-base">
                        </div>
                        <button type="button" class="btn-primary w-full justify-center" onclick="addMarriage()">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Pernikahan
                        </button>
                    </div>
                </details>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-4">
                <a href="{{ route('admin.people.show', $person) }}" class="btn-secondary text-center min-h-[48px] sm:min-h-0 flex items-center justify-center px-6 w-full sm:w-auto sm:min-w-[140px]">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="btn-primary text-center min-h-[48px] sm:min-h-0 flex items-center justify-center gap-2 px-6 w-full sm:w-auto sm:min-w-[180px] shadow-md hover:shadow-lg">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
function submitDeleteMarriage(actionUrl) {
    if (!confirm('Hapus pernikahan ini?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = actionUrl;
    form.innerHTML = `@csrf<input type="hidden" name="_method" value="DELETE">`;
    document.body.appendChild(form);
    form.submit();
}

// Photo preview (opsional)
(function () {
    var input = document.getElementById('photo');
    var preview = document.getElementById('photo-preview');
    if (!input || !preview) return;

    input.addEventListener('change', function () {
        var file = input.files && input.files[0];
        if (!file || !file.type.match(/^image\//)) {
            preview.classList.add('hidden');
            preview.removeAttribute('src');
            return;
        }
        var url = URL.createObjectURL(file);
        preview.onload = function () { URL.revokeObjectURL(url); };
        preview.src = url;
        preview.classList.remove('hidden');
    });
})();

function addMarriage() {
    const spouseId = document.querySelector('select[name="spouse_id"]').value;
    if (!spouseId) {
        alert('Pilih pasangan terlebih dahulu');
        return;
    }
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.people.marriages", $person) }}';
    form.innerHTML = `
        @csrf
        <input type="hidden" name="person1_id" value="{{ $person->id }}">
        <input type="hidden" name="person2_id" value="${spouseId}">
        <input type="hidden" name="marriage_date" value="${document.querySelector('input[name="marriage_date"]').value}">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
