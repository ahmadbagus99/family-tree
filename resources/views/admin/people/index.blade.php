@extends('layouts.app')

@section('title', 'Kelola Data Keluarga - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">
                <i class="fas fa-users text-blue-600 mr-3"></i>
                Kelola Data Keluarga
            </h1>
            <p class="text-gray-600 text-sm sm:text-base">Atur dan kelola informasi anggota keluarga</p>
        </div>
        <a href="{{ route('admin.people.create') }}" class="btn-primary w-full sm:w-auto text-center">
            <i class="fas fa-plus mr-2"></i>
            Tambah Anggota
        </a>
    </div>

    @if(isset($families) && $families->count() > 0)
        <div class="card p-4 sm:p-5 mb-6">
            <p class="text-sm font-semibold text-gray-800 mb-3">Per Keluarga</p>
            <div class="flex flex-wrap gap-2">
                @foreach($families as $family)
                    <a href="{{ route('family-tree.family', $family->family_slug) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs sm:text-sm font-medium transition-colors">
                        <i class="fas fa-tree text-[11px]"></i>
                        <span>{{ $family->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Table Container -->
    <div class="card overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Nama</th>
                    <th class="hidden sm:table-cell px-6 py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Kelamin</th>
                    <th class="hidden md:table-cell px-6 py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Tanggal Lahir</th>
                    <th class="hidden md:table-cell px-6 py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Urutan Anak</th>
                    <th class="hidden lg:table-cell px-6 py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Generasi</th>
                    <th class="hidden sm:table-cell px-6 py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Orang Tua</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs sm:text-sm font-semibold text-gray-900">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($people as $person)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if($person->photo)
                                    <img src="{{ asset('storage/' . $person->photo) }}" alt="{{ $person->name }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-100 flex items-center justify-center text-sm">👤</div>
                                @endif
                                <span class="font-medium text-gray-900 text-sm sm:text-base line-clamp-2">{{ $person->name }}</span>
                            </div>
                        </td>
                        <td class="hidden sm:table-cell px-6 py-4 text-sm text-gray-600">
                            <span class="inline-block px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                {{ $person->gender === 'male' ? '👨 L' : ($person->gender === 'female' ? '👩 P' : '-') }}
                            </span>
                        </td>
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600">
                            {{ $person->birth_date ? $person->birth_date->format('d M Y') : '-' }}
                        </td>
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600">
                            @if($person->parent_id)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                    #{{ $siblingSequenceById[$person->id] ?? '-' }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="hidden lg:table-cell px-6 py-4 text-sm text-gray-600">
                            <span class="badge-info">Gen {{ $person->generation }}</span>
                        </td>
                        <td class="hidden sm:table-cell px-6 py-4 text-sm text-gray-600">
                            {{ $person->parent?->name ? substr($person->parent->name, 0, 12) . (strlen($person->parent->name) > 12 ? '...' : '') : '-' }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.people.show', $person) }}" class="btn-icon text-blue-600 border-blue-200 hover:bg-blue-50 hover:border-blue-300" title="Lihat">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.people.edit', $person) }}" class="btn-icon text-amber-700 border-amber-200 hover:bg-amber-50 hover:border-amber-300" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.people.destroy', $person) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon text-red-600 border-red-200 hover:bg-red-50 hover:border-red-300" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 font-medium">Belum ada data keluarga</p>
                                <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan anggota keluarga pertama</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($people->hasPages())
        <div class="mt-6">
            {{ $people->links() }}
        </div>
    @endif
</div>
@endsection

