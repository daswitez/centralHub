@extends('layouts.app')

@section('page_title', 'Detalle del Producto')

@section('page_header')
    <div>
        <h1 class="m-0">Producto #{{ $productoId }}</h1>
        <p class="text-muted mb-0">
            Detalle del producto
        </p>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">

            {{-- Loading --}}
            <div id="loading" class="text-center p-4">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">Cargando producto...</p>
            </div>

            {{-- Error --}}
            <div id="error" class="text-center text-danger p-4" style="display:none;">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <p id="errorMessage"></p>
            </div>

            {{-- Contenido --}}
            <div id="contenido" style="display:none;">
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Código:</strong> <span id="codigo"></span>
                    </li>
                    <li class="list-group-item">
                        <strong>Nombre:</strong> <span id="nombre"></span>
                    </li>
                    <li class="list-group-item">
                        <strong>Tipo:</strong> <span id="tipo"></span>
                    </li>
                    <li class="list-group-item">
                        <strong>Peso:</strong> <span id="peso"></span>
                    </li>
                    <li class="list-group-item">
                        <strong>Precio:</strong> Bs. <span id="precio"></span>
                    </li>
                    <li class="list-group-item">
                        <strong>Unidad:</strong> <span id="unidad"></span>
                    </li>
                    <li class="list-group-item">
                        <strong>Estado:</strong>
                        <span id="estado" class="badge"></span>
                    </li>
                    <li class="list-group-item">
                        <strong>Descripción:</strong>
                        <p id="descripcion" class="mb-0"></p>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <script>
        async function cargarProducto() {
            try {
                const response = await fetch(
                    `/api/trazabilidad/products/{{ $productoId }}`,
                    { headers: { 'Accept': 'application/json' } }
                );

                if (!response.ok) {
                    throw new Error('Error al cargar producto');
                }

                const json = await response.json();
                const p = json?.data ?? null;

                if (!p) throw new Error('Producto no encontrado');

                document.getElementById('codigo').innerText = p.codigo;
                document.getElementById('nombre').innerText = p.nombre;
                document.getElementById('tipo').innerText = p.tipo;
                document.getElementById('peso').innerText = `${p.peso} ${p.unit?.codigo ?? ''}`;
                document.getElementById('precio').innerText = p.precio_unitario;
                document.getElementById('unidad').innerText = p.unit?.nombre ?? '-';
                document.getElementById('descripcion').innerText = p.descripcion ?? '-';

                const estado = document.getElementById('estado');
                estado.innerText = p.activo ? 'Activo' : 'Inactivo';
                estado.className = `badge badge-${p.activo ? 'success' : 'secondary'}`;

                document.getElementById('loading').style.display = 'none';
                document.getElementById('contenido').style.display = 'block';

            } catch (e) {
                console.error(e);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error').style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo cargar el producto';
            }
        }

        document.addEventListener('DOMContentLoaded', cargarProducto);
    </script>
@endsection