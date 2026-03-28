@extends('layouts.app')

@section('title', $person->name . ' - Admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <a href="{{ route('admin.people.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 mb-6 sm:mb-8 font-medium">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar</span>
    </a>

    <div class="card overflow-hidden">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-0">
            <!-- Photo Section -->
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 p-6 sm:p-8 flex flex-col items-center justify-center">
                @if($person->photo)
                    <img src="{{ asset('storage/' . $person->photo) }}" alt="{{ $person->name }}" class="w-40 h-40 sm:w-48 sm:h-48 rounded-full object-cover border-8 border-white shadow-lg">
                @else
                    <div class="w-40 h-40 sm:w-48 sm:h-48 rounded-full bg-gradient-to-br from-blue-200 to-purple-200 flex items-center justify-center text-8xl border-8 border-white shadow-lg">
                        👤
                    </div>
                @endif
                <span class="mt-4 badge-info">Gen {{ $person->generation }}</span>
            </div>

            <!-- Info Section -->
            <div class="sm:col-span-2 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6 sm:mb-8">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $person->name }}</h1>
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="{{ route('admin.people.edit', $person) }}" class="btn-secondary flex-1 sm:flex-none text-center">
                            <i class="fas fa-edit mr-2"></i>
                            Edit
                        </a>
                        <form action="{{ route('admin.people.destroy', $person) }}" method="POST" class="flex-1 sm:flex-none">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash-alt mr-2"></i>Hapus</button>
                        </form>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="space-y-3 sm:space-y-4">
                    @if($person->gender)
                        <div class="flex items-center gap-3">
                            <span class="text-blue-600"><i class="fas fa-{{ $person->gender === 'male' ? 'mars' : 'venus' }}"></i></span>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Jenis Kelamin</p>
                                <p class="font-medium text-gray-900">{{ $person->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                        </div>
                    @endif

                    @if($person->birth_date)
                        <div class="flex items-center gap-3">
                            <span class="text-green-600"><i class="fas fa-birthday-cake"></i></span>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Tanggal Lahir</p>
                                <p class="font-medium text-gray-900">{{ $person->birth_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($person->death_date)
                        <div class="flex items-center gap-3">
                            <span class="text-red-600"><i class="fas fa-cross"></i></span>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Tanggal Meninggal</p>
                                <p class="font-medium text-red-700">{{ $person->death_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Relations Section -->
        <div class="border-t border-gray-100 p-6 sm:p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Parent -->
                @if($person->parent)
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-user-circle text-blue-600"></i>
                            Orang Tua
                        </h3>
                        <a href="{{ route('admin.people.show', $person->parent) }}" class="block px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium text-center">
                            {{ $person->parent->name }}
                        </a>
                    </div>
                @endif

                <!-- Spouse -->
                @php
                    $spouses = $person->getSpouses();
                @endphp
                @if($spouses->count() > 0)
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-heart text-pink-600"></i>
                            Pasangan
                        </h3>
                        <div class="space-y-2">
                            @foreach($spouses as $spouse)
                                <a href="{{ route('admin.people.show', $spouse) }}" class="block px-4 py-2 bg-pink-50 text-pink-700 rounded-lg hover:bg-pink-100 transition-colors text-sm font-medium text-center">
                                    {{ $spouse->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Children -->
                @if($person->children->count() > 0)
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-child text-purple-600"></i>
                            Anak ({{ $person->children->count() }})
                        </h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($person->children->sortBy('name') as $child)
                                <a href="{{ route('admin.people.show', $child) }}" class="block px-4 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors text-sm font-medium text-center truncate">
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
