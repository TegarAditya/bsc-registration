<?php

namespace App\Filament\Participant\Pages\Auth;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Wizard;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\FormsComponent;
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
                                    Fieldset::make('Data Diri')
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
                            Wizard\Step::make('Detail Lomba')
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
            ->submit('register');
    }
}
