<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Bagian;
use RealRashid\SweetAlert\Facades\Alert;

class PesertaController extends Controller
{
     public function index()
     {
        $peserta = Peserta::with('user')->get();
        return view('peserta.index', compact('peserta'));
     }
        public function create()
        {
            $bagians = Bagian::all();
            return view('peserta.create', compact('bagians'));
        }

        public function store(Request $request)
        {
            // dd($request->all());
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'nomor_identitas' => 'required|string|min:6|max:15|unique:pesertas', // NIK/KTP, harus unik
                'email' => 'required|string|email|max:255|unique:pesertas,email', // Email, harus unik dan format email
                'no_telepon' => 'required|string|max:20', // Nomor telepon
                'alamat' => 'required|string|max:500',
                'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])], // Harus salah satu dari pilihan
                'asal_instansi' => 'required|string|max:255',
                'jurusan' => 'required|string|max:255',
                'tipe_magang' => ['required', Rule::in(['Kerja Praktik', 'Magang Nasional', 'Penelitian'])], // Harus salah satu dari pilihan
                'tanggal_mulai_magang' => 'required|date', // Harus format tanggal
                'tanggal_selesai_magang' => 'required|date|after_or_equal:tanggal_mulai_magang', // Harus tanggal dan setelah/sama dengan tanggal mulai
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Foto opsional, harus gambar dengan ukuran maksimal 2MB
                'bagian_id' => 'required|exists:bagians,id',
            ],[
                // Pesan error kustom untuk setiap aturan validasi
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
                'nama_lengkap.max' => 'Nama lengkap tidak boleh lebih dari :max karakter.',

                'nomor_identitas.required' => 'Nomor identitas wajib diisi.',
                'nomor_identitas.string' => 'Nomor identitas harus berupa teks.',
                'nomor_identitas.min' => 'Nomor identitas minimal :min karakter.',
                'nomor_identitas.max' => 'Nomor identitas maksimal :max karakter.',
                'nomor_identitas.unique' => 'Nomor identitas ini sudah terdaftar.',

                'email.required' => 'Email wajib diisi.',
                'email.string' => 'Email harus berupa teks.',
                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email tidak boleh lebih dari :max karakter.',
                'email.unique' => 'Email ini sudah terdaftar.',

                'no_telepon.required' => 'Nomor telepon wajib diisi.',
                'no_telepon.string' => 'Nomor telepon harus berupa teks.',
                'no_telepon.max' => 'Nomor telepon tidak boleh lebih dari :max karakter.',

                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'alamat.max' => 'Alamat tidak boleh lebih dari :max karakter.',

                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'jenis_kelamin.in' => 'Jenis kelamin yang dipilih tidak valid.',

                'asal_instansi.required' => 'Asal instansi wajib diisi.',
                'asal_instansi.string' => 'Asal instansi harus berupa teks.',
                'asal_instansi.max' => 'Asal instansi tidak boleh lebih dari :max karakter.',

                'jurusan.required' => 'Jurusan wajib diisi.',
                'jurusan.string' => 'Jurusan harus berupa teks.',
                'jurusan.max' => 'Jurusan tidak boleh lebih dari :max karakter.',

                'tipe_magang.required' => 'Tipe magang wajib dipilih.',
                'tipe_magang.in' => 'Tipe magang yang dipilih tidak valid.',

                'tanggal_mulai_magang.required' => 'Tanggal mulai magang wajib diisi.',
                'tanggal_mulai_magang.date' => 'Format tanggal mulai magang tidak valid.',

                'tanggal_selesai_magang.required' => 'Tanggal selesai magang wajib diisi.',
                'tanggal_selesai_magang.date' => 'Format tanggal selesai magang tidak valid.',
                'tanggal_selesai_magang.after_or_equal' => 'Tanggal selesai magang harus setelah atau sama dengan tanggal mulai magang.',

                'bagian_id.required' => 'Bagian wajib dipilih.',
                'bagian_id.exists' => 'Bagian yang dipilih tidak valid.',
            ]);

            $data = $request->except('foto');
            
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = Str::uuid() . '.' . $foto->getClientOriginalExtension();
                
                Storage::disk('public')->putFileAs('foto_peserta', $foto, $filename);
                // Store the file in the public disk under the foto_peserta directory
                // $path = $foto->storeAs('foto_peserta', $filename, 'public');
                
                // Add the file path to the data array
                $data['foto'] = $filename;
            }
            else {
                $filename = $peserta->foto;
            }
            
            // Create the record with all data including the photo path
            $newData = Peserta::create($data);
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'peserta_id' => $newData->id,
            ]);
            $newData->user_id = $user->id;
            $newData->save();

            Alert::success('Success', 'Peserta berhasil ditambahkan.');
            return redirect()->route('peserta.index');
        }

        public function edit(string $id)
        {
            $peserta = Peserta::findOrFail($id);
            $bagians = Bagian::all();
            // dd($peserta);
            return view('peserta.edit', compact('peserta', 'bagians'));
        }

        public function update(Request $request, $id)
        {
            // dd($request->all());
            $peserta = Peserta::findOrFail($id);
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'nomor_identitas' => [
                    'required',
                    'string',
                    'min:6',
                    'max:15',
                    Rule::unique('pesertas')->ignore($peserta->id),
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('pesertas')->ignore($peserta->id),
                ],
                'no_telepon' => 'required|string|max:20',
                'alamat' => 'required|string|max:500',
                'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
                'asal_instansi' => 'required|string|max:255',
                'jurusan' => 'required|string|max:255',
                'tipe_magang' => ['required', Rule::in(['Kerja Praktik', 'Magang Nasional', 'Penelitian'])],
                'tanggal_mulai_magang' => 'required|date',
                'tanggal_selesai_magang' => 'required|date|after_or_equal:tanggal_mulai_magang',
                'foto' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],[
                // Pesan error kustom untuk setiap aturan validasi
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
                'nama_lengkap.max' => 'Nama lengkap tidak boleh lebih dari :max karakter.',

                'nomor_identitas.required' => 'Nomor identitas wajib diisi.',
                'nomor_identitas.string' => 'Nomor identitas harus berupa teks.',
                'nomor_identitas.min' => 'Nomor identitas minimal :min karakter.',
                'nomor_identitas.max' => 'Nomor identitas maksimal :max karakter.',
                'nomor_identitas.unique' => 'Nomor identitas ini sudah terdaftar.',

                'email.required' => 'Email wajib diisi.',
                'email.string' => 'Email harus berupa teks.',
                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email tidak boleh lebih dari :max karakter.',
                'email.unique' => 'Email ini sudah terdaftar.',

                'no_telepon.required' => 'Nomor telepon wajib diisi.',
                'no_telepon.string' => 'Nomor telepon harus berupa teks.',
                'no_telepon.max' => 'Nomor telepon tidak boleh lebih dari :max karakter.',

                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'alamat.max' => 'Alamat tidak boleh lebih dari :max karakter.',

                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'jenis_kelamin.in' => 'Jenis kelamin yang dipilih tidak valid.',

                'asal_instansi.required' => 'Asal instansi wajib diisi.',
                'asal_instansi.string' => 'Asal instansi harus berupa teks.',
                'asal_instansi.max' => 'Asal instansi tidak boleh lebih dari :max karakter.',

                'jurusan.required' => 'Jurusan wajib diisi.',
                'jurusan.string' => 'Jurusan harus berupa teks.',
                'jurusan.max' => 'Jurusan tidak boleh lebih dari :max karakter.',

                'tipe_magang.required' => 'Tipe magang wajib dipilih.',
                'tipe_magang.in' => 'Tipe magang yang dipilih tidak valid.',

                'tanggal_mulai_magang.required' => 'Tanggal mulai magang wajib diisi.',
                'tanggal_mulai_magang.date' => 'Format tanggal mulai magang tidak valid.',

                'tanggal_selesai_magang.required' => 'Tanggal selesai magang wajib diisi.',
                'tanggal_selesai_magang.date' => 'Format tanggal selesai magang tidak valid.',
                'tanggal_selesai_magang.after_or_equal' => 'Tanggal selesai magang harus setelah atau sama dengan tanggal mulai magang.',
            ]);

            $dataToUpdate = $request->except('foto'); // Ambil semua data kecuali 'foto'

            // Cek apakah ada file foto baru di request
            if ($request->hasFile('foto')) {
            // Jika ada, hapus foto lama dari storage (jika ada)
                if ($peserta->foto && Storage::disk('public')->exists($peserta->foto)) {
                Storage::disk('public')->delete($peserta->foto);
            }

                $file = $request->file('foto');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension(); // Generate nama file unik

            // LANGSUNG SIMPAN DAN DAPATKAN PATH LENGKAP
            // Metode storeAs() akan menyimpan file ke 'storage/app/public/foto_peserta/'
            // dan mengembalikan string 'foto_peserta/nama_file_unik.ext'
                $path = $file->storeAs('foto_peserta', $filename, 'public');

                $dataToUpdate['foto'] = $path;
            }

            $peserta->update($dataToUpdate);

            Alert::success('Success', 'Data peserta berhasil diperbarui.');
            // 5. Redirect dengan pesan sukses
            return redirect()->route('peserta.index');
            // $newRequest = $request->except('nomor_identitas');
            
            // // Handle file upload if a new photo is provided
            // if ($request->hasFile('foto')) {
            //     $file = $request->file('foto');
            //     $filename = time() . '_' . $file->getClientOriginalName();
            //     $file->move(public_path('uploads'), $filename);
            //     $newRequest['foto'] = $filename;
            // }
            
            // $peserta->update($request->except('nomor_identitas')); // Tidak mengupdate nomor identitas untuk keamanan
            // return redirect()->route('peserta.index')->with('success', 'Peserta berhasil diperbarui.');

            // $filename = $peserta->foto;
            // $foto = $request->file('foto');

            // if ($foto) {
            //   $filename = Str::uuid() . '.' . $foto->getClientOriginalExtension();
            //   Storage::disk('public')->putFileAs('foto_peserta', $foto, $filename);  
            // }
            // else {
            //     $filename = $peserta->foto;
            // }

            // $newRequest = $request->except('nomor_identitas');
            // $newRequest['foto'] = $filename;
            // $peserta->update($newRequest);
            // return redirect()->route('peserta.index')->with('success', 'Peserta berhasil diperbarui.');
        }

        public function destroy(String $id)
        {
            $peserta = Peserta::with('user')->findOrFail($id);

            // Hapus foto jika ada
            if ($peserta->foto != null) {
                Storage::disk('public')->delete('foto_peserta/' . $peserta->foto);
            }

            // Hapus user terkait jika ada
            if ($peserta->user) {
                $peserta->user->delete();
            }

            // Hapus peserta
            $peserta->delete();
            
            Alert::success('Success', 'Data peserta dan akun user terkait berhasil dihapus.');
            return redirect()->route('peserta.index');
        }
}
