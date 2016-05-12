create or replace function telefono_limit()
 RETURNS "trigger" AS
 $$	
 BEGIN
	if length(New.numero_telefono)>16 THEN
		RAISE EXCEPTION 'Numero de telefono debe ser menor a 16 digitos';
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;


create trigger telefono_trigger
before insert 
on telefono
for each row 
execute procedure telefono_limit();


 

create or replace function contains()
 RETURNS "trigger" AS
 $$	
 BEGIN
	if position('@' in New.correo_electronico) = 0 THEN
		RAISE EXCEPTION 'El correo electronico debe contener @';
	END IF;
	if position('.' in New.correo_electronico) = 0 THEN
		RAISE EXCEPTION 'El correo electronico debe contener .';
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

create trigger correo_trigger
before insert 
on correo
for each row 
execute procedure contains();



create or replace function sexo_choice()
 RETURNS "trigger" AS
 $$	
 BEGIN
	if upper(New.sexo) != 'M' AND upper(New.sexo) != 'F' AND upper(New.sexo) != 'MASCULINO' AND upper(New.sexo) != 'FEMENINO' AND upper(New.sexo) != 'OTRO' THEN
		RAISE EXCEPTION 'Sexo solo puede ser masculino , femenino u otro';
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

  create trigger sexo_trigger
before insert 
on client
for each row 
execute procedure sexo_choice();



create or replace function estado_civil_choice()
 RETURNS "trigger" AS
 $$	
 BEGIN
	if upper(New.estado_civil) != 'SOLTERO' AND upper(New.estado_civil) != 'CASADO' AND upper(New.estado_civil) != 'SOLTERA' AND upper(New.estado_civil) != 'CASADA' AND upper(New.estado_civil) != 'soltero' AND upper(New.estado_civil) != 'casado' AND upper(New.estado_civil) != 'soltera' AND upper(New.estado_civil) != 'casada'THEN
		RAISE EXCEPTION 'Estado civil solo puede ser: solter@ casad@';
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

   create trigger estado_civil_trigger
before insert 
on client
for each row 
execute procedure estado_civil_choice();



create or replace function nit_limit()
 RETURNS "trigger" AS
 $$	
 BEGIN
	if length(New.nit)>10 THEN
		RAISE EXCEPTION 'Numero de nit debe ser menor a 10 digitos';
	END IF;
	if length(New.nit)<8 THEN
		RAISE EXCEPTION 'Numero de nit debe ser mayor a 8 digitos';
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

 create trigger nit_trigger
before insert or update
on client
for each row 
execute procedure nit_limit();



create or replace function dpi_limit()
 RETURNS "trigger" AS
 $$	
 BEGIN
	if length(New.dpi)<>13 THEN
		RAISE EXCEPTION 'Numero de dpi debe ser de 13 digitos';
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

 create trigger dpi_trigger
before insert or update
on client
for each row 
execute procedure dpi_limit();



create or replace function direccion_limit()
 RETURNS "trigger" AS
 $$
 DECLARE
   cantidad integer:=0;	
 BEGIN
	SELECT count(id) into cantidad from direccion where cliente_id = New.cliente_id;
	if cantidad>=2 THEN
		RAISE EXCEPTION 'Solo puede tener 2 direcciones';
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

 create trigger direccion_trigger
before insert or update
on direccion
for each row 
execute procedure direccion_limit();



create or replace function update_expired()
 RETURNS "trigger" AS
 $$
 BEGIN
	if Old.expires_at>=current_date THEN
		Old.expired = True;
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

 create trigger expired_trigger
after update 
on usuario
for each row 
execute procedure update_expired();
 

create or replace function locked()
 RETURNS "trigger" AS
 $$	
 BEGIN
	if Old.expired = True THEN
		Old.locked = True;
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

 create trigger locked_trigger
after insert 
on usuario
for each row 
execute procedure locked();

 

create or replace function update_enabled()
 RETURNS "trigger" AS
 $$
 BEGIN
	if Old.locked=True AND (Old.expired + integer'7')>=current_date THEN
		Old.enabled = True;
	END IF;
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

 create trigger enabled_trigger
after update 
on usuario
for each row 
execute procedure update_enabled();

 
 

create or replace function delete_fk_client()
 RETURNS "trigger" AS
 $$
 BEGIN
	delete from correo where correo.cliente_id = old.id;
	delete from direccion where direccion.cliente_id = old.id;
	delete from telefono where telefono.cliente_id = old.id;
   Return old;
 END;
 $$ LANGUAGE plpgsql;

create trigger delete_fk_client
before delete 
on client
for each row 
execute procedure delete_fk_client();



create or replace function delete_fk_membresia()
 RETURNS "trigger" AS
 $$
 BEGIN
	delete from client where cliente.tipo_membresia_id = old.id;
   Return old;
 END;
 $$ LANGUAGE plpgsql;

  create trigger delete_fk_membresia
before delete 
on tipo_membresia
for each row 
execute procedure delete_fk_membresia();