<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    //vista inicial de subir pdf
    public function index()
    {
        return view('pdf.leer');
    }

    public function ver()
    {
        $rutaCarpeta = public_path('uploads');

        // Obtener la lista de archivos en la carpeta uploads
        $archivos = File::allFiles($rutaCarpeta);

        $archivosInfo = [];

        // Recorrer la lista de archivos y obtener nombres y tamaños
        foreach ($archivos as $archivo) {
            $nombre = $archivo->getFilename();
            $tamaño = $archivo->getSize(); 

            $tamañoMB = round($tamaño / 1024 / 1024, 2); // Tamaño en MB

            $archivosInfo[] = [
                'nombre' => $nombre,
                'tamaño' => $tamañoMB . ' MB', 
            ];
        }
        return $archivosInfo;
        
    }

    //vista incial de ver pdfs
    public function ver_red()
    {
        
        $archivosInfo=$this->ver();
        // Pasar la información de los archivos a la vista
        return view('pdf.ver', ['archivosInfo' => $archivosInfo]);
    }


    //guardar pdf
    public function store(Request $request) {
        $request->validate([
            //validar pdf de 5mb
            'pdf' => 'required|file|mimes:pdf|max:5120', 
            'nombre_documento' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Verificar si ya existe un archivo con el mismo nombre
                    $nombre_archivo = "{$value}.pdf";
                    $ruta_archivo = public_path("uploads/{$nombre_archivo}");
                    
                    if (file_exists($ruta_archivo)) {
                        $fail("El nombre del documento ya está en uso.");
                    }
                },
            ],
        ]);
        
        //si se cargó un archivo
        if ($request->hasFile('pdf')) {
            $nombre_documento = $request->input('nombre_documento');
            $pdf = $request->file('pdf');
            $nombre_archivo = "{$nombre_documento}.pdf";

            //guardar en la carpeta uploads
            $ruta=public_path("uploads/".$nombre_archivo);
            copy($pdf,$ruta);
            $pdf->storeAs('uploads', $nombre_archivo, 'public');

            return view('pdf.leer')->with('exito', 'Archivo PDF subido exitosamente.');
        }
    }

    //eliminar pdf
    public function eliminar( $nombreArchivo) {
        $ruta = public_path('uploads/' . $nombreArchivo);
        //si el archivo se encuentra eliminarlo
        if (File::exists($ruta)) {
            File::delete($ruta);
           
        }
        $archivosInfo=$this->ver();
        return view('pdf.ver', ['archivosInfo' => $archivosInfo])->with('exito', 'Archivo eliminado con éxito');

    }
        
}
