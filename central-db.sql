--
-- PostgreSQL database dump
--

\restrict LNClk3G7D2MwjpoGpQRBF1Hedi30j0AtOgr558cc1mBKdpwCJji6SCzFzh1szg3

-- Dumped from database version 18.1 (Debian 18.1-1.pgdg13+2)
-- Dumped by pg_dump version 18.1 (Debian 18.1-1.pgdg13+2)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: almacen; Type: SCHEMA; Schema: -; Owner: admin
--

CREATE SCHEMA almacen;


ALTER SCHEMA almacen OWNER TO admin;

--
-- Name: campo; Type: SCHEMA; Schema: -; Owner: admin
--

CREATE SCHEMA campo;


ALTER SCHEMA campo OWNER TO admin;

--
-- Name: cat; Type: SCHEMA; Schema: -; Owner: admin
--

CREATE SCHEMA cat;


ALTER SCHEMA cat OWNER TO admin;

--
-- Name: certificacion; Type: SCHEMA; Schema: -; Owner: admin
--

CREATE SCHEMA certificacion;


ALTER SCHEMA certificacion OWNER TO admin;

--
-- Name: comercial; Type: SCHEMA; Schema: -; Owner: admin
--

CREATE SCHEMA comercial;


ALTER SCHEMA comercial OWNER TO admin;

--
-- Name: logistica; Type: SCHEMA; Schema: -; Owner: admin
--

CREATE SCHEMA logistica;


ALTER SCHEMA logistica OWNER TO admin;

--
-- Name: planta; Type: SCHEMA; Schema: -; Owner: admin
--

CREATE SCHEMA planta;


ALTER SCHEMA planta OWNER TO admin;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: inventario; Type: TABLE; Schema: almacen; Owner: admin
--

CREATE TABLE almacen.inventario (
    almacen_id bigint NOT NULL,
    lote_salida_id bigint NOT NULL,
    sku character varying(120) NOT NULL,
    cantidad_t numeric(12,3) NOT NULL
);


ALTER TABLE almacen.inventario OWNER TO admin;

--
-- Name: movimiento; Type: TABLE; Schema: almacen; Owner: admin
--

CREATE TABLE almacen.movimiento (
    mov_id bigint NOT NULL,
    almacen_id bigint NOT NULL,
    lote_salida_id bigint NOT NULL,
    tipo character varying(12) NOT NULL,
    cantidad_t numeric(12,3) NOT NULL,
    fecha_mov timestamp(0) with time zone DEFAULT '2025-12-16 00:12:46+00'::timestamp with time zone NOT NULL,
    referencia character varying(40),
    detalle character varying(200)
);


ALTER TABLE almacen.movimiento OWNER TO admin;

--
-- Name: movimiento_mov_id_seq; Type: SEQUENCE; Schema: almacen; Owner: admin
--

CREATE SEQUENCE almacen.movimiento_mov_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE almacen.movimiento_mov_id_seq OWNER TO admin;

--
-- Name: movimiento_mov_id_seq; Type: SEQUENCE OWNED BY; Schema: almacen; Owner: admin
--

ALTER SEQUENCE almacen.movimiento_mov_id_seq OWNED BY almacen.movimiento.mov_id;


--
-- Name: pedido; Type: TABLE; Schema: almacen; Owner: admin
--

CREATE TABLE almacen.pedido (
    pedido_almacen_id bigint NOT NULL,
    codigo_pedido character varying(40) NOT NULL,
    almacen_id bigint NOT NULL,
    fecha_pedido timestamp(0) with time zone NOT NULL,
    estado character varying(20) DEFAULT 'ABIERTO'::character varying NOT NULL
);


ALTER TABLE almacen.pedido OWNER TO admin;

--
-- Name: pedido_pedido_almacen_id_seq; Type: SEQUENCE; Schema: almacen; Owner: admin
--

CREATE SEQUENCE almacen.pedido_pedido_almacen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE almacen.pedido_pedido_almacen_id_seq OWNER TO admin;

--
-- Name: pedido_pedido_almacen_id_seq; Type: SEQUENCE OWNED BY; Schema: almacen; Owner: admin
--

ALTER SEQUENCE almacen.pedido_pedido_almacen_id_seq OWNED BY almacen.pedido.pedido_almacen_id;


--
-- Name: pedidodetalle; Type: TABLE; Schema: almacen; Owner: admin
--

CREATE TABLE almacen.pedidodetalle (
    pedido_detalle_id bigint NOT NULL,
    pedido_almacen_id bigint NOT NULL,
    sku character varying(120) NOT NULL,
    cantidad_t numeric(12,3) NOT NULL,
    lote_salida_id bigint
);


ALTER TABLE almacen.pedidodetalle OWNER TO admin;

--
-- Name: pedidodetalle_pedido_detalle_id_seq; Type: SEQUENCE; Schema: almacen; Owner: admin
--

CREATE SEQUENCE almacen.pedidodetalle_pedido_detalle_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE almacen.pedidodetalle_pedido_detalle_id_seq OWNER TO admin;

--
-- Name: pedidodetalle_pedido_detalle_id_seq; Type: SEQUENCE OWNED BY; Schema: almacen; Owner: admin
--

ALTER SEQUENCE almacen.pedidodetalle_pedido_detalle_id_seq OWNED BY almacen.pedidodetalle.pedido_detalle_id;


--
-- Name: recepcion; Type: TABLE; Schema: almacen; Owner: admin
--

CREATE TABLE almacen.recepcion (
    recepcion_id bigint NOT NULL,
    envio_id bigint NOT NULL,
    almacen_id bigint NOT NULL,
    fecha_recepcion timestamp(0) with time zone DEFAULT '2025-12-16 00:12:46+00'::timestamp with time zone NOT NULL,
    observacion character varying(200),
    orden_envio_id bigint,
    zona_id bigint,
    ubicacion_id bigint,
    cantidad_esperada_t numeric(12,3),
    cantidad_recibida_t numeric(12,3),
    diferencia_t numeric(12,3),
    estado_producto character varying(30) DEFAULT 'BUENO'::character varying NOT NULL,
    temperatura_llegada_c numeric(5,2),
    recibido_por bigint,
    firma_conductor text
);


ALTER TABLE almacen.recepcion OWNER TO admin;

--
-- Name: COLUMN recepcion.diferencia_t; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.recepcion.diferencia_t IS 'Positivo = sobrante, Negativo = faltante';


--
-- Name: COLUMN recepcion.estado_producto; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.recepcion.estado_producto IS 'BUENO, DAÑADO, PARCIAL, RECHAZADO';


--
-- Name: recepcion_recepcion_id_seq; Type: SEQUENCE; Schema: almacen; Owner: admin
--

CREATE SEQUENCE almacen.recepcion_recepcion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE almacen.recepcion_recepcion_id_seq OWNER TO admin;

--
-- Name: recepcion_recepcion_id_seq; Type: SEQUENCE OWNED BY; Schema: almacen; Owner: admin
--

ALTER SEQUENCE almacen.recepcion_recepcion_id_seq OWNED BY almacen.recepcion.recepcion_id;


--
-- Name: ubicacion; Type: TABLE; Schema: almacen; Owner: admin
--

CREATE TABLE almacen.ubicacion (
    ubicacion_id bigint NOT NULL,
    zona_id bigint NOT NULL,
    codigo_ubicacion character varying(30) NOT NULL,
    pasillo character varying(5) NOT NULL,
    rack integer NOT NULL,
    nivel integer NOT NULL,
    capacidad_t numeric(8,3) NOT NULL,
    ocupado boolean DEFAULT false NOT NULL,
    lote_salida_id bigint,
    cantidad_almacenada_t numeric(8,3),
    fecha_ocupacion timestamp(0) with time zone,
    refrigerado boolean DEFAULT false NOT NULL,
    acceso_montacargas boolean DEFAULT true NOT NULL,
    observaciones character varying(200)
);


ALTER TABLE almacen.ubicacion OWNER TO admin;

--
-- Name: COLUMN ubicacion.pasillo; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.ubicacion.pasillo IS 'A, B, C...';


--
-- Name: COLUMN ubicacion.rack; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.ubicacion.rack IS 'Número de rack: 1, 2, 3...';


--
-- Name: COLUMN ubicacion.nivel; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.ubicacion.nivel IS 'Nivel vertical: 1 (suelo), 2, 3...';


--
-- Name: COLUMN ubicacion.capacidad_t; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.ubicacion.capacidad_t IS 'Capacidad en toneladas';


--
-- Name: COLUMN ubicacion.lote_salida_id; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.ubicacion.lote_salida_id IS 'Lote actualmente almacenado';


--
-- Name: ubicacion_ubicacion_id_seq; Type: SEQUENCE; Schema: almacen; Owner: admin
--

CREATE SEQUENCE almacen.ubicacion_ubicacion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE almacen.ubicacion_ubicacion_id_seq OWNER TO admin;

--
-- Name: ubicacion_ubicacion_id_seq; Type: SEQUENCE OWNED BY; Schema: almacen; Owner: admin
--

ALTER SEQUENCE almacen.ubicacion_ubicacion_id_seq OWNED BY almacen.ubicacion.ubicacion_id;


--
-- Name: almacen; Type: TABLE; Schema: cat; Owner: admin
--

CREATE TABLE cat.almacen (
    almacen_id bigint NOT NULL,
    codigo_almacen character varying(40) NOT NULL,
    nombre character varying(140) NOT NULL,
    municipio_id bigint NOT NULL,
    direccion character varying(200),
    lat numeric(9,6),
    lon numeric(9,6),
    capacidad_total_t numeric(12,3),
    capacidad_disponible_t numeric(12,3),
    tipo character varying(30) DEFAULT 'CENTRAL'::character varying NOT NULL,
    estado character varying(20) DEFAULT 'ACTIVO'::character varying NOT NULL,
    temperatura_min_c numeric(5,2),
    temperatura_max_c numeric(5,2),
    num_zonas integer DEFAULT 1 NOT NULL,
    telefono character varying(20),
    email character varying(100),
    responsable character varying(100),
    horario_operacion character varying(50)
);


ALTER TABLE cat.almacen OWNER TO admin;

--
-- Name: COLUMN almacen.capacidad_total_t; Type: COMMENT; Schema: cat; Owner: admin
--

COMMENT ON COLUMN cat.almacen.capacidad_total_t IS 'Capacidad máxima en toneladas';


--
-- Name: COLUMN almacen.capacidad_disponible_t; Type: COMMENT; Schema: cat; Owner: admin
--

COMMENT ON COLUMN cat.almacen.capacidad_disponible_t IS 'Capacidad actualmente disponible';


--
-- Name: COLUMN almacen.tipo; Type: COMMENT; Schema: cat; Owner: admin
--

COMMENT ON COLUMN cat.almacen.tipo IS 'CENTRAL, DISTRIBUCION, REFRIGERADO, SECO';


--
-- Name: COLUMN almacen.estado; Type: COMMENT; Schema: cat; Owner: admin
--

COMMENT ON COLUMN cat.almacen.estado IS 'ACTIVO, MANTENIMIENTO, INACTIVO';


--
-- Name: COLUMN almacen.responsable; Type: COMMENT; Schema: cat; Owner: admin
--

COMMENT ON COLUMN cat.almacen.responsable IS 'Nombre del jefe de almacén';


--
-- Name: COLUMN almacen.horario_operacion; Type: COMMENT; Schema: cat; Owner: admin
--

COMMENT ON COLUMN cat.almacen.horario_operacion IS 'Ej: 08:00-18:00';


--
-- Name: v_stock; Type: VIEW; Schema: almacen; Owner: admin
--

CREATE VIEW almacen.v_stock AS
 SELECT a.codigo_almacen,
    i.sku,
    sum(i.cantidad_t) AS stock_t
   FROM (almacen.inventario i
     JOIN cat.almacen a ON ((a.almacen_id = i.almacen_id)))
  GROUP BY a.codigo_almacen, i.sku;


ALTER VIEW almacen.v_stock OWNER TO admin;

--
-- Name: zona; Type: TABLE; Schema: almacen; Owner: admin
--

CREATE TABLE almacen.zona (
    zona_id bigint NOT NULL,
    almacen_id bigint NOT NULL,
    codigo_zona character varying(20) NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion character varying(200),
    tipo character varying(30) DEFAULT 'SECO'::character varying NOT NULL,
    capacidad_t numeric(12,3) NOT NULL,
    ocupacion_actual_t numeric(12,3) DEFAULT '0'::numeric NOT NULL,
    temperatura_objetivo_c numeric(5,2),
    humedad_objetivo_pct numeric(5,2),
    estado character varying(20) DEFAULT 'DISPONIBLE'::character varying NOT NULL,
    num_pasillos integer DEFAULT 1 NOT NULL,
    num_racks_por_pasillo integer DEFAULT 1 NOT NULL,
    num_niveles integer DEFAULT 1 NOT NULL,
    created_at timestamp(0) with time zone DEFAULT '2025-12-16 00:12:47+00'::timestamp with time zone NOT NULL
);


ALTER TABLE almacen.zona OWNER TO admin;

--
-- Name: COLUMN zona.tipo; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.zona.tipo IS 'REFRIGERADO, CONGELADO, SECO, CUARENTENA';


--
-- Name: COLUMN zona.capacidad_t; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.zona.capacidad_t IS 'Capacidad máxima en toneladas';


--
-- Name: COLUMN zona.estado; Type: COMMENT; Schema: almacen; Owner: admin
--

COMMENT ON COLUMN almacen.zona.estado IS 'DISPONIBLE, LLENO, MANTENIMIENTO, CERRADO';


--
-- Name: zona_zona_id_seq; Type: SEQUENCE; Schema: almacen; Owner: admin
--

CREATE SEQUENCE almacen.zona_zona_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE almacen.zona_zona_id_seq OWNER TO admin;

--
-- Name: zona_zona_id_seq; Type: SEQUENCE OWNED BY; Schema: almacen; Owner: admin
--

ALTER SEQUENCE almacen.zona_zona_id_seq OWNED BY almacen.zona.zona_id;


--
-- Name: asignacion_conductor; Type: TABLE; Schema: campo; Owner: admin
--

CREATE TABLE campo.asignacion_conductor (
    asignacion_id bigint NOT NULL,
    solicitud_id bigint NOT NULL,
    transportista_id bigint NOT NULL,
    fecha_asignacion timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    estado character varying(255) DEFAULT 'ASIGNADO'::character varying NOT NULL,
    fecha_inicio_ruta timestamp(0) without time zone,
    fecha_completado timestamp(0) without time zone,
    observaciones text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT asignacion_conductor_estado_check CHECK (((estado)::text = ANY ((ARRAY['ASIGNADO'::character varying, 'EN_RUTA'::character varying, 'COMPLETADO'::character varying])::text[])))
);


ALTER TABLE campo.asignacion_conductor OWNER TO admin;

--
-- Name: asignacion_conductor_asignacion_id_seq; Type: SEQUENCE; Schema: campo; Owner: admin
--

CREATE SEQUENCE campo.asignacion_conductor_asignacion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE campo.asignacion_conductor_asignacion_id_seq OWNER TO admin;

--
-- Name: asignacion_conductor_asignacion_id_seq; Type: SEQUENCE OWNED BY; Schema: campo; Owner: admin
--

ALTER SEQUENCE campo.asignacion_conductor_asignacion_id_seq OWNED BY campo.asignacion_conductor.asignacion_id;


--
-- Name: lotecampo; Type: TABLE; Schema: campo; Owner: admin
--

CREATE TABLE campo.lotecampo (
    lote_campo_id bigint NOT NULL,
    codigo_lote_campo character varying(50) NOT NULL,
    productor_id bigint NOT NULL,
    variedad_id bigint NOT NULL,
    superficie_ha numeric(9,2) NOT NULL,
    fecha_siembra date NOT NULL,
    fecha_cosecha date,
    humedad_suelo_pct numeric(5,2)
);


ALTER TABLE campo.lotecampo OWNER TO admin;

--
-- Name: lotecampo_lote_campo_id_seq; Type: SEQUENCE; Schema: campo; Owner: admin
--

CREATE SEQUENCE campo.lotecampo_lote_campo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE campo.lotecampo_lote_campo_id_seq OWNER TO admin;

--
-- Name: lotecampo_lote_campo_id_seq; Type: SEQUENCE OWNED BY; Schema: campo; Owner: admin
--

ALTER SEQUENCE campo.lotecampo_lote_campo_id_seq OWNED BY campo.lotecampo.lote_campo_id;


--
-- Name: productor; Type: TABLE; Schema: campo; Owner: admin
--

CREATE TABLE campo.productor (
    productor_id bigint NOT NULL,
    codigo_productor character varying(40) NOT NULL,
    nombre character varying(140) NOT NULL,
    municipio_id bigint NOT NULL,
    telefono character varying(40)
);


ALTER TABLE campo.productor OWNER TO admin;

--
-- Name: productor_productor_id_seq; Type: SEQUENCE; Schema: campo; Owner: admin
--

CREATE SEQUENCE campo.productor_productor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE campo.productor_productor_id_seq OWNER TO admin;

--
-- Name: productor_productor_id_seq; Type: SEQUENCE OWNED BY; Schema: campo; Owner: admin
--

ALTER SEQUENCE campo.productor_productor_id_seq OWNED BY campo.productor.productor_id;


--
-- Name: sensorlectura; Type: TABLE; Schema: campo; Owner: admin
--

CREATE TABLE campo.sensorlectura (
    lectura_id bigint NOT NULL,
    lote_campo_id bigint NOT NULL,
    fecha_hora timestamp(0) with time zone NOT NULL,
    tipo character varying(50) NOT NULL,
    valor_num numeric(18,6),
    valor_texto character varying(200)
);


ALTER TABLE campo.sensorlectura OWNER TO admin;

--
-- Name: sensorlectura_lectura_id_seq; Type: SEQUENCE; Schema: campo; Owner: admin
--

CREATE SEQUENCE campo.sensorlectura_lectura_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE campo.sensorlectura_lectura_id_seq OWNER TO admin;

--
-- Name: sensorlectura_lectura_id_seq; Type: SEQUENCE OWNED BY; Schema: campo; Owner: admin
--

ALTER SEQUENCE campo.sensorlectura_lectura_id_seq OWNED BY campo.sensorlectura.lectura_id;


--
-- Name: solicitud_produccion; Type: TABLE; Schema: campo; Owner: admin
--

CREATE TABLE campo.solicitud_produccion (
    solicitud_id bigint NOT NULL,
    planta_id bigint NOT NULL,
    productor_id bigint NOT NULL,
    variedad_id bigint NOT NULL,
    cantidad_solicitada_t numeric(10,3) NOT NULL,
    fecha_necesaria date NOT NULL,
    estado character varying(255) DEFAULT 'PENDIENTE'::character varying NOT NULL,
    observaciones text,
    fecha_solicitud timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    fecha_respuesta timestamp(0) without time zone,
    justificacion_rechazo text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    codigo_solicitud character varying(50) NOT NULL,
    CONSTRAINT solicitud_produccion_estado_check CHECK (((estado)::text = ANY ((ARRAY['PENDIENTE'::character varying, 'ACEPTADA'::character varying, 'RECHAZADA'::character varying, 'COMPLETADA'::character varying])::text[])))
);


ALTER TABLE campo.solicitud_produccion OWNER TO admin;

--
-- Name: solicitud_produccion_solicitud_id_seq; Type: SEQUENCE; Schema: campo; Owner: admin
--

CREATE SEQUENCE campo.solicitud_produccion_solicitud_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE campo.solicitud_produccion_solicitud_id_seq OWNER TO admin;

--
-- Name: solicitud_produccion_solicitud_id_seq; Type: SEQUENCE OWNED BY; Schema: campo; Owner: admin
--

ALTER SEQUENCE campo.solicitud_produccion_solicitud_id_seq OWNED BY campo.solicitud_produccion.solicitud_id;


--
-- Name: almacen_almacen_id_seq; Type: SEQUENCE; Schema: cat; Owner: admin
--

CREATE SEQUENCE cat.almacen_almacen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE cat.almacen_almacen_id_seq OWNER TO admin;

--
-- Name: almacen_almacen_id_seq; Type: SEQUENCE OWNED BY; Schema: cat; Owner: admin
--

ALTER SEQUENCE cat.almacen_almacen_id_seq OWNED BY cat.almacen.almacen_id;


--
-- Name: cliente; Type: TABLE; Schema: cat; Owner: admin
--

CREATE TABLE cat.cliente (
    cliente_id bigint NOT NULL,
    codigo_cliente character varying(40) NOT NULL,
    nombre character varying(160) NOT NULL,
    tipo character varying(60) NOT NULL,
    municipio_id bigint,
    direccion character varying(200),
    lat numeric(9,6),
    lon numeric(9,6)
);


ALTER TABLE cat.cliente OWNER TO admin;

--
-- Name: cliente_cliente_id_seq; Type: SEQUENCE; Schema: cat; Owner: admin
--

CREATE SEQUENCE cat.cliente_cliente_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE cat.cliente_cliente_id_seq OWNER TO admin;

--
-- Name: cliente_cliente_id_seq; Type: SEQUENCE OWNED BY; Schema: cat; Owner: admin
--

ALTER SEQUENCE cat.cliente_cliente_id_seq OWNED BY cat.cliente.cliente_id;


--
-- Name: departamento; Type: TABLE; Schema: cat; Owner: admin
--

CREATE TABLE cat.departamento (
    departamento_id bigint NOT NULL,
    nombre character varying(80) NOT NULL
);


ALTER TABLE cat.departamento OWNER TO admin;

--
-- Name: departamento_departamento_id_seq; Type: SEQUENCE; Schema: cat; Owner: admin
--

CREATE SEQUENCE cat.departamento_departamento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE cat.departamento_departamento_id_seq OWNER TO admin;

--
-- Name: departamento_departamento_id_seq; Type: SEQUENCE OWNED BY; Schema: cat; Owner: admin
--

ALTER SEQUENCE cat.departamento_departamento_id_seq OWNED BY cat.departamento.departamento_id;


--
-- Name: municipio; Type: TABLE; Schema: cat; Owner: admin
--

CREATE TABLE cat.municipio (
    municipio_id bigint NOT NULL,
    departamento_id bigint NOT NULL,
    nombre character varying(120) NOT NULL
);


ALTER TABLE cat.municipio OWNER TO admin;

--
-- Name: municipio_municipio_id_seq; Type: SEQUENCE; Schema: cat; Owner: admin
--

CREATE SEQUENCE cat.municipio_municipio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE cat.municipio_municipio_id_seq OWNER TO admin;

--
-- Name: municipio_municipio_id_seq; Type: SEQUENCE OWNED BY; Schema: cat; Owner: admin
--

ALTER SEQUENCE cat.municipio_municipio_id_seq OWNED BY cat.municipio.municipio_id;


--
-- Name: planta; Type: TABLE; Schema: cat; Owner: admin
--

CREATE TABLE cat.planta (
    planta_id bigint NOT NULL,
    codigo_planta character varying(40) NOT NULL,
    nombre character varying(140) NOT NULL,
    municipio_id bigint NOT NULL,
    direccion character varying(200),
    lat numeric(9,6),
    lon numeric(9,6)
);


ALTER TABLE cat.planta OWNER TO admin;

--
-- Name: planta_planta_id_seq; Type: SEQUENCE; Schema: cat; Owner: admin
--

CREATE SEQUENCE cat.planta_planta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE cat.planta_planta_id_seq OWNER TO admin;

--
-- Name: planta_planta_id_seq; Type: SEQUENCE OWNED BY; Schema: cat; Owner: admin
--

ALTER SEQUENCE cat.planta_planta_id_seq OWNED BY cat.planta.planta_id;


--
-- Name: transportista; Type: TABLE; Schema: cat; Owner: admin
--

CREATE TABLE cat.transportista (
    transportista_id bigint NOT NULL,
    codigo_transp character varying(40) NOT NULL,
    nombre character varying(140) NOT NULL,
    nro_licencia character varying(60),
    estado character varying(255) DEFAULT 'DISPONIBLE'::character varying NOT NULL,
    telefono character varying(20),
    vehiculo_asignado_id bigint,
    CONSTRAINT transportista_estado_check CHECK (((estado)::text = ANY ((ARRAY['DISPONIBLE'::character varying, 'OCUPADO'::character varying, 'INACTIVO'::character varying])::text[])))
);


ALTER TABLE cat.transportista OWNER TO admin;

--
-- Name: transportista_transportista_id_seq; Type: SEQUENCE; Schema: cat; Owner: admin
--

CREATE SEQUENCE cat.transportista_transportista_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE cat.transportista_transportista_id_seq OWNER TO admin;

--
-- Name: transportista_transportista_id_seq; Type: SEQUENCE OWNED BY; Schema: cat; Owner: admin
--

ALTER SEQUENCE cat.transportista_transportista_id_seq OWNED BY cat.transportista.transportista_id;


--
-- Name: variedadpapa; Type: TABLE; Schema: cat; Owner: admin
--

CREATE TABLE cat.variedadpapa (
    variedad_id bigint NOT NULL,
    codigo_variedad character varying(40) NOT NULL,
    nombre_comercial character varying(120) NOT NULL,
    aptitud character varying(80),
    ciclo_dias_min integer,
    ciclo_dias_max integer
);


ALTER TABLE cat.variedadpapa OWNER TO admin;

--
-- Name: variedadpapa_variedad_id_seq; Type: SEQUENCE; Schema: cat; Owner: admin
--

CREATE SEQUENCE cat.variedadpapa_variedad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE cat.variedadpapa_variedad_id_seq OWNER TO admin;

--
-- Name: variedadpapa_variedad_id_seq; Type: SEQUENCE OWNED BY; Schema: cat; Owner: admin
--

ALTER SEQUENCE cat.variedadpapa_variedad_id_seq OWNED BY cat.variedadpapa.variedad_id;


--
-- Name: vehiculo; Type: TABLE; Schema: cat; Owner: admin
--

CREATE TABLE cat.vehiculo (
    vehiculo_id bigint NOT NULL,
    codigo_vehiculo character varying(20) NOT NULL,
    placa character varying(15) NOT NULL,
    marca character varying(50) NOT NULL,
    modelo character varying(50) NOT NULL,
    anio integer,
    color character varying(30),
    capacidad_t numeric(8,3) NOT NULL,
    tipo character varying(30) DEFAULT 'CAMION'::character varying NOT NULL,
    estado character varying(20) DEFAULT 'DISPONIBLE'::character varying NOT NULL,
    fecha_ultima_revision date,
    fecha_proxima_revision date,
    kilometraje integer DEFAULT 0 NOT NULL,
    vencimiento_seguro date,
    vencimiento_inspeccion date
);


ALTER TABLE cat.vehiculo OWNER TO admin;

--
-- Name: COLUMN vehiculo.capacidad_t; Type: COMMENT; Schema: cat; Owner: admin
--

COMMENT ON COLUMN cat.vehiculo.capacidad_t IS 'Capacidad en toneladas';


--
-- Name: COLUMN vehiculo.tipo; Type: COMMENT; Schema: cat; Owner: admin
--

COMMENT ON COLUMN cat.vehiculo.tipo IS 'CAMION, FURGON, REFRIGERADO, CISTERNA';


--
-- Name: COLUMN vehiculo.estado; Type: COMMENT; Schema: cat; Owner: admin
--

COMMENT ON COLUMN cat.vehiculo.estado IS 'DISPONIBLE, EN_USO, MANTENIMIENTO, FUERA_SERVICIO';


--
-- Name: vehiculo_vehiculo_id_seq; Type: SEQUENCE; Schema: cat; Owner: admin
--

CREATE SEQUENCE cat.vehiculo_vehiculo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE cat.vehiculo_vehiculo_id_seq OWNER TO admin;

--
-- Name: vehiculo_vehiculo_id_seq; Type: SEQUENCE OWNED BY; Schema: cat; Owner: admin
--

ALTER SEQUENCE cat.vehiculo_vehiculo_id_seq OWNED BY cat.vehiculo.vehiculo_id;


--
-- Name: certificado; Type: TABLE; Schema: certificacion; Owner: admin
--

CREATE TABLE certificacion.certificado (
    certificado_id bigint NOT NULL,
    codigo_certificado character varying(60) NOT NULL,
    ambito character varying(30) NOT NULL,
    area character varying(40) NOT NULL,
    vigente_desde date NOT NULL,
    vigente_hasta date,
    emisor character varying(160) NOT NULL,
    url_archivo character varying(400)
);


ALTER TABLE certificacion.certificado OWNER TO admin;

--
-- Name: certificado_certificado_id_seq; Type: SEQUENCE; Schema: certificacion; Owner: admin
--

CREATE SEQUENCE certificacion.certificado_certificado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE certificacion.certificado_certificado_id_seq OWNER TO admin;

--
-- Name: certificado_certificado_id_seq; Type: SEQUENCE OWNED BY; Schema: certificacion; Owner: admin
--

ALTER SEQUENCE certificacion.certificado_certificado_id_seq OWNED BY certificacion.certificado.certificado_id;


--
-- Name: certificadocadena; Type: TABLE; Schema: certificacion; Owner: admin
--

CREATE TABLE certificacion.certificadocadena (
    certificado_padre_id bigint NOT NULL,
    certificado_hijo_id bigint NOT NULL
);


ALTER TABLE certificacion.certificadocadena OWNER TO admin;

--
-- Name: certificadoenvio; Type: TABLE; Schema: certificacion; Owner: admin
--

CREATE TABLE certificacion.certificadoenvio (
    certificado_id bigint NOT NULL,
    envio_id bigint NOT NULL
);


ALTER TABLE certificacion.certificadoenvio OWNER TO admin;

--
-- Name: certificadoevidencia; Type: TABLE; Schema: certificacion; Owner: admin
--

CREATE TABLE certificacion.certificadoevidencia (
    evidencia_id bigint NOT NULL,
    certificado_id bigint NOT NULL,
    tipo character varying(60) NOT NULL,
    descripcion character varying(400),
    url_archivo character varying(400),
    fecha_registro timestamp(0) with time zone DEFAULT '2025-12-16 00:12:46+00'::timestamp with time zone NOT NULL
);


ALTER TABLE certificacion.certificadoevidencia OWNER TO admin;

--
-- Name: certificadoevidencia_evidencia_id_seq; Type: SEQUENCE; Schema: certificacion; Owner: admin
--

CREATE SEQUENCE certificacion.certificadoevidencia_evidencia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE certificacion.certificadoevidencia_evidencia_id_seq OWNER TO admin;

--
-- Name: certificadoevidencia_evidencia_id_seq; Type: SEQUENCE OWNED BY; Schema: certificacion; Owner: admin
--

ALTER SEQUENCE certificacion.certificadoevidencia_evidencia_id_seq OWNED BY certificacion.certificadoevidencia.evidencia_id;


--
-- Name: certificadolotecampo; Type: TABLE; Schema: certificacion; Owner: admin
--

CREATE TABLE certificacion.certificadolotecampo (
    certificado_id bigint NOT NULL,
    lote_campo_id bigint NOT NULL
);


ALTER TABLE certificacion.certificadolotecampo OWNER TO admin;

--
-- Name: certificadoloteplanta; Type: TABLE; Schema: certificacion; Owner: admin
--

CREATE TABLE certificacion.certificadoloteplanta (
    certificado_id bigint NOT NULL,
    lote_planta_id bigint NOT NULL
);


ALTER TABLE certificacion.certificadoloteplanta OWNER TO admin;

--
-- Name: certificadolotesalida; Type: TABLE; Schema: certificacion; Owner: admin
--

CREATE TABLE certificacion.certificadolotesalida (
    certificado_id bigint NOT NULL,
    lote_salida_id bigint NOT NULL
);


ALTER TABLE certificacion.certificadolotesalida OWNER TO admin;

--
-- Name: enviodetalle; Type: TABLE; Schema: logistica; Owner: admin
--

CREATE TABLE logistica.enviodetalle (
    envio_detalle_id bigint NOT NULL,
    envio_id bigint NOT NULL,
    lote_salida_id bigint NOT NULL,
    cliente_id bigint NOT NULL,
    cantidad_t numeric(12,3) NOT NULL
);


ALTER TABLE logistica.enviodetalle OWNER TO admin;

--
-- Name: loteplanta; Type: TABLE; Schema: planta; Owner: admin
--

CREATE TABLE planta.loteplanta (
    lote_planta_id bigint NOT NULL,
    codigo_lote_planta character varying(50) NOT NULL,
    planta_id bigint NOT NULL,
    fecha_inicio timestamp(0) with time zone NOT NULL,
    fecha_fin timestamp(0) with time zone,
    rendimiento_pct numeric(5,2)
);


ALTER TABLE planta.loteplanta OWNER TO admin;

--
-- Name: loteplanta_entradacampo; Type: TABLE; Schema: planta; Owner: admin
--

CREATE TABLE planta.loteplanta_entradacampo (
    lote_planta_id bigint NOT NULL,
    lote_campo_id bigint NOT NULL,
    peso_entrada_t numeric(12,3) NOT NULL
);


ALTER TABLE planta.loteplanta_entradacampo OWNER TO admin;

--
-- Name: lotesalida; Type: TABLE; Schema: planta; Owner: admin
--

CREATE TABLE planta.lotesalida (
    lote_salida_id bigint NOT NULL,
    codigo_lote_salida character varying(50) NOT NULL,
    lote_planta_id bigint NOT NULL,
    sku character varying(120) NOT NULL,
    peso_t numeric(12,3) NOT NULL,
    fecha_empaque timestamp(0) with time zone NOT NULL
);


ALTER TABLE planta.lotesalida OWNER TO admin;

--
-- Name: v_certificados_por_lote_salida; Type: VIEW; Schema: certificacion; Owner: admin
--

CREATE VIEW certificacion.v_certificados_por_lote_salida AS
 SELECT ls.codigo_lote_salida,
    cert.codigo_certificado,
    cert.ambito,
    cert.area,
    cert.vigente_desde,
    cert.vigente_hasta,
    cert.emisor
   FROM ((planta.lotesalida ls
     JOIN certificacion.certificadolotesalida cls ON ((cls.lote_salida_id = ls.lote_salida_id)))
     JOIN certificacion.certificado cert ON ((cert.certificado_id = cls.certificado_id)))
UNION ALL
 SELECT ls.codigo_lote_salida,
    cert.codigo_certificado,
    cert.ambito,
    cert.area,
    cert.vigente_desde,
    cert.vigente_hasta,
    cert.emisor
   FROM (((planta.lotesalida ls
     JOIN planta.loteplanta lp ON ((lp.lote_planta_id = ls.lote_planta_id)))
     JOIN certificacion.certificadoloteplanta clp ON ((clp.lote_planta_id = lp.lote_planta_id)))
     JOIN certificacion.certificado cert ON ((cert.certificado_id = clp.certificado_id)))
UNION ALL
 SELECT ls.codigo_lote_salida,
    cert.codigo_certificado,
    cert.ambito,
    cert.area,
    cert.vigente_desde,
    cert.vigente_hasta,
    cert.emisor
   FROM ((((planta.lotesalida ls
     JOIN planta.loteplanta lp ON ((lp.lote_planta_id = ls.lote_planta_id)))
     JOIN planta.loteplanta_entradacampo lec ON ((lec.lote_planta_id = lp.lote_planta_id)))
     JOIN certificacion.certificadolotecampo clc ON ((clc.lote_campo_id = lec.lote_campo_id)))
     JOIN certificacion.certificado cert ON ((cert.certificado_id = clc.certificado_id)))
UNION ALL
 SELECT ls.codigo_lote_salida,
    cert.codigo_certificado,
    cert.ambito,
    cert.area,
    cert.vigente_desde,
    cert.vigente_hasta,
    cert.emisor
   FROM (((planta.lotesalida ls
     JOIN logistica.enviodetalle ed ON ((ed.lote_salida_id = ls.lote_salida_id)))
     JOIN certificacion.certificadoenvio ce ON ((ce.envio_id = ed.envio_id)))
     JOIN certificacion.certificado cert ON ((cert.certificado_id = ce.certificado_id)));


ALTER VIEW certificacion.v_certificados_por_lote_salida OWNER TO admin;

--
-- Name: pedido; Type: TABLE; Schema: comercial; Owner: admin
--

CREATE TABLE comercial.pedido (
    pedido_id bigint NOT NULL,
    codigo_pedido character varying(40) NOT NULL,
    cliente_id bigint NOT NULL,
    almacen_id bigint,
    fecha_pedido timestamp(0) with time zone NOT NULL,
    estado character varying(20) DEFAULT 'ABIERTO'::character varying NOT NULL
);


ALTER TABLE comercial.pedido OWNER TO admin;

--
-- Name: pedido_pedido_id_seq; Type: SEQUENCE; Schema: comercial; Owner: admin
--

CREATE SEQUENCE comercial.pedido_pedido_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE comercial.pedido_pedido_id_seq OWNER TO admin;

--
-- Name: pedido_pedido_id_seq; Type: SEQUENCE OWNED BY; Schema: comercial; Owner: admin
--

ALTER SEQUENCE comercial.pedido_pedido_id_seq OWNED BY comercial.pedido.pedido_id;


--
-- Name: pedidodetalle; Type: TABLE; Schema: comercial; Owner: admin
--

CREATE TABLE comercial.pedidodetalle (
    pedido_detalle_id bigint NOT NULL,
    pedido_id bigint NOT NULL,
    sku character varying(120) NOT NULL,
    cantidad_t numeric(12,3) NOT NULL,
    precio_unit_usd numeric(12,2) NOT NULL
);


ALTER TABLE comercial.pedidodetalle OWNER TO admin;

--
-- Name: pedidodetalle_pedido_detalle_id_seq; Type: SEQUENCE; Schema: comercial; Owner: admin
--

CREATE SEQUENCE comercial.pedidodetalle_pedido_detalle_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE comercial.pedidodetalle_pedido_detalle_id_seq OWNER TO admin;

--
-- Name: pedidodetalle_pedido_detalle_id_seq; Type: SEQUENCE OWNED BY; Schema: comercial; Owner: admin
--

ALTER SEQUENCE comercial.pedidodetalle_pedido_detalle_id_seq OWNED BY comercial.pedidodetalle.pedido_detalle_id;


--
-- Name: envio; Type: TABLE; Schema: logistica; Owner: admin
--

CREATE TABLE logistica.envio (
    envio_id bigint NOT NULL,
    codigo_envio character varying(40) NOT NULL,
    ruta_id bigint,
    transportista_id bigint,
    almacen_origen_id bigint,
    fecha_salida timestamp(0) with time zone NOT NULL,
    fecha_llegada timestamp(0) with time zone,
    temp_min_c numeric(6,2),
    temp_max_c numeric(6,2),
    estado character varying(20) DEFAULT 'EN_RUTA'::character varying NOT NULL,
    vehiculo_id bigint
);


ALTER TABLE logistica.envio OWNER TO admin;

--
-- Name: envio_envio_id_seq; Type: SEQUENCE; Schema: logistica; Owner: admin
--

CREATE SEQUENCE logistica.envio_envio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE logistica.envio_envio_id_seq OWNER TO admin;

--
-- Name: envio_envio_id_seq; Type: SEQUENCE OWNED BY; Schema: logistica; Owner: admin
--

ALTER SEQUENCE logistica.envio_envio_id_seq OWNED BY logistica.envio.envio_id;


--
-- Name: enviodetalle_envio_detalle_id_seq; Type: SEQUENCE; Schema: logistica; Owner: admin
--

CREATE SEQUENCE logistica.enviodetalle_envio_detalle_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE logistica.enviodetalle_envio_detalle_id_seq OWNER TO admin;

--
-- Name: enviodetalle_envio_detalle_id_seq; Type: SEQUENCE OWNED BY; Schema: logistica; Owner: admin
--

ALTER SEQUENCE logistica.enviodetalle_envio_detalle_id_seq OWNED BY logistica.enviodetalle.envio_detalle_id;


--
-- Name: enviodetallealmacen; Type: TABLE; Schema: logistica; Owner: admin
--

CREATE TABLE logistica.enviodetallealmacen (
    envio_detalle_alm_id bigint NOT NULL,
    envio_id bigint NOT NULL,
    lote_salida_id bigint NOT NULL,
    almacen_id bigint NOT NULL,
    cantidad_t numeric(12,3) NOT NULL
);


ALTER TABLE logistica.enviodetallealmacen OWNER TO admin;

--
-- Name: enviodetallealmacen_envio_detalle_alm_id_seq; Type: SEQUENCE; Schema: logistica; Owner: admin
--

CREATE SEQUENCE logistica.enviodetallealmacen_envio_detalle_alm_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE logistica.enviodetallealmacen_envio_detalle_alm_id_seq OWNER TO admin;

--
-- Name: enviodetallealmacen_envio_detalle_alm_id_seq; Type: SEQUENCE OWNED BY; Schema: logistica; Owner: admin
--

ALTER SEQUENCE logistica.enviodetallealmacen_envio_detalle_alm_id_seq OWNED BY logistica.enviodetallealmacen.envio_detalle_alm_id;


--
-- Name: orden_envio; Type: TABLE; Schema: logistica; Owner: admin
--

CREATE TABLE logistica.orden_envio (
    orden_envio_id bigint NOT NULL,
    codigo_orden character varying(30) NOT NULL,
    planta_origen_id bigint NOT NULL,
    lote_salida_id bigint NOT NULL,
    almacen_destino_id bigint NOT NULL,
    zona_destino_id bigint,
    transportista_id bigint,
    vehiculo_id bigint,
    cantidad_t numeric(12,3) NOT NULL,
    estado character varying(30) DEFAULT 'PENDIENTE'::character varying NOT NULL,
    fecha_creacion timestamp(0) with time zone DEFAULT '2025-12-16 00:12:47+00'::timestamp with time zone NOT NULL,
    fecha_programada date,
    fecha_asignacion timestamp(0) with time zone,
    fecha_salida timestamp(0) with time zone,
    fecha_llegada timestamp(0) with time zone,
    prioridad character varying(10) DEFAULT 'NORMAL'::character varying NOT NULL,
    observaciones text,
    creado_por bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE logistica.orden_envio OWNER TO admin;

--
-- Name: COLUMN orden_envio.estado; Type: COMMENT; Schema: logistica; Owner: admin
--

COMMENT ON COLUMN logistica.orden_envio.estado IS 'PENDIENTE, CONDUCTOR_ASIGNADO, EN_CARGA, EN_RUTA, ENTREGADO, CANCELADO';


--
-- Name: COLUMN orden_envio.prioridad; Type: COMMENT; Schema: logistica; Owner: admin
--

COMMENT ON COLUMN logistica.orden_envio.prioridad IS 'URGENTE, NORMAL, BAJA';


--
-- Name: orden_envio_orden_envio_id_seq; Type: SEQUENCE; Schema: logistica; Owner: admin
--

CREATE SEQUENCE logistica.orden_envio_orden_envio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE logistica.orden_envio_orden_envio_id_seq OWNER TO admin;

--
-- Name: orden_envio_orden_envio_id_seq; Type: SEQUENCE OWNED BY; Schema: logistica; Owner: admin
--

ALTER SEQUENCE logistica.orden_envio_orden_envio_id_seq OWNED BY logistica.orden_envio.orden_envio_id;


--
-- Name: ruta; Type: TABLE; Schema: logistica; Owner: admin
--

CREATE TABLE logistica.ruta (
    ruta_id bigint NOT NULL,
    codigo_ruta character varying(40) NOT NULL,
    descripcion character varying(160)
);


ALTER TABLE logistica.ruta OWNER TO admin;

--
-- Name: ruta_ruta_id_seq; Type: SEQUENCE; Schema: logistica; Owner: admin
--

CREATE SEQUENCE logistica.ruta_ruta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE logistica.ruta_ruta_id_seq OWNER TO admin;

--
-- Name: ruta_ruta_id_seq; Type: SEQUENCE OWNED BY; Schema: logistica; Owner: admin
--

ALTER SEQUENCE logistica.ruta_ruta_id_seq OWNED BY logistica.ruta.ruta_id;


--
-- Name: rutapunto; Type: TABLE; Schema: logistica; Owner: admin
--

CREATE TABLE logistica.rutapunto (
    ruta_id bigint NOT NULL,
    orden integer NOT NULL,
    cliente_id bigint NOT NULL
);


ALTER TABLE logistica.rutapunto OWNER TO admin;

--
-- Name: controlproceso; Type: TABLE; Schema: planta; Owner: admin
--

CREATE TABLE planta.controlproceso (
    control_id bigint NOT NULL,
    lote_planta_id bigint NOT NULL,
    etapa character varying(40) NOT NULL,
    fecha_hora timestamp(0) with time zone NOT NULL,
    parametro character varying(60) NOT NULL,
    valor_num numeric(18,6),
    valor_texto character varying(200),
    estado character varying(20) DEFAULT 'OK'::character varying NOT NULL
);


ALTER TABLE planta.controlproceso OWNER TO admin;

--
-- Name: controlproceso_control_id_seq; Type: SEQUENCE; Schema: planta; Owner: admin
--

CREATE SEQUENCE planta.controlproceso_control_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE planta.controlproceso_control_id_seq OWNER TO admin;

--
-- Name: controlproceso_control_id_seq; Type: SEQUENCE OWNED BY; Schema: planta; Owner: admin
--

ALTER SEQUENCE planta.controlproceso_control_id_seq OWNED BY planta.controlproceso.control_id;


--
-- Name: loteplanta_lote_planta_id_seq; Type: SEQUENCE; Schema: planta; Owner: admin
--

CREATE SEQUENCE planta.loteplanta_lote_planta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE planta.loteplanta_lote_planta_id_seq OWNER TO admin;

--
-- Name: loteplanta_lote_planta_id_seq; Type: SEQUENCE OWNED BY; Schema: planta; Owner: admin
--

ALTER SEQUENCE planta.loteplanta_lote_planta_id_seq OWNED BY planta.loteplanta.lote_planta_id;


--
-- Name: lotesalida_lote_salida_id_seq; Type: SEQUENCE; Schema: planta; Owner: admin
--

CREATE SEQUENCE planta.lotesalida_lote_salida_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE planta.lotesalida_lote_salida_id_seq OWNER TO admin;

--
-- Name: lotesalida_lote_salida_id_seq; Type: SEQUENCE OWNED BY; Schema: planta; Owner: admin
--

ALTER SEQUENCE planta.lotesalida_lote_salida_id_seq OWNED BY planta.lotesalida.lote_salida_id;


--
-- Name: v_trazabilidad_lote_salida; Type: VIEW; Schema: planta; Owner: admin
--

CREATE VIEW planta.v_trazabilidad_lote_salida AS
 SELECT ls.codigo_lote_salida,
    lp.codigo_lote_planta,
    p.codigo_planta,
    ( SELECT string_agg((lc2.codigo_lote_campo)::text, ', '::text ORDER BY (lc2.codigo_lote_campo)::text) AS string_agg
           FROM (planta.loteplanta_entradacampo lec2
             JOIN campo.lotecampo lc2 ON ((lc2.lote_campo_id = lec2.lote_campo_id)))
          WHERE (lec2.lote_planta_id = lp.lote_planta_id)) AS lotes_campo,
    ( SELECT min((ev2.codigo_envio)::text) AS min
           FROM (logistica.enviodetalle ed2
             JOIN logistica.envio ev2 ON ((ev2.envio_id = ed2.envio_id)))
          WHERE (ed2.lote_salida_id = ls.lote_salida_id)) AS primer_envio,
    ( SELECT string_agg(DISTINCT (c2.codigo_cliente)::text, ', '::text ORDER BY (c2.codigo_cliente)::text) AS string_agg
           FROM (logistica.enviodetalle ed2
             JOIN cat.cliente c2 ON ((c2.cliente_id = ed2.cliente_id)))
          WHERE (ed2.lote_salida_id = ls.lote_salida_id)) AS clientes,
    ( SELECT min(ev2.temp_min_c) AS min
           FROM (logistica.enviodetalle ed2
             JOIN logistica.envio ev2 ON ((ev2.envio_id = ed2.envio_id)))
          WHERE (ed2.lote_salida_id = ls.lote_salida_id)) AS envio_temp_min_c,
    ( SELECT max(ev2.temp_max_c) AS max
           FROM (logistica.enviodetalle ed2
             JOIN logistica.envio ev2 ON ((ev2.envio_id = ed2.envio_id)))
          WHERE (ed2.lote_salida_id = ls.lote_salida_id)) AS envio_temp_max_c,
    ls.peso_t,
    lp.rendimiento_pct
   FROM ((planta.lotesalida ls
     JOIN planta.loteplanta lp ON ((lp.lote_planta_id = ls.lote_planta_id)))
     JOIN cat.planta p ON ((p.planta_id = lp.planta_id)));


ALTER VIEW planta.v_trazabilidad_lote_salida OWNER TO admin;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO admin;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO admin;

--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO admin;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: admin
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO admin;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: admin
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO admin;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO admin;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: admin
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO admin;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: admin
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO admin;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: admin
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO admin;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: admin
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO admin;

--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO admin;

--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO admin;

--
-- Name: permissions; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO admin;

--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: admin
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permissions_id_seq OWNER TO admin;

--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: admin
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


ALTER TABLE public.role_has_permissions OWNER TO admin;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO admin;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: admin
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO admin;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: admin
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO admin;

--
-- Name: users; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO admin;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: admin
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO admin;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: admin
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: movimiento mov_id; Type: DEFAULT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.movimiento ALTER COLUMN mov_id SET DEFAULT nextval('almacen.movimiento_mov_id_seq'::regclass);


--
-- Name: pedido pedido_almacen_id; Type: DEFAULT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.pedido ALTER COLUMN pedido_almacen_id SET DEFAULT nextval('almacen.pedido_pedido_almacen_id_seq'::regclass);


--
-- Name: pedidodetalle pedido_detalle_id; Type: DEFAULT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.pedidodetalle ALTER COLUMN pedido_detalle_id SET DEFAULT nextval('almacen.pedidodetalle_pedido_detalle_id_seq'::regclass);


--
-- Name: recepcion recepcion_id; Type: DEFAULT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.recepcion ALTER COLUMN recepcion_id SET DEFAULT nextval('almacen.recepcion_recepcion_id_seq'::regclass);


--
-- Name: ubicacion ubicacion_id; Type: DEFAULT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.ubicacion ALTER COLUMN ubicacion_id SET DEFAULT nextval('almacen.ubicacion_ubicacion_id_seq'::regclass);


--
-- Name: zona zona_id; Type: DEFAULT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.zona ALTER COLUMN zona_id SET DEFAULT nextval('almacen.zona_zona_id_seq'::regclass);


--
-- Name: asignacion_conductor asignacion_id; Type: DEFAULT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.asignacion_conductor ALTER COLUMN asignacion_id SET DEFAULT nextval('campo.asignacion_conductor_asignacion_id_seq'::regclass);


--
-- Name: lotecampo lote_campo_id; Type: DEFAULT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.lotecampo ALTER COLUMN lote_campo_id SET DEFAULT nextval('campo.lotecampo_lote_campo_id_seq'::regclass);


--
-- Name: productor productor_id; Type: DEFAULT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.productor ALTER COLUMN productor_id SET DEFAULT nextval('campo.productor_productor_id_seq'::regclass);


--
-- Name: sensorlectura lectura_id; Type: DEFAULT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.sensorlectura ALTER COLUMN lectura_id SET DEFAULT nextval('campo.sensorlectura_lectura_id_seq'::regclass);


--
-- Name: solicitud_produccion solicitud_id; Type: DEFAULT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.solicitud_produccion ALTER COLUMN solicitud_id SET DEFAULT nextval('campo.solicitud_produccion_solicitud_id_seq'::regclass);


--
-- Name: almacen almacen_id; Type: DEFAULT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.almacen ALTER COLUMN almacen_id SET DEFAULT nextval('cat.almacen_almacen_id_seq'::regclass);


--
-- Name: cliente cliente_id; Type: DEFAULT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.cliente ALTER COLUMN cliente_id SET DEFAULT nextval('cat.cliente_cliente_id_seq'::regclass);


--
-- Name: departamento departamento_id; Type: DEFAULT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.departamento ALTER COLUMN departamento_id SET DEFAULT nextval('cat.departamento_departamento_id_seq'::regclass);


--
-- Name: municipio municipio_id; Type: DEFAULT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.municipio ALTER COLUMN municipio_id SET DEFAULT nextval('cat.municipio_municipio_id_seq'::regclass);


--
-- Name: planta planta_id; Type: DEFAULT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.planta ALTER COLUMN planta_id SET DEFAULT nextval('cat.planta_planta_id_seq'::regclass);


--
-- Name: transportista transportista_id; Type: DEFAULT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.transportista ALTER COLUMN transportista_id SET DEFAULT nextval('cat.transportista_transportista_id_seq'::regclass);


--
-- Name: variedadpapa variedad_id; Type: DEFAULT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.variedadpapa ALTER COLUMN variedad_id SET DEFAULT nextval('cat.variedadpapa_variedad_id_seq'::regclass);


--
-- Name: vehiculo vehiculo_id; Type: DEFAULT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.vehiculo ALTER COLUMN vehiculo_id SET DEFAULT nextval('cat.vehiculo_vehiculo_id_seq'::regclass);


--
-- Name: certificado certificado_id; Type: DEFAULT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificado ALTER COLUMN certificado_id SET DEFAULT nextval('certificacion.certificado_certificado_id_seq'::regclass);


--
-- Name: certificadoevidencia evidencia_id; Type: DEFAULT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadoevidencia ALTER COLUMN evidencia_id SET DEFAULT nextval('certificacion.certificadoevidencia_evidencia_id_seq'::regclass);


--
-- Name: pedido pedido_id; Type: DEFAULT; Schema: comercial; Owner: admin
--

ALTER TABLE ONLY comercial.pedido ALTER COLUMN pedido_id SET DEFAULT nextval('comercial.pedido_pedido_id_seq'::regclass);


--
-- Name: pedidodetalle pedido_detalle_id; Type: DEFAULT; Schema: comercial; Owner: admin
--

ALTER TABLE ONLY comercial.pedidodetalle ALTER COLUMN pedido_detalle_id SET DEFAULT nextval('comercial.pedidodetalle_pedido_detalle_id_seq'::regclass);


--
-- Name: envio envio_id; Type: DEFAULT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.envio ALTER COLUMN envio_id SET DEFAULT nextval('logistica.envio_envio_id_seq'::regclass);


--
-- Name: enviodetalle envio_detalle_id; Type: DEFAULT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetalle ALTER COLUMN envio_detalle_id SET DEFAULT nextval('logistica.enviodetalle_envio_detalle_id_seq'::regclass);


--
-- Name: enviodetallealmacen envio_detalle_alm_id; Type: DEFAULT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetallealmacen ALTER COLUMN envio_detalle_alm_id SET DEFAULT nextval('logistica.enviodetallealmacen_envio_detalle_alm_id_seq'::regclass);


--
-- Name: orden_envio orden_envio_id; Type: DEFAULT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio ALTER COLUMN orden_envio_id SET DEFAULT nextval('logistica.orden_envio_orden_envio_id_seq'::regclass);


--
-- Name: ruta ruta_id; Type: DEFAULT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.ruta ALTER COLUMN ruta_id SET DEFAULT nextval('logistica.ruta_ruta_id_seq'::regclass);


--
-- Name: controlproceso control_id; Type: DEFAULT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.controlproceso ALTER COLUMN control_id SET DEFAULT nextval('planta.controlproceso_control_id_seq'::regclass);


--
-- Name: loteplanta lote_planta_id; Type: DEFAULT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.loteplanta ALTER COLUMN lote_planta_id SET DEFAULT nextval('planta.loteplanta_lote_planta_id_seq'::regclass);


--
-- Name: lotesalida lote_salida_id; Type: DEFAULT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.lotesalida ALTER COLUMN lote_salida_id SET DEFAULT nextval('planta.lotesalida_lote_salida_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: inventario; Type: TABLE DATA; Schema: almacen; Owner: admin
--

COPY almacen.inventario (almacen_id, lote_salida_id, sku, cantidad_t) FROM stdin;
1	19	Papa lavada 25kg	5.000
\.


--
-- Data for Name: movimiento; Type: TABLE DATA; Schema: almacen; Owner: admin
--

COPY almacen.movimiento (mov_id, almacen_id, lote_salida_id, tipo, cantidad_t, fecha_mov, referencia, detalle) FROM stdin;
5	1	19	ENTRADA	5.000	2025-12-14 04:23:13+00	REF-INIT-001	Entrada inicial por ajuste
\.


--
-- Data for Name: pedido; Type: TABLE DATA; Schema: almacen; Owner: admin
--

COPY almacen.pedido (pedido_almacen_id, codigo_pedido, almacen_id, fecha_pedido, estado) FROM stdin;
\.


--
-- Data for Name: pedidodetalle; Type: TABLE DATA; Schema: almacen; Owner: admin
--

COPY almacen.pedidodetalle (pedido_detalle_id, pedido_almacen_id, sku, cantidad_t, lote_salida_id) FROM stdin;
\.


--
-- Data for Name: recepcion; Type: TABLE DATA; Schema: almacen; Owner: admin
--

COPY almacen.recepcion (recepcion_id, envio_id, almacen_id, fecha_recepcion, observacion, orden_envio_id, zona_id, ubicacion_id, cantidad_esperada_t, cantidad_recibida_t, diferencia_t, estado_producto, temperatura_llegada_c, recibido_por, firma_conductor) FROM stdin;
7	75	1	2025-12-16 04:24:12+00	Recepción conforme, sin daños visibles	\N	\N	\N	\N	\N	\N	BUENO	\N	\N	\N
\.


--
-- Data for Name: ubicacion; Type: TABLE DATA; Schema: almacen; Owner: admin
--

COPY almacen.ubicacion (ubicacion_id, zona_id, codigo_ubicacion, pasillo, rack, nivel, capacidad_t, ocupado, lote_salida_id, cantidad_almacenada_t, fecha_ocupacion, refrigerado, acceso_montacargas, observaciones) FROM stdin;
\.


--
-- Data for Name: zona; Type: TABLE DATA; Schema: almacen; Owner: admin
--

COPY almacen.zona (zona_id, almacen_id, codigo_zona, nombre, descripcion, tipo, capacidad_t, ocupacion_actual_t, temperatura_objetivo_c, humedad_objetivo_pct, estado, num_pasillos, num_racks_por_pasillo, num_niveles, created_at) FROM stdin;
\.


--
-- Data for Name: asignacion_conductor; Type: TABLE DATA; Schema: campo; Owner: admin
--

COPY campo.asignacion_conductor (asignacion_id, solicitud_id, transportista_id, fecha_asignacion, estado, fecha_inicio_ruta, fecha_completado, observaciones, created_at, updated_at) FROM stdin;
1	1	1	2025-12-16 00:15:51	ASIGNADO	\N	\N	\N	2025-12-16 00:15:51	2025-12-16 00:15:51
\.


--
-- Data for Name: lotecampo; Type: TABLE DATA; Schema: campo; Owner: admin
--

COPY campo.lotecampo (lote_campo_id, codigo_lote_campo, productor_id, variedad_id, superficie_ha, fecha_siembra, fecha_cosecha, humedad_suelo_pct) FROM stdin;
60	LC-HIST-25-709	1	3	7.00	2025-03-19	2025-07-17	\N
61	LC-HIST-26-477	1	4	5.00	2025-06-25	2025-10-23	\N
62	LC-HIST-27-480	2	4	5.00	2025-05-21	2025-09-19	\N
63	LC-HIST-28-868	1	4	2.00	2025-05-18	2025-09-16	\N
64	LC-HIST-29-128	2	4	2.00	2025-03-28	2025-07-26	\N
65	LC-HIST-30-585	4	2	10.00	2025-05-17	2025-09-15	\N
66	LC-HIST-31-898	2	2	10.00	2025-08-03	2025-12-01	\N
67	LC-HIST-32-739	2	2	2.00	2025-06-23	2025-10-21	\N
68	LC-HIST-33-631	4	3	7.00	2025-05-20	2025-09-18	\N
69	LC-HIST-34-550	3	1	2.00	2025-06-15	2025-10-13	\N
70	LC-HIST-35-948	2	2	2.00	2025-02-19	2025-06-17	\N
71	LC-HIST-36-765	2	1	6.00	2025-08-02	2025-11-30	\N
72	LC-HIST-37-341	1	1	5.00	2025-03-18	2025-07-16	\N
73	LC-HIST-38-881	2	1	7.00	2025-06-22	2025-10-20	\N
74	LC-HIST-39-140	4	3	2.00	2025-03-01	2025-06-29	\N
115	LC-HIST-35-153	3	3	5.00	2025-05-07	2025-09-05	\N
116	LC-HIST-36-670	3	4	4.00	2025-05-13	2025-09-11	\N
117	LC-HIST-37-244	4	3	6.00	2025-05-04	2025-09-02	\N
80	LC-HIST-0-158	2	3	9.00	2025-04-11	2025-08-09	\N
81	LC-HIST-1-942	2	4	2.00	2025-03-19	2025-07-17	\N
35	LC-HIST-0-542	1	3	4.00	2025-03-25	2025-07-23	\N
36	LC-HIST-1-687	2	3	5.00	2025-05-18	2025-09-16	\N
37	LC-HIST-2-307	1	3	2.00	2025-05-17	2025-09-15	\N
38	LC-HIST-3-894	3	3	9.00	2025-07-26	2025-11-24	\N
39	LC-HIST-4-235	2	1	5.00	2025-06-16	2025-10-14	\N
40	LC-HIST-5-490	1	4	8.00	2025-03-16	2025-07-14	\N
41	LC-HIST-6-175	1	1	9.00	2025-03-19	2025-07-17	\N
42	LC-HIST-7-802	3	2	10.00	2025-08-04	2025-12-02	\N
43	LC-HIST-8-388	4	4	4.00	2025-07-12	2025-11-10	\N
44	LC-HIST-9-511	4	3	3.00	2025-04-24	2025-08-22	\N
45	LC-HIST-10-152	3	2	2.00	2025-03-05	2025-07-03	\N
46	LC-HIST-11-149	2	3	4.00	2025-06-18	2025-10-16	\N
47	LC-HIST-12-926	4	2	4.00	2025-06-02	2025-09-30	\N
48	LC-HIST-13-585	1	4	4.00	2025-06-14	2025-10-12	\N
49	LC-HIST-14-382	2	3	5.00	2025-03-04	2025-07-02	\N
50	LC-HIST-15-609	2	2	4.00	2025-08-08	2025-12-06	\N
51	LC-HIST-16-519	4	1	2.00	2025-04-07	2025-08-05	\N
52	LC-HIST-17-476	2	4	10.00	2025-06-02	2025-09-30	\N
53	LC-HIST-18-211	1	1	2.00	2025-02-23	2025-06-21	\N
54	LC-HIST-19-302	4	2	3.00	2025-02-28	2025-06-26	\N
55	LC-HIST-20-621	4	2	2.00	2025-07-27	2025-11-25	\N
56	LC-HIST-21-330	1	1	4.00	2025-03-19	2025-07-17	\N
57	LC-HIST-22-793	3	1	4.00	2025-03-17	2025-07-15	\N
58	LC-HIST-23-292	1	2	7.00	2025-05-06	2025-09-04	\N
59	LC-HIST-24-563	1	2	6.00	2025-04-19	2025-08-17	\N
82	LC-HIST-2-112	1	2	9.00	2025-05-04	2025-09-02	\N
83	LC-HIST-3-866	3	3	5.00	2025-02-19	2025-06-17	\N
84	LC-HIST-4-888	3	2	3.00	2025-07-29	2025-11-27	\N
85	LC-HIST-5-520	3	2	9.00	2025-03-02	2025-06-30	\N
86	LC-HIST-6-943	3	4	2.00	2025-02-27	2025-06-25	\N
87	LC-HIST-7-290	3	2	4.00	2025-04-12	2025-08-10	\N
88	LC-HIST-8-799	2	4	10.00	2025-08-11	2025-12-09	\N
89	LC-HIST-9-128	1	2	4.00	2025-05-01	2025-08-30	\N
90	LC-HIST-10-562	2	4	10.00	2025-07-01	2025-10-30	\N
91	LC-HIST-11-400	2	4	2.00	2025-04-28	2025-08-26	\N
92	LC-HIST-12-506	2	3	4.00	2025-07-12	2025-11-10	\N
93	LC-HIST-13-761	4	2	7.00	2025-06-08	2025-10-06	\N
94	LC-HIST-14-728	2	1	5.00	2025-02-24	2025-06-22	\N
95	LC-HIST-15-274	2	1	3.00	2025-04-02	2025-07-31	\N
96	LC-HIST-16-186	1	4	6.00	2025-05-22	2025-09-20	\N
97	LC-HIST-17-886	3	3	10.00	2025-05-26	2025-09-24	\N
98	LC-HIST-18-350	2	3	9.00	2025-02-20	2025-06-18	\N
99	LC-HIST-19-980	1	2	5.00	2025-03-01	2025-06-29	\N
100	LC-HIST-20-232	1	1	4.00	2025-04-06	2025-08-04	\N
101	LC-HIST-21-756	4	1	6.00	2025-02-21	2025-06-19	\N
102	LC-HIST-22-598	1	3	3.00	2025-03-16	2025-07-14	\N
103	LC-HIST-23-454	4	1	5.00	2025-05-17	2025-09-15	\N
104	LC-HIST-24-745	1	3	10.00	2025-05-28	2025-09-26	\N
105	LC-HIST-25-618	1	1	10.00	2025-04-15	2025-08-13	\N
106	LC-HIST-26-283	1	1	4.00	2025-08-13	2025-12-11	\N
107	LC-HIST-27-781	4	2	7.00	2025-07-12	2025-11-10	\N
108	LC-HIST-28-277	4	2	9.00	2025-06-06	2025-10-04	\N
109	LC-HIST-29-386	4	3	4.00	2025-05-12	2025-09-10	\N
110	LC-HIST-30-383	2	3	6.00	2025-06-28	2025-10-26	\N
111	LC-HIST-31-694	4	2	2.00	2025-05-01	2025-08-30	\N
112	LC-HIST-32-170	2	2	10.00	2025-05-26	2025-09-24	\N
113	LC-HIST-33-348	4	2	10.00	2025-07-12	2025-11-10	\N
114	LC-HIST-34-846	2	2	10.00	2025-06-26	2025-10-24	\N
118	LC-HIST-38-329	4	4	10.00	2025-06-13	2025-10-11	\N
119	LC-HIST-39-387	3	1	7.00	2025-05-13	2025-09-11	\N
123	LC-2024-002	1	1	3.80	2024-08-15	2024-11-28	\N
124	LC-2024-003	1	1	4.50	2024-09-01	2024-12-01	\N
125	LC-HIST-0-919	4	1	5.00	2025-03-14	2025-07-12	\N
122	LC-2024-001	1	1	5.20	2024-08-10	2024-11-25	\N
126	LC-HIST-1-120	3	3	4.00	2025-05-22	2025-09-20	\N
127	LC-HIST-2-661	4	4	5.00	2025-06-17	2025-10-15	\N
128	LC-HIST-3-840	1	4	2.00	2025-08-05	2025-12-03	\N
129	LC-HIST-4-924	2	4	6.00	2025-05-13	2025-09-11	\N
130	LC-HIST-5-717	1	3	2.00	2025-04-20	2025-08-18	\N
131	LC-HIST-6-396	1	3	6.00	2025-05-22	2025-09-20	\N
132	LC-HIST-7-744	1	2	10.00	2025-07-19	2025-11-17	\N
133	LC-HIST-8-721	2	1	10.00	2025-02-24	2025-06-22	\N
134	LC-HIST-9-121	1	3	8.00	2025-05-09	2025-09-07	\N
135	LC-HIST-10-411	2	4	4.00	2025-03-01	2025-06-27	\N
136	LC-HIST-11-460	4	3	9.00	2025-06-06	2025-10-04	\N
137	LC-HIST-12-725	4	3	7.00	2025-07-21	2025-11-19	\N
138	LC-HIST-13-977	2	3	9.00	2025-02-24	2025-06-22	\N
139	LC-HIST-14-419	3	2	6.00	2025-05-08	2025-09-06	\N
140	LC-HIST-15-921	4	2	6.00	2025-04-10	2025-08-08	\N
141	LC-HIST-16-827	2	2	4.00	2025-08-10	2025-12-08	\N
142	LC-HIST-17-158	4	1	10.00	2025-04-09	2025-08-07	\N
143	LC-HIST-18-781	2	2	2.00	2025-07-01	2025-10-29	\N
144	LC-HIST-19-373	4	2	7.00	2025-07-14	2025-11-12	\N
145	LC-HIST-20-813	2	3	8.00	2025-08-02	2025-11-30	\N
146	LC-HIST-21-768	2	4	4.00	2025-02-20	2025-06-18	\N
147	LC-HIST-22-243	4	2	3.00	2025-07-15	2025-11-13	\N
148	LC-HIST-23-888	4	4	4.00	2025-04-03	2025-08-01	\N
149	LC-HIST-24-528	4	4	5.00	2025-06-20	2025-10-18	\N
150	LC-HIST-25-688	2	4	3.00	2025-07-28	2025-11-26	\N
151	LC-HIST-26-195	3	2	2.00	2025-06-18	2025-10-16	\N
152	LC-HIST-27-347	3	2	6.00	2025-05-27	2025-09-25	\N
153	LC-HIST-28-617	1	1	2.00	2025-02-19	2025-06-17	\N
154	LC-HIST-29-847	2	3	5.00	2025-03-24	2025-07-22	\N
155	LC-HIST-30-938	1	1	10.00	2025-07-20	2025-11-18	\N
156	LC-HIST-31-298	4	2	2.00	2025-05-23	2025-09-21	\N
157	LC-HIST-32-816	1	4	3.00	2025-08-08	2025-12-06	\N
158	LC-HIST-33-837	4	4	5.00	2025-05-23	2025-09-21	\N
159	LC-HIST-34-592	2	4	5.00	2025-03-26	2025-07-24	\N
160	LC-HIST-35-290	4	3	5.00	2025-05-22	2025-09-20	\N
161	LC-HIST-36-910	3	1	4.00	2025-02-28	2025-06-26	\N
162	LC-HIST-37-581	2	4	4.00	2025-05-09	2025-09-07	\N
163	LC-HIST-38-151	4	1	2.00	2025-03-20	2025-07-18	\N
164	LC-HIST-39-719	3	1	3.00	2025-04-19	2025-08-17	\N
\.


--
-- Data for Name: productor; Type: TABLE DATA; Schema: campo; Owner: admin
--

COPY campo.productor (productor_id, codigo_productor, nombre, municipio_id, telefono) FROM stdin;
1	PROD-001	Juan Pérez Mamani	5	71234567
2	PROD-002	María González Quispe	5	72345678
3	PROD-003	Pedro Condori	6	73456789
4	PROD-004	Ana Flores	3	74567890
\.


--
-- Data for Name: sensorlectura; Type: TABLE DATA; Schema: campo; Owner: admin
--

COPY campo.sensorlectura (lectura_id, lote_campo_id, fecha_hora, tipo, valor_num, valor_texto) FROM stdin;
91	60	2025-12-16 04:23:12+00	HUMEDAD_SUELO	62.000000	\N
92	60	2025-12-15 04:23:12+00	HUMEDAD_SUELO	61.000000	\N
93	60	2025-12-14 04:23:12+00	HUMEDAD_SUELO	48.000000	\N
94	60	2025-12-13 04:23:12+00	HUMEDAD_SUELO	73.000000	\N
95	60	2025-12-12 04:23:12+00	HUMEDAD_SUELO	78.000000	\N
96	60	2025-12-16 04:23:12+00	TEMPERATURA	26.000000	\N
97	60	2025-12-15 04:23:12+00	TEMPERATURA	26.000000	\N
98	60	2025-12-14 04:23:12+00	TEMPERATURA	64.000000	\N
99	60	2025-12-13 04:23:12+00	TEMPERATURA	24.000000	\N
100	60	2025-12-12 04:23:12+00	TEMPERATURA	29.000000	\N
101	60	2025-12-16 04:23:12+00	PH	29.000000	\N
102	60	2025-12-15 04:23:12+00	PH	73.000000	\N
103	60	2025-12-14 04:23:12+00	PH	70.000000	\N
104	60	2025-12-13 04:23:12+00	PH	79.000000	\N
105	60	2025-12-12 04:23:12+00	PH	41.000000	\N
\.


--
-- Data for Name: solicitud_produccion; Type: TABLE DATA; Schema: campo; Owner: admin
--

COPY campo.solicitud_produccion (solicitud_id, planta_id, productor_id, variedad_id, cantidad_solicitada_t, fecha_necesaria, estado, observaciones, fecha_solicitud, fecha_respuesta, justificacion_rechazo, created_at, updated_at, codigo_solicitud) FROM stdin;
1	1	1	1	10.500	2025-12-30	ACEPTADA	\N	2025-12-16 00:12:53	2025-12-16 00:12:53	\N	\N	\N	SOL-2024-001
\.


--
-- Data for Name: almacen; Type: TABLE DATA; Schema: cat; Owner: admin
--

COPY cat.almacen (almacen_id, codigo_almacen, nombre, municipio_id, direccion, lat, lon, capacidad_total_t, capacidad_disponible_t, tipo, estado, temperatura_min_c, temperatura_max_c, num_zonas, telefono, email, responsable, horario_operacion) FROM stdin;
1	ALM-LP-01	Almacén La Paz Centro	1	Av. Buenos Aires 1234	\N	\N	\N	\N	CENTRAL	ACTIVO	\N	\N	1	\N	\N	\N	\N
2	ALM-CB-01	Almacén Cochabamba Valle	3	Av. Ayacucho 567	\N	\N	\N	\N	CENTRAL	ACTIVO	\N	\N	1	\N	\N	\N	\N
3	ALM-EA-01	Almacén El Alto Industrial	2	Ciudad Satélite Mz A	\N	\N	\N	\N	CENTRAL	ACTIVO	\N	\N	1	\N	\N	\N	\N
\.


--
-- Data for Name: cliente; Type: TABLE DATA; Schema: cat; Owner: admin
--

COPY cat.cliente (cliente_id, codigo_cliente, nombre, tipo, municipio_id, direccion, lat, lon) FROM stdin;
1	CLI-001	Supermercados ABC	RETAIL	1	Av. Arce 2345	\N	\N
2	CLI-002	Distribuidora La Económica	MAYORISTA	2	Calle 1 #123	\N	\N
3	CLI-003	Procesadora de Alimentos SRL	PROCESADOR	3	Av. Blanco Galindo km 8	\N	\N
4	CLI-004	Mercado Campesino	MAYORISTA	1	Plaza del Agricultor	\N	\N
\.


--
-- Data for Name: departamento; Type: TABLE DATA; Schema: cat; Owner: admin
--

COPY cat.departamento (departamento_id, nombre) FROM stdin;
1	La Paz
2	Cochabamba
3	Potosí
4	Oruro
\.


--
-- Data for Name: municipio; Type: TABLE DATA; Schema: cat; Owner: admin
--

COPY cat.municipio (municipio_id, departamento_id, nombre) FROM stdin;
1	1	La Paz
2	1	El Alto
3	2	Cochabamba
4	2	Quillacollo
5	3	Potosí
6	4	Oruro
\.


--
-- Data for Name: planta; Type: TABLE DATA; Schema: cat; Owner: admin
--

COPY cat.planta (planta_id, codigo_planta, nombre, municipio_id, direccion, lat, lon) FROM stdin;
1	PLT-PT-01	Planta Potosí - Principal	5	Av. Industrial km 5	\N	\N
2	PLT-OR-01	Planta Oruro - Norte	6	Zona Norte Industrial	\N	\N
3	PLT-CB-01	Planta Cochabamba - Valle	3	Valle Alto s/n	\N	\N
\.


--
-- Data for Name: transportista; Type: TABLE DATA; Schema: cat; Owner: admin
--

COPY cat.transportista (transportista_id, codigo_transp, nombre, nro_licencia, estado, telefono, vehiculo_asignado_id) FROM stdin;
1	TRN-001	Transportes Andinos SRL	LIC-2024-001	DISPONIBLE	\N	1
2	TRN-002	Logística del Valle	LIC-2024-002	DISPONIBLE	\N	2
3	TRN-003	Transporte Urbano Express	LIC-2024-003	DISPONIBLE	\N	3
\.


--
-- Data for Name: variedadpapa; Type: TABLE DATA; Schema: cat; Owner: admin
--

COPY cat.variedadpapa (variedad_id, codigo_variedad, nombre_comercial, aptitud, ciclo_dias_min, ciclo_dias_max) FROM stdin;
1	HUA	Huaycha	CONSUMO_FRESCO	120	150
2	WAY	Waych'a	INDUSTRIAL	130	160
3	DES	Desirée	CONSUMO_FRESCO	110	140
4	ALF	Alpha	INDUSTRIAL	140	170
\.


--
-- Data for Name: vehiculo; Type: TABLE DATA; Schema: cat; Owner: admin
--

COPY cat.vehiculo (vehiculo_id, codigo_vehiculo, placa, marca, modelo, anio, color, capacidad_t, tipo, estado, fecha_ultima_revision, fecha_proxima_revision, kilometraje, vencimiento_seguro, vencimiento_inspeccion) FROM stdin;
1	VEH-001	1234-ABC	Volvo	FH16	2020	Blanco	25.000	REFRIGERADO	DISPONIBLE	\N	\N	85000	\N	\N
2	VEH-002	5678-DEF	Mercedes-Benz	Actros	2019	Azul	20.000	CAMION	DISPONIBLE	\N	\N	120000	\N	\N
3	VEH-003	9012-GHI	Scania	R500	2021	Rojo	30.000	REFRIGERADO	DISPONIBLE	\N	\N	45000	\N	\N
4	VEH-004	3456-JKL	Hyundai	HD78	2022	Blanco	8.000	FURGON	DISPONIBLE	\N	\N	25000	\N	\N
\.


--
-- Data for Name: certificado; Type: TABLE DATA; Schema: certificacion; Owner: admin
--

COPY certificacion.certificado (certificado_id, codigo_certificado, ambito, area, vigente_desde, vigente_hasta, emisor, url_archivo) FROM stdin;
1	CERT-ISO-9001	PLANTA	CALIDAD	2024-01-01	2025-12-31	Bureau Veritas	https://example.com/cert-iso.pdf
2	CERT-ORG-001	CAMPO	ORGANICO	2024-01-01	2024-12-31	Certificadora BioLatina	https://example.com/cert-org.pdf
3	CERT-BPM-2024	PLANTA	BPM	2024-06-01	2025-06-01	Senasag	https://example.com/cert-bpm.pdf
4	CERT-FUM-12345	ENVIO	FUMIGACION	2025-12-15	2026-12-16	Fumigaciones La Paz	https://example.com/fumigacion.pdf
\.


--
-- Data for Name: certificadocadena; Type: TABLE DATA; Schema: certificacion; Owner: admin
--

COPY certificacion.certificadocadena (certificado_padre_id, certificado_hijo_id) FROM stdin;
\.


--
-- Data for Name: certificadoenvio; Type: TABLE DATA; Schema: certificacion; Owner: admin
--

COPY certificacion.certificadoenvio (certificado_id, envio_id) FROM stdin;
4	13
\.


--
-- Data for Name: certificadoevidencia; Type: TABLE DATA; Schema: certificacion; Owner: admin
--

COPY certificacion.certificadoevidencia (evidencia_id, certificado_id, tipo, descripcion, url_archivo, fecha_registro) FROM stdin;
\.


--
-- Data for Name: certificadolotecampo; Type: TABLE DATA; Schema: certificacion; Owner: admin
--

COPY certificacion.certificadolotecampo (certificado_id, lote_campo_id) FROM stdin;
2	60
\.


--
-- Data for Name: certificadoloteplanta; Type: TABLE DATA; Schema: certificacion; Owner: admin
--

COPY certificacion.certificadoloteplanta (certificado_id, lote_planta_id) FROM stdin;
3	19
\.


--
-- Data for Name: certificadolotesalida; Type: TABLE DATA; Schema: certificacion; Owner: admin
--

COPY certificacion.certificadolotesalida (certificado_id, lote_salida_id) FROM stdin;
1	19
\.


--
-- Data for Name: pedido; Type: TABLE DATA; Schema: comercial; Owner: admin
--

COPY comercial.pedido (pedido_id, codigo_pedido, cliente_id, almacen_id, fecha_pedido, estado) FROM stdin;
1	PED-HIST-0-1099	3	\N	2025-07-05 04:23:14+00	CANCELADO
2	PED-HIST-0-1004	1	\N	2025-10-16 04:24:13+00	COMPLETADO
3	PED-HIST-1-9169	3	\N	2025-08-01 04:24:13+00	COMPLETADO
4	PED-HIST-2-2956	1	\N	2025-08-05 04:24:13+00	COMPLETADO
5	PED-HIST-3-8987	4	\N	2025-09-05 04:24:13+00	COMPLETADO
6	PED-HIST-4-2935	2	\N	2025-08-01 04:24:13+00	COMPLETADO
7	PED-HIST-5-1313	1	\N	2025-07-09 04:24:13+00	COMPLETADO
8	PED-HIST-6-4552	3	\N	2025-09-12 04:24:13+00	COMPLETADO
9	PED-HIST-7-9602	3	\N	2025-08-11 04:24:13+00	COMPLETADO
10	PED-HIST-8-7708	1	\N	2025-12-15 04:24:13+00	COMPLETADO
11	PED-HIST-9-4557	3	\N	2025-12-01 04:24:13+00	COMPLETADO
12	PED-HIST-10-9960	4	\N	2025-10-08 04:24:13+00	COMPLETADO
13	PED-HIST-11-3619	2	\N	2025-09-16 04:24:13+00	COMPLETADO
14	PED-HIST-12-8985	3	\N	2025-10-09 04:24:13+00	COMPLETADO
15	PED-HIST-13-2791	4	\N	2025-11-19 04:24:13+00	COMPLETADO
16	PED-HIST-14-7196	4	\N	2025-09-23 04:24:13+00	COMPLETADO
17	PED-HIST-15-7553	1	\N	2025-07-17 04:24:13+00	COMPLETADO
18	PED-HIST-16-5021	4	\N	2025-09-08 04:24:13+00	COMPLETADO
19	PED-HIST-17-9748	3	\N	2025-10-02 04:24:13+00	COMPLETADO
20	PED-HIST-18-7244	2	\N	2025-09-16 04:24:13+00	COMPLETADO
21	PED-HIST-19-6487	4	\N	2025-12-11 04:24:13+00	COMPLETADO
22	PED-HIST-20-9641	2	\N	2025-08-23 04:24:13+00	COMPLETADO
23	PED-HIST-21-8654	3	\N	2025-08-09 04:24:14+00	COMPLETADO
24	PED-HIST-22-9712	3	\N	2025-10-29 04:24:14+00	COMPLETADO
25	PED-HIST-23-9773	4	\N	2025-10-18 04:24:14+00	COMPLETADO
26	PED-HIST-24-9066	2	\N	2025-11-15 04:24:14+00	CANCELADO
27	PED-HIST-25-3000	2	\N	2025-08-01 04:24:14+00	PENDIENTE
28	PED-HIST-26-5734	3	\N	2025-08-06 04:24:14+00	COMPLETADO
29	PED-HIST-27-5147	3	\N	2025-07-01 04:24:14+00	COMPLETADO
30	PED-HIST-28-8161	4	\N	2025-08-03 04:24:14+00	COMPLETADO
31	PED-HIST-29-5567	4	\N	2025-10-10 04:24:14+00	COMPLETADO
32	PED-HIST-30-5982	2	\N	2025-07-25 04:24:14+00	COMPLETADO
33	PED-HIST-31-7990	3	\N	2025-08-19 04:24:14+00	COMPLETADO
34	PED-HIST-32-9201	4	\N	2025-11-14 04:24:14+00	COMPLETADO
35	PED-HIST-33-6485	2	\N	2025-07-13 04:24:14+00	COMPLETADO
36	PED-HIST-34-6212	3	\N	2025-07-09 04:24:14+00	COMPLETADO
37	PED-HIST-35-4317	4	\N	2025-11-06 04:24:14+00	COMPLETADO
38	PED-HIST-36-5853	2	\N	2025-08-01 04:24:14+00	COMPLETADO
39	PED-HIST-37-1583	4	\N	2025-08-18 04:24:14+00	PENDIENTE
40	PED-HIST-38-3973	4	\N	2025-10-19 04:24:14+00	COMPLETADO
41	PED-HIST-39-5758	2	\N	2025-10-14 04:24:14+00	COMPLETADO
42	PED-HIST-40-9252	4	\N	2025-08-24 04:24:14+00	PENDIENTE
43	PED-HIST-41-8410	2	\N	2025-10-16 04:24:14+00	COMPLETADO
44	PED-HIST-42-9766	4	\N	2025-09-01 04:24:14+00	COMPLETADO
45	PED-HIST-43-4065	4	\N	2025-08-28 04:24:14+00	COMPLETADO
46	PED-HIST-44-3357	4	\N	2025-06-26 04:24:14+00	CANCELADO
47	PED-HIST-45-3472	3	\N	2025-11-18 04:24:14+00	COMPLETADO
48	PED-HIST-46-3921	1	\N	2025-10-24 04:24:14+00	PENDIENTE
49	PED-HIST-47-4359	3	\N	2025-08-05 04:24:14+00	COMPLETADO
50	PED-HIST-48-4447	2	\N	2025-07-03 04:24:14+00	COMPLETADO
51	PED-HIST-49-7644	1	\N	2025-08-03 04:24:14+00	COMPLETADO
52	PED-HIST-50-8945	1	\N	2025-11-03 04:24:14+00	COMPLETADO
53	PED-HIST-51-6088	3	\N	2025-07-08 04:24:14+00	COMPLETADO
54	PED-HIST-52-4019	2	\N	2025-11-19 04:24:14+00	COMPLETADO
55	PED-HIST-53-6079	3	\N	2025-08-12 04:24:14+00	COMPLETADO
56	PED-HIST-54-9411	3	\N	2025-07-30 04:24:14+00	COMPLETADO
57	PED-HIST-55-8974	2	\N	2025-08-28 04:24:14+00	COMPLETADO
58	PED-HIST-56-8907	3	\N	2025-11-18 04:24:14+00	COMPLETADO
59	PED-HIST-57-4571	1	\N	2025-09-09 04:24:14+00	COMPLETADO
60	PED-HIST-58-2648	2	\N	2025-11-02 04:24:14+00	CANCELADO
61	PED-HIST-59-4864	3	\N	2025-10-05 04:24:14+00	PENDIENTE
\.


--
-- Data for Name: pedidodetalle; Type: TABLE DATA; Schema: comercial; Owner: admin
--

COPY comercial.pedidodetalle (pedido_detalle_id, pedido_id, sku, cantidad_t, precio_unit_usd) FROM stdin;
1	2	Papa Lavada 25kg	40.000	505.00
2	3	Papa Industrial 1tn	12.000	393.00
3	4	Papa Industrial 1tn	35.000	395.00
4	5	Papa Industrial 1tn	14.000	343.00
5	6	Papa Lavada 25kg	14.000	346.00
6	7	Papa Industrial 1tn	23.000	470.00
7	8	Papa Industrial 1tn	24.000	566.00
8	9	Papa Industrial 1tn	41.000	310.00
9	10	Papa Lavada 25kg	39.000	505.00
10	11	Papa Industrial 1tn	6.000	357.00
11	12	Papa Industrial 1tn	32.000	595.00
12	13	Papa Industrial 1tn	9.000	371.00
13	14	Papa Lavada 25kg	16.000	512.00
14	15	Papa Lavada 25kg	14.000	459.00
15	16	Papa Lavada 25kg	32.000	351.00
16	17	Papa Industrial 1tn	43.000	347.00
17	18	Papa Lavada 25kg	6.000	592.00
18	19	Papa Lavada 25kg	31.000	302.00
19	20	Papa Lavada 25kg	33.000	592.00
20	21	Papa Lavada 25kg	39.000	594.00
21	22	Papa Industrial 1tn	18.000	567.00
22	23	Papa Lavada 25kg	45.000	563.00
23	24	Papa Lavada 25kg	24.000	317.00
24	25	Papa Industrial 1tn	14.000	316.00
25	26	Papa Lavada 25kg	36.000	360.00
26	27	Papa Industrial 1tn	33.000	512.00
27	28	Papa Lavada 25kg	20.000	307.00
28	29	Papa Lavada 25kg	44.000	560.00
29	30	Papa Industrial 1tn	22.000	457.00
30	31	Papa Industrial 1tn	37.000	538.00
31	32	Papa Industrial 1tn	7.000	475.00
32	33	Papa Industrial 1tn	42.000	569.00
33	34	Papa Lavada 25kg	46.000	375.00
34	35	Papa Lavada 25kg	16.000	436.00
35	36	Papa Industrial 1tn	20.000	397.00
36	37	Papa Industrial 1tn	28.000	477.00
37	38	Papa Industrial 1tn	17.000	534.00
38	39	Papa Industrial 1tn	26.000	347.00
39	40	Papa Lavada 25kg	36.000	584.00
40	41	Papa Industrial 1tn	49.000	391.00
41	42	Papa Industrial 1tn	10.000	537.00
42	43	Papa Industrial 1tn	15.000	579.00
43	44	Papa Industrial 1tn	33.000	391.00
44	45	Papa Industrial 1tn	12.000	459.00
45	46	Papa Industrial 1tn	5.000	311.00
46	47	Papa Industrial 1tn	28.000	553.00
47	48	Papa Industrial 1tn	28.000	561.00
48	49	Papa Industrial 1tn	15.000	433.00
49	50	Papa Lavada 25kg	32.000	467.00
50	51	Papa Industrial 1tn	26.000	587.00
51	52	Papa Lavada 25kg	31.000	466.00
52	53	Papa Industrial 1tn	43.000	523.00
53	54	Papa Industrial 1tn	31.000	308.00
54	55	Papa Lavada 25kg	47.000	534.00
55	56	Papa Industrial 1tn	11.000	368.00
56	57	Papa Lavada 25kg	23.000	374.00
57	58	Papa Lavada 25kg	14.000	341.00
58	59	Papa Lavada 25kg	25.000	526.00
59	60	Papa Industrial 1tn	44.000	592.00
60	61	Papa Lavada 25kg	9.000	592.00
\.


--
-- Data for Name: envio; Type: TABLE DATA; Schema: logistica; Owner: admin
--

COPY logistica.envio (envio_id, codigo_envio, ruta_id, transportista_id, almacen_origen_id, fecha_salida, fecha_llegada, temp_min_c, temp_max_c, estado, vehiculo_id) FROM stdin;
13	ENV-HIST-0-2047	4	2	2	2025-06-30 04:22:19+00	2025-07-01 04:22:19+00	\N	\N	ENTREGADO	\N
14	ENV-HIST-1-9954	3	2	1	2025-10-30 04:22:19+00	2025-10-31 04:22:19+00	\N	\N	ENTREGADO	\N
15	ENV-HIST-2-5816	1	2	3	2025-12-13 04:22:19+00	\N	\N	\N	EN_RUTA	\N
16	ENV-HIST-3-8606	4	3	3	2025-09-24 04:22:19+00	2025-09-26 04:22:19+00	\N	\N	ENTREGADO	\N
17	ENV-HIST-4-9444	4	1	3	2025-08-15 04:22:19+00	2025-08-19 04:22:19+00	\N	\N	ENTREGADO	\N
18	ENV-HIST-5-6566	4	3	1	2025-10-25 04:22:19+00	\N	\N	\N	CANCELADO	\N
19	ENV-HIST-6-4106	1	1	3	2025-06-21 04:22:19+00	2025-06-25 04:22:19+00	\N	\N	ENTREGADO	\N
20	ENV-HIST-7-2393	2	2	3	2025-09-02 04:22:19+00	2025-09-03 04:22:19+00	\N	\N	ENTREGADO	\N
21	ENV-HIST-8-8383	1	3	1	2025-08-05 04:22:19+00	2025-08-07 04:22:19+00	\N	\N	ENTREGADO	\N
22	ENV-HIST-9-8441	1	3	3	2025-07-29 04:22:19+00	2025-08-02 04:22:19+00	\N	\N	ENTREGADO	\N
23	ENV-HIST-10-3641	3	2	1	2025-11-02 04:22:19+00	2025-11-05 04:22:19+00	\N	\N	ENTREGADO	\N
24	ENV-HIST-11-7146	4	2	2	2025-10-19 04:22:19+00	2025-10-23 04:22:19+00	\N	\N	ENTREGADO	\N
25	ENV-HIST-12-9280	2	3	3	2025-09-04 04:22:19+00	2025-09-06 04:22:19+00	\N	\N	ENTREGADO	\N
26	ENV-HIST-13-5263	3	1	1	2025-06-23 04:22:19+00	2025-06-27 04:22:19+00	\N	\N	ENTREGADO	\N
27	ENV-HIST-14-9101	4	1	2	2025-09-19 04:22:19+00	\N	\N	\N	CANCELADO	\N
28	ENV-HIST-15-4443	1	3	3	2025-08-24 04:22:19+00	2025-08-27 04:22:19+00	\N	\N	ENTREGADO	\N
29	ENV-HIST-16-9976	4	3	3	2025-08-29 04:22:19+00	\N	\N	\N	CANCELADO	\N
30	ENV-HIST-17-8380	2	3	1	2025-07-25 04:22:19+00	2025-07-26 04:22:19+00	\N	\N	ENTREGADO	\N
31	ENV-HIST-18-8433	3	1	1	2025-10-31 04:22:19+00	\N	\N	\N	CANCELADO	\N
32	ENV-HIST-19-6667	1	3	3	2025-11-23 04:22:19+00	2025-11-25 04:22:19+00	\N	\N	ENTREGADO	\N
33	ENV-HIST-20-8593	1	1	2	2025-07-22 04:22:19+00	2025-07-23 04:22:19+00	\N	\N	ENTREGADO	\N
34	ENV-HIST-21-9409	3	1	1	2025-07-02 04:22:19+00	2025-07-05 04:22:19+00	\N	\N	ENTREGADO	\N
35	ENV-HIST-22-2478	3	2	1	2025-06-25 04:22:19+00	2025-06-28 04:22:19+00	\N	\N	ENTREGADO	\N
36	ENV-HIST-23-6529	4	3	1	2025-09-01 04:22:19+00	\N	\N	\N	CANCELADO	\N
37	ENV-HIST-24-8360	3	1	3	2025-08-08 04:22:19+00	2025-08-11 04:22:19+00	\N	\N	ENTREGADO	\N
38	ENV-HIST-25-8485	3	1	3	2025-09-22 04:22:19+00	2025-09-23 04:22:19+00	\N	\N	ENTREGADO	\N
39	ENV-HIST-26-1379	2	3	3	2025-07-14 04:22:19+00	2025-07-16 04:22:19+00	\N	\N	ENTREGADO	\N
40	ENV-HIST-27-8655	2	1	1	2025-08-20 04:22:19+00	2025-08-23 04:22:19+00	\N	\N	ENTREGADO	\N
41	ENV-HIST-28-2277	1	2	2	2025-08-01 04:22:19+00	\N	\N	\N	CANCELADO	\N
42	ENV-HIST-29-1199	1	2	2	2025-10-19 04:22:19+00	2025-10-20 04:22:19+00	\N	\N	ENTREGADO	\N
45	ENV-HIST-0-9164	2	2	3	2025-07-13 04:23:13+00	\N	\N	\N	CANCELADO	\N
46	ENV-HIST-1-4010	4	1	2	2025-11-19 04:23:13+00	2025-11-21 04:23:13+00	\N	\N	ENTREGADO	\N
47	ENV-HIST-2-4421	2	3	2	2025-07-03 04:23:13+00	2025-07-06 04:23:13+00	\N	\N	ENTREGADO	\N
48	ENV-HIST-3-7449	2	1	2	2025-07-05 04:23:13+00	2025-07-07 04:23:13+00	\N	\N	ENTREGADO	\N
49	ENV-HIST-4-8706	2	2	3	2025-09-26 04:23:13+00	2025-09-29 04:23:13+00	\N	\N	ENTREGADO	\N
50	ENV-HIST-5-8022	1	3	2	2025-11-10 04:23:14+00	2025-11-14 04:23:14+00	\N	\N	ENTREGADO	\N
51	ENV-HIST-6-2124	2	3	1	2025-09-12 04:23:14+00	2025-09-16 04:23:14+00	\N	\N	ENTREGADO	\N
52	ENV-HIST-7-3546	1	2	3	2025-10-04 04:23:14+00	\N	\N	\N	CANCELADO	\N
53	ENV-HIST-8-5269	1	3	2	2025-09-12 04:23:14+00	2025-09-15 04:23:14+00	\N	\N	ENTREGADO	\N
54	ENV-HIST-9-8094	2	3	1	2025-07-18 04:23:14+00	2025-07-22 04:23:14+00	\N	\N	ENTREGADO	\N
55	ENV-HIST-10-3865	3	2	1	2025-09-17 04:23:14+00	2025-09-20 04:23:14+00	\N	\N	ENTREGADO	\N
56	ENV-HIST-11-7729	1	2	1	2025-08-05 04:23:14+00	2025-08-06 04:23:14+00	\N	\N	ENTREGADO	\N
57	ENV-HIST-12-5759	2	1	3	2025-10-23 04:23:14+00	2025-10-26 04:23:14+00	\N	\N	ENTREGADO	\N
58	ENV-HIST-13-8083	3	3	1	2025-08-15 04:23:14+00	2025-08-18 04:23:14+00	\N	\N	ENTREGADO	\N
59	ENV-HIST-14-9843	2	1	1	2025-06-26 04:23:14+00	\N	\N	\N	CANCELADO	\N
60	ENV-HIST-15-6084	4	3	1	2025-10-22 04:23:14+00	2025-10-24 04:23:14+00	\N	\N	ENTREGADO	\N
61	ENV-HIST-16-6381	4	3	3	2025-08-13 04:23:14+00	2025-08-17 04:23:14+00	\N	\N	ENTREGADO	\N
62	ENV-HIST-17-2315	4	1	1	2025-08-08 04:23:14+00	2025-08-11 04:23:14+00	\N	\N	ENTREGADO	\N
63	ENV-HIST-18-8292	2	1	2	2025-06-27 04:23:14+00	2025-06-29 04:23:14+00	\N	\N	ENTREGADO	\N
64	ENV-HIST-19-4594	2	1	3	2025-09-02 04:23:14+00	2025-09-06 04:23:14+00	\N	\N	ENTREGADO	\N
65	ENV-HIST-20-5474	4	2	1	2025-09-09 04:23:14+00	2025-09-12 04:23:14+00	\N	\N	ENTREGADO	\N
66	ENV-HIST-21-5936	2	2	3	2025-07-04 04:23:14+00	2025-07-06 04:23:14+00	\N	\N	ENTREGADO	\N
67	ENV-HIST-22-3087	3	2	1	2025-11-10 04:23:14+00	2025-11-13 04:23:14+00	\N	\N	ENTREGADO	\N
68	ENV-HIST-23-6174	2	2	1	2025-08-28 04:23:14+00	2025-08-31 04:23:14+00	\N	\N	ENTREGADO	\N
69	ENV-HIST-24-2330	3	2	3	2025-07-27 04:23:14+00	\N	\N	\N	CANCELADO	\N
70	ENV-HIST-25-2198	1	2	1	2025-08-31 04:23:14+00	2025-09-04 04:23:14+00	\N	\N	ENTREGADO	\N
71	ENV-HIST-26-2950	4	1	2	2025-10-17 04:23:14+00	\N	\N	\N	CANCELADO	\N
72	ENV-HIST-27-4512	2	3	3	2025-10-24 04:23:14+00	2025-10-25 04:23:14+00	\N	\N	ENTREGADO	\N
73	ENV-HIST-28-6673	1	3	2	2025-07-15 04:23:14+00	\N	\N	\N	CANCELADO	\N
74	ENV-HIST-29-2585	4	2	3	2025-08-09 04:23:14+00	2025-08-13 04:23:14+00	\N	\N	ENTREGADO	\N
75	ENV-2024-001	1	1	1	2024-11-28 08:00:00+00	\N	\N	\N	COMPLETADO	\N
76	ENV-2024-002	1	1	1	2024-12-03 07:30:00+00	\N	\N	\N	EN_RUTA	\N
77	ENV-HIST-0-7529	1	2	3	2025-10-23 04:24:13+00	2025-10-26 04:24:13+00	\N	\N	ENTREGADO	\N
78	ENV-HIST-1-8320	3	1	3	2025-11-17 04:24:13+00	2025-11-18 04:24:13+00	\N	\N	ENTREGADO	\N
79	ENV-HIST-2-7357	4	1	2	2025-10-31 04:24:13+00	2025-11-03 04:24:13+00	\N	\N	ENTREGADO	\N
80	ENV-HIST-3-3834	4	3	2	2025-12-01 04:24:13+00	2025-12-04 04:24:13+00	\N	\N	ENTREGADO	\N
81	ENV-HIST-4-5028	3	2	3	2025-06-28 04:24:13+00	\N	\N	\N	CANCELADO	\N
82	ENV-HIST-5-1025	4	2	3	2025-08-10 04:24:13+00	2025-08-11 04:24:13+00	\N	\N	ENTREGADO	\N
83	ENV-HIST-6-2307	1	3	1	2025-11-19 04:24:13+00	\N	\N	\N	CANCELADO	\N
84	ENV-HIST-7-2222	4	1	1	2025-08-04 04:24:13+00	2025-08-07 04:24:13+00	\N	\N	ENTREGADO	\N
85	ENV-HIST-8-3214	2	1	1	2025-11-11 04:24:13+00	2025-11-13 04:24:13+00	\N	\N	ENTREGADO	\N
86	ENV-HIST-9-8633	1	3	1	2025-11-05 04:24:13+00	2025-11-09 04:24:13+00	\N	\N	ENTREGADO	\N
87	ENV-HIST-10-4437	3	1	2	2025-10-26 04:24:13+00	2025-10-29 04:24:13+00	\N	\N	ENTREGADO	\N
88	ENV-HIST-11-8184	3	1	1	2025-08-10 04:24:13+00	2025-08-12 04:24:13+00	\N	\N	ENTREGADO	\N
89	ENV-HIST-12-7671	1	2	3	2025-12-02 04:24:13+00	2025-12-05 04:24:13+00	\N	\N	ENTREGADO	\N
90	ENV-HIST-13-9435	1	1	3	2025-08-06 04:24:13+00	\N	\N	\N	CANCELADO	\N
91	ENV-HIST-14-1538	4	2	1	2025-10-21 04:24:13+00	2025-10-25 04:24:13+00	\N	\N	ENTREGADO	\N
92	ENV-HIST-15-3343	2	2	1	2025-11-26 04:24:13+00	2025-11-28 04:24:13+00	\N	\N	ENTREGADO	\N
93	ENV-HIST-16-1091	3	2	2	2025-06-22 04:24:13+00	2025-06-24 04:24:13+00	\N	\N	ENTREGADO	\N
94	ENV-HIST-17-9949	1	3	1	2025-09-05 04:24:13+00	\N	\N	\N	CANCELADO	\N
95	ENV-HIST-18-1055	2	1	3	2025-12-06 04:24:13+00	\N	\N	\N	CANCELADO	\N
96	ENV-HIST-19-2392	1	3	2	2025-06-29 04:24:13+00	\N	\N	\N	CANCELADO	\N
97	ENV-HIST-20-2761	3	1	3	2025-11-24 04:24:13+00	2025-11-28 04:24:13+00	\N	\N	ENTREGADO	\N
98	ENV-HIST-21-6377	4	3	1	2025-06-25 04:24:13+00	2025-06-27 04:24:13+00	\N	\N	ENTREGADO	\N
99	ENV-HIST-22-7554	1	1	2	2025-11-21 04:24:13+00	\N	\N	\N	CANCELADO	\N
100	ENV-HIST-23-3791	2	1	1	2025-11-22 04:24:13+00	\N	\N	\N	CANCELADO	\N
101	ENV-HIST-24-9245	3	3	1	2025-08-25 04:24:13+00	2025-08-28 04:24:13+00	\N	\N	ENTREGADO	\N
102	ENV-HIST-25-3298	4	1	1	2025-07-20 04:24:13+00	\N	\N	\N	CANCELADO	\N
103	ENV-HIST-26-5745	1	3	3	2025-10-09 04:24:13+00	2025-10-10 04:24:13+00	\N	\N	ENTREGADO	\N
104	ENV-HIST-27-1117	2	2	2	2025-09-29 04:24:13+00	2025-10-01 04:24:13+00	\N	\N	ENTREGADO	\N
105	ENV-HIST-28-9706	2	3	1	2025-07-31 04:24:13+00	\N	\N	\N	CANCELADO	\N
106	ENV-HIST-29-5053	4	1	1	2025-10-26 04:24:13+00	2025-10-30 04:24:13+00	\N	\N	ENTREGADO	\N
\.


--
-- Data for Name: enviodetalle; Type: TABLE DATA; Schema: logistica; Owner: admin
--

COPY logistica.enviodetalle (envio_detalle_id, envio_id, lote_salida_id, cliente_id, cantidad_t) FROM stdin;
22	75	147	1	15.200
23	76	148	1	10.300
24	76	149	1	8.500
\.


--
-- Data for Name: enviodetallealmacen; Type: TABLE DATA; Schema: logistica; Owner: admin
--

COPY logistica.enviodetallealmacen (envio_detalle_alm_id, envio_id, lote_salida_id, almacen_id, cantidad_t) FROM stdin;
\.


--
-- Data for Name: orden_envio; Type: TABLE DATA; Schema: logistica; Owner: admin
--

COPY logistica.orden_envio (orden_envio_id, codigo_orden, planta_origen_id, lote_salida_id, almacen_destino_id, zona_destino_id, transportista_id, vehiculo_id, cantidad_t, estado, fecha_creacion, fecha_programada, fecha_asignacion, fecha_salida, fecha_llegada, prioridad, observaciones, creado_por, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ruta; Type: TABLE DATA; Schema: logistica; Owner: admin
--

COPY logistica.ruta (ruta_id, codigo_ruta, descripcion) FROM stdin;
1	R-LPZ-001	Ruta Altiplano Norte: La Paz - El Alto - Copacabana
2	R-CBB-001	Ruta Valle Bajo: Cochabamba - Quillacollo - Vinto
3	R-SCZ-001	Ruta Norte Integrado: Santa Cruz - Montero
4	R-PTS-001	Ruta Potosí - Sucre
\.


--
-- Data for Name: rutapunto; Type: TABLE DATA; Schema: logistica; Owner: admin
--

COPY logistica.rutapunto (ruta_id, orden, cliente_id) FROM stdin;
1	1	1
1	2	2
1	3	4
2	1	1
2	2	3
2	3	4
3	1	2
3	2	3
3	3	4
4	1	1
4	2	2
4	3	3
\.


--
-- Data for Name: controlproceso; Type: TABLE DATA; Schema: planta; Owner: admin
--

COPY planta.controlproceso (control_id, lote_planta_id, etapa, fecha_hora, parametro, valor_num, valor_texto, estado) FROM stdin;
16	19	LAVADO	2025-12-16 04:23:13+00	Temperatura	15.500000	\N	OK
17	19	SELECCION	2025-12-16 04:23:13+00	Temperatura	15.500000	\N	OK
18	19	EMPAQUE	2025-12-16 04:23:13+00	Temperatura	15.500000	\N	OK
\.


--
-- Data for Name: loteplanta; Type: TABLE DATA; Schema: planta; Owner: admin
--

COPY planta.loteplanta (lote_planta_id, codigo_lote_planta, planta_id, fecha_inicio, fecha_fin, rendimiento_pct) FROM stdin;
19	LP-HIST-0-277	3	2025-07-25 04:22:18+00	\N	90.00
20	LP-HIST-1-351	2	2025-09-18 04:22:18+00	\N	80.00
21	LP-HIST-2-829	1	2025-09-17 04:22:18+00	\N	83.00
22	LP-HIST-3-963	3	2025-11-26 04:22:18+00	\N	93.00
23	LP-HIST-4-536	2	2025-10-16 04:22:18+00	\N	70.00
24	LP-HIST-5-748	1	2025-07-16 04:22:18+00	\N	74.00
25	LP-HIST-6-819	3	2025-07-19 04:22:18+00	\N	83.00
26	LP-HIST-7-864	1	2025-12-04 04:22:18+00	\N	74.00
27	LP-HIST-8-434	3	2025-11-12 04:22:18+00	\N	72.00
28	LP-HIST-9-752	1	2025-08-24 04:22:18+00	\N	77.00
29	LP-HIST-10-788	2	2025-07-05 04:22:18+00	\N	87.00
30	LP-HIST-11-534	1	2025-10-18 04:22:18+00	\N	70.00
31	LP-HIST-12-657	1	2025-10-02 04:22:18+00	\N	75.00
32	LP-HIST-13-873	3	2025-10-14 04:22:18+00	\N	80.00
33	LP-HIST-14-866	1	2025-07-04 04:22:18+00	\N	92.00
34	LP-HIST-15-648	2	2025-12-08 04:22:18+00	\N	83.00
35	LP-HIST-16-806	1	2025-08-07 04:22:18+00	\N	70.00
36	LP-HIST-17-589	3	2025-10-02 04:22:19+00	\N	77.00
37	LP-HIST-18-923	3	2025-06-23 04:22:19+00	\N	91.00
38	LP-HIST-19-224	2	2025-06-28 04:22:19+00	\N	93.00
39	LP-HIST-20-445	3	2025-11-27 04:22:19+00	\N	94.00
40	LP-HIST-21-202	1	2025-07-19 04:22:19+00	\N	89.00
41	LP-HIST-22-354	2	2025-07-17 04:22:19+00	\N	80.00
42	LP-HIST-23-424	1	2025-09-06 04:22:19+00	\N	84.00
43	LP-HIST-24-198	1	2025-08-19 04:22:19+00	\N	80.00
44	LP-HIST-25-419	2	2025-07-19 04:22:19+00	\N	84.00
45	LP-HIST-26-581	2	2025-10-25 04:22:19+00	\N	90.00
46	LP-HIST-27-353	3	2025-09-21 04:22:19+00	\N	93.00
47	LP-HIST-28-876	3	2025-09-18 04:22:19+00	\N	92.00
48	LP-HIST-29-579	2	2025-07-28 04:22:19+00	\N	85.00
49	LP-HIST-30-565	1	2025-09-17 04:22:19+00	\N	93.00
50	LP-HIST-31-103	3	2025-12-03 04:22:19+00	\N	87.00
51	LP-HIST-32-854	1	2025-10-23 04:22:19+00	\N	91.00
52	LP-HIST-33-807	2	2025-09-20 04:22:19+00	\N	92.00
53	LP-HIST-34-583	2	2025-10-15 04:22:19+00	\N	89.00
54	LP-HIST-35-840	3	2025-06-19 04:22:19+00	\N	79.00
55	LP-HIST-36-547	2	2025-12-02 04:22:19+00	\N	76.00
56	LP-HIST-37-978	1	2025-07-18 04:22:19+00	\N	73.00
57	LP-HIST-38-100	1	2025-10-22 04:22:19+00	\N	88.00
58	LP-HIST-39-748	3	2025-07-01 04:22:19+00	\N	93.00
62	LP-HIST-0-765	1	2025-08-11 04:23:13+00	\N	83.00
63	LP-HIST-1-458	3	2025-07-19 04:23:13+00	\N	77.00
64	LP-HIST-2-423	3	2025-09-04 04:23:13+00	\N	75.00
65	LP-HIST-3-972	1	2025-06-19 04:23:13+00	\N	74.00
66	LP-HIST-4-998	1	2025-11-29 04:23:13+00	\N	82.00
67	LP-HIST-5-467	2	2025-07-02 04:23:13+00	\N	91.00
68	LP-HIST-6-731	2	2025-06-27 04:23:13+00	\N	74.00
69	LP-HIST-7-152	2	2025-08-12 04:23:13+00	\N	85.00
70	LP-HIST-8-978	2	2025-12-11 04:23:13+00	\N	91.00
71	LP-HIST-9-641	1	2025-09-01 04:23:13+00	\N	86.00
72	LP-HIST-10-383	2	2025-11-01 04:23:13+00	\N	95.00
73	LP-HIST-11-321	1	2025-08-28 04:23:13+00	\N	88.00
74	LP-HIST-12-747	1	2025-11-12 04:23:13+00	\N	71.00
75	LP-HIST-13-640	1	2025-10-08 04:23:13+00	\N	84.00
76	LP-HIST-14-835	1	2025-06-24 04:23:13+00	\N	85.00
77	LP-HIST-15-945	1	2025-08-02 04:23:13+00	\N	82.00
78	LP-HIST-16-377	3	2025-09-22 04:23:13+00	\N	70.00
79	LP-HIST-17-302	1	2025-09-26 04:23:13+00	\N	83.00
80	LP-HIST-18-830	1	2025-06-20 04:23:13+00	\N	95.00
81	LP-HIST-19-679	2	2025-07-01 04:23:13+00	\N	93.00
82	LP-HIST-20-619	1	2025-08-06 04:23:13+00	\N	87.00
83	LP-HIST-21-147	2	2025-06-21 04:23:13+00	\N	88.00
84	LP-HIST-22-932	1	2025-07-16 04:23:13+00	\N	81.00
85	LP-HIST-23-487	1	2025-09-17 04:23:13+00	\N	92.00
86	LP-HIST-24-672	1	2025-09-28 04:23:13+00	\N	89.00
87	LP-HIST-25-432	1	2025-08-15 04:23:13+00	\N	70.00
88	LP-HIST-26-384	2	2025-12-13 04:23:13+00	\N	80.00
89	LP-HIST-27-128	3	2025-11-12 04:23:13+00	\N	89.00
90	LP-HIST-28-460	1	2025-10-06 04:23:13+00	\N	83.00
91	LP-HIST-29-678	1	2025-09-12 04:23:13+00	\N	74.00
92	LP-HIST-30-400	2	2025-10-28 04:23:13+00	\N	79.00
93	LP-HIST-31-850	1	2025-09-01 04:23:13+00	\N	85.00
94	LP-HIST-32-834	1	2025-09-26 04:23:13+00	\N	82.00
95	LP-HIST-33-818	3	2025-11-12 04:23:13+00	\N	87.00
96	LP-HIST-34-544	1	2025-10-26 04:23:13+00	\N	85.00
97	LP-HIST-35-711	1	2025-09-07 04:23:13+00	\N	72.00
98	LP-HIST-36-971	1	2025-09-13 04:23:13+00	\N	73.00
99	LP-HIST-37-354	2	2025-09-04 04:23:13+00	\N	70.00
100	LP-HIST-38-885	1	2025-10-13 04:23:13+00	\N	77.00
101	LP-HIST-39-261	2	2025-09-13 04:23:13+00	\N	83.00
102	LP-2024-001	1	2024-11-26 00:00:00+00	\N	82.00
103	LP-2024-002	1	2024-11-29 00:00:00+00	\N	75.00
104	LP-2024-003	1	2024-12-02 00:00:00+00	\N	79.00
105	LP-HIST-0-509	1	2025-07-14 04:24:12+00	\N	72.00
106	LP-HIST-1-752	2	2025-09-22 04:24:12+00	\N	82.00
107	LP-HIST-2-486	3	2025-10-17 04:24:12+00	\N	86.00
108	LP-HIST-3-992	1	2025-12-05 04:24:12+00	\N	79.00
109	LP-HIST-4-243	1	2025-09-13 04:24:12+00	\N	92.00
110	LP-HIST-5-532	1	2025-08-20 04:24:12+00	\N	80.00
111	LP-HIST-6-339	1	2025-09-22 04:24:12+00	\N	83.00
112	LP-HIST-7-233	2	2025-11-19 04:24:13+00	\N	77.00
113	LP-HIST-8-731	1	2025-06-24 04:24:13+00	\N	92.00
114	LP-HIST-9-962	2	2025-09-09 04:24:13+00	\N	87.00
115	LP-HIST-10-766	3	2025-06-29 04:24:13+00	\N	78.00
116	LP-HIST-11-853	1	2025-10-06 04:24:13+00	\N	82.00
117	LP-HIST-12-764	3	2025-11-21 04:24:13+00	\N	92.00
118	LP-HIST-13-492	3	2025-06-24 04:24:13+00	\N	70.00
119	LP-HIST-14-884	3	2025-09-08 04:24:13+00	\N	73.00
120	LP-HIST-15-519	2	2025-08-10 04:24:13+00	\N	91.00
121	LP-HIST-16-136	1	2025-12-10 04:24:13+00	\N	72.00
122	LP-HIST-17-544	3	2025-08-09 04:24:13+00	\N	91.00
123	LP-HIST-18-308	2	2025-10-31 04:24:13+00	\N	83.00
124	LP-HIST-19-991	2	2025-11-14 04:24:13+00	\N	76.00
125	LP-HIST-20-760	3	2025-12-02 04:24:13+00	\N	78.00
126	LP-HIST-21-401	1	2025-06-20 04:24:13+00	\N	92.00
127	LP-HIST-22-920	2	2025-11-15 04:24:13+00	\N	71.00
128	LP-HIST-23-481	2	2025-08-03 04:24:13+00	\N	76.00
129	LP-HIST-24-230	1	2025-10-20 04:24:13+00	\N	78.00
130	LP-HIST-25-703	3	2025-11-28 04:24:13+00	\N	87.00
131	LP-HIST-26-228	1	2025-10-18 04:24:13+00	\N	89.00
132	LP-HIST-27-892	1	2025-09-27 04:24:13+00	\N	73.00
133	LP-HIST-28-450	2	2025-06-19 04:24:13+00	\N	86.00
134	LP-HIST-29-143	3	2025-07-24 04:24:13+00	\N	77.00
135	LP-HIST-30-579	2	2025-11-20 04:24:13+00	\N	72.00
136	LP-HIST-31-654	3	2025-09-23 04:24:13+00	\N	95.00
137	LP-HIST-32-803	2	2025-12-08 04:24:13+00	\N	94.00
138	LP-HIST-33-762	3	2025-09-23 04:24:13+00	\N	84.00
139	LP-HIST-34-633	1	2025-07-26 04:24:13+00	\N	86.00
140	LP-HIST-35-668	1	2025-09-22 04:24:13+00	\N	86.00
141	LP-HIST-36-237	3	2025-06-28 04:24:13+00	\N	83.00
142	LP-HIST-37-463	2	2025-09-09 04:24:13+00	\N	91.00
143	LP-HIST-38-330	1	2025-07-20 04:24:13+00	\N	74.00
144	LP-HIST-39-792	3	2025-08-19 04:24:13+00	\N	70.00
\.


--
-- Data for Name: loteplanta_entradacampo; Type: TABLE DATA; Schema: planta; Owner: admin
--

COPY planta.loteplanta_entradacampo (lote_planta_id, lote_campo_id, peso_entrada_t) FROM stdin;
19	35	18.000
20	36	21.000
21	37	44.000
22	38	49.000
23	39	28.000
24	40	29.000
25	41	28.000
26	42	19.000
27	43	50.000
28	44	19.000
29	45	16.000
30	46	18.000
31	47	39.000
32	48	20.000
33	49	50.000
34	50	24.000
35	51	14.000
36	52	31.000
37	53	35.000
38	54	24.000
39	55	13.000
40	56	34.000
41	57	34.000
42	58	45.000
43	59	12.000
44	60	30.000
45	61	28.000
46	62	39.000
47	63	24.000
48	64	41.000
49	65	47.000
50	66	37.000
51	67	21.000
52	68	17.000
53	69	19.000
54	70	45.000
55	71	49.000
56	72	10.000
57	73	11.000
58	74	13.000
62	80	16.000
63	81	29.000
64	82	18.000
65	83	44.000
66	84	36.000
67	85	48.000
68	86	47.000
69	87	12.000
70	88	36.000
71	89	26.000
72	90	43.000
73	91	10.000
74	92	23.000
75	93	45.000
76	94	17.000
77	95	37.000
78	96	31.000
79	97	15.000
80	98	17.000
81	99	26.000
82	100	48.000
83	101	50.000
84	102	28.000
85	103	36.000
86	104	20.000
87	105	33.000
88	106	33.000
89	107	22.000
90	108	48.000
91	109	14.000
92	110	26.000
93	111	30.000
94	112	21.000
95	113	41.000
96	114	42.000
97	115	33.000
98	116	22.000
99	117	12.000
100	118	11.000
101	119	35.000
102	122	18.500
103	123	13.800
104	124	16.200
105	125	24.000
106	126	22.000
107	127	36.000
108	128	38.000
109	129	12.000
110	130	12.000
111	131	48.000
112	132	48.000
113	133	27.000
114	134	39.000
115	135	36.000
116	136	10.000
117	137	34.000
118	138	40.000
119	139	28.000
120	140	47.000
121	141	25.000
122	142	17.000
123	143	34.000
124	144	31.000
125	145	37.000
126	146	14.000
127	147	35.000
128	148	13.000
129	149	15.000
130	150	21.000
131	151	48.000
132	152	25.000
133	153	31.000
134	154	47.000
135	155	31.000
136	156	33.000
137	157	32.000
138	158	36.000
139	159	27.000
140	160	41.000
141	161	36.000
142	162	21.000
143	163	41.000
144	164	14.000
\.


--
-- Data for Name: lotesalida; Type: TABLE DATA; Schema: planta; Owner: admin
--

COPY planta.lotesalida (lote_salida_id, codigo_lote_salida, lote_planta_id, sku, peso_t, fecha_empaque) FROM stdin;
19	LS-HIST-0-0-326	19	Papa Industrial 1tn	5.000	2025-07-28 04:22:18+00
20	LS-HIST-0-1-870	19	Papa Industrial 1tn	5.000	2025-07-26 04:22:18+00
21	LS-HIST-1-0-701	20	Papa Lavada 25kg	5.000	2025-09-20 04:22:18+00
22	LS-HIST-1-1-465	20	Papa Lavada 25kg	11.000	2025-09-19 04:22:18+00
23	LS-HIST-2-0-918	21	Papa Lavada 25kg	17.000	2025-09-19 04:22:18+00
24	LS-HIST-2-1-657	21	Papa Industrial 1tn	20.000	2025-09-19 04:22:18+00
25	LS-HIST-3-0-434	22	Papa Lavada 25kg	14.000	2025-11-27 04:22:18+00
26	LS-HIST-4-0-653	23	Papa Industrial 1tn	7.000	2025-10-19 04:22:18+00
27	LS-HIST-4-1-658	23	Papa Industrial 1tn	13.000	2025-10-19 04:22:18+00
28	LS-HIST-5-0-975	24	Papa Industrial 1tn	9.000	2025-07-17 04:22:18+00
29	LS-HIST-5-1-989	24	Papa Lavada 25kg	7.000	2025-07-19 04:22:18+00
30	LS-HIST-6-0-859	25	Papa Lavada 25kg	14.000	2025-07-20 04:22:18+00
31	LS-HIST-7-0-539	26	Papa Lavada 25kg	13.000	2025-12-07 04:22:18+00
32	LS-HIST-7-1-923	26	Papa Industrial 1tn	10.000	2025-12-05 04:22:18+00
33	LS-HIST-8-0-900	27	Papa Industrial 1tn	13.000	2025-11-15 04:22:18+00
34	LS-HIST-8-1-641	27	Papa Lavada 25kg	10.000	2025-11-14 04:22:18+00
35	LS-HIST-9-0-236	28	Papa Lavada 25kg	15.000	2025-08-27 04:22:18+00
36	LS-HIST-9-1-429	28	Papa Lavada 25kg	11.000	2025-08-26 04:22:18+00
37	LS-HIST-10-0-345	29	Papa Lavada 25kg	8.000	2025-07-07 04:22:18+00
38	LS-HIST-11-0-699	30	Papa Industrial 1tn	15.000	2025-10-21 04:22:18+00
39	LS-HIST-12-0-741	31	Papa Lavada 25kg	20.000	2025-10-03 04:22:18+00
40	LS-HIST-12-1-549	31	Papa Lavada 25kg	19.000	2025-10-03 04:22:18+00
41	LS-HIST-13-0-830	32	Papa Lavada 25kg	16.000	2025-10-17 04:22:18+00
42	LS-HIST-13-1-807	32	Papa Lavada 25kg	10.000	2025-10-17 04:22:18+00
43	LS-HIST-14-0-759	33	Papa Industrial 1tn	14.000	2025-07-06 04:22:18+00
44	LS-HIST-14-1-717	33	Papa Lavada 25kg	17.000	2025-07-05 04:22:18+00
45	LS-HIST-15-0-842	34	Papa Lavada 25kg	15.000	2025-12-09 04:22:18+00
46	LS-HIST-16-0-280	35	Papa Industrial 1tn	19.000	2025-08-10 04:22:18+00
47	LS-HIST-16-1-517	35	Papa Lavada 25kg	5.000	2025-08-10 04:22:18+00
48	LS-HIST-17-0-555	36	Papa Industrial 1tn	6.000	2025-10-04 04:22:19+00
49	LS-HIST-18-0-738	37	Papa Industrial 1tn	10.000	2025-06-24 04:22:19+00
50	LS-HIST-19-0-808	38	Papa Lavada 25kg	18.000	2025-06-29 04:22:19+00
51	LS-HIST-19-1-665	38	Papa Industrial 1tn	15.000	2025-06-30 04:22:19+00
52	LS-HIST-20-0-258	39	Papa Lavada 25kg	14.000	2025-11-29 04:22:19+00
53	LS-HIST-20-1-116	39	Papa Lavada 25kg	14.000	2025-11-30 04:22:19+00
54	LS-HIST-21-0-347	40	Papa Industrial 1tn	12.000	2025-07-21 04:22:19+00
55	LS-HIST-22-0-790	41	Papa Industrial 1tn	9.000	2025-07-19 04:22:19+00
56	LS-HIST-22-1-632	41	Papa Industrial 1tn	12.000	2025-07-18 04:22:19+00
57	LS-HIST-23-0-704	42	Papa Industrial 1tn	19.000	2025-09-09 04:22:19+00
58	LS-HIST-24-0-743	43	Papa Lavada 25kg	6.000	2025-08-21 04:22:19+00
59	LS-HIST-24-1-493	43	Papa Industrial 1tn	14.000	2025-08-21 04:22:19+00
60	LS-HIST-25-0-716	44	Papa Lavada 25kg	7.000	2025-07-22 04:22:19+00
61	LS-HIST-26-0-708	45	Papa Lavada 25kg	12.000	2025-10-26 04:22:19+00
62	LS-HIST-26-1-388	45	Papa Lavada 25kg	10.000	2025-10-27 04:22:19+00
63	LS-HIST-27-0-349	46	Papa Industrial 1tn	15.000	2025-09-23 04:22:19+00
64	LS-HIST-27-1-875	46	Papa Lavada 25kg	13.000	2025-09-22 04:22:19+00
65	LS-HIST-28-0-451	47	Papa Industrial 1tn	16.000	2025-09-19 04:22:19+00
66	LS-HIST-29-0-848	48	Papa Lavada 25kg	5.000	2025-07-30 04:22:19+00
67	LS-HIST-29-1-183	48	Papa Industrial 1tn	6.000	2025-07-30 04:22:19+00
68	LS-HIST-30-0-819	49	Papa Industrial 1tn	19.000	2025-09-19 04:22:19+00
69	LS-HIST-31-0-454	50	Papa Lavada 25kg	7.000	2025-12-04 04:22:19+00
70	LS-HIST-32-0-530	51	Papa Industrial 1tn	18.000	2025-10-26 04:22:19+00
71	LS-HIST-33-0-498	52	Papa Industrial 1tn	6.000	2025-09-23 04:22:19+00
72	LS-HIST-34-0-650	53	Papa Industrial 1tn	14.000	2025-10-17 04:22:19+00
73	LS-HIST-34-1-614	53	Papa Lavada 25kg	15.000	2025-10-16 04:22:19+00
74	LS-HIST-35-0-779	54	Papa Lavada 25kg	6.000	2025-06-21 04:22:19+00
75	LS-HIST-35-1-627	54	Papa Lavada 25kg	11.000	2025-06-21 04:22:19+00
76	LS-HIST-36-0-373	55	Papa Industrial 1tn	10.000	2025-12-03 04:22:19+00
77	LS-HIST-37-0-707	56	Papa Lavada 25kg	15.000	2025-07-19 04:22:19+00
78	LS-HIST-38-0-783	57	Papa Lavada 25kg	10.000	2025-10-25 04:22:19+00
79	LS-HIST-38-1-611	57	Papa Industrial 1tn	11.000	2025-10-23 04:22:19+00
80	LS-HIST-39-0-684	58	Papa Industrial 1tn	17.000	2025-07-04 04:22:19+00
81	LS-HIST-39-1-158	58	Papa Industrial 1tn	10.000	2025-07-04 04:22:19+00
85	LS-HIST-0-0-222	62	Papa Lavada 25kg	10.000	2025-08-14 04:23:13+00
86	LS-HIST-1-0-624	63	Papa Lavada 25kg	17.000	2025-07-21 04:23:13+00
87	LS-HIST-1-1-262	63	Papa Industrial 1tn	19.000	2025-07-20 04:23:13+00
88	LS-HIST-2-0-615	64	Papa Lavada 25kg	18.000	2025-09-05 04:23:13+00
89	LS-HIST-2-1-798	64	Papa Industrial 1tn	18.000	2025-09-06 04:23:13+00
90	LS-HIST-3-0-678	65	Papa Industrial 1tn	15.000	2025-06-21 04:23:13+00
91	LS-HIST-3-1-607	65	Papa Industrial 1tn	5.000	2025-06-21 04:23:13+00
92	LS-HIST-4-0-291	66	Papa Lavada 25kg	14.000	2025-11-30 04:23:13+00
93	LS-HIST-5-0-684	67	Papa Lavada 25kg	19.000	2025-07-04 04:23:13+00
94	LS-HIST-5-1-106	67	Papa Industrial 1tn	15.000	2025-07-05 04:23:13+00
95	LS-HIST-6-0-840	68	Papa Lavada 25kg	6.000	2025-06-29 04:23:13+00
96	LS-HIST-6-1-571	68	Papa Industrial 1tn	17.000	2025-06-28 04:23:13+00
97	LS-HIST-7-0-381	69	Papa Industrial 1tn	9.000	2025-08-13 04:23:13+00
98	LS-HIST-8-0-851	70	Papa Lavada 25kg	17.000	2025-12-12 04:23:13+00
99	LS-HIST-9-0-612	71	Papa Industrial 1tn	9.000	2025-09-03 04:23:13+00
100	LS-HIST-9-1-589	71	Papa Industrial 1tn	8.000	2025-09-04 04:23:13+00
101	LS-HIST-10-0-986	72	Papa Lavada 25kg	17.000	2025-11-04 04:23:13+00
102	LS-HIST-11-0-358	73	Papa Lavada 25kg	7.000	2025-08-30 04:23:13+00
103	LS-HIST-12-0-334	74	Papa Industrial 1tn	17.000	2025-11-14 04:23:13+00
104	LS-HIST-13-0-986	75	Papa Lavada 25kg	9.000	2025-10-09 04:23:13+00
105	LS-HIST-14-0-147	76	Papa Industrial 1tn	16.000	2025-06-26 04:23:13+00
106	LS-HIST-15-0-982	77	Papa Industrial 1tn	9.000	2025-08-04 04:23:13+00
107	LS-HIST-16-0-605	78	Papa Lavada 25kg	6.000	2025-09-25 04:23:13+00
108	LS-HIST-16-1-413	78	Papa Lavada 25kg	12.000	2025-09-24 04:23:13+00
109	LS-HIST-17-0-370	79	Papa Industrial 1tn	13.000	2025-09-29 04:23:13+00
110	LS-HIST-18-0-805	80	Papa Industrial 1tn	20.000	2025-06-22 04:23:13+00
111	LS-HIST-18-1-266	80	Papa Industrial 1tn	14.000	2025-06-21 04:23:13+00
112	LS-HIST-19-0-965	81	Papa Lavada 25kg	13.000	2025-07-04 04:23:13+00
113	LS-HIST-19-1-726	81	Papa Lavada 25kg	15.000	2025-07-04 04:23:13+00
114	LS-HIST-20-0-668	82	Papa Industrial 1tn	15.000	2025-08-07 04:23:13+00
115	LS-HIST-20-1-145	82	Papa Lavada 25kg	11.000	2025-08-09 04:23:13+00
116	LS-HIST-21-0-935	83	Papa Industrial 1tn	15.000	2025-06-24 04:23:13+00
117	LS-HIST-22-0-950	84	Papa Lavada 25kg	13.000	2025-07-17 04:23:13+00
118	LS-HIST-22-1-766	84	Papa Lavada 25kg	11.000	2025-07-19 04:23:13+00
119	LS-HIST-23-0-663	85	Papa Industrial 1tn	12.000	2025-09-20 04:23:13+00
120	LS-HIST-24-0-553	86	Papa Lavada 25kg	16.000	2025-09-30 04:23:13+00
121	LS-HIST-24-1-874	86	Papa Industrial 1tn	8.000	2025-09-30 04:23:13+00
122	LS-HIST-25-0-305	87	Papa Lavada 25kg	14.000	2025-08-17 04:23:13+00
123	LS-HIST-26-0-681	88	Papa Industrial 1tn	7.000	2025-12-15 04:23:13+00
124	LS-HIST-27-0-890	89	Papa Industrial 1tn	15.000	2025-11-13 04:23:13+00
125	LS-HIST-28-0-224	90	Papa Lavada 25kg	17.000	2025-10-09 04:23:13+00
126	LS-HIST-28-1-377	90	Papa Industrial 1tn	16.000	2025-10-08 04:23:13+00
127	LS-HIST-29-0-118	91	Papa Lavada 25kg	7.000	2025-09-13 04:23:13+00
128	LS-HIST-29-1-300	91	Papa Lavada 25kg	5.000	2025-09-15 04:23:13+00
129	LS-HIST-30-0-300	92	Papa Industrial 1tn	18.000	2025-10-29 04:23:13+00
130	LS-HIST-30-1-671	92	Papa Industrial 1tn	7.000	2025-10-31 04:23:13+00
131	LS-HIST-31-0-283	93	Papa Lavada 25kg	20.000	2025-09-03 04:23:13+00
132	LS-HIST-31-1-634	93	Papa Lavada 25kg	18.000	2025-09-02 04:23:13+00
133	LS-HIST-32-0-949	94	Papa Industrial 1tn	12.000	2025-09-29 04:23:13+00
134	LS-HIST-32-1-247	94	Papa Lavada 25kg	14.000	2025-09-28 04:23:13+00
135	LS-HIST-33-0-526	95	Papa Lavada 25kg	15.000	2025-11-13 04:23:13+00
136	LS-HIST-33-1-447	95	Papa Lavada 25kg	9.000	2025-11-15 04:23:13+00
137	LS-HIST-34-0-494	96	Papa Industrial 1tn	9.000	2025-10-29 04:23:13+00
138	LS-HIST-34-1-237	96	Papa Industrial 1tn	15.000	2025-10-27 04:23:13+00
139	LS-HIST-35-0-845	97	Papa Industrial 1tn	6.000	2025-09-09 04:23:13+00
140	LS-HIST-35-1-609	97	Papa Industrial 1tn	7.000	2025-09-10 04:23:13+00
141	LS-HIST-36-0-807	98	Papa Industrial 1tn	19.000	2025-09-16 04:23:13+00
142	LS-HIST-36-1-323	98	Papa Industrial 1tn	13.000	2025-09-15 04:23:13+00
143	LS-HIST-37-0-187	99	Papa Industrial 1tn	20.000	2025-09-07 04:23:13+00
144	LS-HIST-38-0-572	100	Papa Lavada 25kg	13.000	2025-10-14 04:23:13+00
145	LS-HIST-39-0-745	101	Papa Lavada 25kg	13.000	2025-09-14 04:23:13+00
146	LS-HIST-39-1-482	101	Papa Lavada 25kg	12.000	2025-09-16 04:23:13+00
147	LS-2024-001	102	Papa lavada 25kg	15.200	2024-11-27 10:30:00+00
148	LS-2024-002	103	Papa seleccionada 50kg	10.300	2024-11-30 14:15:00+00
149	LS-2024-003	104	Papa premium 10kg	8.500	2024-12-02 16:45:00+00
150	LS-HIST-0-0-300	105	Papa Lavada 25kg	19.000	2025-07-17 04:24:12+00
151	LS-HIST-1-0-252	106	Papa Industrial 1tn	5.000	2025-09-23 04:24:12+00
152	LS-HIST-1-1-761	106	Papa Industrial 1tn	12.000	2025-09-25 04:24:12+00
153	LS-HIST-2-0-862	107	Papa Lavada 25kg	8.000	2025-10-20 04:24:12+00
154	LS-HIST-3-0-158	108	Papa Industrial 1tn	7.000	2025-12-06 04:24:12+00
155	LS-HIST-3-1-488	108	Papa Lavada 25kg	17.000	2025-12-06 04:24:12+00
156	LS-HIST-4-0-558	109	Papa Industrial 1tn	19.000	2025-09-14 04:24:12+00
157	LS-HIST-4-1-998	109	Papa Industrial 1tn	11.000	2025-09-15 04:24:12+00
158	LS-HIST-5-0-245	110	Papa Lavada 25kg	7.000	2025-08-23 04:24:12+00
159	LS-HIST-6-0-145	111	Papa Industrial 1tn	15.000	2025-09-24 04:24:12+00
160	LS-HIST-6-1-885	111	Papa Industrial 1tn	12.000	2025-09-24 04:24:12+00
161	LS-HIST-7-0-572	112	Papa Industrial 1tn	18.000	2025-11-22 04:24:13+00
162	LS-HIST-7-1-764	112	Papa Lavada 25kg	20.000	2025-11-22 04:24:13+00
163	LS-HIST-8-0-304	113	Papa Industrial 1tn	7.000	2025-06-25 04:24:13+00
164	LS-HIST-8-1-564	113	Papa Industrial 1tn	14.000	2025-06-27 04:24:13+00
165	LS-HIST-9-0-220	114	Papa Lavada 25kg	18.000	2025-09-11 04:24:13+00
166	LS-HIST-9-1-296	114	Papa Industrial 1tn	7.000	2025-09-10 04:24:13+00
167	LS-HIST-10-0-425	115	Papa Lavada 25kg	15.000	2025-07-01 04:24:13+00
168	LS-HIST-11-0-596	116	Papa Lavada 25kg	19.000	2025-10-09 04:24:13+00
169	LS-HIST-12-0-873	117	Papa Lavada 25kg	17.000	2025-11-22 04:24:13+00
170	LS-HIST-12-1-498	117	Papa Industrial 1tn	12.000	2025-11-24 04:24:13+00
171	LS-HIST-13-0-115	118	Papa Industrial 1tn	13.000	2025-06-25 04:24:13+00
172	LS-HIST-13-1-202	118	Papa Industrial 1tn	6.000	2025-06-27 04:24:13+00
173	LS-HIST-14-0-229	119	Papa Lavada 25kg	16.000	2025-09-09 04:24:13+00
174	LS-HIST-14-1-282	119	Papa Lavada 25kg	9.000	2025-09-10 04:24:13+00
175	LS-HIST-15-0-964	120	Papa Industrial 1tn	12.000	2025-08-11 04:24:13+00
176	LS-HIST-15-1-357	120	Papa Lavada 25kg	8.000	2025-08-12 04:24:13+00
177	LS-HIST-16-0-171	121	Papa Industrial 1tn	17.000	2025-12-13 04:24:13+00
178	LS-HIST-17-0-686	122	Papa Lavada 25kg	14.000	2025-08-12 04:24:13+00
179	LS-HIST-17-1-766	122	Papa Lavada 25kg	19.000	2025-08-11 04:24:13+00
180	LS-HIST-18-0-381	123	Papa Industrial 1tn	9.000	2025-11-01 04:24:13+00
181	LS-HIST-18-1-580	123	Papa Industrial 1tn	6.000	2025-11-02 04:24:13+00
182	LS-HIST-19-0-100	124	Papa Lavada 25kg	6.000	2025-11-15 04:24:13+00
183	LS-HIST-19-1-602	124	Papa Industrial 1tn	16.000	2025-11-16 04:24:13+00
184	LS-HIST-20-0-724	125	Papa Lavada 25kg	11.000	2025-12-04 04:24:13+00
185	LS-HIST-20-1-489	125	Papa Industrial 1tn	11.000	2025-12-05 04:24:13+00
186	LS-HIST-21-0-493	126	Papa Industrial 1tn	14.000	2025-06-21 04:24:13+00
187	LS-HIST-22-0-398	127	Papa Lavada 25kg	7.000	2025-11-16 04:24:13+00
188	LS-HIST-23-0-282	128	Papa Lavada 25kg	18.000	2025-08-05 04:24:13+00
189	LS-HIST-24-0-392	129	Papa Lavada 25kg	17.000	2025-10-23 04:24:13+00
190	LS-HIST-24-1-531	129	Papa Lavada 25kg	12.000	2025-10-23 04:24:13+00
191	LS-HIST-25-0-114	130	Papa Industrial 1tn	7.000	2025-11-30 04:24:13+00
192	LS-HIST-25-1-393	130	Papa Lavada 25kg	5.000	2025-11-30 04:24:13+00
193	LS-HIST-26-0-591	131	Papa Industrial 1tn	13.000	2025-10-19 04:24:13+00
194	LS-HIST-26-1-805	131	Papa Lavada 25kg	13.000	2025-10-20 04:24:13+00
195	LS-HIST-27-0-609	132	Papa Lavada 25kg	18.000	2025-09-30 04:24:13+00
196	LS-HIST-28-0-985	133	Papa Lavada 25kg	18.000	2025-06-21 04:24:13+00
197	LS-HIST-28-1-711	133	Papa Lavada 25kg	8.000	2025-06-20 04:24:13+00
198	LS-HIST-29-0-382	134	Papa Industrial 1tn	15.000	2025-07-27 04:24:13+00
199	LS-HIST-30-0-440	135	Papa Lavada 25kg	18.000	2025-11-21 04:24:13+00
200	LS-HIST-30-1-436	135	Papa Industrial 1tn	8.000	2025-11-23 04:24:13+00
201	LS-HIST-31-0-139	136	Papa Lavada 25kg	20.000	2025-09-25 04:24:13+00
202	LS-HIST-31-1-111	136	Papa Lavada 25kg	11.000	2025-09-26 04:24:13+00
203	LS-HIST-32-0-302	137	Papa Lavada 25kg	8.000	2025-12-11 04:24:13+00
204	LS-HIST-33-0-602	138	Papa Lavada 25kg	11.000	2025-09-26 04:24:13+00
205	LS-HIST-34-0-277	139	Papa Industrial 1tn	7.000	2025-07-29 04:24:13+00
206	LS-HIST-35-0-901	140	Papa Lavada 25kg	9.000	2025-09-23 04:24:13+00
207	LS-HIST-35-1-248	140	Papa Industrial 1tn	8.000	2025-09-24 04:24:13+00
208	LS-HIST-36-0-752	141	Papa Industrial 1tn	20.000	2025-07-01 04:24:13+00
209	LS-HIST-36-1-351	141	Papa Industrial 1tn	18.000	2025-07-01 04:24:13+00
210	LS-HIST-37-0-615	142	Papa Industrial 1tn	11.000	2025-09-10 04:24:13+00
211	LS-HIST-37-1-148	142	Papa Industrial 1tn	5.000	2025-09-10 04:24:13+00
212	LS-HIST-38-0-922	143	Papa Lavada 25kg	8.000	2025-07-23 04:24:13+00
213	LS-HIST-39-0-649	144	Papa Lavada 25kg	5.000	2025-08-22 04:24:13+00
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_11_30_000000_init_all_schemas_and_tables	1
5	2025_11_30_202554_create_cat_departamento_table	1
6	2025_11_30_202555_create_cat_municipio_table	1
7	2025_11_30_202556_create_cat_variedadpapa_table	1
8	2025_11_30_202557_create_cat_planta_table	1
9	2025_11_30_202558_create_cat_cliente_table	1
10	2025_11_30_202559_create_cat_transportista_table	1
11	2025_11_30_202600_create_cat_almacen_table	1
12	2025_11_30_202601_create_campo_productor_table	1
13	2025_11_30_202602_create_campo_lotecampo_table	1
14	2025_11_30_202603_create_campo_sensorlectura_table	1
15	2025_11_30_202604_create_planta_loteplanta_table	1
16	2025_11_30_202605_create_planta_loteplanta_entradacampo_table	1
17	2025_11_30_202606_create_planta_controlproceso_table	1
18	2025_11_30_202607_create_planta_lotesalida_table	1
19	2025_11_30_202608_create_logistica_ruta_table	1
20	2025_11_30_202609_create_logistica_rutapunto_table	1
21	2025_11_30_202610_create_logistica_envio_table	1
22	2025_11_30_202611_create_logistica_enviodetalle_table	1
23	2025_11_30_202612_create_logistica_enviodetallealmacen_table	1
24	2025_11_30_202613_create_comercial_pedido_table	1
25	2025_11_30_202614_create_comercial_pedidodetalle_table	1
26	2025_11_30_202615_create_certificacion_certificado_table	1
27	2025_11_30_202616_create_certificacion_certificadolotecampo_table	1
28	2025_11_30_202617_create_certificacion_certificadoloteplanta_table	1
29	2025_11_30_202618_create_certificacion_certificadolotesalida_table	1
30	2025_11_30_202619_create_certificacion_certificadoenvio_table	1
31	2025_11_30_202620_create_certificacion_certificadoevidencia_table	1
32	2025_11_30_202621_create_certificacion_certificadocadena_table	1
33	2025_11_30_202622_create_almacen_pedido_table	1
34	2025_11_30_202623_create_almacen_pedidodetalle_table	1
35	2025_11_30_202624_create_almacen_recepcion_table	1
36	2025_11_30_202625_create_almacen_inventario_table	1
37	2025_11_30_202626_create_almacen_movimiento_table	1
38	2025_11_30_900000_create_planta_v_trazabilidad_lote_salida	1
39	2025_11_30_900100_create_certificacion_v_certificados_por_lote_salida	1
40	2025_11_30_900200_create_almacen_v_stock	1
41	2025_12_03_183904_create_solicitud_produccion_table	1
42	2025_12_03_183942_add_estado_to_transportista_table	1
43	2025_12_03_183945_create_asignacion_conductor_table	1
44	2025_12_03_184253_add_codigo_solicitud_to_solicitud_produccion_table	1
45	2025_12_03_184729_create_permission_tables	1
46	2025_12_07_180001_add_campos_to_cat_almacen	1
47	2025_12_07_180002_create_cat_vehiculo	1
48	2025_12_07_180003_add_vehiculo_to_transportista_y_envio	1
49	2025_12_07_180004_create_almacen_zona	1
50	2025_12_07_180005_create_almacen_ubicacion	1
51	2025_12_07_180006_create_logistica_orden_envio	1
52	2025_12_07_180007_add_campos_to_almacen_recepcion	1
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
4	App\\Models\\User	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
1	crear_solicitudes	web	2025-12-16 00:12:52	2025-12-16 00:12:52
2	ver_solicitudes	web	2025-12-16 00:12:52	2025-12-16 00:12:52
3	responder_solicitudes	web	2025-12-16 00:12:52	2025-12-16 00:12:52
4	gestionar_conductores	web	2025-12-16 00:12:52	2025-12-16 00:12:52
5	ver_conductores	web	2025-12-16 00:12:52	2025-12-16 00:12:52
6	ver_trazabilidad	web	2025-12-16 00:12:52	2025-12-16 00:12:52
7	gestionar_planta	web	2025-12-16 00:12:52	2025-12-16 00:12:52
8	gestionar_almacen	web	2025-12-16 00:12:52	2025-12-16 00:12:52
9	gestionar_campo	web	2025-12-16 00:12:52	2025-12-16 00:12:52
\.


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
1	1
2	1
7	1
6	1
5	1
3	2
2	2
9	2
6	2
5	3
1	4
2	4
3	4
4	4
5	4
6	4
7	4
8	4
9	4
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
1	planta	web	2025-12-16 00:12:52	2025-12-16 00:12:52
2	productor	web	2025-12-16 00:12:52	2025-12-16 00:12:52
3	conductor	web	2025-12-16 00:12:52	2025-12-16 00:12:52
4	admin	web	2025-12-16 00:12:52	2025-12-16 00:12:52
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
Cub4INqhZZH4cyTEOdpRLjkHmheuJgzSi910uWSv	1	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0	YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN0REMDIxdXNYNE5ldFY0anB2M2RZNUZGeGdCVVEwdTVDTzVrb1puYyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcmVwb3J0ZXMvaW52ZW50YXJpbyI7czo1OiJyb3V0ZSI7czoyNToicmVwb3J0ZXMuaW52ZW50YXJpby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==	1765859754
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: admin
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
1	Admin CentralHub	admin@centralhub.com	\N	$2y$12$EdbEwIRNice/cIJhPNOd5ON25mPimqppBp.VlpqluBQCHs3/B/SXO	\N	2025-12-16 00:12:52	2025-12-16 00:12:52
\.


--
-- Name: movimiento_mov_id_seq; Type: SEQUENCE SET; Schema: almacen; Owner: admin
--

SELECT pg_catalog.setval('almacen.movimiento_mov_id_seq', 5, true);


--
-- Name: pedido_pedido_almacen_id_seq; Type: SEQUENCE SET; Schema: almacen; Owner: admin
--

SELECT pg_catalog.setval('almacen.pedido_pedido_almacen_id_seq', 1, false);


--
-- Name: pedidodetalle_pedido_detalle_id_seq; Type: SEQUENCE SET; Schema: almacen; Owner: admin
--

SELECT pg_catalog.setval('almacen.pedidodetalle_pedido_detalle_id_seq', 1, false);


--
-- Name: recepcion_recepcion_id_seq; Type: SEQUENCE SET; Schema: almacen; Owner: admin
--

SELECT pg_catalog.setval('almacen.recepcion_recepcion_id_seq', 7, true);


--
-- Name: ubicacion_ubicacion_id_seq; Type: SEQUENCE SET; Schema: almacen; Owner: admin
--

SELECT pg_catalog.setval('almacen.ubicacion_ubicacion_id_seq', 1, false);


--
-- Name: zona_zona_id_seq; Type: SEQUENCE SET; Schema: almacen; Owner: admin
--

SELECT pg_catalog.setval('almacen.zona_zona_id_seq', 1, false);


--
-- Name: asignacion_conductor_asignacion_id_seq; Type: SEQUENCE SET; Schema: campo; Owner: admin
--

SELECT pg_catalog.setval('campo.asignacion_conductor_asignacion_id_seq', 1, true);


--
-- Name: lotecampo_lote_campo_id_seq; Type: SEQUENCE SET; Schema: campo; Owner: admin
--

SELECT pg_catalog.setval('campo.lotecampo_lote_campo_id_seq', 164, true);


--
-- Name: productor_productor_id_seq; Type: SEQUENCE SET; Schema: campo; Owner: admin
--

SELECT pg_catalog.setval('campo.productor_productor_id_seq', 4, true);


--
-- Name: sensorlectura_lectura_id_seq; Type: SEQUENCE SET; Schema: campo; Owner: admin
--

SELECT pg_catalog.setval('campo.sensorlectura_lectura_id_seq', 105, true);


--
-- Name: solicitud_produccion_solicitud_id_seq; Type: SEQUENCE SET; Schema: campo; Owner: admin
--

SELECT pg_catalog.setval('campo.solicitud_produccion_solicitud_id_seq', 1, true);


--
-- Name: almacen_almacen_id_seq; Type: SEQUENCE SET; Schema: cat; Owner: admin
--

SELECT pg_catalog.setval('cat.almacen_almacen_id_seq', 3, true);


--
-- Name: cliente_cliente_id_seq; Type: SEQUENCE SET; Schema: cat; Owner: admin
--

SELECT pg_catalog.setval('cat.cliente_cliente_id_seq', 4, true);


--
-- Name: departamento_departamento_id_seq; Type: SEQUENCE SET; Schema: cat; Owner: admin
--

SELECT pg_catalog.setval('cat.departamento_departamento_id_seq', 4, true);


--
-- Name: municipio_municipio_id_seq; Type: SEQUENCE SET; Schema: cat; Owner: admin
--

SELECT pg_catalog.setval('cat.municipio_municipio_id_seq', 6, true);


--
-- Name: planta_planta_id_seq; Type: SEQUENCE SET; Schema: cat; Owner: admin
--

SELECT pg_catalog.setval('cat.planta_planta_id_seq', 3, true);


--
-- Name: transportista_transportista_id_seq; Type: SEQUENCE SET; Schema: cat; Owner: admin
--

SELECT pg_catalog.setval('cat.transportista_transportista_id_seq', 3, true);


--
-- Name: variedadpapa_variedad_id_seq; Type: SEQUENCE SET; Schema: cat; Owner: admin
--

SELECT pg_catalog.setval('cat.variedadpapa_variedad_id_seq', 4, true);


--
-- Name: vehiculo_vehiculo_id_seq; Type: SEQUENCE SET; Schema: cat; Owner: admin
--

SELECT pg_catalog.setval('cat.vehiculo_vehiculo_id_seq', 4, true);


--
-- Name: certificado_certificado_id_seq; Type: SEQUENCE SET; Schema: certificacion; Owner: admin
--

SELECT pg_catalog.setval('certificacion.certificado_certificado_id_seq', 4, true);


--
-- Name: certificadoevidencia_evidencia_id_seq; Type: SEQUENCE SET; Schema: certificacion; Owner: admin
--

SELECT pg_catalog.setval('certificacion.certificadoevidencia_evidencia_id_seq', 1, false);


--
-- Name: pedido_pedido_id_seq; Type: SEQUENCE SET; Schema: comercial; Owner: admin
--

SELECT pg_catalog.setval('comercial.pedido_pedido_id_seq', 61, true);


--
-- Name: pedidodetalle_pedido_detalle_id_seq; Type: SEQUENCE SET; Schema: comercial; Owner: admin
--

SELECT pg_catalog.setval('comercial.pedidodetalle_pedido_detalle_id_seq', 60, true);


--
-- Name: envio_envio_id_seq; Type: SEQUENCE SET; Schema: logistica; Owner: admin
--

SELECT pg_catalog.setval('logistica.envio_envio_id_seq', 106, true);


--
-- Name: enviodetalle_envio_detalle_id_seq; Type: SEQUENCE SET; Schema: logistica; Owner: admin
--

SELECT pg_catalog.setval('logistica.enviodetalle_envio_detalle_id_seq', 24, true);


--
-- Name: enviodetallealmacen_envio_detalle_alm_id_seq; Type: SEQUENCE SET; Schema: logistica; Owner: admin
--

SELECT pg_catalog.setval('logistica.enviodetallealmacen_envio_detalle_alm_id_seq', 1, false);


--
-- Name: orden_envio_orden_envio_id_seq; Type: SEQUENCE SET; Schema: logistica; Owner: admin
--

SELECT pg_catalog.setval('logistica.orden_envio_orden_envio_id_seq', 1, false);


--
-- Name: ruta_ruta_id_seq; Type: SEQUENCE SET; Schema: logistica; Owner: admin
--

SELECT pg_catalog.setval('logistica.ruta_ruta_id_seq', 4, true);


--
-- Name: controlproceso_control_id_seq; Type: SEQUENCE SET; Schema: planta; Owner: admin
--

SELECT pg_catalog.setval('planta.controlproceso_control_id_seq', 18, true);


--
-- Name: loteplanta_lote_planta_id_seq; Type: SEQUENCE SET; Schema: planta; Owner: admin
--

SELECT pg_catalog.setval('planta.loteplanta_lote_planta_id_seq', 144, true);


--
-- Name: lotesalida_lote_salida_id_seq; Type: SEQUENCE SET; Schema: planta; Owner: admin
--

SELECT pg_catalog.setval('planta.lotesalida_lote_salida_id_seq', 213, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: admin
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: admin
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: admin
--

SELECT pg_catalog.setval('public.migrations_id_seq', 52, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: admin
--

SELECT pg_catalog.setval('public.permissions_id_seq', 9, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: admin
--

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: admin
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: pedido almacen_pedido_codigo_pedido_unique; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.pedido
    ADD CONSTRAINT almacen_pedido_codigo_pedido_unique UNIQUE (codigo_pedido);


--
-- Name: ubicacion almacen_ubicacion_codigo_ubicacion_unique; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.ubicacion
    ADD CONSTRAINT almacen_ubicacion_codigo_ubicacion_unique UNIQUE (codigo_ubicacion);


--
-- Name: ubicacion almacen_ubicacion_zona_id_pasillo_rack_nivel_unique; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.ubicacion
    ADD CONSTRAINT almacen_ubicacion_zona_id_pasillo_rack_nivel_unique UNIQUE (zona_id, pasillo, rack, nivel);


--
-- Name: zona almacen_zona_codigo_zona_unique; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.zona
    ADD CONSTRAINT almacen_zona_codigo_zona_unique UNIQUE (codigo_zona);


--
-- Name: inventario inventario_pkey; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.inventario
    ADD CONSTRAINT inventario_pkey PRIMARY KEY (almacen_id, lote_salida_id);


--
-- Name: movimiento movimiento_pkey; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.movimiento
    ADD CONSTRAINT movimiento_pkey PRIMARY KEY (mov_id);


--
-- Name: pedido pedido_pkey; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.pedido
    ADD CONSTRAINT pedido_pkey PRIMARY KEY (pedido_almacen_id);


--
-- Name: pedidodetalle pedidodetalle_pkey; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.pedidodetalle
    ADD CONSTRAINT pedidodetalle_pkey PRIMARY KEY (pedido_detalle_id);


--
-- Name: recepcion recepcion_pkey; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.recepcion
    ADD CONSTRAINT recepcion_pkey PRIMARY KEY (recepcion_id);


--
-- Name: ubicacion ubicacion_pkey; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.ubicacion
    ADD CONSTRAINT ubicacion_pkey PRIMARY KEY (ubicacion_id);


--
-- Name: zona zona_pkey; Type: CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.zona
    ADD CONSTRAINT zona_pkey PRIMARY KEY (zona_id);


--
-- Name: asignacion_conductor asignacion_conductor_pkey; Type: CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.asignacion_conductor
    ADD CONSTRAINT asignacion_conductor_pkey PRIMARY KEY (asignacion_id);


--
-- Name: lotecampo campo_lotecampo_codigo_lote_campo_unique; Type: CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.lotecampo
    ADD CONSTRAINT campo_lotecampo_codigo_lote_campo_unique UNIQUE (codigo_lote_campo);


--
-- Name: productor campo_productor_codigo_productor_unique; Type: CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.productor
    ADD CONSTRAINT campo_productor_codigo_productor_unique UNIQUE (codigo_productor);


--
-- Name: solicitud_produccion campo_solicitud_produccion_codigo_solicitud_unique; Type: CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.solicitud_produccion
    ADD CONSTRAINT campo_solicitud_produccion_codigo_solicitud_unique UNIQUE (codigo_solicitud);


--
-- Name: lotecampo lotecampo_pkey; Type: CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.lotecampo
    ADD CONSTRAINT lotecampo_pkey PRIMARY KEY (lote_campo_id);


--
-- Name: productor productor_pkey; Type: CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.productor
    ADD CONSTRAINT productor_pkey PRIMARY KEY (productor_id);


--
-- Name: sensorlectura sensorlectura_pkey; Type: CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.sensorlectura
    ADD CONSTRAINT sensorlectura_pkey PRIMARY KEY (lectura_id);


--
-- Name: solicitud_produccion solicitud_produccion_pkey; Type: CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.solicitud_produccion
    ADD CONSTRAINT solicitud_produccion_pkey PRIMARY KEY (solicitud_id);


--
-- Name: almacen almacen_pkey; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.almacen
    ADD CONSTRAINT almacen_pkey PRIMARY KEY (almacen_id);


--
-- Name: almacen cat_almacen_codigo_almacen_unique; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.almacen
    ADD CONSTRAINT cat_almacen_codigo_almacen_unique UNIQUE (codigo_almacen);


--
-- Name: cliente cat_cliente_codigo_cliente_unique; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.cliente
    ADD CONSTRAINT cat_cliente_codigo_cliente_unique UNIQUE (codigo_cliente);


--
-- Name: departamento cat_departamento_nombre_unique; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.departamento
    ADD CONSTRAINT cat_departamento_nombre_unique UNIQUE (nombre);


--
-- Name: municipio cat_municipio_departamento_id_nombre_unique; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.municipio
    ADD CONSTRAINT cat_municipio_departamento_id_nombre_unique UNIQUE (departamento_id, nombre);


--
-- Name: planta cat_planta_codigo_planta_unique; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.planta
    ADD CONSTRAINT cat_planta_codigo_planta_unique UNIQUE (codigo_planta);


--
-- Name: transportista cat_transportista_codigo_transp_unique; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.transportista
    ADD CONSTRAINT cat_transportista_codigo_transp_unique UNIQUE (codigo_transp);


--
-- Name: variedadpapa cat_variedadpapa_codigo_variedad_unique; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.variedadpapa
    ADD CONSTRAINT cat_variedadpapa_codigo_variedad_unique UNIQUE (codigo_variedad);


--
-- Name: vehiculo cat_vehiculo_codigo_vehiculo_unique; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.vehiculo
    ADD CONSTRAINT cat_vehiculo_codigo_vehiculo_unique UNIQUE (codigo_vehiculo);


--
-- Name: vehiculo cat_vehiculo_placa_unique; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.vehiculo
    ADD CONSTRAINT cat_vehiculo_placa_unique UNIQUE (placa);


--
-- Name: cliente cliente_pkey; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.cliente
    ADD CONSTRAINT cliente_pkey PRIMARY KEY (cliente_id);


--
-- Name: departamento departamento_pkey; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.departamento
    ADD CONSTRAINT departamento_pkey PRIMARY KEY (departamento_id);


--
-- Name: municipio municipio_pkey; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.municipio
    ADD CONSTRAINT municipio_pkey PRIMARY KEY (municipio_id);


--
-- Name: planta planta_pkey; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.planta
    ADD CONSTRAINT planta_pkey PRIMARY KEY (planta_id);


--
-- Name: transportista transportista_pkey; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.transportista
    ADD CONSTRAINT transportista_pkey PRIMARY KEY (transportista_id);


--
-- Name: variedadpapa variedadpapa_pkey; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.variedadpapa
    ADD CONSTRAINT variedadpapa_pkey PRIMARY KEY (variedad_id);


--
-- Name: vehiculo vehiculo_pkey; Type: CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.vehiculo
    ADD CONSTRAINT vehiculo_pkey PRIMARY KEY (vehiculo_id);


--
-- Name: certificado certificacion_certificado_codigo_certificado_unique; Type: CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificado
    ADD CONSTRAINT certificacion_certificado_codigo_certificado_unique UNIQUE (codigo_certificado);


--
-- Name: certificado certificado_pkey; Type: CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificado
    ADD CONSTRAINT certificado_pkey PRIMARY KEY (certificado_id);


--
-- Name: certificadocadena certificadocadena_pkey; Type: CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadocadena
    ADD CONSTRAINT certificadocadena_pkey PRIMARY KEY (certificado_padre_id, certificado_hijo_id);


--
-- Name: certificadoenvio certificadoenvio_pkey; Type: CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadoenvio
    ADD CONSTRAINT certificadoenvio_pkey PRIMARY KEY (certificado_id, envio_id);


--
-- Name: certificadoevidencia certificadoevidencia_pkey; Type: CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadoevidencia
    ADD CONSTRAINT certificadoevidencia_pkey PRIMARY KEY (evidencia_id);


--
-- Name: certificadolotecampo certificadolotecampo_pkey; Type: CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadolotecampo
    ADD CONSTRAINT certificadolotecampo_pkey PRIMARY KEY (certificado_id, lote_campo_id);


--
-- Name: certificadoloteplanta certificadoloteplanta_pkey; Type: CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadoloteplanta
    ADD CONSTRAINT certificadoloteplanta_pkey PRIMARY KEY (certificado_id, lote_planta_id);


--
-- Name: certificadolotesalida certificadolotesalida_pkey; Type: CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadolotesalida
    ADD CONSTRAINT certificadolotesalida_pkey PRIMARY KEY (certificado_id, lote_salida_id);


--
-- Name: pedido comercial_pedido_codigo_pedido_unique; Type: CONSTRAINT; Schema: comercial; Owner: admin
--

ALTER TABLE ONLY comercial.pedido
    ADD CONSTRAINT comercial_pedido_codigo_pedido_unique UNIQUE (codigo_pedido);


--
-- Name: pedido pedido_pkey; Type: CONSTRAINT; Schema: comercial; Owner: admin
--

ALTER TABLE ONLY comercial.pedido
    ADD CONSTRAINT pedido_pkey PRIMARY KEY (pedido_id);


--
-- Name: pedidodetalle pedidodetalle_pkey; Type: CONSTRAINT; Schema: comercial; Owner: admin
--

ALTER TABLE ONLY comercial.pedidodetalle
    ADD CONSTRAINT pedidodetalle_pkey PRIMARY KEY (pedido_detalle_id);


--
-- Name: envio envio_pkey; Type: CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.envio
    ADD CONSTRAINT envio_pkey PRIMARY KEY (envio_id);


--
-- Name: enviodetalle enviodetalle_pkey; Type: CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetalle
    ADD CONSTRAINT enviodetalle_pkey PRIMARY KEY (envio_detalle_id);


--
-- Name: enviodetallealmacen enviodetallealmacen_pkey; Type: CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetallealmacen
    ADD CONSTRAINT enviodetallealmacen_pkey PRIMARY KEY (envio_detalle_alm_id);


--
-- Name: envio logistica_envio_codigo_envio_unique; Type: CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.envio
    ADD CONSTRAINT logistica_envio_codigo_envio_unique UNIQUE (codigo_envio);


--
-- Name: orden_envio logistica_orden_envio_codigo_orden_unique; Type: CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio
    ADD CONSTRAINT logistica_orden_envio_codigo_orden_unique UNIQUE (codigo_orden);


--
-- Name: ruta logistica_ruta_codigo_ruta_unique; Type: CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.ruta
    ADD CONSTRAINT logistica_ruta_codigo_ruta_unique UNIQUE (codigo_ruta);


--
-- Name: orden_envio orden_envio_pkey; Type: CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio
    ADD CONSTRAINT orden_envio_pkey PRIMARY KEY (orden_envio_id);


--
-- Name: ruta ruta_pkey; Type: CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.ruta
    ADD CONSTRAINT ruta_pkey PRIMARY KEY (ruta_id);


--
-- Name: rutapunto rutapunto_pkey; Type: CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.rutapunto
    ADD CONSTRAINT rutapunto_pkey PRIMARY KEY (ruta_id, orden);


--
-- Name: controlproceso controlproceso_pkey; Type: CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.controlproceso
    ADD CONSTRAINT controlproceso_pkey PRIMARY KEY (control_id);


--
-- Name: loteplanta_entradacampo loteplanta_entradacampo_pkey; Type: CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.loteplanta_entradacampo
    ADD CONSTRAINT loteplanta_entradacampo_pkey PRIMARY KEY (lote_planta_id, lote_campo_id);


--
-- Name: loteplanta loteplanta_pkey; Type: CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.loteplanta
    ADD CONSTRAINT loteplanta_pkey PRIMARY KEY (lote_planta_id);


--
-- Name: lotesalida lotesalida_pkey; Type: CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.lotesalida
    ADD CONSTRAINT lotesalida_pkey PRIMARY KEY (lote_salida_id);


--
-- Name: loteplanta planta_loteplanta_codigo_lote_planta_unique; Type: CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.loteplanta
    ADD CONSTRAINT planta_loteplanta_codigo_lote_planta_unique UNIQUE (codigo_lote_planta);


--
-- Name: lotesalida planta_lotesalida_codigo_lote_salida_unique; Type: CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.lotesalida
    ADD CONSTRAINT planta_lotesalida_codigo_lote_salida_unique UNIQUE (codigo_lote_salida);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: almacen_ubicacion_lote_salida_id_index; Type: INDEX; Schema: almacen; Owner: admin
--

CREATE INDEX almacen_ubicacion_lote_salida_id_index ON almacen.ubicacion USING btree (lote_salida_id);


--
-- Name: almacen_ubicacion_ocupado_index; Type: INDEX; Schema: almacen; Owner: admin
--

CREATE INDEX almacen_ubicacion_ocupado_index ON almacen.ubicacion USING btree (ocupado);


--
-- Name: almacen_zona_almacen_id_tipo_index; Type: INDEX; Schema: almacen; Owner: admin
--

CREATE INDEX almacen_zona_almacen_id_tipo_index ON almacen.zona USING btree (almacen_id, tipo);


--
-- Name: almacen_zona_estado_index; Type: INDEX; Schema: almacen; Owner: admin
--

CREATE INDEX almacen_zona_estado_index ON almacen.zona USING btree (estado);


--
-- Name: ix_inv_sku; Type: INDEX; Schema: almacen; Owner: admin
--

CREATE INDEX ix_inv_sku ON almacen.inventario USING btree (almacen_id, sku);


--
-- Name: ix_mov_alm_fec; Type: INDEX; Schema: almacen; Owner: admin
--

CREATE INDEX ix_mov_alm_fec ON almacen.movimiento USING btree (almacen_id, fecha_mov);


--
-- Name: ix_palm_pedido; Type: INDEX; Schema: almacen; Owner: admin
--

CREATE INDEX ix_palm_pedido ON almacen.pedidodetalle USING btree (pedido_almacen_id);


--
-- Name: ix_rec_alm_fec; Type: INDEX; Schema: almacen; Owner: admin
--

CREATE INDEX ix_rec_alm_fec ON almacen.recepcion USING btree (almacen_id, fecha_recepcion);


--
-- Name: ix_ubic_pos; Type: INDEX; Schema: almacen; Owner: admin
--

CREATE INDEX ix_ubic_pos ON almacen.ubicacion USING btree (zona_id, pasillo, rack);


--
-- Name: campo_asignacion_conductor_estado_index; Type: INDEX; Schema: campo; Owner: admin
--

CREATE INDEX campo_asignacion_conductor_estado_index ON campo.asignacion_conductor USING btree (estado);


--
-- Name: campo_asignacion_conductor_solicitud_id_index; Type: INDEX; Schema: campo; Owner: admin
--

CREATE INDEX campo_asignacion_conductor_solicitud_id_index ON campo.asignacion_conductor USING btree (solicitud_id);


--
-- Name: campo_asignacion_conductor_transportista_id_index; Type: INDEX; Schema: campo; Owner: admin
--

CREATE INDEX campo_asignacion_conductor_transportista_id_index ON campo.asignacion_conductor USING btree (transportista_id);


--
-- Name: campo_solicitud_produccion_estado_index; Type: INDEX; Schema: campo; Owner: admin
--

CREATE INDEX campo_solicitud_produccion_estado_index ON campo.solicitud_produccion USING btree (estado);


--
-- Name: campo_solicitud_produccion_fecha_necesaria_index; Type: INDEX; Schema: campo; Owner: admin
--

CREATE INDEX campo_solicitud_produccion_fecha_necesaria_index ON campo.solicitud_produccion USING btree (fecha_necesaria);


--
-- Name: campo_solicitud_produccion_planta_id_index; Type: INDEX; Schema: campo; Owner: admin
--

CREATE INDEX campo_solicitud_produccion_planta_id_index ON campo.solicitud_produccion USING btree (planta_id);


--
-- Name: campo_solicitud_produccion_productor_id_index; Type: INDEX; Schema: campo; Owner: admin
--

CREATE INDEX campo_solicitud_produccion_productor_id_index ON campo.solicitud_produccion USING btree (productor_id);


--
-- Name: ix_sensor_lotehora; Type: INDEX; Schema: campo; Owner: admin
--

CREATE INDEX ix_sensor_lotehora ON campo.sensorlectura USING btree (lote_campo_id, fecha_hora);


--
-- Name: cat_vehiculo_estado_index; Type: INDEX; Schema: cat; Owner: admin
--

CREATE INDEX cat_vehiculo_estado_index ON cat.vehiculo USING btree (estado);


--
-- Name: cat_vehiculo_tipo_index; Type: INDEX; Schema: cat; Owner: admin
--

CREATE INDEX cat_vehiculo_tipo_index ON cat.vehiculo USING btree (tipo);


--
-- Name: ix_pd_pedido; Type: INDEX; Schema: comercial; Owner: admin
--

CREATE INDEX ix_pd_pedido ON comercial.pedidodetalle USING btree (pedido_id);


--
-- Name: ix_ed_cliente; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX ix_ed_cliente ON logistica.enviodetalle USING btree (cliente_id);


--
-- Name: ix_ed_envio; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX ix_ed_envio ON logistica.enviodetalle USING btree (envio_id);


--
-- Name: ix_ed_lote; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX ix_ed_lote ON logistica.enviodetalle USING btree (lote_salida_id);


--
-- Name: ix_eda_almacen; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX ix_eda_almacen ON logistica.enviodetallealmacen USING btree (almacen_id);


--
-- Name: ix_eda_envio; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX ix_eda_envio ON logistica.enviodetallealmacen USING btree (envio_id);


--
-- Name: ix_eda_lote; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX ix_eda_lote ON logistica.enviodetallealmacen USING btree (lote_salida_id);


--
-- Name: logistica_orden_envio_almacen_destino_id_estado_index; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX logistica_orden_envio_almacen_destino_id_estado_index ON logistica.orden_envio USING btree (almacen_destino_id, estado);


--
-- Name: logistica_orden_envio_estado_index; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX logistica_orden_envio_estado_index ON logistica.orden_envio USING btree (estado);


--
-- Name: logistica_orden_envio_fecha_programada_index; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX logistica_orden_envio_fecha_programada_index ON logistica.orden_envio USING btree (fecha_programada);


--
-- Name: logistica_orden_envio_planta_origen_id_estado_index; Type: INDEX; Schema: logistica; Owner: admin
--

CREATE INDEX logistica_orden_envio_planta_origen_id_estado_index ON logistica.orden_envio USING btree (planta_origen_id, estado);


--
-- Name: ix_control_lotehora; Type: INDEX; Schema: planta; Owner: admin
--

CREATE INDEX ix_control_lotehora ON planta.controlproceso USING btree (lote_planta_id, fecha_hora);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: inventario almacen_inventario_almacen_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.inventario
    ADD CONSTRAINT almacen_inventario_almacen_id_foreign FOREIGN KEY (almacen_id) REFERENCES cat.almacen(almacen_id);


--
-- Name: inventario almacen_inventario_lote_salida_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.inventario
    ADD CONSTRAINT almacen_inventario_lote_salida_id_foreign FOREIGN KEY (lote_salida_id) REFERENCES planta.lotesalida(lote_salida_id);


--
-- Name: movimiento almacen_movimiento_almacen_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.movimiento
    ADD CONSTRAINT almacen_movimiento_almacen_id_foreign FOREIGN KEY (almacen_id) REFERENCES cat.almacen(almacen_id);


--
-- Name: movimiento almacen_movimiento_lote_salida_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.movimiento
    ADD CONSTRAINT almacen_movimiento_lote_salida_id_foreign FOREIGN KEY (lote_salida_id) REFERENCES planta.lotesalida(lote_salida_id);


--
-- Name: pedido almacen_pedido_almacen_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.pedido
    ADD CONSTRAINT almacen_pedido_almacen_id_foreign FOREIGN KEY (almacen_id) REFERENCES cat.almacen(almacen_id);


--
-- Name: pedidodetalle almacen_pedidodetalle_lote_salida_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.pedidodetalle
    ADD CONSTRAINT almacen_pedidodetalle_lote_salida_id_foreign FOREIGN KEY (lote_salida_id) REFERENCES planta.lotesalida(lote_salida_id);


--
-- Name: pedidodetalle almacen_pedidodetalle_pedido_almacen_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.pedidodetalle
    ADD CONSTRAINT almacen_pedidodetalle_pedido_almacen_id_foreign FOREIGN KEY (pedido_almacen_id) REFERENCES almacen.pedido(pedido_almacen_id);


--
-- Name: recepcion almacen_recepcion_almacen_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.recepcion
    ADD CONSTRAINT almacen_recepcion_almacen_id_foreign FOREIGN KEY (almacen_id) REFERENCES cat.almacen(almacen_id);


--
-- Name: recepcion almacen_recepcion_envio_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.recepcion
    ADD CONSTRAINT almacen_recepcion_envio_id_foreign FOREIGN KEY (envio_id) REFERENCES logistica.envio(envio_id);


--
-- Name: recepcion almacen_recepcion_orden_envio_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.recepcion
    ADD CONSTRAINT almacen_recepcion_orden_envio_id_foreign FOREIGN KEY (orden_envio_id) REFERENCES logistica.orden_envio(orden_envio_id) ON DELETE SET NULL;


--
-- Name: recepcion almacen_recepcion_recibido_por_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.recepcion
    ADD CONSTRAINT almacen_recepcion_recibido_por_foreign FOREIGN KEY (recibido_por) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: recepcion almacen_recepcion_ubicacion_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.recepcion
    ADD CONSTRAINT almacen_recepcion_ubicacion_id_foreign FOREIGN KEY (ubicacion_id) REFERENCES almacen.ubicacion(ubicacion_id) ON DELETE SET NULL;


--
-- Name: recepcion almacen_recepcion_zona_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.recepcion
    ADD CONSTRAINT almacen_recepcion_zona_id_foreign FOREIGN KEY (zona_id) REFERENCES almacen.zona(zona_id) ON DELETE SET NULL;


--
-- Name: ubicacion almacen_ubicacion_lote_salida_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.ubicacion
    ADD CONSTRAINT almacen_ubicacion_lote_salida_id_foreign FOREIGN KEY (lote_salida_id) REFERENCES planta.lotesalida(lote_salida_id) ON DELETE SET NULL;


--
-- Name: ubicacion almacen_ubicacion_zona_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.ubicacion
    ADD CONSTRAINT almacen_ubicacion_zona_id_foreign FOREIGN KEY (zona_id) REFERENCES almacen.zona(zona_id) ON DELETE CASCADE;


--
-- Name: zona almacen_zona_almacen_id_foreign; Type: FK CONSTRAINT; Schema: almacen; Owner: admin
--

ALTER TABLE ONLY almacen.zona
    ADD CONSTRAINT almacen_zona_almacen_id_foreign FOREIGN KEY (almacen_id) REFERENCES cat.almacen(almacen_id) ON DELETE CASCADE;


--
-- Name: asignacion_conductor campo_asignacion_conductor_solicitud_id_foreign; Type: FK CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.asignacion_conductor
    ADD CONSTRAINT campo_asignacion_conductor_solicitud_id_foreign FOREIGN KEY (solicitud_id) REFERENCES campo.solicitud_produccion(solicitud_id) ON DELETE CASCADE;


--
-- Name: asignacion_conductor campo_asignacion_conductor_transportista_id_foreign; Type: FK CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.asignacion_conductor
    ADD CONSTRAINT campo_asignacion_conductor_transportista_id_foreign FOREIGN KEY (transportista_id) REFERENCES cat.transportista(transportista_id);


--
-- Name: lotecampo campo_lotecampo_productor_id_foreign; Type: FK CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.lotecampo
    ADD CONSTRAINT campo_lotecampo_productor_id_foreign FOREIGN KEY (productor_id) REFERENCES campo.productor(productor_id);


--
-- Name: lotecampo campo_lotecampo_variedad_id_foreign; Type: FK CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.lotecampo
    ADD CONSTRAINT campo_lotecampo_variedad_id_foreign FOREIGN KEY (variedad_id) REFERENCES cat.variedadpapa(variedad_id);


--
-- Name: productor campo_productor_municipio_id_foreign; Type: FK CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.productor
    ADD CONSTRAINT campo_productor_municipio_id_foreign FOREIGN KEY (municipio_id) REFERENCES cat.municipio(municipio_id);


--
-- Name: sensorlectura campo_sensorlectura_lote_campo_id_foreign; Type: FK CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.sensorlectura
    ADD CONSTRAINT campo_sensorlectura_lote_campo_id_foreign FOREIGN KEY (lote_campo_id) REFERENCES campo.lotecampo(lote_campo_id);


--
-- Name: solicitud_produccion campo_solicitud_produccion_planta_id_foreign; Type: FK CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.solicitud_produccion
    ADD CONSTRAINT campo_solicitud_produccion_planta_id_foreign FOREIGN KEY (planta_id) REFERENCES cat.planta(planta_id);


--
-- Name: solicitud_produccion campo_solicitud_produccion_productor_id_foreign; Type: FK CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.solicitud_produccion
    ADD CONSTRAINT campo_solicitud_produccion_productor_id_foreign FOREIGN KEY (productor_id) REFERENCES campo.productor(productor_id);


--
-- Name: solicitud_produccion campo_solicitud_produccion_variedad_id_foreign; Type: FK CONSTRAINT; Schema: campo; Owner: admin
--

ALTER TABLE ONLY campo.solicitud_produccion
    ADD CONSTRAINT campo_solicitud_produccion_variedad_id_foreign FOREIGN KEY (variedad_id) REFERENCES cat.variedadpapa(variedad_id);


--
-- Name: almacen cat_almacen_municipio_id_foreign; Type: FK CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.almacen
    ADD CONSTRAINT cat_almacen_municipio_id_foreign FOREIGN KEY (municipio_id) REFERENCES cat.municipio(municipio_id);


--
-- Name: cliente cat_cliente_municipio_id_foreign; Type: FK CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.cliente
    ADD CONSTRAINT cat_cliente_municipio_id_foreign FOREIGN KEY (municipio_id) REFERENCES cat.municipio(municipio_id);


--
-- Name: municipio cat_municipio_departamento_id_foreign; Type: FK CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.municipio
    ADD CONSTRAINT cat_municipio_departamento_id_foreign FOREIGN KEY (departamento_id) REFERENCES cat.departamento(departamento_id);


--
-- Name: planta cat_planta_municipio_id_foreign; Type: FK CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.planta
    ADD CONSTRAINT cat_planta_municipio_id_foreign FOREIGN KEY (municipio_id) REFERENCES cat.municipio(municipio_id);


--
-- Name: transportista cat_transportista_vehiculo_asignado_id_foreign; Type: FK CONSTRAINT; Schema: cat; Owner: admin
--

ALTER TABLE ONLY cat.transportista
    ADD CONSTRAINT cat_transportista_vehiculo_asignado_id_foreign FOREIGN KEY (vehiculo_asignado_id) REFERENCES cat.vehiculo(vehiculo_id) ON DELETE SET NULL;


--
-- Name: certificadocadena certificacion_certificadocadena_certificado_hijo_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadocadena
    ADD CONSTRAINT certificacion_certificadocadena_certificado_hijo_id_foreign FOREIGN KEY (certificado_hijo_id) REFERENCES certificacion.certificado(certificado_id);


--
-- Name: certificadocadena certificacion_certificadocadena_certificado_padre_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadocadena
    ADD CONSTRAINT certificacion_certificadocadena_certificado_padre_id_foreign FOREIGN KEY (certificado_padre_id) REFERENCES certificacion.certificado(certificado_id);


--
-- Name: certificadoenvio certificacion_certificadoenvio_certificado_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadoenvio
    ADD CONSTRAINT certificacion_certificadoenvio_certificado_id_foreign FOREIGN KEY (certificado_id) REFERENCES certificacion.certificado(certificado_id);


--
-- Name: certificadoenvio certificacion_certificadoenvio_envio_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadoenvio
    ADD CONSTRAINT certificacion_certificadoenvio_envio_id_foreign FOREIGN KEY (envio_id) REFERENCES logistica.envio(envio_id);


--
-- Name: certificadoevidencia certificacion_certificadoevidencia_certificado_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadoevidencia
    ADD CONSTRAINT certificacion_certificadoevidencia_certificado_id_foreign FOREIGN KEY (certificado_id) REFERENCES certificacion.certificado(certificado_id);


--
-- Name: certificadolotecampo certificacion_certificadolotecampo_certificado_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadolotecampo
    ADD CONSTRAINT certificacion_certificadolotecampo_certificado_id_foreign FOREIGN KEY (certificado_id) REFERENCES certificacion.certificado(certificado_id);


--
-- Name: certificadolotecampo certificacion_certificadolotecampo_lote_campo_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadolotecampo
    ADD CONSTRAINT certificacion_certificadolotecampo_lote_campo_id_foreign FOREIGN KEY (lote_campo_id) REFERENCES campo.lotecampo(lote_campo_id);


--
-- Name: certificadoloteplanta certificacion_certificadoloteplanta_certificado_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadoloteplanta
    ADD CONSTRAINT certificacion_certificadoloteplanta_certificado_id_foreign FOREIGN KEY (certificado_id) REFERENCES certificacion.certificado(certificado_id);


--
-- Name: certificadoloteplanta certificacion_certificadoloteplanta_lote_planta_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadoloteplanta
    ADD CONSTRAINT certificacion_certificadoloteplanta_lote_planta_id_foreign FOREIGN KEY (lote_planta_id) REFERENCES planta.loteplanta(lote_planta_id);


--
-- Name: certificadolotesalida certificacion_certificadolotesalida_certificado_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadolotesalida
    ADD CONSTRAINT certificacion_certificadolotesalida_certificado_id_foreign FOREIGN KEY (certificado_id) REFERENCES certificacion.certificado(certificado_id);


--
-- Name: certificadolotesalida certificacion_certificadolotesalida_lote_salida_id_foreign; Type: FK CONSTRAINT; Schema: certificacion; Owner: admin
--

ALTER TABLE ONLY certificacion.certificadolotesalida
    ADD CONSTRAINT certificacion_certificadolotesalida_lote_salida_id_foreign FOREIGN KEY (lote_salida_id) REFERENCES planta.lotesalida(lote_salida_id);


--
-- Name: pedido comercial_pedido_almacen_id_foreign; Type: FK CONSTRAINT; Schema: comercial; Owner: admin
--

ALTER TABLE ONLY comercial.pedido
    ADD CONSTRAINT comercial_pedido_almacen_id_foreign FOREIGN KEY (almacen_id) REFERENCES cat.almacen(almacen_id);


--
-- Name: pedido comercial_pedido_cliente_id_foreign; Type: FK CONSTRAINT; Schema: comercial; Owner: admin
--

ALTER TABLE ONLY comercial.pedido
    ADD CONSTRAINT comercial_pedido_cliente_id_foreign FOREIGN KEY (cliente_id) REFERENCES cat.cliente(cliente_id);


--
-- Name: pedidodetalle comercial_pedidodetalle_pedido_id_foreign; Type: FK CONSTRAINT; Schema: comercial; Owner: admin
--

ALTER TABLE ONLY comercial.pedidodetalle
    ADD CONSTRAINT comercial_pedidodetalle_pedido_id_foreign FOREIGN KEY (pedido_id) REFERENCES comercial.pedido(pedido_id);


--
-- Name: envio logistica_envio_almacen_origen_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.envio
    ADD CONSTRAINT logistica_envio_almacen_origen_id_foreign FOREIGN KEY (almacen_origen_id) REFERENCES cat.almacen(almacen_id);


--
-- Name: envio logistica_envio_ruta_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.envio
    ADD CONSTRAINT logistica_envio_ruta_id_foreign FOREIGN KEY (ruta_id) REFERENCES logistica.ruta(ruta_id);


--
-- Name: envio logistica_envio_transportista_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.envio
    ADD CONSTRAINT logistica_envio_transportista_id_foreign FOREIGN KEY (transportista_id) REFERENCES cat.transportista(transportista_id);


--
-- Name: envio logistica_envio_vehiculo_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.envio
    ADD CONSTRAINT logistica_envio_vehiculo_id_foreign FOREIGN KEY (vehiculo_id) REFERENCES cat.vehiculo(vehiculo_id) ON DELETE SET NULL;


--
-- Name: enviodetalle logistica_enviodetalle_cliente_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetalle
    ADD CONSTRAINT logistica_enviodetalle_cliente_id_foreign FOREIGN KEY (cliente_id) REFERENCES cat.cliente(cliente_id);


--
-- Name: enviodetalle logistica_enviodetalle_envio_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetalle
    ADD CONSTRAINT logistica_enviodetalle_envio_id_foreign FOREIGN KEY (envio_id) REFERENCES logistica.envio(envio_id);


--
-- Name: enviodetalle logistica_enviodetalle_lote_salida_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetalle
    ADD CONSTRAINT logistica_enviodetalle_lote_salida_id_foreign FOREIGN KEY (lote_salida_id) REFERENCES planta.lotesalida(lote_salida_id);


--
-- Name: enviodetallealmacen logistica_enviodetallealmacen_almacen_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetallealmacen
    ADD CONSTRAINT logistica_enviodetallealmacen_almacen_id_foreign FOREIGN KEY (almacen_id) REFERENCES cat.almacen(almacen_id);


--
-- Name: enviodetallealmacen logistica_enviodetallealmacen_envio_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetallealmacen
    ADD CONSTRAINT logistica_enviodetallealmacen_envio_id_foreign FOREIGN KEY (envio_id) REFERENCES logistica.envio(envio_id);


--
-- Name: enviodetallealmacen logistica_enviodetallealmacen_lote_salida_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.enviodetallealmacen
    ADD CONSTRAINT logistica_enviodetallealmacen_lote_salida_id_foreign FOREIGN KEY (lote_salida_id) REFERENCES planta.lotesalida(lote_salida_id);


--
-- Name: orden_envio logistica_orden_envio_almacen_destino_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio
    ADD CONSTRAINT logistica_orden_envio_almacen_destino_id_foreign FOREIGN KEY (almacen_destino_id) REFERENCES cat.almacen(almacen_id);


--
-- Name: orden_envio logistica_orden_envio_creado_por_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio
    ADD CONSTRAINT logistica_orden_envio_creado_por_foreign FOREIGN KEY (creado_por) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: orden_envio logistica_orden_envio_lote_salida_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio
    ADD CONSTRAINT logistica_orden_envio_lote_salida_id_foreign FOREIGN KEY (lote_salida_id) REFERENCES planta.lotesalida(lote_salida_id);


--
-- Name: orden_envio logistica_orden_envio_planta_origen_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio
    ADD CONSTRAINT logistica_orden_envio_planta_origen_id_foreign FOREIGN KEY (planta_origen_id) REFERENCES cat.planta(planta_id);


--
-- Name: orden_envio logistica_orden_envio_transportista_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio
    ADD CONSTRAINT logistica_orden_envio_transportista_id_foreign FOREIGN KEY (transportista_id) REFERENCES cat.transportista(transportista_id) ON DELETE SET NULL;


--
-- Name: orden_envio logistica_orden_envio_vehiculo_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio
    ADD CONSTRAINT logistica_orden_envio_vehiculo_id_foreign FOREIGN KEY (vehiculo_id) REFERENCES cat.vehiculo(vehiculo_id) ON DELETE SET NULL;


--
-- Name: orden_envio logistica_orden_envio_zona_destino_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.orden_envio
    ADD CONSTRAINT logistica_orden_envio_zona_destino_id_foreign FOREIGN KEY (zona_destino_id) REFERENCES almacen.zona(zona_id) ON DELETE SET NULL;


--
-- Name: rutapunto logistica_rutapunto_cliente_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.rutapunto
    ADD CONSTRAINT logistica_rutapunto_cliente_id_foreign FOREIGN KEY (cliente_id) REFERENCES cat.cliente(cliente_id);


--
-- Name: rutapunto logistica_rutapunto_ruta_id_foreign; Type: FK CONSTRAINT; Schema: logistica; Owner: admin
--

ALTER TABLE ONLY logistica.rutapunto
    ADD CONSTRAINT logistica_rutapunto_ruta_id_foreign FOREIGN KEY (ruta_id) REFERENCES logistica.ruta(ruta_id);


--
-- Name: controlproceso planta_controlproceso_lote_planta_id_foreign; Type: FK CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.controlproceso
    ADD CONSTRAINT planta_controlproceso_lote_planta_id_foreign FOREIGN KEY (lote_planta_id) REFERENCES planta.loteplanta(lote_planta_id);


--
-- Name: loteplanta_entradacampo planta_loteplanta_entradacampo_lote_campo_id_foreign; Type: FK CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.loteplanta_entradacampo
    ADD CONSTRAINT planta_loteplanta_entradacampo_lote_campo_id_foreign FOREIGN KEY (lote_campo_id) REFERENCES campo.lotecampo(lote_campo_id);


--
-- Name: loteplanta_entradacampo planta_loteplanta_entradacampo_lote_planta_id_foreign; Type: FK CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.loteplanta_entradacampo
    ADD CONSTRAINT planta_loteplanta_entradacampo_lote_planta_id_foreign FOREIGN KEY (lote_planta_id) REFERENCES planta.loteplanta(lote_planta_id);


--
-- Name: loteplanta planta_loteplanta_planta_id_foreign; Type: FK CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.loteplanta
    ADD CONSTRAINT planta_loteplanta_planta_id_foreign FOREIGN KEY (planta_id) REFERENCES cat.planta(planta_id);


--
-- Name: lotesalida planta_lotesalida_lote_planta_id_foreign; Type: FK CONSTRAINT; Schema: planta; Owner: admin
--

ALTER TABLE ONLY planta.lotesalida
    ADD CONSTRAINT planta_lotesalida_lote_planta_id_foreign FOREIGN KEY (lote_planta_id) REFERENCES planta.loteplanta(lote_planta_id);


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict LNClk3G7D2MwjpoGpQRBF1Hedi30j0AtOgr558cc1mBKdpwCJji6SCzFzh1szg3

