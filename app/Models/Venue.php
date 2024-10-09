<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\Region;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class Venue extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'region' => Region::class,
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm(): array{
        return [
            TextInput::make('name')
                ->required(),
            TextInput::make('city')
                ->required(),
            TextInput::make('country')
                ->required(),
            TextInput::make('postal_code')
                ->required(),
            Select::make('region')
                ->enum(Region::class)
                -> options(Region::class),
            ];
    }
}
