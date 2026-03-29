@php
    $generationLevel = $generationLevel ?? 0; // 0=anak, 1=cucu, dst.
    $showSummaryOpen = $showSummaryOpen ?? false;

    $spouses = $person->getSpouses()->unique('id')->sortBy('name')->values();

    $children = collect($person->children);
    foreach ($spouses as $spouse) {
        $children = $children->merge($spouse->children);
    }
    $children = $children->unique('id')->sortBy(function ($c) {
        return [
            $c->birth_date ? $c->birth_date->timestamp : PHP_INT_MAX,
            $c->id,
        ];
    })->values();

    $descLabel = $generationLevel === 0 ? 'Anak' : ($generationLevel === 1 ? 'Cucu' : 'Keturunan');
    $partnerLabel = $person->gender === 'male' ? 'Istri' : 'Suami';
@endphp

<div class="w-full flex flex-col items-center">
    @php
        $isCoupleLayout = $spouses->count() > 0;
    @endphp

    <div class="w-full flex flex-wrap justify-center items-stretch gap-4 sm:gap-6 pb-2 -mb-2">
        <div class="text-center {{ $isCoupleLayout ? 'w-[calc(50%-0.5rem)] sm:w-auto' : 'w-full sm:w-auto' }}">
            @include('family-tree.partials.person-card', [
                'person' => $person,
                'relationshipLabel' => $relationshipLabel ?? ($generationLevel === 0 ? 'Kepala Keluarga' : null),
                'showChildren' => false,
            ])
        </div>

        @foreach($spouses as $spouse)
            <div class="text-center w-[calc(50%-0.5rem)] sm:w-auto">
                @include('family-tree.partials.person-card', [
                    'person' => $spouse,
                    'relationshipLabel' => $partnerLabel,
                    'showChildren' => false,
                ])
            </div>
        @endforeach
    </div>

    @if($children->count() > 0)
        <details class="w-full mt-4 sm:mt-6" {{ $showSummaryOpen ? 'open' : '' }}>
            <summary class="list-none cursor-pointer flex items-center justify-center">
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-xs sm:text-sm font-semibold hover:bg-blue-100 transition-colors select-none">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white border border-blue-100 text-blue-600">
                        <i class="fas fa-sitemap text-[12px]"></i>
                    </span>
                    <span>Lihat {{ $descLabel }} ({{ $children->count() }})</span>
                </span>
            </summary>

            <div class="flex flex-col items-center">
                <div class="w-0.5 h-8 sm:h-10 bg-gradient-to-b from-blue-300 to-blue-100 mt-2 rounded-full"></div>
                <div class="w-full flex flex-wrap justify-center gap-4 sm:gap-6 mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-dashed border-blue-100">
                    @foreach($children as $child)
                        @php
                            $childLabel = $child->gender === 'male'
                                ? ($descLabel . ' Laki-laki')
                                : ($descLabel . ' Perempuan');
                        @endphp
                        <div class="w-full">
                            @include('family-tree.partials.family-unit', [
                                'person' => $child,
                                'relationshipLabel' => $childLabel,
                                'generationLevel' => $generationLevel + 1,
                                'showSummaryOpen' => false,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </details>
    @endif
</div>
