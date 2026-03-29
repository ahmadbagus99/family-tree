<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marriage;
use App\Models\Person;
use App\Models\User;
use App\Services\ProfilePhotoService;
use App\Services\UserProvisioningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PersonController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Person::class, 'person');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $people = Person::query()
            ->with(['parent', 'children', 'person1Marriages.person2', 'person2Marriages.person1']);

        if (! $user->is_super_admin) {
            $people->whereIn('id', $user->manageablePersonIds());
        }

        if ($user->person_id) {
            $people->orderByRaw('CASE WHEN people.id = ? THEN 0 ELSE 1 END', [$user->person_id]);
        }

        $people = $people->orderedByFamilyTree()->paginate(15);

        $siblingSequenceById = Person::siblingSequenceByIdMap();

        if ($user->is_super_admin) {
            $families = Person::where('generation', 1)
                ->orderByRaw('birth_date IS NULL')
                ->orderBy('birth_date')
                ->orderBy('name')
                ->orderBy('id')
                ->get()
                ->map(function ($root) {
                    $root->family_slug = Str::slug($root->name);

                    return $root;
                });
        } else {
            $root = Person::query()->findOrFail($user->person_id)->familyRoot();
            $root->family_slug = Str::slug($root->name);
            $families = collect([$root]);
        }

        return view('admin.people.index', compact('people', 'families', 'siblingSequenceById'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $manageable = $user->manageablePersonIds();

        $parents = Person::query()
            ->whereIn('id', $manageable)
            ->orderBy('generation')
            ->orderBy('name')
            ->get();

        $potentialSpouses = Person::query()
            ->whereIn('id', $manageable)
            ->orderBy('name')
            ->get();

        return view('admin.people.create', compact('parents', 'potentialSpouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, UserProvisioningService $provisioning, ProfilePhotoService $photos)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'parent_id' => [
                'nullable',
                'exists:people,id',
                Rule::requiredIf(fn () => ! $user->is_super_admin && ! $request->filled('spouse_id')),
            ],
            'photo' => 'nullable|image|max:12288',
            'spouse_id' => [
                'nullable',
                'exists:people,id',
                Rule::requiredIf(fn () => ! $user->is_super_admin && ! $request->filled('parent_id')),
            ],
            'marriage_date' => 'nullable|date',
        ];

        $validated = $request->validate($rules);

        $parentId = isset($validated['parent_id']) ? $validated['parent_id'] : null;
        if ($parentId === '' || $parentId === null) {
            $parentId = null;
        } else {
            $parentId = (int) $parentId;
        }

        $spouseId = isset($validated['spouse_id']) ? $validated['spouse_id'] : null;
        if ($spouseId === '' || $spouseId === null) {
            $spouseId = null;
        } else {
            $spouseId = (int) $spouseId;
        }

        $validated['parent_id'] = $parentId;

        if (! $user->is_super_admin && $parentId !== null) {
            if (! $user->canManagePersonId($parentId)) {
                abort(403);
            }
        }

        if ($spouseId !== null && ! $user->canManagePersonId($spouseId)) {
            abort(403);
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $photos->storeCompressed($request->file('photo'));
        }

        if ($parentId !== null) {
            $parent = Person::query()->findOrFail($parentId);
            $generation = (int) $parent->generation + 1;
        } elseif ($spouseId !== null) {
            $validated['parent_id'] = null;
            $spouse = Person::query()->findOrFail($spouseId);
            $generation = (int) $spouse->generation;
            if ($generation < 1) {
                $generation = 1;
            }
        } elseif ($user->is_super_admin) {
            $generation = 1;
        } else {
            $generation = 1;
        }

        unset($validated['spouse_id'], $validated['marriage_date']);

        $person = Person::create(array_merge($validated, ['generation' => $generation]));

        if ($spouseId !== null) {
            $marriage = Marriage::create([
                'person1_id' => $person->id,
                'person2_id' => $spouseId,
                'marriage_date' => $request->marriage_date,
            ]);
            $provisioning->syncForMarriage($marriage);
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
        $user = auth()->user();
        $manageable = $user->manageablePersonIds();

        $parents = Person::query()
            ->where('id', '!=', $person->id)
            ->whereIn('id', $manageable)
            ->orderBy('name')
            ->get();

        $potentialSpouses = Person::query()
            ->where('id', '!=', $person->id)
            ->whereIn('id', $manageable)
            ->orderBy('name')
            ->get();

        $marriages = Marriage::where('person1_id', $person->id)
            ->orWhere('person2_id', $person->id)
            ->with(['person1', 'person2'])
            ->get();

        return view('admin.people.edit', compact('person', 'parents', 'potentialSpouses', 'marriages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person, UserProvisioningService $provisioning, ProfilePhotoService $photos)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'parent_id' => [
                Rule::requiredIf(fn () => ! $user->is_super_admin && $person->parent_id !== null),
                'nullable',
                'exists:people,id',
            ],
            'photo' => 'nullable|image|max:12288',
        ]);

        if (! $user->is_super_admin && $request->input('parent_id') === null && $person->parent_id !== null) {
            abort(403);
        }

        if ($request->filled('parent_id')) {
            if (! $user->is_super_admin && ! $user->canManagePersonId((int) $request->parent_id)) {
                abort(403);
            }
        }

        if ($request->hasFile('photo')) {
            $oldPhoto = $person->photo;
            $validated['photo'] = $photos->storeCompressed($request->file('photo'));
            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        } else {
            unset($validated['photo']);
        }

        if ($request->filled('parent_id')
            && (int) $request->parent_id !== (int) $person->parent_id) {
            $parent = Person::find($request->parent_id);
            $validated['generation'] = $parent->generation + 1;
        } elseif ($user->is_super_admin && ! $request->filled('parent_id')) {
            $validated['parent_id'] = null;
            $validated['generation'] = 1;
        }

        $person->update($validated);

        $provisioning->syncLinkedUserFromPerson($person->fresh());

        if ($person->gender === 'male') {
            $provisioning->ensureUserForMale($person->fresh());
        }

        return redirect()->route('admin.people.show', $person)->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        User::query()->where('person_id', $person->id)->delete();

        Marriage::where('person1_id', $person->id)
            ->orWhere('person2_id', $person->id)
            ->delete();

        $person->delete();

        return redirect()->route('admin.people.index')->with('success', 'Anggota keluarga berhasil dihapus!');
    }

    /**
     * Add marriage between two people
     */
    public function addMarriage(Request $request, UserProvisioningService $provisioning)
    {
        $validated = $request->validate([
            'person1_id' => 'required|exists:people,id',
            'person2_id' => 'required|exists:people,id|different:person1_id',
            'marriage_date' => 'nullable|date',
        ]);

        $user = auth()->user();
        if (! $user->canManagePersonId((int) $validated['person1_id'])
            && ! $user->canManagePersonId((int) $validated['person2_id'])) {
            abort(403);
        }

        $marriage = Marriage::create($validated);
        $provisioning->syncForMarriage($marriage);

        return back()->with('success', 'Pernikahan berhasil ditambahkan!');
    }

    /**
     * Delete marriage between two people.
     */
    public function deleteMarriage(Request $request, Marriage $marriage)
    {
        $authUser = auth()->user();
        $authUserId = $authUser->id;

        $pid1 = $marriage->person1_id;
        $pid2 = $marriage->person2_id;

        // Satu pernikahan menghubungkan dua orang; pengurus cabang hanya punya akses ke
        // satu sisi pohon. Wajib bisa mengelola minimal satu pihak (bukan keduanya).
        if (! $authUser->canManagePersonId($pid1)
            && ! $authUser->canManagePersonId($pid2)) {
            abort(403);
        }

        $marriage->delete();

        foreach ([$pid1, $pid2] as $pid) {
            $p = Person::find($pid);
            if ($p && $p->gender === 'male') {
                $hasMarriage = Marriage::query()
                    ->where(function ($q) use ($p) {
                        $q->where('person1_id', $p->id)
                            ->orWhere('person2_id', $p->id);
                    })
                    ->exists();

                if (! $hasMarriage) {
                    User::query()->where('person_id', $p->id)->delete();
                }
            }
        }

        if (! User::query()->whereKey($authUserId)->exists()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Pernikahan dihapus. Akun login Anda dinonaktifkan karena tidak ada lagi pasangan; silakan hubungi admin jika perlu.');
        }

        return back()->with('success', 'Pernikahan berhasil dihapus!');
    }
}
