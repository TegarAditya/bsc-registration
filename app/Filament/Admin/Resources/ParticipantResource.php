<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ParticipantResource\Pages;
use App\Filament\Admin\Resources\ParticipantResource\RelationManagers;
use App\Models\City;
use App\Models\Participant;
use App\Models\Province;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;

class ParticipantResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Data Peserta';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Daftar Peserta';

    protected static ?string $modelLabel = 'Peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Section::make('Buat Akun')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->rule(Password::default())
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Data Diri')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Fieldset::make('userDetail')
                            ->hiddenLabel()
                            ->relationship('userDetail')
                            ->schema([
                                Forms\Components\TextInput::make('phone_number')
                                    ->label('Nomor telepon')
                                    ->helperText('Nomor telepon pribadi yang dapat dihubungi')
                                    ->tel()
                                    ->required(fn ($get) => $get('../email') == null)
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('companion_phone_number')
                                    ->label('Nomor telepon pendamping')
                                    ->helperText('Nomor telepon pendamping atau guru yang dapat dihubungi')
                                    ->tel()
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('school')
                                    ->label('Sekolah asal'),
                                Forms\Components\Select::make('grade')
                                    ->label('Jenjang saat ini')
                                    ->options([
                                        'SD' => 'SD',
                                        'SMP' => 'SMP',
                                        'SMA' => 'SMA',
                                        'MI' => 'MI',
                                        'MTs' => 'MTs',
                                        'MA' => 'MA'
                                    ])
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $grade = $state;

                                        if ($grade === 'SD' || $grade === 'SMP' || $grade === 'SMA') {
                                            $set('type', 'KSN');
                                        }
                                    }),
                                Forms\Components\Select::make('province_id')
                                    ->label('Provinsi')
                                    ->options(fn (Province $province) => $province->all()->pluck('name', 'id')->toArray())
                                    ->searchable()
                                    ->live(),
                                Forms\Components\Select::make('city_id')
                                    ->label('Kabupaten/Kota')
                                    ->options(fn ($get) => $get('province_id') ? City::where('province_id', $get('province_id'))->orderBy('name', 'asc')->pluck('name', 'id')->toArray() : [])
                                    // ->searchable()
                                    ->disabled(fn ($get) => $get('province_id') === null),
                                Forms\Components\Textarea::make('address')
                                    ->label('Alamat (opsional)')
                                    ->columnSpanFull(),
                            ]),
                    ]),
                Forms\Components\Section::make('Data Lomba')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Fieldset::make('Data Lomba')
                            ->hiddenLabel()
                            ->relationship('userDetail')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Jenis Lomba')
                                    ->helperText('Pilih jenis lomba yang akan anda ikuti. Peserta hanya dapat mengikuti satu jenis lomba.')
                                    ->options(function (Get $get) {
                                        $grade = $get('grade');

                                        if ($grade === 'SD' || $grade === 'SMP' || $grade === 'SMA') {
                                            return [
                                                'KSN' => 'BSC Umum (KSN)',
                                            ];
                                        }

                                        if ($grade === 'MI' || $grade === 'MTs' || $grade === 'MA') {
                                            return [
                                                'KSN' => 'BSC Umum (KSN)',
                                                'KSM' => 'BSC Madrasah (KSM)',
                                            ];
                                        }

                                        return [];
                                    })
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ]),
                Forms\Components\Section::make('Syarat dan Ketentuan')
                    ->visibleOn(['create'])
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Forms\Components\Placeholder::make('placeholder')
                            ->label('Kebijakan Privasi')
                            ->content(
                                'Kami menghormati privasi setiap peserta lomba yang telah mendaftar. 
                                    Informasi pribadi yang Anda berikan, seperti nama, kontak, dan informasi terkait lomba, 
                                    hanya akan digunakan untuk keperluan administrasi dan komunikasi terkait lomba. 
                                    Kami tidak akan menyebarkan atau menjual informasi Anda kepada pihak ketiga tanpa izin Anda. 
                                    Data Anda akan disimpan dengan aman sesuai dengan kebijakan privasi kami. 
                                    Jika Anda memiliki pertanyaan lebih lanjut tentang penggunaan data Anda, jangan ragu untuk menghubungi kami. 
                                    Terima kasih atas partisipasi Anda dalam lomba kami.'
                            )
                            ->columnSpanFull(),
                        Forms\Components\Checkbox::make('terms')
                            ->label(fn () => new HtmlString('Saya menyetujui <a href="#" class="underline">syarat dan ketentuan</a> yang berlaku'))
                            ->required()
                            ->accepted()
                            ->validationMessages([
                                'accepted' => 'Kolom ini harus dicentang'
                            ])
                            ->dehydrated(false)
                            ->live()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn () => parent::getEloquentQuery()->whereHas('userDetail')->orWhereHas('roles', fn (Builder $query) => $query->where('name', 'participant')))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('userDetail.phone_number')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('userDetail.school')
                    ->label('Sekolah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('userDetail.grade')
                    ->label('Jenjang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('userDetail.type')
                    ->label('Jenis Lomba')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
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
            'index' => Pages\ListParticipants::route('/'),
            'create' => Pages\CreateParticipant::route('/create'),
            'edit' => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }
}
