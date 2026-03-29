@extends('layouts.app')

@section('title', 'Tambah Anggota Keluarga - Admin')

@section('header', 'Tambah anggota')

@section('content')
<div class="max-w-2xl mx-auto pb-8 sm:pb-10">
    <a href="{{ route('admin.people.index') }}" class="inline-flex items-center gap-2 sm:gap-3 text-purple-600 hover:text-purple-800 mb-5 sm:mb-6 font-medium text-sm sm:text-base min-h-[44px] sm:min-h-0 px-1 -ml-1 rounded-xl hover:bg-purple-50/80 transition-colors">
        <span class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-full bg-white shadow-sm border border-gray-100 text-purple-600">
            <i class="fas fa-arrow-left text-sm"></i>
        </span>
        <span>Kembali ke Daftar</span>
    </a>

    <div class="card overflow-hidden shadow-md border-0 ring-1 ring-gray-100/80 fade-in">
        <div class="hero-gradient px-5 py-6 sm:px-8 sm:py-8 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex h-14 w-14 sm:h-16 sm:w-16 shrink-0 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 shadow-lg">
                    <i class="fas fa-user-plus text-2xl sm:text-3xl"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight">
                        Tambah Anggota Keluarga
                    </h1>
                    <p class="text-white/90 text-sm sm:text-base mt-1 max-w-lg leading-relaxed">
                        Lengkapi data berikut. Tampilan disesuaikan untuk ponsel dan desktop.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-8 bg-gradient-to-b from-white to-gray-50/50">
            <form action="{{ route('admin.people.store') }}" method="POST" enctype="multipart/form-data" class="form-stack space-y-6 sm:space-y-8">
                @csrf

                <!-- Basic Info -->
                <section class="rounded-2xl border border-gray-100 bg-white/90 shadow-sm overflow-hidden" aria-labelledby="section-basic">
                    <div class="flex items-center gap-3 px-4 py-3.5 sm:px-5 sm:py-4 bg-gradient-to-r from-slate-50 to-blue-50/80 border-b border-gray-100">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600 shrink-0">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <div class="min-w-0">
                            <h2 id="section-basic" class="text-base sm:text-lg font-bold text-gray-900">Informasi Dasar</h2>
                            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Nama, jenis kelamin, dan tanggal penting</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-800 mb-1.5">Nama Lengkap <span class="text-red-600">*</span></label>
                            <input type="text" id="name" name="name" required class="text-sm sm:text-base min-h-[44px] sm:min-h-0" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" autocomplete="name">
                            @error('name')
                                <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-start gap-1.5"><i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i><span>{{ $message }}</span></p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                            <div>
                                <label for="gender" class="block text-sm font-semibold text-gray-800 mb-1.5">Jenis Kelamin</label>
                                <select id="gender" name="gender" class="text-sm sm:text-base min-h-[44px] sm:min-h-0">
                                    <option value="">Pilih</option>
                                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-semibold text-gray-800 mb-1.5">Tanggal Lahir</label>
                                <input type="date" id="birth_date" name="birth_date" class="text-sm sm:text-base min-h-[44px] sm:min-h-0" value="{{ old('birth_date') }}">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="death_date" class="block text-sm font-semibold text-gray-800 mb-1.5">Tanggal Meninggal</label>
                                <input type="date" id="death_date" name="death_date" class="text-sm sm:text-base min-h-[44px] sm:min-h-0 max-w-full" value="{{ old('death_date') }}">
                                <p class="text-gray-500 text-xs mt-2 leading-relaxed">Kosongkan jika masih hidup.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Family Relations -->
                <section class="rounded-2xl border border-gray-100 bg-white/90 shadow-sm overflow-hidden" aria-labelledby="section-family">
                    <div class="flex items-center gap-3 px-4 py-3.5 sm:px-5 sm:py-4 bg-gradient-to-r from-slate-50 to-blue-50/80 border-b border-gray-100">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600 shrink-0">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="min-w-0">
                            <h2 id="section-family" class="text-base sm:text-lg font-bold text-gray-900">Hubungan Keluarga</h2>
                            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Menentukan posisi di pohon keluarga</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                        <div>
                            <label for="parent_id" class="block text-sm font-semibold text-gray-800 mb-1.5">Orang Tua</label>
                            <select id="parent_id" name="parent_id" class="text-sm sm:text-base min-h-[44px] sm:min-h-0">
                                @if(auth()->user()->is_super_admin)
                                    <option value="" @selected(old('parent_id') === null || old('parent_id') === '')>Tanpa orang tua (Generasi 1)</option>
                                @else
                                    <option value="" @selected(old('parent_id') === null || old('parent_id') === '')>Tanpa orang tua di pohon — wajib pilih pasangan di bawah</option>
                                @endif
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }} (Gen {{ $parent->generation }})
                                    </option>
                                @endforeach
                            </select>
                            @if(auth()->user()->is_super_admin)
                                <p class="text-gray-500 text-xs mt-2 leading-relaxed">Pilih induk di pohon, atau biarkan kosong untuk akar silsilah.</p>
                            @else
                                <p class="text-gray-500 text-xs mt-2 leading-relaxed">Kosongkan hanya untuk pasangan (suami/istri) yang tidak punya orang tua di silsilah ini — lalu pilih pasangan yang sudah ada di cabang Anda.</p>
                            @endif
                            @error('parent_id')
                                <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-start gap-1.5"><i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i><span>{{ $message }}</span></p>
                            @enderror
                        </div>

                        @if($potentialSpouses->isNotEmpty())
                            <div class="rounded-xl border border-pink-100 bg-pink-50/50 p-4 sm:p-5 space-y-4">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-ring text-pink-600"></i>
                                        Pasangan (pernikahan)
                                    </h3>
                                    <p class="text-xs text-gray-600 mt-1">Jika orang tua dikosongkan, pilih pasangan di cabang Anda (misalnya suami/istri dari luar garis keturunan).</p>
                                </div>
                                <div>
                                    <label for="spouse_id" class="block text-sm font-semibold text-gray-800 mb-1.5">Pilih pasangan</label>
                                    <select id="spouse_id" name="spouse_id" class="text-sm sm:text-base min-h-[44px] sm:min-h-0 w-full">
                                        <option value="">— Tidak ada / isi nanti —</option>
                                        @foreach($potentialSpouses as $spouse)
                                            <option value="{{ $spouse->id }}" {{ old('spouse_id') == $spouse->id ? 'selected' : '' }}>
                                                {{ $spouse->name }} (Gen {{ $spouse->generation }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('spouse_id')
                                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-start gap-1.5"><i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i><span>{{ $message }}</span></p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="marriage_date" class="block text-sm font-semibold text-gray-800 mb-1.5">Tanggal pernikahan</label>
                                    <input type="date" id="marriage_date" name="marriage_date" class="text-sm sm:text-base min-h-[44px] sm:min-h-0 w-full max-w-full" value="{{ old('marriage_date') }}">
                                </div>
                            </div>
                        @elseif(!auth()->user()->is_super_admin)
                            <p class="text-amber-800 text-xs sm:text-sm rounded-lg bg-amber-50 border border-amber-100 px-3 py-2.5">
                                Untuk menambah anggota tanpa orang tua di pohon, harus ada minimal satu orang lain di cabang Anda yang bisa dipilih sebagai pasangan.
                            </p>
                        @endif
                    </div>
                </section>

                <!-- Photo -->
                <section class="rounded-2xl border border-gray-100 bg-white/90 shadow-sm overflow-hidden" aria-labelledby="section-photo">
                    <div class="flex items-center gap-3 px-4 py-3.5 sm:px-5 sm:py-4 bg-gradient-to-r from-slate-50 to-blue-50/80 border-b border-gray-100">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 shrink-0">
                            <i class="fas fa-camera"></i>
                        </span>
                        <div class="min-w-0">
                            <h2 id="section-photo" class="text-base sm:text-lg font-bold text-gray-900">Foto Profil</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Unggah hingga 12 MB; sistem akan mengompres & merapikan ukuran otomatis.</p>
                            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Opsional — JPG atau PNG, maks. 2MB</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3">
                        <label for="photo" class="relative flex flex-col items-center justify-center w-full min-h-[140px] sm:min-h-[160px] px-4 py-6 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/80 hover:border-blue-300 hover:bg-blue-50/30 transition-colors cursor-pointer group">
                            <input type="file" id="photo" name="photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <span class="pointer-events-none flex flex-col items-center text-center gap-2">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm border border-gray-100 text-blue-600 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-lg"></i>
                                </span>
                                <span class="text-sm font-semibold text-gray-800">Ketuk untuk memilih foto</span>
                                <span class="text-xs text-gray-500 hidden sm:inline">Atau seret file ke area ini</span>
                            </span>
                            <img id="photo-preview" src="" alt="" class="hidden max-h-40 rounded-xl object-contain mt-2 shadow-md border border-gray-100 max-w-full">
                        </label>
                        <p class="text-gray-500 text-xs text-center sm:text-left">Format: JPG, PNG. Ukuran maksimal 2MB.</p>
                    </div>
                </section>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-4 pt-1">
                    <a href="{{ route('admin.people.index') }}" class="btn-secondary text-center min-h-[48px] sm:min-h-0 flex items-center justify-center px-6 w-full sm:w-auto sm:min-w-[140px]">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn-primary min-h-[48px] sm:min-h-0 flex items-center justify-center gap-2 px-6 w-full sm:w-auto sm:min-w-[180px] shadow-md hover:shadow-lg">
                        <i class="fas fa-save"></i>
                        <span>Simpan Anggota</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush
