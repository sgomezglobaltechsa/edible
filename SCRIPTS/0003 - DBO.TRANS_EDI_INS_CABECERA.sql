BEGIN TRY
	DROP PROCEDURE DBO.TRANS_EDI_INS_CABECERA
END TRY BEGIN CATCH END CATCH
GO

CREATE PROCEDURE DBO.TRANS_EDI_INS_CABECERA
@poCliente		varchar(15),
@doc_ext		varchar(100),
@poNumber		varchar(100),
@poVendorId		varchar(100),
@poCompany		varchar(100),
@session		varchar(1000)
as
Begin
	declare @comando	varchar(1000)
	declare @error		varchar(4000)
	declare @ctn		numeric(20,0)
	declare @msg_suc	varchar(1000)
	begin try

		
		set @msg_suc='SE CREO EL AGENTE ' + @poCompany + ', CON EL ID ' + @poVendorId;
		
		select	@ctn=count(*)
		from	SUCURSAL
		where	cliente_id=@poCliente
				and SUCURSAL_ID=@poVendorId

		if @ctn=0 begin

			insert into sucursal (cliente_id, sucursal_id, nombre, activa, cliente_interno, genera_ing_at, tipo_agente_id)
			values(	@poCliente, 
					ltrim(rtrim(upper(@poVendorID))),
					ltrim(rtrim(upper(@poCompany))),
					'1',
					'0',
					'0',
					'PROVEEDOR');

			EXEC DBO.INS_LOG_PROCESO @doc_ext, null, 'TRANS_EDI_INS_CABECERA',@comando,'OK', @msg_suc,@poNumber, @session		
			
		end

		insert into sys_int_documento (cliente_id, tipo_documento_id, agente_id, orden_de_compra, doc_ext, customs_1)
		values(	@poCliente,
				'I01',
				@poVendorId,
				@poNumber,
				@doc_ext,
				@poCompany);

	end try
	begin catch
		--Guardo Error en tabla de logs.
		set @error=cast(ERROR_MESSAGE() as varchar(4000));
		EXEC DBO.INS_LOG_PROCESO @doc_ext, null, 'TRANS_EDI_INS_CABECERA',@comando,'ERR', @error  ,@poNumber, @session	
	end catch
end


