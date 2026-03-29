@php
    $showChildren = $showChildren ?? true;
    $descendantLevel = $descendantLevel ?? 1; // 1=Anak, 2=Cucu, >=3=Keturunan

    $role = $descendantLevel === 1
        ? 'Anak'
        : ($descendantLevel === 2 ? 'Cucu' : 'Keturunan');

    $hasChildren = $person->children->count() > 0;
@endphp

<div class="tree-generation flex flex-col items-center">
    <a href="{{ route('family-tree.show', $person) }}" class="inline-block w-full max-w-[16rem] sm:max-w-[18rem] h-full group">
        <article class="relative overflow-hidden rounded-2xl bg-white ring-1 ring-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 h-full min-h-[19rem] sm:min-h-[19.5rem]">
            <div class="absolute inset-x-0 top-0 h-20 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>
            <div class="relative px-4 sm:px-5 pb-4 pt-6 sm:pt-7 text-center h-full flex flex-col">
                <div class="relative mb-3 flex justify-center">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-lg overflow-hidden flex-shrink-0 bg-white">
                        @if($person->photo)
                            <img src="{{ $person->photo_url }}" alt="{{ $person->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center text-3xl sm:text-4xl">
                                👤
                            </div>
                        @endif
                    </div>
                </div>

                @isset($relationshipLabel)
                    @if($relationshipLabel)
                        <p class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-blue-100 text-blue-700 mb-2">
                            {{ $relationshipLabel }}
                        </p>
                    @endif
                @endisset

                <h3 class="font-bold text-gray-900 text-sm sm:text-base leading-snug line-clamp-2 min-h-[2.75rem]">
                    {{ $person->name }}
                </h3>

                @php
                    $spouses = $person->getSpouses();
                @endphp
                <div class="mt-2 flex items-center justify-center gap-2 text-xs text-gray-500 min-h-[1.25rem]">
                    @if($spouses->count() > 0)
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-ring text-pink-500"></i>
                            {{ $spouses->count() }} pasangan
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-user text-gray-400"></i>
                            Profil
                        </span>
                    @endif
                </div>

                <div class="mt-auto pt-3 inline-flex items-center justify-center gap-1 text-blue-600 text-xs sm:text-sm font-semibold">
                    <span>Lihat detail</span>
                    <i class="fas fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                </div>
            </div>
        </article>
    </a>

    @if($showChildren && $hasChildren)
        <details class="w-full mt-4 sm:mt-6 group">
            <summary class="list-none cursor-pointer flex items-center justify-center">
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-xs sm:text-sm font-semibold hover:bg-blue-100 transition-colors select-none">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white border border-blue-100 text-blue-600">
                        <i class="fas fa-sitemap text-[12px]"></i>
                    </span>
                    <span>Lihat {{ $role }} ({{ $person->children->count() }})</span>
                    <i class="fas fa-chevron-down text-[11px] transition-transform"></i>
                </span>
            </summary>

            <div class="flex flex-col items-center">
                <div class="w-0.5 h-8 sm:h-10 bg-gradient-to-b from-blue-300 to-blue-100 mt-2 rounded-full"></div>
                <div class="children-container w-full flex flex-wrap justify-center gap-4 sm:gap-6 mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-dashed border-blue-100">
                    @foreach($person->children->sortBy('name') as $child)
                        @php
                            $childLabel = $child->gender === 'male'
                                ? ($role . ' Laki-laki')
                                : ($role . ' Perempuan');
                        @endphp
                        @include('family-tree.partials.person-card', [
                            'person' => $child,
                            'relationshipLabel' => $childLabel,
                            'showChildren' => $showChildren,
                            'descendantLevel' => $descendantLevel + 1,
                        ])
                    @endforeach
                </div>
            </div>
        </details>
    @endif
</div>