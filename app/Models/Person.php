<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Person extends Model
{
    protected $fillable = [
        'name',
        'birth_date',
        'death_date',
        'gender',
        'parent_id',
        'generation',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    /**
     * URL publik foto. Memakai route /media/... (bukan /storage/...) supaya file dilayani Laravel
     * dan tidak bergantung symlink public/storage (sering menyebabkan 403 di hosting).
     */
    protected function photoUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (! $this->photo) {
                return null;
            }

            return route('public-storage.file', ['path' => $this->photo], absolute: true);
        });
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'person_id');
    }

    // Parent relationship
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'parent_id');
    }

    /**
     * Akar silsilah (orang tanpa parent_id).
     */
    public function familyRoot(): Person
    {
        $p = $this;
        while ($p->parent_id !== null) {
            $p = static::query()->findOrFail($p->parent_id);
        }

        return $p;
    }

    /**
     * Semua id keturunan langsung (anak, cucu, …) dari satu orang.
     *
     * @return Collection<int, int>
     */
    public static function descendantIds(int $personId): Collection
    {
        $ids = collect();
        $queue = [$personId];
        while ($queue !== []) {
            $id = array_shift($queue);
            $childIds = static::query()->where('parent_id', $id)->pluck('id');
            foreach ($childIds as $cid) {
                $cid = (int) $cid;
                $ids->push($cid);
                $queue[] = $cid;
            }
        }

        return $ids;
    }

    /**
     * ID pasangan seseorang dari tabel `marriages`.
     *
     * @return Collection<int, int>
     */
    protected static function spouseIdsForPersonId(int $personId): Collection
    {
        return Marriage::query()
            ->where(function ($q) use ($personId) {
                $q->where('person1_id', $personId)
                    ->orWhere('person2_id', $personId);
            })
            ->get()
            ->map(function (Marriage $m) use ($personId) {
                return (int) $m->person1_id === $personId ? (int) $m->person2_id : (int) $m->person1_id;
            })
            ->unique()
            ->values();
    }

    /**
     * ID yang boleh dikelola oleh kepala cabang: diri sendiri, pasangan, keturunan, serta pasangan dari siapa pun
     * di cabang itu (mis. menantu: menikah dengan anak, tanpa parent_id ke mertua).
     *
     * Perluasan diulang sampai tidak ada id baru (agar menantu + keturunan menantu ikut).
     *
     * @return Collection<int, int>
     */
    public static function manageableBranchIdsFor(Person $p): Collection
    {
        $pId = (int) $p->id;

        $roots = collect([$pId])->merge(static::spouseIdsForPersonId($pId))->unique()->values();

        $ids = collect();
        foreach ($roots as $rid) {
            $rid = (int) $rid;
            $ids->push($rid);
            $ids = $ids->merge(static::descendantIds($rid));
        }

        $ids = $ids->unique()->values();

        $changed = true;
        while ($changed) {
            $changed = false;
            $snapshot = $ids->values();

            foreach ($snapshot as $pid) {
                $pid = (int) $pid;

                foreach (static::spouseIdsForPersonId($pid) as $sid) {
                    $sid = (int) $sid;

                    if ($ids->contains(fn ($id): bool => (int) $id === $sid)) {
                        continue;
                    }

                    $ids->push($sid);
                    $ids = $ids->merge(static::descendantIds($sid));
                    $changed = true;
                }
            }

            $ids = $ids->unique()->values();
        }

        return $ids;
    }

    // Children relationship
    public function children(): HasMany
    {
        return $this->hasMany(Person::class, 'parent_id')
            ->orderByRaw('birth_date IS NULL')
            ->orderBy('birth_date')
            ->orderBy('id');
    }

    /**
     * Urutan tampilan daftar (generasi → orang tua → urutan anak: lahir dulu, tanpa tanggal di akhir).
     */
    public function scopeOrderedByFamilyTree(Builder $query): Builder
    {
        return $query
            ->orderBy('generation')
            ->orderBy('parent_id')
            ->orderByRaw('birth_date IS NULL')
            ->orderBy('birth_date')
            ->orderBy('id');
    }

    /**
     * Peta id orang → nomor urut anak (1,2,3…) dalam satu orang tua (sama dengan urutan sort).
     *
     * @return array<int, int>
     */
    public static function siblingSequenceByIdMap(): array
    {
        $map = [];
        $rows = static::query()
            ->whereNotNull('parent_id')
            ->orderBy('parent_id')
            ->orderByRaw('birth_date IS NULL')
            ->orderBy('birth_date')
            ->orderBy('id')
            ->get(['id', 'parent_id']);

        $counterByParent = [];
        foreach ($rows as $row) {
            $pid = (int) $row->parent_id;
            $counterByParent[$pid] = ($counterByParent[$pid] ?? 0) + 1;
            $map[$row->id] = $counterByParent[$pid];
        }

        return $map;
    }

    // Marriages relationship
    public function marriages(): HasMany
    {
        return $this->hasMany(Marriage::class, 'person1_id')
            ->orWhere('person2_id', $this->id);
    }

    // Spouse relationship
    public function spouse(): HasOne
    {
        return $this->hasOne(Marriage::class, 'person1_id')
            ->select('id', 'person1_id', 'person2_id', 'marriage_date')
            ->with(['person2']);
    }

    public function person1Marriages()
    {
        return $this->hasMany(Marriage::class, 'person1_id');
    }

    public function person2Marriages()
    {
        return $this->hasMany(Marriage::class, 'person2_id');
    }

    public function getSpouses()
    {
        $spouse1 = $this->person1Marriages->map(fn ($m) => $m->person2);
        $spouse2 = $this->person2Marriages->map(fn ($m) => $m->person1);

        return $spouse1->concat($spouse2);
    }
}
