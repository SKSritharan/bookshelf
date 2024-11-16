<?php

namespace App\Filament\Resources;

use App\Enums\Language;
use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('Title'))
                                    ->required(),
                                Forms\Components\Select::make('language')
                                    ->label(__('Language'))
                                    ->options(Language::class)
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label(__('Description'))
                                    ->required()
                                    ->placeholder(__('Description')),
                            ]),
                        Forms\Components\Section::make('Cover Image')
                            ->schema([
                                Forms\Components\FileUpload::make('cover')
                                    ->hiddenLabel()
                                    ->image()
                                    ->required(),
                            ])
                            ->collapsible(),
                        Forms\Components\Section::make('Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('isbn')
                                    ->label(__('ISBN'))
                                    ->required()
                                    ->placeholder(__('ISBN')),
                                Forms\Components\TextInput::make('isbn13')
                                    ->label(__('ISBN13'))
                                    ->required(),
                                Forms\Components\TextInput::make('available_copies')
                                    ->label(__('Available Copies'))
                                    ->required(),
                                Forms\Components\TextInput::make('total_copies')
                                    ->label(__('Total Copies'))
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema(
                        [
                            Forms\Components\Section::make('')
                                ->schema([
                                    Forms\Components\Select::make('author_id')
                                        ->label(__('Author'))
                                        ->relationship('author', 'name')
                                        ->required(),
                                    Forms\Components\Select::make('category_id')
                                        ->label(__('Category'))
                                        ->relationship('category', 'name')
                                        ->required()
                                        ->placeholder(__('Category')),
                                ]),
                            Forms\Components\Section::make('Tags')
                                ->schema([
                                    Forms\Components\SpatieTagsInput::make('tags')
                                        ->hiddenLabel(),
                                ]),
                        ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('cover')
                        ->label(__('Cover'))
                        ->alignCenter()
                        ->height('100%')
                        ->width('75%'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('title')
                            ->label(__('Title'))
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('description')
                            ->label(__('Description'))
                            ->words(10),
                    ])
                ])->space(3),
            ])
            ->filters([
                //
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
