<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Marriage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $people = Person::with(['parent', 'children', 'person1Marriages.person2', 'person2Marriages.person1'])
            ->orderBy('generation')
            ->orderBy('name')
            ->paginate(15);

        // Sequence number anak per orang tua (anak ke-1, ke-2, dst)
        $parentIds = $people->getCollection()
            ->pluck('parent_id')
            ->filter()
            ->unique()
            ->values();

        $siblingSequenceById = [];
        if ($parentIds->isNotEmpty()) {
            $siblings = Person::whereIn('parent_id', $parentIds)
                ->orderByRaw('birth_date IS NULL')
                ->orderBy('birth_date')
                ->orderBy('id')
                ->get(['id', 'parent_id']);

            $counterByParent = [];
            foreach ($siblings as $sibling) {
                $parentId = (int) $sibling->parent_id;
                $counterByParent[$parentId] = ($counterByParent[$parentId] ?? 0) + 1;
                $siblingSequenceById[$sibling->id] = $counterByParent[$parentId];
            }
        }

        $families = Person::where('generation', 1)
            ->orderBy('name')
            ->get()
            ->map(function ($root) {
                $root->family_slug = Str::slug($root->name);
                return $root;
            });

        return view('admin.people.index', compact('people', 'families', 'siblingSequenceById'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Person::whereNull('parent_id')->orWhere('generation', '<', 10)->get();
        return view('admin.people.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'parent_id' => 'nullable|exists:people,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('people', 'public');
        }

        // Calculate generation based on parent
        if ($request->parent_id) {
            $parent = Person::find($request->parent_id);
            $validated['generation'] = $parent->generation + 1;
        }

        $person = Person::create($validated);

        // Handle marriage if spouse_id provided
        if ($request->filled('spouse_id')) {
            Marriage::create([
                'person1_id' => $person->id,
                'person2_id' => $request->spouse_id,
                'marriage_date' => $request->marriage_date,
            ]);
        }

        return redirect()->route('admin.people.index')->with('success', 'Anggota keluarga berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        $person->load(['parent', 'children', 'person1Marriages.person2', 'person2Marriages.person1']);
        return view('admin.people.show', compact('person'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        $parents = Person::where('id', '!=', $person->id)->get();
        $potentialSpouses = Person::where('id', '!=', $person->id)->get();
        $marriages = Marriage::where('person1_id', $person->id)
            ->orWhere('person2_id', $person->id)
            ->with(['person1', 'person2'])
            ->get();

        return view('admin.people.edit', compact('person', 'parents', 'potentialSpouses', 'marriages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'parent_id' => 'nullable|exists:people,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Jangan hapus foto lama jika user tidak upload file baru.
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('people', 'public');
        } else {
            unset($validated['photo']);
        }

        // Recalculate generation if parent changed
        if ($request->parent_id && $request->parent_id != $person->parent_id) {
            $parent = Person::find($request->parent_id);
            $validated['generation'] = $parent->generation + 1;
        }

        $person->update($validated);

        return redirect()->route('admin.people.show', $person)->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        Marriage::where('person1_id', $person->id)
            ->orWhere('person2_id', $person->id)
            ->delete();

        $person->delete();

        return redirect()->route('admin.people.index')->with('success', 'Anggota keluarga berhasil dihapus!');
    }

    /**
     * Add marriage between two people
     */
    public function addMarriage(Request $request)
    {
        $validated = $request->validate([
            'person1_id' => 'required|exists:people,id',
            'person2_id' => 'required|exists:people,id|different:person1_id',
            'marriage_date' => 'nullable|date',
        ]);

        Marriage::create($validated);

        return back()->with('success', 'Pernikahan berhasil ditambahkan!');
    }

    /**
     * Delete marriage between two people.
     */
    public function deleteMarriage(Marriage $marriage)
    {
        $marriage->delete();

        return back()->with('success', 'Pernikahan berhasil dihapus!');
    }
}
