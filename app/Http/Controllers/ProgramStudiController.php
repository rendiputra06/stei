<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index()
    {
        return view('program-studi.index');
    }

    public function visiMisi()
    {
        return view('program-studi.visi-misi');
    }

    public function kurikulum()
    {
        return view('program-studi.kurikulum');
    }

    public function profilLulusan()
    {
        return view('program-studi.profil-lulusan');
    }

    public function prospekKarir()
    {
        return view('program-studi.prospek-karir');
    }

    public function dosen()
    {
        return view('program-studi.dosen');
    }

    public function fasilitas()
    {
        return view('program-studi.fasilitas');
    }
}
