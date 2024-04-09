<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ParticipantResource\Pages;
use App\Filament\Admin\Resources\ParticipantResource\RelationManagers;
use App\Models\Participant;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ParticipantResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Buat Akun')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('password')
                                ->label('Password')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('password_confirmation')
                                ->label('Konfirmasi Password')
                                ->required()
                                ->columnSpanFull(),
                        ]),
                    Forms\Components\Wizard\Step::make('Data Diri')
                        ->icon('heroicon-o-user-circle')
                        ->schema([
                            Forms\Components\Fieldset::make('Data Diri')
                                ->model(User::class)
                                ->relationship('userDetail')
                                ->schema([
                                    Forms\Components\TextInput::make('phone')
                                        ->label('Nomor telepon')
                                        ->tel()
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('school')
                                        ->label('Sekolah asal'),
                                    Forms\Components\Select::make('grade')
                                        ->label('Jenjang saat ini')
                                        ->searchable(),
                                    Forms\Components\Select::make('province_id')
                                        ->label('Provinsi')
                                        ->relationship('province', 'name')
                                        ->options([
                                            '1' => 'Aceh',
                                        ])
                                        ->searchable()
                                        ->live(),
                                    Forms\Components\Select::make('regency_id')
                                        ->label('Kabupaten/Kota')
                                        ->disabled(fn ($get) => $get('province_id') === null)
                                        ->options([
                                            '1' => 'Aceh Barat',
                                        ])
                                        ->searchable(),
                                    Forms\Components\Textarea::make('address')
                                        ->label('Alamat')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Forms\Components\Wizard\Step::make('Detail Lomba')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            Forms\Components\Select::make('jenjang')
                                ->label('Jenjang')
                                ->options([
                                    'SD' => 'SD',
                                    'SMP' => 'SMP',
                                    'SMA' => 'SMA',
                                    'MI' => 'MI',
                                    'MTs' => 'MTs',
                                    'MA' => 'MA'
                                ])
                                ->required(),
                            Forms\Components\Checkbox::make('terms')
                                ->label(fn () => new HtmlString('Saya menyetujui <a href="#" class="underline">syarat dan ketentuan</a> yang berlaku'))
                                ->required()
                                ->accepted()
                                ->validationMessages([
                                    'accepted' => 'Kolom ini harus dicentang'
                                ])
                                ->dehydrated(false)
                        ]),

                ])
                    ->submitAction(
                        Action::make('register')
                            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'))
                            ->submit('register')
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn () => parent::getEloquentQuery()->whereHas('userDetail'))
            ->columns([
                //
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
            'index' => Pages\ListParticipants::route('/'),
            'create' => Pages\CreateParticipant::route('/create'),
            'edit' => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }
}
