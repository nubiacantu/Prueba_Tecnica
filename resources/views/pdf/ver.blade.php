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
            }, 100),
            window.location.href = '{{ route("pdf.ver") }}';
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
        })

    </script>
@endif
<div class="container ">
    <div class="card" style="margin-top: 4%;">
        <div class="card-body">
            <h1>Archivos PDF</h1>
          
            <table  id="myTable" class=" display custom-table">
                <thead>
                    <tr>
                        <th>Nombre del Archivo</th>
                        <th>Tamaño</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($archivosInfo as $archivo)
                    <tr>
                        <td>{{ $archivo['nombre'] }}</td>
                        <td>{{ $archivo['tamaño'] }}</td>
                        <td>
                            <a href="uploads/{{ $archivo['nombre'] }}" target="_blank" class="a-tabla ">Ver</a>
                            <a href="#" class="a-tabla eliminar-archivo" data-nombre="{{ $archivo['nombre'] }}">Eliminar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready( function () {
        $('#myTable').DataTable({
            "pageLength": 5, 
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        });
    });
    
    document.querySelectorAll('.eliminar-archivo').forEach(function(enlace) {
        enlace.addEventListener('click', function(event) {
            event.preventDefault();

            const nombreArchivo = this.getAttribute('data-nombre');

            // Mostrar un SweetAlert de confirmación
            Swal.fire({
                title: '<span class="alert-title">¿Estás seguro de eliminarlo?</span>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0b3171',
                cancelButtonColor: '#ababab',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si se confirma, redirige al controlador para eliminar el archivo
                    window.location.href = 'eliminar-pdf/' + nombreArchivo;
                }
            });
        });
    });

</script>
@endpush
