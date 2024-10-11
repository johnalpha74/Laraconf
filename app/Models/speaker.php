<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Forms\Components\TextInput;


class Speaker extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'qualifications'=> 'array',
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }
    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required(),
            TextInput::make('email')
                ->email()
                ->required(),
            TextInput::make('bio')
                ->required(),
            TextInput::make('twitter_handle')
                ->required(),
            CheckboxList::make('qualifications')
                 ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->options( options: [
                    'business-leader' => 'Business leader',
                    'charisma'=> 'Charismatic Speaker',
                    'first-time'=> 'First Time Speaker',
                    'hometown-hero'=> 'Hometown Hero',
                    'humanitarian'=> 'Works in Humanitarian Field',
                    'laracasts-contributor' => 'Laracasts Contributor',
                    'twitter-influencer'=> 'Large Twitter Following',
                    'youtube-influencer'=> 'Large YouTube Following',
                    'open-source'=> 'Open Source Creator / Maintainer',
                    'unique-perspective'=> 'Unique Perspective',

                ]
            )
            ->descriptions([
                'business-leader' => 'Are you a business leader',
                'charisma' => 'This is someone who talks too much',
            ])
            ->columns(3),
        ];
}

}
