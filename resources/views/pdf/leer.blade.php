@extends('layouts.app')

@section('content')
@if (isset($exito))
    <script>
        let timerInterval
        Swal.fire({
        icon: 'success',
        title: '<span class="alert-title">{{$exito}}</span>',
        timer: 3000,
        showConfirmButton: true, 
        showCloseButton: false, 
        showCancelButton: false, 
        timerProgressBar: true,
        confirmButtonColor: '#0b3171',
        didOpen: () => {
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
            b.textContent = Swal.getTimerLeft()
            }, 100)
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
        })

    </script>
@endif
<div class="container">
    <div class="card" style="margin-top: 4%;">
        <div class="card-body">
            <h1>Subir Archivo PDF</h1>
            <form method="post" action="{{route('pdf.store')}}" enctype="multipart/form-data" id="pdf-upload-form" novalidate>
                @csrf
                <div class="file-upload">
                    <label for="pdf" class="custom-file-label">
                        <div class="file-label-content">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                            </div>
                            <span>Arrastra y suelta un archivo PDF aquí o haz clic para seleccionar uno</span>
                        </div>
                    </label>
                    <input style="display: none;" type="file" name="pdf" id="pdf" accept=".pdf" class="custom-file-input @error('pdf') is-invalid @enderror" required>
                    
                </div>
                @error('pdf')
                    <div class=" error-message">{{ $message }}</div>
                @enderror
                <div id="pdf-preview" style="display: none">
                    <span id="pdf-name"></span>
                </div>
                <div class="form-group">
                    <label for="nombre_documento">Nombre del documento:</label>
                    <input type="text" value="{{ old('nombre_documento') }}" name="nombre_documento" id="nombre_documento" class="form-control @error('nombre_documento') is-invalid @enderror" required>
                    
                </div>
                @error('nombre_documento')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="div-button">
                    <button type="submit" class="btn btn-primary custom-button">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
    const pdfUploadForm = document.getElementById('pdf-upload-form');
    const pdfInput = document.getElementById('pdf');
    const pdfLabel = document.querySelector('.custom-file-label');

    pdfUploadForm.addEventListener('dragover', (e) => {
        e.preventDefault();
        pdfUploadForm.classList.add('dragover');
    });

    pdfUploadForm.addEventListener('dragleave', () => {
        pdfUploadForm.classList.remove('dragover');
    });

    pdfUploadForm.addEventListener('drop', (e) => {
        e.preventDefault();
        pdfUploadForm.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            // Tomar solo el primer archivo y actualizar la etiqueta de archivo
            const firstFile = files[0];
            updateFileLabel(firstFile.name);

            // Llena el campo de entrada de tipo archivo con el archivo soltado
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(firstFile);
            pdfInput.files = dataTransfer.files;
        }
    });


    // Evento para cuando se selecciona un archivo haciendo clic
    pdfInput.addEventListener('change', (e) => {
        const files = pdfInput.files;
        if (files.length > 0) {
            updateFileLabel(files[0].name);
        }
    });

    function updateFileLabel(fileName) {
        pdfLabel.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" style="height:35px;width:35px;" height="2em" viewBox="0 0 512 512"><path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V304H176c-35.3 0-64 28.7-64 64V512H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zM176 352h32c30.9 0 56 25.1 56 56s-25.1 56-56 56H192v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V448 368c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24H192v48h16zm96-80h32c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H304c-8.8 0-16-7.2-16-16V368c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H320v96h16zm80-112c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16s-7.2 16-16 16H448v32h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H448v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V432 368z"/></svg> ${fileName}`;

        // Cambiar el color del texto a negro
        pdfLabel.style.color = "black";

        // Agregar un ícono FontAwesome de PDF al nombre del archivo
        const pdfName = document.getElementById('pdf-name');

        document.getElementById('pdf-preview').style.display = 'block';
    }
</script>
@endpush

