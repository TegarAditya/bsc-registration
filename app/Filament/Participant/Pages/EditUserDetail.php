<?php

namespace App\Filament\Participant\Pages;

use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;

class EditUserDetail extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Detail Peserta';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.participant.pages.edit-user-detail';

    public ?array $data = [];

    public function mount(): void 
    {
        $this->form->fill(auth()->user()->attributesToArray());
    }
 
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Buat Akun')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->autocomplete(false)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->autocomplete(false)
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->autocomplete(false)
                            ->revealable(filament()->arePasswordsRevealable())
                            ->rule(Password::default())
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Data Diri')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Forms\Components\Fieldset::make('userDetail')
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
                        Forms\Components\Fieldset::make('Data Lomba')
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
            ])
            ->statePath('data')
            ->model(auth()->user());
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
 
            auth()->user()->userDetail->update($data);
        } catch (Halt $exception) {
            return;
        }
 
        Notification::make() 
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send(); 
    }
}
