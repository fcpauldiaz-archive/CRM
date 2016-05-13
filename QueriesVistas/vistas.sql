-- Top 5 productos
CREATE VIEW top5_producto AS
    SELECT p.id, COUNT(v.cantidad) as cantidad
    FROM producto p
        INNER JOIN ventas v ON v.producto_id = p.id
    GROUP BY p.id
    ORDER BY cantidad DESC
    LIMIT 5;

-- Top cliente
CREATE VIEW top_cliente AS 
    SELECT c.id, c.nombres, COUNT(v.id) as cantidadVentas
    FROM client c
        INNER JOIN ventas v ON v.client_id = c.id
    GROUP BY c.id
    ORDER BY cantidadVentas DESC
    LIMIT 1;

-- Compras por cada membresia
CREATE VIEW compra_cliente_membresia AS
    SELECT tp.id, tp.tipo_membresia, COUNT(v.id) as cantidadVentas
    FROM tipo_membresia tp
        INNER JOIN client c ON c.tipo_membresia_id = tp.id
        INNER JOIN ventas v ON v.client_id = c.id
    GROUP BY tp.id
    ORDER BY cantidadVentas DESC;

-- Total comprado por cliente
CREATE VIEW total_por_cliente AS
    SELECT SUM(ventas.total) AS totalVendido, client.nombres
    FROM ventas JOIN client ON client.id = ventas.client_id
    GROUP BY client.nombres
    ORDER BY totalVendido;
--ventas mayores a 100
create view ventas_mayor_a_100 as
select sum(ventas.total),producto.producto
from ventas join producto on producto.id = ventas.producto_id
where ventas.total>100
group by producto.producto;
--vista 1 bofo
CREATE VIEW cliente_membresia AS
    SELECT c.id, c.nombres, c.apellidos, c.dpi, tp.tipo_membresia FROM client c
    INNER JOIN tipo_membresia tp ON tp.id = c.tipo_membresia_id
--vista 2 bofo
CREATE VIEW venta_producto AS
SELECT v.id, v.cantidad, v.total, v.fecha, p.producto FROM ventas v
    INNER JOIN producto p ON p.id = v.producto_id
    WHERE p.producto = 'lana'
    and v.fecha >= '2011-01-01'
    AND v.fecha <= '2020-01-01';


