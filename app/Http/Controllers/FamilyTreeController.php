<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Support\Str;

class FamilyTreeController extends Controller
{
    /**
     * Display the family tree
     */
    public function index()
    {
        // Daftar keluarga berdasarkan akar silsilah (generasi 1).
        $roots = Person::where('generation', 1)
            ->with(['children', 'person1Marriages.person2', 'person2Marriages.person1'])
            ->orderByRaw('birth_date IS NULL')
            ->orderBy('birth_date')
            ->orderBy('name')
            ->orderBy('id')
            ->get()
            ->map(function ($root) {
                $root->family_slug = Str::slug($root->name);

                return $root;
            });

        return view('family-tree.index', compact('roots'));
    }

    /**
     * Display tree for a specific family root slug.
     */
    public function family(string $family)
    {
        $roots = Person::where('generation', 1)
            ->with(['children', 'person1Marriages.person2', 'person2Marriages.person1'])
            ->orderByRaw('birth_date IS NULL')
            ->orderBy('birth_date')
            ->orderBy('name')
            ->orderBy('id')
            ->get();

        $root = $roots->first(function ($person) use ($family) {
            $slug = Str::slug($person->name);

            return $slug === $family || Str::contains($slug, $family);
        });

        abort_if(! $root, 404);

        return view('family-tree.show', compact('root'));
    }

    /**
     * Display a specific person and their details
     */
    public function show(Person $person)
    {
        $person->load([
            'parent',
            'children',
            'person1Marriages.person2',
            'person2Marriages.person1',
        ]);

        return view('family-tree.show', compact('person'));
    }
}
