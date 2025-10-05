<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function search(Request $request)
    {
        $students = \App\Models\Student::all();

        return view('search', [
            'students' => $students
        ]);
    }

    public function show($id)
    {
        $student = \App\Models\Student::find($id);

        $sertifikat = \App\Models\Media::where('student_id', $id)
            ->where('file_type', 'sertifikat')
            ->get();

        $berkas = \App\Models\Media::where('student_id', $id)
            ->where('file_type', 'berkas')
            ->get();

        return view('student_detail', [
            'student' => $student,
            'sertifikat' => $sertifikat,
            'berkas' => $berkas
        ]);
    }
}
