create table almacen.inventario (
  almacen_id         int not null references cat.almacen(almacen_id),
  lote_salida_id     int not null references planta.lotesalida(lote_salida_id),
  sku                varchar(120) not null,
  cantidad_t         numeric(12,3) not null,
  primary key (almacen_id, lote_salida_id)
);