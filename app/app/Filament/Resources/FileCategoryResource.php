<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileCategoryResource\Pages;
use App\Filament\Resources\FileCategoryResource\RelationManagers;
use App\Models\FileCategory;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FileCategoryResource extends Resource
{
    protected static ?string $model = FileCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Category')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('sort')
                                    ->default(500),
                                FileUpload::make('icon_image')
                                    ->image(),
                                TextInput::make('icon'),
                                Textarea::make('description'),
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
                                Repeater::make('translations')
                                    ->relationship('translations')
                                    ->schema([
                                        Select::make('locale')
                                            ->label('Language')
                                            ->options(Language::all()->pluck('name', 'code')->toArray())
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
                TextColumn::make('slug')->sortable()->searchable(),
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
            'index' => Pages\ListFileCategories::route('/'),
            'create' => Pages\CreateFileCategory::route('/create'),
            'edit' => Pages\EditFileCategory::route('/{record}/edit'),
        ];
    }
}
