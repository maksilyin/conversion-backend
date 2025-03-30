<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Language;
use App\Models\Page;
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
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Page')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->schema([
                                Toggle::make('active')
                                    ->label('Active')
                                    ->default(true),
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('url')
                                    ->required()
                                    ->maxLength(255),
                                FileUpload::make('image')
                                    ->image(),
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
                            ]),
                        Tabs\Tab::make('SEO')
                            ->schema([
                                Repeater::make('seo')
                                    ->relationship('seo')
                                    ->schema([
                                        Select::make('locale')
                                            ->label('Language')
                                            ->options(Language::all()->pluck('name', 'code')->toArray())
                                            ->required()
                                            ->default('en'),
                                        Textarea::make('meta_title')->label('Meta Title'),
                                        Textarea::make('meta_description')->label('Meta Description'),
                                        Textarea::make('meta_keywords')->label('Meta Keywords'),
                                    ])
                                    ->collapsed(false)
                                    ->createItemButtonLabel('Add Translation')
                            ]),
                        Tabs\Tab::make('Translations')
                            ->schema([
                                Textarea::make('import_translations')
                                    ->label('Импорт переводов (JSON)')
                                    ->columnSpanFull()
                                    ->rows(10)
                                    ->dehydrated(false)
                                    ->live(),
                                Repeater::make('translations')
                                    ->relationship('translations')
                                    ->schema([
                                        Select::make('locale')
                                            ->label('Language')
                                            ->options(Language::all()->pluck('code', 'code')->toArray())
                                            ->required()
                                            ->default('ru'),
                                        TextInput::make('name')->label('Name'),
                                        Textarea::make('description')->label('Description'),
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
                TextColumn::make('sort')->sortable(),
                TextColumn::make('url')->sortable()->searchable(),
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

                Filter::make('url')
                    ->label('URL')
                    ->form([
                        TextInput::make('url')
                            ->placeholder('Введите URL'),
                    ])
                    ->query(fn ($query, array $data) =>
                        $query->when($data['url'] ?? null, fn ($q, $value) => $q->where('url', 'like', "%{$value}%"))
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
