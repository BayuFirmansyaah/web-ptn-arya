<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    public function index()
    {
        $students = \App\Models\Student::all();
        $isSuper = session('is_super_admin', false);

        return view('dashboard.index', compact('students', 'isSuper'));
    }

    public function create()
    {
        return view('dashboard.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'program' => 'nullable|string',
            'gender' => 'nullable|string',
            'kelas' => 'nullable|string',
            'ma' => 'nullable|string',
            'jurusan' => 'nullable|string|max:255',
        ]);

        // Assuming you have a Student model
        $student = new \App\Models\Student();
        $student->nama = $request->nama;
        $student->program = $request->program;
        $student->gender = $request->gender;
        $student->kelas = $request->kelas;
        $student->ma = $request->ma;
        $student->jurusan = $request->jurusan ?? '-';
        $student->save();

        return redirect()->back()->with('success', 'Peserta berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $student = \App\Models\Student::find($id);
        $sertifikat = \App\Models\Media::where('student_id', $id)
            ->where('file_type', 'sertifikat')
            ->get();

        $berkas = \App\Models\Media::where('student_id', $id)
            ->where('file_type', 'berkas')
            ->get();

        return view('dashboard.show', [
            'student' => $student,
            'sertifikat' => $sertifikat,
            'berkas' => $berkas 
        ]);
    }

    public function updateStudent(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:students,id',
                'nama' => 'required|string|max:255',
                'program' => 'nullable|string',
                'gender' => 'nullable|string',
                'kelas' => 'nullable|string',
                'ma' => 'nullable|string',
                'jurusan' => 'nullable|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $student = \App\Models\Student::find($request->id);
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta tidak ditemukan'
                ], 404);
            }

            $student->nama = $request->nama;
            $student->program = $request->program;
            $student->gender = $request->gender;
            $student->kelas = $request->kelas;
            $student->ma = $request->ma;
            $student->jurusan = $request->jurusan ?? '-';

            // Handle photo upload
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/photos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Delete old photo if exists
                if ($student->foto && file_exists(public_path('uploads/photos/' . $student->foto))) {
                    unlink(public_path('uploads/photos/' . $student->foto));
                }
                
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($uploadPath, $filename);
                $student->profile = $filename;
            }

            $student->save();

            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil diperbarui'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyStudent(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:students,id',
            ]);

            $student = \App\Models\Student::find($request->id);
            if ($student) {
                // Delete associated media files
                $mediaFiles = \App\Models\Media::where('student_id', $student->id)->get();
                foreach ($mediaFiles as $media) {
                    if (file_exists(public_path($media->file_path))) {
                        unlink(public_path($media->file_path));
                    }
                    $media->delete();
                }

                // Delete profile photo if exists
                if ($student->profile && file_exists(public_path('uploads/photos/' . $student->profile))) {
                    unlink(public_path('uploads/photos/' . $student->profile));
                }

                $student->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Peserta berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta tidak ditemukan'
                ], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadMedia(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|integer|exists:students,id',
                'type' => 'required|string|in:sertifikat,berkas',
                'file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // max 5MB
            ]);

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/media'), $filename);

            $media = new \App\Models\Media();
            $media->student_id = $request->student_id;
            $media->file_type = $request->type;
            $media->file_path = 'uploads/media/' . $filename;
            $media->save();

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diunggah',
                'media' => $media
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMedia(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:media,id',
                'type' => 'nullable|string',
            ]);

            $media = \App\Models\Media::find($request->id);
            if ($media) {
                // Delete the file from storage
                if (file_exists(public_path($media->file_path))) {
                    unlink(public_path($media->file_path));
                }
                $media->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'File berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
