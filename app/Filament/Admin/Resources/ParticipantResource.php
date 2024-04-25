<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ParticipantResource\Pages;
use App\Filament\Admin\Resources\ParticipantResource\RelationManagers;
use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
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
use Notification;
use stdClass;

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
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Nomor telepon')
                            ->helperText('Nomor telepon pribadi yang dapat dihubungi')
                            ->tel()
                            ->unique(ignoreRecord: true)
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
                                Forms\Components\Hidden::make('phone_number')
                                    ->formatStateUsing(fn ($get) => $get('../phone_number')),
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

                                        if ($grade === 'MI' || $grade === 'MTs' || $grade === 'MA') {
                                            $set('type', 'KSM');
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
                                                'KSM' => 'BSC Madrasah (KSM)',
                                            ];
                                        }

                                        return [];
                                    })
                                    ->selectablePlaceholder(false)
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
            ->query(function () {
                return parent::getEloquentQuery()
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'participant');
                    })
                    ->whereHas('userDetail');
            })
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('No.')
                    ->default(fn (stdClass $rowLoop) => $rowLoop->index + 1 . '.'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
