<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Notifications\UserVerified;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $cluster = UserManagement::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('Roles'))
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn($state): string => Str::headline($state)),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(__('Email Verified'))
                    ->placeholder(__('Not Verified'))
                    ->since()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('approved_at')
                    ->label(__('Approved'))
                    ->updateStateUsing(function (User $record) {
                        if ($record->approved_at) {
                            $record->removeRole('member');

                            $record->approved_at = null;
                            $record->approved_by = null;
                            $record->save();

                            Notification::make('User Unverified')
                                ->title('User has been unverified')
                                ->success()
                                ->send();
                        } else {
                            $record->member()->create([
                                'membership_date' => now(),
                            ]);

                            $record->assignRole('member');

                            $record->approved_at = now();
                            $record->approved_by = auth()->id();
                            $record->save();

                            $record->notify(new UserVerified($record));

                            $record->notify(
                                Notification::make('User Verified')
                                    ->title('User has been verified')
                                    ->toDatabase()
                            );

                            Notification::make('User Verified')
                                ->title('User has been verified')
                                ->success()
                                ->send();
                        }
                    }),
                Tables\Columns\TextColumn::make('approvedBy.name')
                    ->label(__('Approved By')),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip('F j, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->since()
                    ->dateTimeTooltip('F j, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->multiple()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('assign-role')
                    ->icon('heroicon-o-user')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable(),
                    ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
