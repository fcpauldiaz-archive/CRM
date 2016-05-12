CREATE OR REPLACE FUNCTION ventas_fecha (fecha1 date,fecha2 date) 
RETURNS TABLE (
	cant bigint,
	f date)
AS $$
	
BEGIN
	RETURN QUERY
	Select sum(cantidad) as suma, ventas.fecha
	from ventas
	where fecha >= '2016-05-01' and fecha <= '2016-05-15'
	group by ventas.fecha;
	

	
END; $$ 

LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION clientes_fecha (fecha1 date,fecha2 date) 
RETURNS TABLE (
	cant bigint,
	f date)
AS $$
DECLARE
BEGIN
	return QUERY
	Select count(distinct(c.id)), fecha
	from ventas v
	join client c
	on c.id = v.client_id
	where fecha >= '2016-05-01' and fecha <= '2016-05-15'
	group by fecha;
END; $$ 
 
LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION ventas_totales_fecha (fecha1 date,fecha2 date) 
RETURNS TABLE (
	cant double precision,
	f date)
AS $$	
BEGIN
	return query
	Select sum(v.total), fecha
	From ventas v
	where fecha >= '2016-05-01' and fecha <= '2016-05-15'
	group by fecha;


END; $$ 
 
LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION producto_fecha (fecha1 date,fecha2 date) 
RETURNS TABLE (
	cant bigint,
	f date)
AS $$
DECLARE
BEGIN
	return QUERY
	Select count(distinct(p.id)), fecha
	From ventas v
	inner join producto p on p.id = v.producto_id
	where fecha >= '2016-05-01' and fecha <= '2016-05-15'
	group by fecha;
END; $$ 
 
LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION producto (fecha1 date,fecha2 date) 
RETURNS TABLE (
	cant bigint,
	f date,
	producto text)
AS $$
	
BEGIN
	RETURN QUERY
	Select sum(total) as suma, ventas.fecha,producto.producto
	from ventas join producto on ventas.producto_id = producto.id
	where fecha >= '2016-05-01' and fecha <= '2016-05-15'
	group by ventas.fecha;
	

	
END; $$ 

LANGUAGE 'plpgsql';