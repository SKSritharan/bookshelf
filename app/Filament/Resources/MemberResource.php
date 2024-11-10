<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use App\Models\User;
use App\Notifications\UserVerified;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MemberResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'Members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label(__('Email'))
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->label(__('Password'))
                    ->required()
                    ->password()
                    ->revealable()
                    ->rule(Password::default())
                    ->autocomplete('new-password')
                    ->dehydrated(fn($state): bool => filled($state))
                    ->dehydrateStateUsing(fn($state): string => Hash::make($state)),
                Forms\Components\Grid::make()
                    ->relationship('member')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label(__('Phone'))
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('address')
                            ->label(__('Address'))
                            ->required()
                            ->placeholder('1234 Main St, City, State, Zip'),
                        Forms\Components\DatePicker::make('membership_date')
                            ->label(__('Membership Date'))
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('member.membership_date')
                    ->label(__('Membership Date'))
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('approved_at')
                    ->label(__('Approved'))
                    ->updateStateUsing(function (User $record) {
                        if ($record->approved_at) {
                            $record->approved_at = null;
                            $record->approved_by = null;
                            $record->save();
                        }else{
                            $record->approved_at = now();
                            $record->approved_by = auth()->id();
                            $record->save();

                            $record->notify(new UserVerified($record));
                        }
                    }),
                Tables\Columns\TextColumn::make('approvedByUser.name')
                    ->label(__('Approved By')),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('roles', function ($query) {
            $query->where('name', 'member');
        });
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
