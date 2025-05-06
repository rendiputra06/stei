<?php

namespace App\Livewire;

use App\Models\TahunAkademik;
use Livewire\Component;

class StatusMahasiswaPage extends Component
{
    public function render()
    {
        $tahunAkademikAktif = TahunAkademik::getAktif();

        return view('livewire.status-mahasiswa-page', [
            'tahunAkademikAktif' => $tahunAkademikAktif,
        ]);
    }
}
