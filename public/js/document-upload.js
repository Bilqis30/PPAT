/**
 * WebArsipp - Admin Document Upload Script
 * Handles dynamic sub-category toggle, field hiding for 'Berkas', drag & drop, and PDF validation preview.
 */

document.addEventListener('DOMContentLoaded', () => {
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        // Trigger initial state (e.g. if validation failed and old input preserved)
        handleTypeChange(typeSelect.value);
    }

    // Setup Drag and Drop onto upload box
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');

    if (dropZone && fileInput) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('border-slate-800', 'bg-slate-100');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-slate-800', 'bg-slate-100');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                handleFileSelected(fileInput);
            }
        }, false);
    }
});

/**
 * Toggle dynamic fields based on selected Tipe Dokumen:
 * - If 'Berkas': Hide Nomor Surat (KTP, KK, berkas pendukung don't have letter numbers)
 * - If 'Laporan': Show Sub-Kategori Laporan (Laporan BPN / Laporan Dispenda)
 */
function handleTypeChange(selectedType) {
    const subTypeContainer = document.getElementById('sub-type-container');
    const subTypeSelect = document.getElementById('sub_type');
    const tanggalAcaraContainer = document.getElementById('tanggal-acara-container');
    const tanggalAcaraInput = document.getElementById('tanggal_acara');
    const docNumberContainer = document.getElementById('document-number-container');
    const docNumberInput = document.getElementById('document_number');
    const docMetaGrid = document.getElementById('document-meta-grid');
    const titleLabel = document.getElementById('title-label');
    const titleInput = document.getElementById('title');
    const berkasNotice = document.getElementById('berkas-notice');

    // 1. Kondisi Khusus Tipe: Berkas (KTP, KK, warkah, dsb.)
    if (selectedType === 'Berkas') {
        if (docNumberContainer) {
            docNumberContainer.classList.add('hidden');
        }
        if (docNumberInput) {
            docNumberInput.required = false;
            docNumberInput.value = '';
        }
        if (docMetaGrid) {
            docMetaGrid.classList.remove('md:grid-cols-2');
            docMetaGrid.classList.add('grid-cols-1');
        }
        if (titleLabel) {
            titleLabel.innerHTML = 'Nama Berkas / Perihal Dokumen <span class="text-rose-500">*</span>';
        }
        if (titleInput) {
            titleInput.placeholder = 'Contoh: Berkas KTP & Kartu Keluarga Pemohon, Salinan SHM No. 124';
        }
        if (berkasNotice) {
            berkasNotice.classList.remove('hidden');
        }
    } else {
        // Tipe selain Berkas (Surat Masuk, Surat Keluar, Laporan) -> Nomor Dokumen wajib tampil
        if (docNumberContainer) {
            docNumberContainer.classList.remove('hidden');
        }
        if (docNumberInput) {
            docNumberInput.required = true;
        }
        if (docMetaGrid) {
            docMetaGrid.classList.remove('grid-cols-1');
            docMetaGrid.classList.add('md:grid-cols-2');
        }
        if (titleLabel) {
            titleLabel.innerHTML = 'Perihal / Judul Dokumen <span class="text-rose-500">*</span>';
        }
        if (titleInput) {
            titleInput.placeholder = 'Contoh: Permohonan Validasi Hak Milik Tanah Wilayah B';
        }
        if (berkasNotice) {
            berkasNotice.classList.add('hidden');
        }
    }

    // 2. Kondisi Khusus Tipe: Laporan (BPN / Dispenda)
    if (subTypeContainer && subTypeSelect) {
        if (selectedType === 'Laporan') {
            subTypeContainer.classList.remove('hidden');
            subTypeSelect.required = true;
        } else {
            subTypeContainer.classList.add('hidden');
            subTypeSelect.required = false;
            subTypeSelect.value = '';
        }
    }

    // 3. Kondisi Khusus Tipe: Surat Masuk (Tanggal Acara H-2)
    if (tanggalAcaraContainer) {
        if (selectedType === 'Surat Masuk') {
            tanggalAcaraContainer.classList.remove('hidden');
        } else {
            tanggalAcaraContainer.classList.add('hidden');
            if (tanggalAcaraInput) {
                tanggalAcaraInput.value = '';
            }
        }
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

/**
 * Handle File Selection & Validation Preview
 */
function handleFileSelected(input) {
    if (!input || !input.files || input.files.length === 0) return;

    const file = input.files[0];
    const placeholder = document.getElementById('upload-placeholder');
    const selectedInfo = document.getElementById('upload-selected-info');
    const fileNameEl = document.getElementById('selected-file-name');
    const fileSizeEl = document.getElementById('selected-file-size');

    // Strict PDF Extension & Mime Check
    if (!file.name.toLowerCase().endsWith('.pdf') && file.type !== 'application/pdf') {
        alert('Format Berkas Ditolak: Hanya berkas dokumen PDF (.pdf) yang diperbolehkan!');
        input.value = '';
        if (placeholder) placeholder.classList.remove('hidden');
        if (selectedInfo) {
            selectedInfo.classList.add('hidden');
            selectedInfo.classList.remove('flex');
        }
        return;
    }

    // Size limit check (20MB)
    const maxSize = 20 * 1024 * 1024;
    if (file.size > maxSize) {
        alert('Ukuran Berkas Terlalu Besar: Maksimal ukuran dokumen adalah 20 MB!');
        input.value = '';
        if (placeholder) placeholder.classList.remove('hidden');
        if (selectedInfo) {
            selectedInfo.classList.add('hidden');
            selectedInfo.classList.remove('flex');
        }
        return;
    }

    // Update UI elements
    if (placeholder) placeholder.classList.add('hidden');
    if (selectedInfo) {
        selectedInfo.classList.remove('hidden');
        selectedInfo.classList.add('flex');
    }
    
    if (fileNameEl) fileNameEl.textContent = file.name;
    
    if (fileSizeEl) {
        let sizeStr = (file.size / 1024).toFixed(1) + ' KB';
        if (file.size > 1024 * 1024) {
            sizeStr = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        }
        fileSizeEl.textContent = sizeStr;
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

/**
 * Clear selected file
 */
function clearSelectedFile(event) {
    if (event) event.stopPropagation();

    const fileInput = document.getElementById('file-input');
    const placeholder = document.getElementById('upload-placeholder');
    const selectedInfo = document.getElementById('upload-selected-info');

    if (fileInput) fileInput.value = '';
    if (placeholder) placeholder.classList.remove('hidden');
    if (selectedInfo) {
        selectedInfo.classList.add('hidden');
        selectedInfo.classList.remove('flex');
    }
}
