<x-layouts.admin title="Edit Syarat Berkas" header="Edit Syarat Berkas">

    <div class="max-w-2xl">
        <a href="{{ route('admin.elections.requirements.index', $election) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-zinc-600 hover:text-zinc-950 mb-6 transition">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke Daftar Syarat
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">
            <div class="p-6 border-b border-zinc-100 bg-zinc-50">
                <h3 class="text-base font-bold text-zinc-900">Edit Syarat Berkas Pendaftaran</h3>
                <p class="text-xs text-zinc-500 mt-1">Pemilihan: <strong class="text-zinc-800">{{ $election->name }}</strong></p>
            </div>

            <form method="POST" action="{{ route('admin.elections.requirements.update', [$election, $requirement]) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Syarat --}}
                <div class="space-y-1">
                    <label for="name" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider">
                        Nama Syarat / Dokumen <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $requirement->name) }}"
                        required
                        class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 shadow-sm"
                    />
                </div>

                {{-- Deskripsi / Panduan --}}
                <div class="space-y-1">
                    <label for="description" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider">
                        Petunjuk / Deskripsi Pengunggahan
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="2"
                        class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 shadow-sm"
                    >{{ old('description', $requirement->description) }}</textarea>
                </div>

                {{-- Tipe & Urutan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label for="type" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider">
                            Tipe Input <span class="text-rose-500">*</span>
                        </label>
                        <select id="type" name="type" required class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 shadow-sm">
                            <option value="file" {{ old('type', $requirement->type) === 'file' ? 'selected' : '' }}>Dokumen / File (PDF, DOCX, dll)</option>
                            <option value="image" {{ old('type', $requirement->type) === 'image' ? 'selected' : '' }}>Foto / Gambar (JPG, PNG)</option>
                            <option value="text" {{ old('type', $requirement->type) === 'text' ? 'selected' : '' }}>Isian Teks Singkat / URL</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label for="sort_order" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider">
                            Nomor Urut Tampil
                        </label>
                        <input
                            type="number"
                            id="sort_order"
                            name="sort_order"
                            value="{{ old('sort_order', $requirement->sort_order) }}"
                            min="1"
                            class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 shadow-sm"
                        />
                    </div>
                </div>

                {{-- Pengaturan File (Ekstensi & Ukuran) --}}
                <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-200 space-y-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider">
                            Format Ekstensi File yang Diizinkan
                        </label>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 text-xs">
                            @foreach (['pdf' => 'PDF (.pdf)', 'jpg' => 'JPG (.jpg)', 'jpeg' => 'JPEG (.jpeg)', 'png' => 'PNG (.png)', 'doc' => 'Word (.doc)', 'docx' => 'Word (.docx)', 'zip' => 'ZIP (.zip)'] as $ext => $extLabel)
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-zinc-200 bg-white cursor-pointer hover:border-amber-500 transition">
                                    <input
                                        type="checkbox"
                                        name="allowed_extensions[]"
                                        value="{{ $ext }}"
                                        {{ in_array($ext, old('allowed_extensions', $requirement->allowed_extensions ?? [])) ? 'checked' : '' }}
                                        class="rounded text-amber-600 focus:ring-amber-500"
                                    />
                                    <span class="text-zinc-700 font-mono">{{ $extLabel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label for="max_file_size" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider">
                            Batas Maksimal Ukuran File (Kilobyte / KB)
                        </label>
                        <div class="flex items-center gap-3">
                            <input
                                type="number"
                                id="max_file_size"
                                name="max_file_size"
                                value="{{ old('max_file_size', $requirement->max_file_size ?? 5120) }}"
                                min="100"
                                max="51200"
                                step="512"
                                class="w-48 px-4 py-2 text-sm bg-white border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 shadow-sm"
                            />
                            <span class="text-xs text-zinc-500">Maksimum 50 MB (51200 KB)</span>
                        </div>
                    </div>
                </div>

                {{-- Checkbox Status Wajib & Aktif --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input
                            type="checkbox"
                            name="required"
                            value="1"
                            {{ old('required', $requirement->required) ? 'checked' : '' }}
                            class="rounded text-amber-600 focus:ring-amber-500 w-4 h-4"
                        />
                        <span class="text-xs sm:text-sm font-bold text-zinc-800">Syarat Wajib Diunggah (Mandatory)</span>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input
                            type="checkbox"
                            name="active"
                            value="1"
                            {{ old('active', $requirement->active) ? 'checked' : '' }}
                            class="rounded text-amber-600 focus:ring-amber-500 w-4 h-4"
                        />
                        <span class="text-xs sm:text-sm font-bold text-zinc-800">Status Aktif</span>
                    </label>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-zinc-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.elections.requirements.index', $election) }}" class="px-4 py-2 text-xs font-bold text-zinc-600 hover:text-zinc-800">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-amber-500 text-zinc-950 font-bold text-xs rounded-lg hover:bg-amber-400 shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
