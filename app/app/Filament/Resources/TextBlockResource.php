<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TextBlockResource\Pages;
use App\Filament\Resources\TextBlockResource\RelationManagers;
use App\Models\Language;
use App\Models\TextBlock;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TextBlockResource extends Resource
{
    protected static ?string $model = TextBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Page')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('key')
                                    ->required()
                                    ->maxLength(255),
                                RichEditor::make('description')
                                    ->required()
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ]),
                            ]),
                        Tabs\Tab::make('Translations')
                            ->schema([
                                Repeater::make('translations')
                                    ->relationship('translations')
                                    ->schema([
                                        Select::make('locale')
                                            ->label('Language')
                                            ->options(Language::all()->pluck('name', 'code')->toArray())
                                            ->required()
                                            ->default('ru'),
                                        RichEditor::make('description')
                                            ->toolbarButtons([
                                                'attachFiles',
                                                'blockquote',
                                                'bold',
                                                'bulletList',
                                                'codeBlock',
                                                'h2',
                                                'h3',
                                                'italic',
                                                'link',
                                                'orderedList',
                                                'redo',
                                                'strike',
                                                'underline',
                                                'undo',
                                            ]),
                                    ])
                                    ->collapsed(false)
                                    ->createItemButtonLabel('Add Translation')
                            ]),
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('key')->sortable()->searchable(),
            ])
            ->filters([
                Filter::make('id')
                    ->label('ID')
                    ->form([
                        TextInput::make('id')
                            ->numeric()
                    ])
                    ->query(fn ($query, array $data) =>
                    $query->when($data['id'] ?? null, fn ($q, $value) => $q->where('id', $value))
                    ),

                Filter::make('name')
                    ->label('Name')
                    ->form([
                        TextInput::make('name')
                    ])
                    ->query(fn ($query, array $data) =>
                    $query->when($data['name'] ?? null, fn ($q, $value) => $q->where('name', 'like', "%{$value}%"))
                    ),

                Filter::make('key')
                    ->label('Key')
                    ->form([
                        TextInput::make('key')
                    ])
                    ->query(fn ($query, array $data) =>
                    $query->when($data['key'] ?? null, fn ($q, $value) => $q->where('key', 'like', "%{$value}%"))
                    ),
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
            'index' => Pages\ListTextBlocks::route('/'),
            'create' => Pages\CreateTextBlock::route('/create'),
            'edit' => Pages\EditTextBlock::route('/{record}/edit'),
        ];
    }
}
