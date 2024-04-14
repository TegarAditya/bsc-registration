<?php

namespace App\Filament\Participant\Pages\Auth;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Wizard;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\FormsComponent;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Register extends BaseRegister
{
    public function getHeading(): string | Htmlable
    {
        return 'Buat Akun BSC';
    }

    protected ?string $maxWidth = '3xl';

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Wizard::make([
                            Wizard\Step::make('Buat Akun')
                                ->icon('heroicon-o-user')
                                ->schema([
                                    $this->getNameFormComponent(),
                                    $this->getEmailFormComponent(),
                                    $this->getPasswordFormComponent(),
                                    $this->getPasswordConfirmationFormComponent(),
                                ]),
                            Wizard\Step::make('Data Diri')
                                ->icon('heroicon-o-user-circle')
                                ->schema([
                                    Fieldset::make('userDetail')
                                        ->hiddenLabel()
                                        ->model(User::class)
                                        ->relationship('userDetail')
                                        ->schema([
                                            Forms\Components\TextInput::make('phone_number')
                                                ->label('Nomor telepon')
                                                ->helperText('Nomor telepon pribadi yang dapat dihubungi')
                                                ->tel()
                                                ->unique()
                                                ->columnSpanFull(),
                                            Forms\Components\TextInput::make('companion_phone_number')
                                                ->label('Nomor telepon pendamping')
                                                ->helperText('Nomor telepon pendamping atau guru yang dapat dihubungi')
                                                ->tel()
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
                                                ->relationship('province', 'name')
                                                ->options([
                                                    '1' => 'Aceh',
                                                ])
                                                ->searchable()
                                                ->live(),
                                            Forms\Components\Select::make('regency_id')
                                                ->label('Kabupaten/Kota')
                                                ->options([
                                                    '1' => 'Aceh Barat',
                                                ])
                                                ->disabled(fn ($get) => $get('province_id') === null),
                                            Forms\Components\Textarea::make('address')
                                                ->label('Alamat (opsional)')
                                                ->columnSpanFull(),
                                        ]),
                                ]),
                            Wizard\Step::make('Data Lomba')
                                ->icon('heroicon-o-academic-cap')
                                ->schema([
                                    Fieldset::make('Data Lomba')
                                        ->hiddenLabel()
                                        ->model(User::class)
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
                            Wizard\Step::make('Syarat dan Ketentuan')
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

                        ])
                            ->submitAction($this->getCustomRegisterFormAction()),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getEmailFormComponent(): Component
    {
        return Forms\Components\TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->email()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getUsernameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('username')
            ->label('Username')
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'))
            ->submit('register')
            ->hidden();
    }

    public function getCustomRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'))
            ->submit('register')
            ->disabled();
    }
}
