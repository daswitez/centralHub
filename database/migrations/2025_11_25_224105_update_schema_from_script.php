<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = <<<'SQL'
create schema if not exists cat;
create schema if not exists campo;
create schema if not exists planta;
create schema if not exists logistica;
create schema if not exists comercial;
create schema if not exists certificacion;
create schema if not exists almacen;

drop view if exists planta.v_trazabilidad_lote_salida cascade;
drop view if exists certificacion.v_certificados_por_lote_salida cascade;
drop view if exists almacen.v_stock cascade;

drop function if exists planta.sp_registrar_lote_planta(text,int,timestamptz,jsonb) cascade;
drop function if exists planta.sp_registrar_lote_salida_y_envio(text,int,text,numeric,timestamptz,boolean,text,int,int,timestamptz) cascade;
drop function if exists almacen.sp_despachar_a_almacen(text,int,int,timestamptz,jsonb) cascade;
drop function if exists almacen.sp_recepcionar_envio(text,int,text) cascade;
drop function if exists almacen.sp_despachar_a_cliente(text,int,int,int,timestamptz,jsonb) cascade;

drop table if exists almacen.movimiento cascade;
drop table if exists almacen.inventario cascade;
drop table if exists almacen.recepcion cascade;
drop table if exists almacen.pedidodetalle cascade;
drop table if exists almacen.pedido cascade;

drop table if exists certificacion.certificadocadena cascade;
drop table if exists certificacion.certificadoevidencia cascade;
drop table if exists certificacion.certificadoenvio cascade;
drop table if exists certificacion.certificadolotesalida cascade;
drop table if exists certificacion.certificadoloteplanta cascade;
drop table if exists certificacion.certificadolotecampo cascade;
drop table if exists certificacion.certificado cascade;

drop table if exists comercial.pedidodetalle cascade;
drop table if exists comercial.pedido cascade;

drop table if exists logistica.enviodetallealmacen cascade;
drop table if exists logistica.enviodetalle cascade;
drop table if exists logistica.envio cascade;
drop table if exists logistica.rutapunto cascade;
drop table if exists logistica.ruta cascade;

drop table if exists planta.lotesalida cascade;
drop table if exists planta.controlproceso cascade;
drop table if exists planta.loteplanta_entradacampo cascade;
drop table if exists planta.loteplanta cascade;

drop table if exists campo.sensorlectura cascade;
drop table if exists campo.lotecampo cascade;
drop table if exists campo.productor cascade;

drop table if exists cat.almacen cascade;
drop table if exists cat.transportista cascade;
drop table if exists cat.cliente cascade;
drop table if exists cat.planta cascade;
drop table if exists cat.variedadpapa cascade;
drop table if exists cat.municipio cascade;
drop table if exists cat.departamento cascade;

create table cat.departamento (
  departamento_id   int generated always as identity primary key,
  nombre            varchar(80) not null unique
);

create table cat.municipio (
  municipio_id      int generated always as identity primary key,
  departamento_id   int not null references cat.departamento(departamento_id),
  nombre            varchar(120) not null,
  constraint uq_municipio unique (departamento_id, nombre)
);

create table cat.variedadpapa (
  variedad_id       int generated always as identity primary key,
  codigo_variedad   varchar(40) not null unique,
  nombre_comercial  varchar(120) not null,
  aptitud           varchar(80),
  ciclo_dias_min    int,
  ciclo_dias_max    int
);

create table cat.planta (
  planta_id         int generated always as identity primary key,
  codigo_planta     varchar(40) not null unique,
  nombre            varchar(140) not null,
  municipio_id      int not null references cat.municipio(municipio_id),
  direccion         varchar(200),
  lat               numeric(9,6),
  lon               numeric(9,6)
);

create table cat.cliente (
  cliente_id        int generated always as identity primary key,
  codigo_cliente    varchar(40) not null unique,
  nombre            varchar(160) not null,
  tipo              varchar(60)  not null,
  municipio_id      int references cat.municipio(municipio_id),
  direccion         varchar(200),
  lat               numeric(9,6),
  lon               numeric(9,6)
);

create table cat.transportista (
  transportista_id  int generated always as identity primary key,
  codigo_transp     varchar(40) not null unique,
  nombre            varchar(140) not null,
  nro_licencia      varchar(60)
);

create table cat.almacen (
  almacen_id        int generated always as identity primary key,
  codigo_almacen    varchar(40) not null unique,
  nombre            varchar(140) not null,
  municipio_id      int not null references cat.municipio(municipio_id),
  direccion         varchar(200),
  lat               numeric(9,6),
  lon               numeric(9,6)
);

-- 4) Campo
create table campo.productor (
  productor_id      int generated always as identity primary key,
  codigo_productor  varchar(40) not null unique,
  nombre            varchar(140) not null,
  municipio_id      int not null references cat.municipio(municipio_id),
  telefono          varchar(40)
);

create table campo.lotecampo (
  lote_campo_id     int generated always as identity primary key,
  codigo_lote_campo varchar(50) not null unique,
  productor_id      int not null references campo.productor(productor_id),
  variedad_id       int not null references cat.variedadpapa(variedad_id),
  superficie_ha     numeric(9,2) not null,
  fecha_siembra     date not null,
  fecha_cosecha     date,
  humedad_suelo_pct numeric(5,2)
);

create table campo.sensorlectura (
  lectura_id        bigint generated always as identity primary key,
  lote_campo_id     int not null references campo.lotecampo(lote_campo_id),
  fecha_hora        timestamptz not null,
  tipo              varchar(50) not null,
  valor_num         numeric(18,6),
  valor_texto       varchar(200)
);
create index ix_sensor_lotehora on campo.sensorlectura(lote_campo_id, fecha_hora);

create table planta.loteplanta (
  lote_planta_id        int generated always as identity primary key,
  codigo_lote_planta    varchar(50) not null unique,
  planta_id             int not null references cat.planta(planta_id),
  fecha_inicio          timestamptz not null,
  fecha_fin             timestamptz,
  rendimiento_pct       numeric(5,2)
);

create table planta.loteplanta_entradacampo (
  lote_planta_id        int not null references planta.loteplanta(lote_planta_id),
  lote_campo_id         int not null references campo.lotecampo(lote_campo_id),
  peso_entrada_t        numeric(12,3) not null,
  primary key (lote_planta_id, lote_campo_id)
);

create table planta.controlproceso (
  control_id            bigint generated always as identity primary key,
  lote_planta_id        int not null references planta.loteplanta(lote_planta_id),
  etapa                 varchar(40) not null,
  fecha_hora            timestamptz not null,
  parametro             varchar(60) not null,
  valor_num             numeric(18,6),
  valor_texto           varchar(200),
  estado                varchar(20) not null default 'OK'
);
create index ix_control_lotehora on planta.controlproceso(lote_planta_id, fecha_hora);

create table planta.lotesalida (
  lote_salida_id        int generated always as identity primary key,
  codigo_lote_salida    varchar(50) not null unique,
  lote_planta_id        int not null references planta.loteplanta(lote_planta_id),
  sku                   varchar(120) not null,
  peso_t                numeric(12,3) not null,
  fecha_empaque         timestamptz not null
);

create table logistica.ruta (
  ruta_id           int generated always as identity primary key,
  codigo_ruta       varchar(40) not null unique,
  descripcion       varchar(160)
);

create table logistica.rutapunto (
  ruta_id           int not null references logistica.ruta(ruta_id),
  orden             int not null,
  cliente_id        int not null references cat.cliente(cliente_id),
  primary key (ruta_id, orden)
);

create table logistica.envio (
  envio_id          int generated always as identity primary key,
  codigo_envio      varchar(40) not null unique,
  ruta_id           int references logistica.ruta(ruta_id),
  transportista_id  int references cat.transportista(transportista_id),
  fecha_salida      timestamptz not null,
  fecha_llegada     timestamptz,
  temp_min_c        numeric(6,2),
  temp_max_c        numeric(6,2),
  estado            varchar(20) not null default 'EN_RUTA',
  almacen_origen_id int references cat.almacen(almacen_id)
);

create table logistica.enviodetalle (
  envio_detalle_id  bigint generated always as identity primary key,
  envio_id          int not null references logistica.envio(envio_id),
  lote_salida_id    int not null references planta.lotesalida(lote_salida_id),
  cliente_id        int not null references cat.cliente(cliente_id),
  cantidad_t        numeric(12,3) not null
);
create index ix_ed_envio on logistica.enviodetalle(envio_id);
create index ix_ed_lote on logistica.enviodetalle(lote_salida_id);
create index ix_ed_cliente on logistica.enviodetalle(cliente_id);

create table logistica.enviodetallealmacen (
  envio_detalle_alm_id bigint generated always as identity primary key,
  envio_id             int not null references logistica.envio(envio_id),
  lote_salida_id       int not null references planta.lotesalida(lote_salida_id),
  almacen_id           int not null references cat.almacen(almacen_id),
  cantidad_t           numeric(12,3) not null
);
create index ix_eda_envio on logistica.enviodetallealmacen(envio_id);
create index ix_eda_lote on logistica.enviodetallealmacen(lote_salida_id);
create index ix_eda_almacen on logistica.enviodetallealmacen(almacen_id);

-- 7) Comercial
create table comercial.pedido (
  pedido_id         int generated always as identity primary key,
  codigo_pedido     varchar(40) not null unique,
  cliente_id        int not null references cat.cliente(cliente_id),
  fecha_pedido      timestamptz not null,
  estado            varchar(20) not null default 'ABIERTO',
  almacen_id        int references cat.almacen(almacen_id)
);

create table comercial.pedidodetalle (
  pedido_detalle_id bigint generated always as identity primary key,
  pedido_id         int not null references comercial.pedido(pedido_id),
  sku               varchar(120) not null,
  cantidad_t        numeric(12,3) not null,
  precio_unit_usd   numeric(12,2) not null
);
create index ix_pd_pedido on comercial.pedidodetalle(pedido_id);

-- 8) Certificaciones
create table certificacion.certificado (
  certificado_id      int generated always as identity primary key,
  codigo_certificado  varchar(60) not null unique,
  ambito              varchar(30) not null,   -- CAMPO/PLANTA/ENVIO/GENERAL
  area                varchar(40) not null,   -- HACCP/ISO/BPM/etc.
  vigente_desde       date not null,
  vigente_hasta       date,
  emisor              varchar(160) not null,
  url_archivo         varchar(400)
);

create table certificacion.certificadolotecampo (
  certificado_id    int not null references certificacion.certificado(certificado_id),
  lote_campo_id     int not null references campo.lotecampo(lote_campo_id),
  primary key (certificado_id, lote_campo_id)
);

create table certificacion.certificadoloteplanta (
  certificado_id    int not null references certificacion.certificado(certificado_id),
  lote_planta_id    int not null references planta.loteplanta(lote_planta_id),
  primary key (certificado_id, lote_planta_id)
);

create table certificacion.certificadolotesalida (
  certificado_id    int not null references certificacion.certificado(certificado_id),
  lote_salida_id    int not null references planta.lotesalida(lote_salida_id),
  primary key (certificado_id, lote_salida_id)
);

create table certificacion.certificadoenvio (
  certificado_id    int not null references certificacion.certificado(certificado_id),
  envio_id          int not null references logistica.envio(envio_id),
  primary key (certificado_id, envio_id)
);

create table certificacion.certificadoevidencia (
  evidencia_id       bigint generated always as identity primary key,
  certificado_id     int not null references certificacion.certificado(certificado_id),
  tipo               varchar(60) not null,
  descripcion        varchar(400),
  url_archivo        varchar(400),
  fecha_registro     timestamptz not null default now()
);

create table certificacion.certificadocadena (
  certificado_padre_id   int not null references certificacion.certificado(certificado_id),
  certificado_hijo_id    int not null references certificacion.certificado(certificado_id),
  primary key (certificado_padre_id, certificado_hijo_id)
);

create table almacen.pedido (
  pedido_almacen_id  int generated always as identity primary key,
  codigo_pedido      varchar(40) not null unique,
  almacen_id         int not null references cat.almacen(almacen_id),
  fecha_pedido       timestamptz not null,
  estado             varchar(20) not null default 'ABIERTO'  -- ABIERTO/APROBADO/ENVIADO/RECEPCIONADO/CERRADO/CANCELADO
);

create table almacen.pedidodetalle (
  pedido_detalle_id  bigint generated always as identity primary key,
  pedido_almacen_id  int not null references almacen.pedido(pedido_almacen_id),
  sku                varchar(120) not null,
  cantidad_t         numeric(12,3) not null,
  lote_salida_id     int references planta.lotesalida(lote_salida_id)
);
create index ix_palm_pedido on almacen.pedidodetalle(pedido_almacen_id);

create table almacen.recepcion (
  recepcion_id       bigint generated always as identity primary key,
  envio_id           int not null references logistica.envio(envio_id),
  almacen_id         int not null references cat.almacen(almacen_id),
  fecha_recepcion    timestamptz not null default now(),
  observacion        varchar(200)
);
create index ix_rec_alm_fec on almacen.recepcion(almacen_id, fecha_recepcion);

create table almacen.inventario (
  almacen_id         int not null references cat.almacen(almacen_id),
  lote_salida_id     int not null references planta.lotesalida(lote_salida_id),
  sku                varchar(120) not null,
  cantidad_t         numeric(12,3) not null,
  primary key (almacen_id, lote_salida_id)
);
create index ix_inv_sku on almacen.inventario(almacen_id, sku);

create table almacen.movimiento (
  mov_id             bigint generated always as identity primary key,
  almacen_id         int not null references cat.almacen(almacen_id),
  lote_salida_id     int not null references planta.lotesalida(lote_salida_id),
  tipo               varchar(12) not null,      
  cantidad_t         numeric(12,3) not null,
  fecha_mov          timestamptz not null default now(),
  referencia         varchar(40),
  detalle            varchar(200)
);
create index ix_mov_alm_fec on almacen.movimiento(almacen_id, fecha_mov);


create or replace function planta.sp_registrar_lote_planta(
  p_codigo_lote_planta text,
  p_planta_id int,
  p_fecha_inicio timestamptz,
  p_entradas jsonb
) returns void language plpgsql as $$
declare
  v_lote_planta_id int;
begin
  insert into planta.loteplanta(codigo_lote_planta, planta_id, fecha_inicio)
  values (p_codigo_lote_planta, p_planta_id, p_fecha_inicio)
  returning lote_planta_id into v_lote_planta_id;

  insert into planta.loteplanta_entradacampo(lote_planta_id, lote_campo_id, peso_entrada_t)
  select v_lote_planta_id,
         (elem->>'lote_campo_id')::int,
         (elem->>'peso_entrada_t')::numeric
  from jsonb_array_elements(p_entradas) as elem;
end;
$$;

create or replace function planta.sp_registrar_lote_salida_y_envio(
  p_codigo_lote_salida text,
  p_lote_planta_id int,
  p_sku text,
  p_peso_t numeric,
  p_fecha_empaque timestamptz,
  p_crear_envio boolean default false,
  p_codigo_envio text default null,
  p_ruta_id int default null,
  p_transportista_id int default null,
  p_fecha_salida timestamptz default null
) returns void language plpgsql as $$
declare
  v_envio_id int;
begin
  insert into planta.lotesalida(codigo_lote_salida, lote_planta_id, sku, peso_t, fecha_empaque)
  values (p_codigo_lote_salida, p_lote_planta_id, p_sku, p_peso_t, p_fecha_empaque);

  if p_crear_envio then
    if p_fecha_salida is null then
      p_fecha_salida := now();
    end if;
    insert into logistica.envio(codigo_envio, ruta_id, transportista_id, fecha_salida)
    values (p_codigo_envio, p_ruta_id, p_transportista_id, p_fecha_salida)
    returning envio_id into v_envio_id;
    
  end if;
end;
$$;

create or replace function almacen.sp_despachar_a_almacen(
  p_codigo_envio text,
  p_transportista_id int,
  p_almacen_destino_id int,
  p_fecha_salida timestamptz,
  p_detalle jsonb
) returns void language plpgsql as $$
declare
  v_envio_id int;
begin
  insert into logistica.envio(codigo_envio, transportista_id, fecha_salida, estado)
  values (p_codigo_envio, p_transportista_id, p_fecha_salida, 'EN_RUTA')
  returning envio_id into v_envio_id;

  insert into logistica.enviodetallealmacen(envio_id, lote_salida_id, almacen_id, cantidad_t)
  select v_envio_id, ls.lote_salida_id, p_almacen_destino_id, (elem->>'cantidad_t')::numeric
  from jsonb_array_elements(p_detalle) elem
  join planta.lotesalida ls on ls.codigo_lote_salida = elem->>'codigo_lote_salida';
end;
$$;

create or replace function almacen.sp_recepcionar_envio(
  p_codigo_envio text,
  p_almacen_id int,
  p_observacion text default null
) returns void language plpgsql as $$
declare
  v_envio_id int;
begin
  select envio_id into v_envio_id from logistica.envio where codigo_envio = p_codigo_envio;
  if v_envio_id is null then
    raise exception 'Envio no encontrado: %', p_codigo_envio using errcode='P0001';
  end if;

  insert into almacen.recepcion(envio_id, almacen_id, observacion)
  values (v_envio_id, p_almacen_id, p_observacion);

  insert into almacen.inventario(almacen_id, lote_salida_id, sku, cantidad_t)
  select eda.almacen_id, eda.lote_salida_id, ls.sku, eda.cantidad_t
  from logistica.enviodetallealmacen eda
  join planta.lotesalida ls on ls.lote_salida_id = eda.lote_salida_id
  where eda.envio_id = v_envio_id and eda.almacen_id = p_almacen_id
  on conflict (almacen_id, lote_salida_id)
  do update set cantidad_t = almacen.inventario.cantidad_t + excluded.cantidad_t,
                sku = excluded.sku;

  insert into almacen.movimiento(almacen_id, lote_salida_id, tipo, cantidad_t, referencia, detalle)
  select eda.almacen_id, eda.lote_salida_id, 'ENTRADA', eda.cantidad_t, p_codigo_envio, 'Recepción de envío'
  from logistica.enviodetallealmacen eda
  where eda.envio_id = v_envio_id and eda.almacen_id = p_almacen_id;

  update logistica.envio
     set estado = 'ENTREGADO', fecha_llegada = now()
   where envio_id = v_envio_id;
end;
$$;

create or replace function almacen.sp_despachar_a_cliente(
  p_codigo_envio text,
  p_almacen_origen_id int,
  p_cliente_id int,
  p_transportista_id int,
  p_fecha_salida timestamptz,
  p_detalle jsonb
) returns void language plpgsql as $$
declare
  v_envio_id int;
begin
  insert into logistica.envio(codigo_envio, transportista_id, fecha_salida, estado, almacen_origen_id)
  values (p_codigo_envio, p_transportista_id, p_fecha_salida, 'EN_RUTA', p_almacen_origen_id)
  returning envio_id into v_envio_id;

  insert into logistica.enviodetalle(envio_id, lote_salida_id, cliente_id, cantidad_t)
  select v_envio_id, ls.lote_salida_id, p_cliente_id, (elem->>'cantidad_t')::numeric
  from jsonb_array_elements(p_detalle) elem
  join planta.lotesalida ls on ls.codigo_lote_salida = elem->>'codigo_lote_salida';

  update almacen.inventario inv
     set cantidad_t = inv.cantidad_t - d.cantidad_t
  from (
    select ls.lote_salida_id as lote_salida_id, (elem->>'cantidad_t')::numeric as cantidad_t
    from jsonb_array_elements(p_detalle) elem
    join planta.lotesalida ls on ls.codigo_lote_salida = elem->>'codigo_lote_salida'
  ) d
  where inv.almacen_id = p_almacen_origen_id and inv.lote_salida_id = d.lote_salida_id;

  insert into almacen.movimiento(almacen_id, lote_salida_id, tipo, cantidad_t, referencia, detalle)
  select p_almacen_origen_id, ls.lote_salida_id, 'SALIDA', (elem->>'cantidad_t')::numeric, p_codigo_envio, 'Despacho a cliente'
  from jsonb_array_elements(p_detalle) elem
  join planta.lotesalida ls on ls.codigo_lote_salida = elem->>'codigo_lote_salida';
end;
$$;

create or replace view planta.v_trazabilidad_lote_salida as
select
  ls.codigo_lote_salida,
  lp.codigo_lote_planta,
  p.codigo_planta,
  (
    select string_agg(lc2.codigo_lote_campo, ', ' order by lc2.codigo_lote_campo)
    from planta.loteplanta_entradacampo lec2
    join campo.lotecampo lc2 on lc2.lote_campo_id = lec2.lote_campo_id
    where lec2.lote_planta_id = lp.lote_planta_id
  ) as lotes_campo,
  (
    select min(ev2.codigo_envio)
    from logistica.enviodetalle ed2
    join logistica.envio ev2 on ev2.envio_id = ed2.envio_id
    where ed2.lote_salida_id = ls.lote_salida_id
  ) as primer_envio,
  (
    select string_agg(distinct c2.codigo_cliente, ', ' order by c2.codigo_cliente)
    from logistica.enviodetalle ed2
    join cat.cliente c2 on c2.cliente_id = ed2.cliente_id
    where ed2.lote_salida_id = ls.lote_salida_id
  ) as clientes,
  (
    select min(ev2.temp_min_c)
    from logistica.enviodetalle ed2
    join logistica.envio ev2 on ev2.envio_id = ed2.envio_id
    where ed2.lote_salida_id = ls.lote_salida_id
  ) as envio_temp_min_c,
  (
    select max(ev2.temp_max_c)
    from logistica.enviodetalle ed2
    join logistica.envio ev2 on ev2.envio_id = ed2.envio_id
    where ed2.lote_salida_id = ls.lote_salida_id
  ) as envio_temp_max_c,
  ls.peso_t,
  lp.rendimiento_pct
from planta.lotesalida ls
join planta.loteplanta lp on lp.lote_planta_id = ls.lote_planta_id
join cat.planta p         on p.planta_id = lp.planta_id;

create or replace view certificacion.v_certificados_por_lote_salida as
select
  ls.codigo_lote_salida,
  cert.codigo_certificado,
  cert.ambito,
  cert.area,
  cert.vigente_desde,
  cert.vigente_hasta,
  cert.emisor
from planta.lotesalida ls
join certificacion.certificadolotesalida cls on cls.lote_salida_id = ls.lote_salida_id
join certificacion.certificado cert          on cert.certificado_id = cls.certificado_id

union all
select
  ls.codigo_lote_salida,
  cert.codigo_certificado,
  cert.ambito,
  cert.area,
  cert.vigente_desde,
  cert.vigente_hasta,
  cert.emisor
from planta.lotesalida ls
join planta.loteplanta lp                    on lp.lote_planta_id = ls.lote_planta_id
join certificacion.certificadoloteplanta clp on clp.lote_planta_id = lp.lote_planta_id
join certificacion.certificado cert          on cert.certificado_id = clp.certificado_id

union all
select
  ls.codigo_lote_salida,
  cert.codigo_certificado,
  cert.ambito,
  cert.area,
  cert.vigente_desde,
  cert.vigente_hasta,
  cert.emisor
from planta.lotesalida ls
join planta.loteplanta lp                         on lp.lote_planta_id = ls.lote_planta_id
join planta.loteplanta_entradacampo lec           on lec.lote_planta_id = lp.lote_planta_id
join certificacion.certificadolotecampo clc       on clc.lote_campo_id = lec.lote_campo_id
join certificacion.certificado cert               on cert.certificado_id = clc.certificado_id

union all
select
  ls.codigo_lote_salida,
  cert.codigo_certificado,
  cert.ambito,
  cert.area,
  cert.vigente_desde,
  cert.vigente_hasta,
  cert.emisor
from planta.lotesalida ls
join logistica.enviodetalle ed                    on ed.lote_salida_id = ls.lote_salida_id
join certificacion.certificadoenvio ce            on ce.envio_id = ed.envio_id
join certificacion.certificado cert               on cert.certificado_id = ce.certificado_id
;

create or replace view almacen.v_stock as
select
  a.codigo_almacen,
  i.sku,
  sum(i.cantidad_t) as stock_t
from almacen.inventario i
join cat.almacen a on a.almacen_id = i.almacen_id
group by a.codigo_almacen, i.sku;

insert into cat.departamento(nombre) values ('La Paz'), ('Cochabamba'), ('Santa Cruz');

insert into cat.municipio(departamento_id, nombre)
values (1,'La Paz'),(2,'Cochabamba'),(3,'Santa Cruz');

insert into cat.variedadpapa(codigo_variedad,nombre_comercial,aptitud,ciclo_dias_min,ciclo_dias_max)
values ('WAYCHA','Waych''a','Mesa',110,140),
       ('DESIREE','Desirée','Mesa/Industria',120,150);

insert into cat.planta(codigo_planta,nombre,municipio_id)
values ('PL-SCZ-01','Planta Santa Cruz 1',3);

insert into cat.cliente(codigo_cliente,nombre,tipo,municipio_id)
values ('MAY-LPZ-01','Mayorista La Paz 1','Mayorista',1),
       ('RET-SCZ-01','Retail SCZ 1','Retail',3);

insert into cat.transportista(codigo_transp,nombre) values ('TRP-001','TransACME');

insert into cat.almacen(codigo_almacen,nombre,municipio_id)
values ('ALM-SCZ-01','Almacén Central SCZ',3);

insert into campo.productor(codigo_productor,nombre,municipio_id)
values ('PROD-001','Agro Andina',1);

insert into campo.lotecampo(codigo_lote_campo,productor_id,variedad_id,superficie_ha,fecha_siembra,humedad_suelo_pct)
values ('LC-0001',1,1,12.5,'2025-06-01',61.0);

insert into planta.loteplanta(codigo_lote_planta,planta_id,fecha_inicio,rendimiento_pct)
values ('LP-1001',1,now(),93.5);

insert into planta.loteplanta_entradacampo(lote_planta_id,lote_campo_id,peso_entrada_t)
values (1,1,22.400);

insert into planta.lotesalida(codigo_lote_salida,lote_planta_id,sku,peso_t,fecha_empaque)
values ('LS-2001',1,'Papa lavada 25kg',21.000,now());

insert into logistica.ruta(codigo_ruta,descripcion) values ('R-SCZ-LPZ','SCZ → LPZ');

insert into logistica.envio(codigo_envio,ruta_id,transportista_id,fecha_salida,temp_min_c,temp_max_c,estado)
values ('ENV-9001',1,1,now() - interval '4 hours',4.5,7.2,'ENTREGADO');

insert into logistica.enviodetalle(envio_id,lote_salida_id,cliente_id,cantidad_t)
values (1,1,1,8.000);

insert into logistica.envio(codigo_envio,transportista_id,fecha_salida,estado)
values ('ENV-9002',1,now() - interval '2 hours','EN_RUTA');

insert into logistica.enviodetallealmacen(envio_id,lote_salida_id,almacen_id,cantidad_t)
values (2,1,1,10.000);

select almacen.sp_recepcionar_envio('ENV-9002', 1, 'OK');

insert into comercial.pedido(codigo_pedido,cliente_id,fecha_pedido,estado,almacen_id)
values ('PED-CL-01',2,now(),'ABIERTO',1);

insert into comercial.pedidodetalle(pedido_id,sku,cantidad_t,precio_unit_usd)
values (1,'Papa lavada 25kg',5.000,18.50);

select almacen.sp_despachar_a_cliente(
  'ENV-9003',
  1,
  2,
  1,
  now(),
  '[{"codigo_lote_salida":"LS-2001","cantidad_t":5.000}]'::jsonb
);

insert into certificacion.certificado(codigo_certificado,ambito,area,vigente_desde,emisor)
values ('CERT-HACCP-PLANTA-2025','PLANTA','HACCP','2025-01-01','INTI'),
       ('CERT-CAMPO-BPM-2025','CAMPO','BPM','2025-01-01','INIAF'),
       ('CERT-CADENA-2025','GENERAL','Cadena','2025-01-01','Auditor Externo');

insert into certificacion.certificadoloteplanta(certificado_id,lote_planta_id) values (1,1);
insert into certificacion.certificadolotecampo(certificado_id,lote_campo_id)  values (2,1);
insert into certificacion.certificadolotesalida(certificado_id,lote_salida_id) values (1,1);
insert into certificacion.certificadoenvio(certificado_id,envio_id)           values (1,1);

insert into certificacion.certificadocadena(certificado_padre_id,certificado_hijo_id)
values (3,1),(3,2);
SQL;
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
