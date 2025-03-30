<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileFormatResource\Pages;
use App\Filament\Resources\FileFormatResource\RelationManagers;
use App\Models\FileFormat;
use App\Models\Language;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FileFormatResource extends Resource
{
    protected static ?string $model = FileFormat::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Format Details')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                                Textarea::make('extended_name'),
                                TextInput::make('extension')
                                    ->required(),
                                Toggle::make('active')
                                    ->label('Active')
                                    ->default(true),
                                TextInput::make('sort')
                                    ->default(500),
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->preload()
                                    ->label('File type'),
                                TextInput::make('color'),
                                TextInput::make('mime_type'),
                                FileUpload::make('icon_image')
                                    ->image(),
                                TextInput::make('icon'),
                                Select::make('convertible')
                                    ->multiple()
                                    ->relationship('convertible', 'name')
                                    ->preload()
                                    ->label('Can convert'),
                                Select::make('convertibleCategory')
                                    ->multiple()
                                    ->relationship('convertibleCategory', 'name')
                                    ->preload()
                                    ->label('Can type convert'),
                                Textarea::make('excerpt'),
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
                                        TextInput::make('meta_title')->label('Meta Title'),
                                        Textarea::make('meta_description')->label('Meta Description'),
                                        Textarea::make('meta_keywords')->label('Meta Keywords'),
                                    ])
                                    ->defaultItems(0)
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
                                        Textarea::make('extended_name'),
                                        Textarea::make('excerpt'),
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
                                    ->defaultItems(0)
                                    ->collapsed(false)
                                    ->createItemButtonLabel('Add Translation')
                            ]),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('sort')->sortable(),
                TextColumn::make('extension')->sortable()->searchable(),
                TextColumn::make('color')->sortable()->searchable(),
                TextColumn::make('mime_type')->sortable()->searchable(),
                TextColumn::make('category.name')->label('Type'),
                IconColumn::make('active')
                    ->label('Active')
                    ->boolean(),
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
            'index' => Pages\ListFileFormats::route('/'),
            'create' => Pages\CreateFileFormat::route('/create'),
            'edit' => Pages\EditFileFormat::route('/{record}/edit'),
        ];
    }
}
