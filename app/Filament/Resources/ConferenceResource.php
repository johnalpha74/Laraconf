<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConferenceResource\Pages;
use App\Filament\Resources\ConferenceResource\RelationManagers;
use App\Models\Conference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\Region;
use App\Models\Venue;

class ConferenceResource extends Resource
{
    protected static ?string $model = Conference::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->default(state: 'My Conference')
                    ->helperText( text: 'This is the name of your conference')
                    ->required()
                    ->maxLength(length: 60),
                Forms\Components\MarkDownEditor::make('description')
                ->helperText( text: 'Hello')
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->native( condition: false)
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                    ->native( condition: false)
                    ->required(),
                Forms\Components\Checkbox::make(name: 'is_published')
                    ->default(state: true),
                Forms\Components\Select::make('status')
                    ->options( options: [
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ] )
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                    Forms\Components\Select::make('region')
                    ->live()
                    ->enum(Region::class)
                    -> options(Region::class),
                Forms\Components\Select::make('venue_id')
                    ->searchable()
                    ->preload()
                    ->createOptionForm(schema: Venue::getForm())
                    ->editOptionForm(Venue::getForm(''))
                    ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                        return $query->where(column: 'region', operator: $get(path: 'region'));
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region')
                    ->searchable(),
                Tables\Columns\TextColumn::make('venue.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConferences::route('/'),
            'create' => Pages\CreateConference::route('/create'),
            'edit' => Pages\EditConference::route('/{record}/edit'),
        ];
    }
}
