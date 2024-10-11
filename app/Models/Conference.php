<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Region;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'region' => Region::class,
        'venue_id' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm(): array
    {
        return  [
            TextInput::make('name')
                ->default(state: 'My Conference')
                ->helperText( text: 'This is the name of your conference')
                ->required()
                ->maxLength(length: 60),
            MarkdownEditor::make('description')
            ->helperText( text: 'Hello')
                ->required(),
            DateTimePicker::make('start_date')
                ->native( condition: false)
                ->required(),
            DateTimePicker::make('end_date')
                ->native( condition: false)
                ->required(),
            Toggle::make(name: 'is_published')
                ->default(state: true),
            Select::make('status')
                ->options( options: [
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ] )
                ->required(),
            TextInput::make('status')
                ->required(),
                Select::make('region')
                ->live()
                ->enum(Region::class)
                -> options(Region::class),
            Select::make('venue_id')
                ->searchable()
                ->preload()
                ->createOptionForm(schema: Venue::getForm())
                ->editOptionForm(Venue::getForm(''))
                ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, \Filament\Forms\Get $get) {
                    return $query->where(column: 'region', operator: $get(path: 'region'));
                }),
            CheckboxList::make('speakers')
            ->relationship(name: 'speakers', titleAttribute: 'name')
            ->options(
                options:Speaker::all()->pluck(value:'name', key:'id')
            )
            ];
    }
}
